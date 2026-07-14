@if (!session('business.enable_price_tax'))
    @php
        $default = 0;
        $class = 'hide';
    @endphp
@else
    @php
        $default = null;
        $class = '';
    @endphp
@endif

@php
    $usdExchangeRate = (float) data_get(Session::get('business'), 'usd_exchange_rate', 0);

    $firstVariation = $product_deatails->variations->first();

    $usdPurchasePrice = !empty($firstVariation) ? (float) $firstVariation->default_purchase_price_usd : 0;

    $usesUsd = $usdPurchasePrice > 0;

    $usdColumnClass = $usesUsd ? '' : 'hide';
@endphp

@if (!empty($firstVariation))

    <div class="col-sm-12">
        <div class="form-group">
            <br>

            <label>
                {!! Form::checkbox('purchase_price_in_usd', 1, $usesUsd, [
                    'class' => 'input-icheck',
                    'id' => 'purchase_price_in_usd',
                ]) !!}

                <strong>¿Ingresar precio de compra en dólares?</strong>
            </label>

            @show_tooltip(
            'Al activar esta opción, el precio de compra en pesos se calcula utilizando la cotización actual.'
            )

            <p class="help-block">
                <i>
                    Si no se activa, los precios se ingresan manualmente en pesos como antes.
                </i>
            </p>
        </div>
    </div>

    <div class="clearfix"></div>

    {{-- Valor sin formato para utilizarlo desde JavaScript --}}
    <input type="hidden" id="usd_exchange_rate_raw" value="{{ $usdExchangeRate }}">

    <div class="col-sm-12">
        <br>

        <div class="table-responsive">
            <table class="table table-bordered add-product-price-table table-condensed {{ $class }}"
                style="min-width: 1200px;">
                <thead>
                    <tr>
                        <th class="usd-purchase-column {{ $usdColumnClass }}">
                            Precio de compra USD
                        </th>

                        <th class="usd-purchase-column {{ $usdColumnClass }}">
                            Cotización
                        </th>

                        <th>
                            @lang('product.default_purchase_price')
                        </th>

                        <th>
                            @lang('product.profit_percent')
                            @show_tooltip(__('tooltip.profit_percent'))
                        </th>

                        <th>
                            @lang('product.default_selling_price')
                        </th>

                        <th>
                            Cantidad mayorista
                        </th>

                        <th>
                            Precio mayorista
                        </th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        {{-- ID de la variación --}}
                        <input type="hidden" name="single_variation_id" value="{{ $firstVariation->id }}">

                        {{-- Precio base en dólares --}}
                        <td class="usd-purchase-column {{ $usdColumnClass }}" style="min-width: 165px;">
                            {!! Form::label('single_dpp_usd', 'Precio base USD:*') !!}

                            {!! Form::text('single_dpp_usd', $usesUsd ? @num_format($firstVariation->default_purchase_price_usd) : null, [
                                'class' => 'form-control input-sm input_number',
                                'id' => 'single_dpp_usd',
                                'placeholder' => '0.00',
                                'disabled' => !$usesUsd,
                                'required' => $usesUsd,
                            ]) !!}

                            <small class="text-muted">
                                Precio del proveedor sin ganancia.
                            </small>
                        </td>

                        {{-- Cotización actual --}}
                        <td class="usd-purchase-column {{ $usdColumnClass }}" style="min-width: 150px;">
                            {!! Form::label('usd_exchange_rate_display', 'USD 1 = ARS') !!}

                            <input type="text" id="usd_exchange_rate_display" class="form-control input-sm"
                                value="{{ $usdExchangeRate > 0 ? @num_format($usdExchangeRate) : '' }}" readonly>

                            @if ($usdExchangeRate > 0)
                                <small class="text-muted">
                                    Cotización actual.
                                </small>
                            @else
                                <small class="text-danger">
                                    Debes configurar la cotización.
                                </small>
                            @endif
                        </td>

                        {{-- Precio de compra en pesos --}}
                        <td style="min-width: 300px;">
                            <div class="row">
                                <div class="col-sm-6">
                                    {!! Form::label('single_dpp', trans('product.exc_of_tax') . ':*') !!}

                                    {!! Form::text('single_dpp', @num_format($firstVariation->default_purchase_price), [
                                        'class' => 'form-control input-sm dpp input_number',
                                        'placeholder' => __('product.exc_of_tax'),
                                        'id' => 'single_dpp',
                                        'readonly' => $usesUsd,
                                        'required',
                                    ]) !!}
                                </div>

                                <div class="col-sm-6">
                                    {!! Form::label('single_dpp_inc_tax', trans('product.inc_of_tax') . ':*') !!}

                                    {!! Form::text('single_dpp_inc_tax', @num_format($firstVariation->dpp_inc_tax), [
                                        'class' => 'form-control input-sm dpp_inc_tax input_number',
                                        'placeholder' => __('product.inc_of_tax'),
                                        'id' => 'single_dpp_inc_tax',
                                        'readonly' => $usesUsd,
                                        'required',
                                    ]) !!}
                                </div>
                            </div>
                        </td>

                        {{-- Margen --}}
                        <td style="min-width: 130px;">
                            <br>

                            {!! Form::text('profit_percent', @num_format($firstVariation->profit_percent), [
                                'class' => 'form-control input-sm input_number',
                                'id' => 'profit_percent',
                                'required',
                            ]) !!}
                        </td>

                        {{-- Precio de venta --}}
                        <td style="min-width: 170px;">
                            <label>
                                <span class="dsp_label">
                                    @lang('product.exc_of_tax')
                                </span>
                            </label>

                            {!! Form::text('single_dsp', @num_format($firstVariation->default_sell_price), [
                                'class' => 'form-control input-sm dsp input_number',
                                'placeholder' => __('product.exc_of_tax'),
                                'id' => 'single_dsp',
                                'required',
                            ]) !!}

                            {!! Form::text('single_dsp_inc_tax', @num_format($firstVariation->sell_price_inc_tax), [
                                'class' => 'form-control input-sm hide input_number',
                                'placeholder' => __('product.inc_of_tax'),
                                'id' => 'single_dsp_inc_tax',
                                'required',
                            ]) !!}
                        </td>

                        {{-- Cantidad mayorista --}}
                        <td style="min-width: 145px;">
                            <br>

                            {!! Form::text(
                                'cantidadMayorista',
                                !is_null($firstVariation->cantidadMayorista) ? @num_format($firstVariation->cantidadMayorista) : null,
                                [
                                    'class' => 'form-control input-sm input_number',
                                    'id' => 'cantidadMayorista',
                                ],
                            ) !!}
                        </td>

                        {{-- Precio mayorista --}}
                        <td style="min-width: 155px;">
                            <br>

                            {!! Form::text(
                                'precioMayorista',
                                !is_null($firstVariation->precioMayorista) ? @num_format($firstVariation->precioMayorista) : null,
                                [
                                    'class' => 'form-control input-sm input_number',
                                    'id' => 'precioMayorista',
                                ],
                            ) !!}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

@endif
