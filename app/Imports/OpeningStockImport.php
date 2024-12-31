<?php

namespace App\Imports;

use App\BusinessLocation;
use App\Transaction;
use App\Utils\ProductUtil;
use App\Variation;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\WithMapping;

class OpeningStockImport implements WithMapping, WithHeadingRow
{
    public function map($row): array
    {
        // Aquí defines cómo se mapean los datos desde el archivo importado
        return [
            'sku' => $row['sku'],
            'ubicacion' => $row['ubicacion'],
            'cantidad' => $row['cantidad'],
            'costo_unitario_antes_de_impuestos' => $row['costo_unitario_antes_de_impuestos'],
            'numero_de_lote' => $row['numero_de_lote'],
            'fecha_de_caducidad' => $row['fecha_de_caducidad'],
        ];
    }

    public function rules(): array
    {
        return [
            'sku' => ['required', 'no_empty_spaces', Rule::exists('variations', 'sub_sku')],
            'ubicacion' => ['required', 'no_empty_spaces'],
            'cantidad' => ['numeric'],
            'costo_unitario_antes_de_impuestos' => ['required', 'no_empty_spaces'],
            'numero_de_lote' => ['nullable'],
            'fecha_de_caducidad' => ['nullable', 'date_format:d-m-Y'],
        ];
    }

    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        $results = [];
        $errors = [];
        $currentRow = 0;
        $business_id = session()->get('user.business_id');
        $rows = $collection->first();

        foreach ($rows as $data) {
            $currentRow++;

            $validator = Validator::make($data->toArray(), $this->rules(), [
                'fecha_de_caducidad.date_format' => 'El campo Fecha de Caducidad debe tener el formato dia-mes-año.',
            ]);

            if ($validator->fails()) {
                $errors[] = [
                    'fila' => $currentRow,
                    'errores' => $validator->errors()->all(),
                ];
                continue;
            }

            $product_info = Variation::where('sub_sku', $data['sku'])
                ->join('products AS P', 'variations.product_id', '=', 'P.id')
                ->leftjoin('tax_rates AS TR', 'P.tax', 'TR.id')
                ->where('P.business_id', $business_id)
                ->select(['P.id', 'variations.id as variation_id',
                    'P.enable_stock', 'TR.amount as tax_percent',
                    'TR.id as tax_id'])
                ->first();

            
            $location = BusinessLocation::where('name', $data['ubicacion'])
                ->where('business_id', $business_id)
                ->first();
            
            if(!$location) {
                $location = BusinessLocation::where('business_id', $business_id)->first();
            }

            $os_transaction = Transaction::where('business_id', $business_id)
                ->where('location_id', $location->id)
                ->where('type', 'opening_stock')
                ->where('opening_stock_product_id', $product_info->id)
                ->first();

            $results[] = [
                'cantidad' => $data['cantidad'],
                'numero_de_lote' => $data['numero_de_lote'],
                'fecha_de_caducidad' => $data['fecha_de_caducidad'],
                'product_info' => $product_info,
                'location' => $location,
                'unit_cost_before_tax' => $data['costo_unitario_antes_de_impuestos'],
                'os_transaction' => $os_transaction,
            ];
        }

        return [
            'results' => $results,
            'errors' => $errors,
        ];
    }
}
