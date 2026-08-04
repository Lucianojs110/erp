<div class="row" id="quick_product_opening_stock_div">
    <div class="col-sm-12">
        <h4>@lang('lang_v1.add_opening_stock')</h4>
    </div>

    <div class="col-sm-12">
        <table class="table table-condensed table-th-green" id="quick_product_opening_stock_table">
            <thead>
                <tr>
                    <th>@lang('sale.location')</th>

                    {{-- Solo se muestran cuando está activo "Maneja piezas" --}}
                    <th class="package-stock-column" style="display: none;">
                        Piezas
                    </th>

                    <th class="package-stock-column" style="display: none;">
                        Kg por pieza
                    </th>

                    <th>
                        @lang('lang_v1.quantity')
                        <span class="stock-quantity-unit"></span>
                    </th>

                    <th>@lang('purchase.unit_cost_before_tax')</th>

                    @if ($enable_expiry)
                        <th>@lang('lang_v1.expiry_date')</th>
                    @endif

                    @if ($enable_lot)
                        <th>@lang('lang_v1.lot_number')</th>
                    @endif

                    <th>@lang('purchase.subtotal_before_tax')</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($locations as $key => $value)
                    <tr>
                        <td>{{ $value }}</td>

                        {{-- Cantidad de piezas: campo auxiliar --}}
                        <td class="package-stock-column" style="display: none;">
                            {!! Form::number('', null, [
                                'class' => 'form-control input-sm opening-stock-packages',
                                'min' => '0',
                                'step' => '1',
                                'placeholder' => '0',
                            ]) !!}
                        </td>

                        {{-- Peso calculado de cada pieza --}}
                        <td class="package-stock-column" style="display: none;">
                            {!! Form::text('', null, [
                                'class' => 'form-control input-sm opening-stock-package-weight',
                                'readonly',
                                'placeholder' => '0,00',
                            ]) !!}
                        </td>

                        {{-- El sistema sigue guardando el stock real en quantity --}}
                        <td>
                            {!! Form::text('opening_stock[' . $key . '][quantity]', 0, [
                                'class' => 'form-control input-sm input_number purchase_quantity opening-stock-quantity',
                                'required',
                            ]) !!}
                        </td>

                        <td>
                            {!! Form::text('opening_stock[' . $key . '][purchase_price]', null, [
                                'class' => 'form-control input-sm input_number unit_price',
                                'required',
                            ]) !!}
                        </td>

                        @if ($enable_expiry)
                            <td>
                                {!! Form::text('opening_stock[' . $key . '][exp_date]', null, [
                                    'class' => 'form-control input-sm os_exp_date',
                                    'readonly',
                                ]) !!}
                            </td>
                        @endif

                        @if ($enable_lot)
                            <td>
                                {!! Form::text('opening_stock[' . $key . '][lot_number]', null, [
                                    'class' => 'form-control input-sm',
                                ]) !!}
                            </td>
                        @endif

                        <td>
                            <span class="row_subtotal_before_tax">0</span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
