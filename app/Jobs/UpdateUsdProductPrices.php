<?php

namespace App\Jobs;

use App\Business;
use App\Variation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class UpdateUsdProductPrices implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Cantidad de intentos.
     */
    public $tries = 3;

    /**
     * Tiempo máximo del Job en segundos.
     */
    public $timeout = 600;

    protected $businessId;
    protected $exchangeRate;
    protected $trackingId;

    public function __construct(
        $businessId,
        $exchangeRate,
        $trackingId
    ) {
        $this->businessId = (int) $businessId;
        $this->exchangeRate = (float) $exchangeRate;
        $this->trackingId = (string) $trackingId;
    }

    /**
     * Ejecuta la actualización de precios.
     */
    public function handle()
    {
        $this->setStatus(
            'processing',
            'Comenzando la actualización de precios.',
            [
                'updated_count' => 0,
                'total_count' => 0,
                'started_at' => now()->toDateTimeString(),
            ]
        );

        if ($this->exchangeRate <= 0) {
            Log::warning(
                'Actualización USD cancelada: cotización inválida.',
                [
                    'business_id' => $this->businessId,
                    'exchange_rate' => $this->exchangeRate,
                ]
            );

            $this->setStatus(
                'failed',
                'La actualización se canceló porque la cotización no es válida.'
            );

            return;
        }

        $business = Business::find($this->businessId);

        if (empty($business)) {
            Log::warning(
                'Actualización USD cancelada: negocio inexistente.',
                [
                    'business_id' => $this->businessId,
                ]
            );

            $this->setStatus(
                'failed',
                'La actualización se canceló porque no se encontró el negocio.'
            );

            return;
        }

        /*
         * Evita que un Job viejo actualice precios cuando ya existe
         * una cotización más nueva.
         */
        if (!$this->isCurrentExchangeRate()) {
            Log::info(
                'Actualización USD omitida: existe una cotización más nueva.',
                [
                    'business_id' => $this->businessId,
                    'job_exchange_rate' => $this->exchangeRate,
                    'current_exchange_rate' =>
                    $business->usd_exchange_rate,
                ]
            );

            $this->setStatus(
                'skipped',
                'La actualización fue omitida porque existe una cotización más nueva.'
            );

            return;
        }

        /*
         * Cantidad total de precios que serán procesados.
         */
        $totalCount = Variation::query()
            ->where('default_purchase_price_usd', '>', 0)
            ->whereHas('product', function ($query) {
                $query->where(
                    'business_id',
                    $this->businessId
                );
            })
            ->count();

        $this->setStatus(
            'processing',
            'Actualizando los precios de los productos.',
            [
                'updated_count' => 0,
                'total_count' => $totalCount,
            ]
        );

        $updatedCount = 0;
        $stoppedByNewRate = false;

        Variation::query()
            ->with([
                'product',
                'product.product_tax',
            ])
            ->where('default_purchase_price_usd', '>', 0)
            ->whereHas('product', function ($query) {
                $query->where(
                    'business_id',
                    $this->businessId
                );
            })
            ->orderBy('id')
            ->chunkById(
                250,
                function ($variations) use (
                    &$updatedCount,
                    &$stoppedByNewRate,
                    $totalCount
                ) {
                    /*
                     * Se vuelve a comprobar la cotización antes
                     * de cada bloque.
                     */
                    if (!$this->isCurrentExchangeRate()) {
                        $stoppedByNewRate = true;

                        Log::info(
                            'Actualización USD detenida por una nueva cotización.',
                            [
                                'business_id' => $this->businessId,
                                'job_exchange_rate' =>
                                $this->exchangeRate,
                            ]
                        );

                        return false;
                    }

                    DB::transaction(
                        function () use (
                            $variations,
                            &$updatedCount
                        ) {
                            foreach ($variations as $variation) {
                                if (empty($variation->product)) {
                                    continue;
                                }

                                $purchasePriceUsd = (float)
                                $variation
                                    ->default_purchase_price_usd;

                                if ($purchasePriceUsd <= 0) {
                                    continue;
                                }

                                $profitPercent = (float)
                                $variation->profit_percent;

                                $taxRate = 0;

                                if (
                                    !empty($variation
                                        ->product
                                        ->product_tax) &&
                                    !is_null(
                                        $variation
                                            ->product
                                            ->product_tax
                                            ->amount
                                    )
                                ) {
                                    $taxRate = (float)
                                    $variation
                                        ->product
                                        ->product_tax
                                        ->amount;
                                }

                                /*
                                 * default_purchase_price =
                                 * precio USD × cotización.
                                 */
                                $purchasePrice = round(
                                    $purchasePriceUsd *
                                        $this->exchangeRate,
                                    4
                                );

                                /*
                                 * dpp_inc_tax =
                                 * compra sin impuesto + impuesto.
                                 */
                                $purchasePriceIncTax = round(
                                    $purchasePrice *
                                        (1 + ($taxRate / 100)),
                                    4
                                );

                                /*
                                 * default_sell_price =
                                 * compra sin impuesto + ganancia.
                                 */
                                $sellingPrice = round(
                                    $purchasePrice *
                                        (
                                            1 +
                                            (
                                                $profitPercent /
                                                100
                                            )
                                        ),
                                    4
                                );

                                /*
                                 * sell_price_inc_tax =
                                 * venta sin impuesto + impuesto.
                                 */
                                $sellingPriceIncTax = round(
                                    $sellingPrice *
                                        (1 + ($taxRate / 100)),
                                    4
                                );

                                $variation
                                    ->default_purchase_price =
                                    $purchasePrice;

                                $variation->dpp_inc_tax =
                                    $purchasePriceIncTax;

                                $variation
                                    ->default_sell_price =
                                    $sellingPrice;

                                $variation
                                    ->sell_price_inc_tax =
                                    $sellingPriceIncTax;

                                /*
                                 * No se modifican:
                                 *
                                 * - default_purchase_price_usd
                                 * - profit_percent
                                 * - precioMayorista
                                 * - cantidadMayorista
                                 */
                                $variation->save();

                                $updatedCount++;
                            }
                        }
                    );

                    /*
                     * Informa el avance después de cada bloque.
                     */
                    $this->setStatus(
                        'processing',
                        'Actualizando los precios de los productos.',
                        [
                            'updated_count' => $updatedCount,
                            'total_count' => $totalCount,
                        ]
                    );
                }
            );

        if ($stoppedByNewRate) {
            $this->setStatus(
                'skipped',
                'La actualización fue detenida porque existe una cotización más nueva.',
                [
                    'updated_count' => $updatedCount,
                    'total_count' => $totalCount,
                    'finished_at' => now()->toDateTimeString(),
                ]
            );

            return;
        }

        Log::info(
            'Precios USD actualizados correctamente.',
            [
                'business_id' => $this->businessId,
                'exchange_rate' => $this->exchangeRate,
                'updated_variations' => $updatedCount,
            ]
        );

        $this->setStatus(
            'completed',
            'La actualización de precios finalizó correctamente.',
            [
                'updated_count' => $updatedCount,
                'total_count' => $totalCount,
                'finished_at' => now()->toDateTimeString(),
            ]
        );
    }

    /**
     * Comprueba que la cotización del Job continúe siendo
     * la cotización actual del negocio.
     */
    protected function isCurrentExchangeRate()
    {
        $currentExchangeRate = Business::where(
            'id',
            $this->businessId
        )->value('usd_exchange_rate');

        if (is_null($currentExchangeRate)) {
            return false;
        }

        return abs(
            (float) $currentExchangeRate -
                $this->exchangeRate
        ) < 0.00001;
    }

    /**
     * Guarda el estado actual del Job en cache.
     */
    protected function setStatus(
        $status,
        $message,
        array $additionalData = []
    ) {
        $currentStatus = Cache::get(
            $this->getStatusCacheKey(),
            []
        );

        $statusData = array_merge(
            $currentStatus,
            [
                'status' => $status,
                'message' => $message,
                'business_id' => $this->businessId,
                'exchange_rate' => $this->exchangeRate,
                'tracking_id' => $this->trackingId,
                'status_updated_at' =>
                now()->toDateTimeString(),
            ],
            $additionalData
        );

        Cache::put(
            $this->getStatusCacheKey(),
            $statusData,
            now()->addHours(2)
        );
    }

    /**
     * Clave utilizada para consultar el estado del Job.
     */
    protected function getStatusCacheKey()
    {
        return 'usd_price_update_status_' .
            $this->trackingId;
    }

    /**
     * Registra un fallo definitivo del Job.
     */
    public function failed(Throwable $exception)
    {
        Log::error(
            'Falló la actualización de precios USD.',
            [
                'business_id' => $this->businessId,
                'exchange_rate' => $this->exchangeRate,
                'error' => $exception->getMessage(),
            ]
        );

        $this->setStatus(
            'failed',
            'Falló la actualización de precios.',
            [
                'error' => $exception->getMessage(),
                'finished_at' => now()->toDateTimeString(),
            ]
        );
    }
}
