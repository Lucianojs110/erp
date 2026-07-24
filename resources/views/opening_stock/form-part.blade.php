@php
    /*
     * weight guarda el peso por metro.
     * length guarda el largo de la pieza.
     */

    $rawWeight = trim((string) ($product->weight ?? ''));

    if (is_numeric($rawWeight)) {
        $weightPerMeter = (float) $rawWeight;
    } else {
        $normalizedWeight = str_replace('.', '', $rawWeight);
        $normalizedWeight = str_replace(',', '.', $normalizedWeight);

        $weightPerMeter = (float) $normalizedWeight;
    }

    $rawLength = trim((string) ($product->length ?? ''));

    if (is_numeric($rawLength)) {
        $pieceLength = (float) $rawLength;
    } else {
        $normalizedLength = str_replace('.', '', $rawLength);
        $normalizedLength = str_replace(',', '.', $normalizedLength);

        $pieceLength = (float) $normalizedLength;
    }

    /*
     * Peso total de una barra, caño o pieza completa.
     */
    $weightPerPiece = $weightPerMeter * $pieceLength;

    $managesPieces = (bool) $product->manages_packages && $weightPerPiece > 0;

    $footerColspan = 3;

    if ($managesPieces) {
        $footerColspan++;
    }

    if ($enable_expiry == 1 && $product->enable_stock == 1) {
        $footerColspan++;
    }

    if ($enable_lot == 1) {
        $footerColspan++;
    }
@endphp

<div class="row">
    <div class="col-sm-12">
        @foreach ($locations as $key => $value)
            <div class="box box-solid">
                <div class="box-header">
                    <h3 class="box-title">
                        @lang('sale.location'): {{ $value }}
                    </h3>
                </div>

                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <table
                                class="table table-condensed table-bordered table-th-green text-center table-striped add_opening_stock_table">
                                <thead>
                                    <tr>
                                        <th>
                                            @lang('product.product_name')
                                        </th>

                                        @if ($managesPieces)
                                            <th>
                                                @lang('product.pieces')
                                            </th>

                                            <th>
                                                @lang('product.weight_kg')
                                            </th>
                                        @else
                                            <th>
                                                @lang('lang_v1.quantity_left')
                                            </th>
                                        @endif

                                        <th>
                                            @lang('purchase.unit_cost_before_tax')
                                        </th>

                                        @if ($enable_expiry == 1 && $product->enable_stock == 1)
                                            <th>
                                                Exp. Date
                                            </th>
                                        @endif

                                        @if ($enable_lot == 1)
                                            <th>
                                                @lang('lang_v1.lot_number')
                                            </th>
                                        @endif

                                        <th>
                                            @lang('purchase.subtotal_before_tax')
                                        </th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @php
                                        $subtotal = 0;
                                    @endphp

                                    @foreach ($product->variations as $variation)
                                        @if (empty($purchases[$key][$variation->id]))
                                            @php
                                                $purchases[$key][$variation->id][] = [
                                                    'quantity' => 0,
                                                    'purchase_price' => $variation->default_purchase_price,
                                                    'purchase_line_id' => null,
                                                    'lot_number' => null,
                                                ];
                                            @endphp
                                        @endif

                                        @foreach ($purchases[$key][$variation->id] as $sub_key => $var)
                                            @php
                                                $purchase_line_id = $var['purchase_line_id'];

                                                $qty = (float) $var['quantity'];

                                                $purchase_price = (float) $var['purchase_price'];

                                                $row_total = $qty * $purchase_price;

                                                $subtotal += $row_total;

                                                $lot_number = $var['lot_number'];

                                                /*
                                                 * Para registros existentes:
                                                 * piezas = kilos guardados ÷ peso por pieza.
                                                 */
                                                $pieces = $managesPieces ? $qty / $weightPerPiece : 0;

                                                $quantityAttributes = [
                                                    'class' =>
                                                        'form-control input-sm input_number purchase_quantity input_quantity',
                                                    'required' => true,
                                                ];

                                                /*
                                                 * Cuando se trabaja con piezas, el peso
                                                 * se genera automáticamente.
                                                 *
                                                 * readonly permite que el valor igualmente
                                                 * se envíe al backend.
                                                 */
                                                if ($managesPieces) {
                                                    $quantityAttributes['readonly'] = true;
                                                }
                                            @endphp

                                            <tr>
                                                <td>
                                                    {{ $product->name }}

                                                    @if ($product->type == 'variable')
                                                        (<b>
                                                            {{ $variation->product_variation->name }}
                                                        </b>
                                                        :
                                                        {{ $variation->name }})
                                                    @endif

                                                    @if (!empty($purchase_line_id))
                                                        {!! Form::hidden(
                                                            'stocks[' . $key . '][' . $variation->id . '][' . $sub_key . '][purchase_line_id]',
                                                            $purchase_line_id,
                                                        ) !!}
                                                    @endif
                                                </td>

                                                @if ($managesPieces)
                                                    <td>
                                                        {!! Form::text(
                                                            'piece_quantity_display[' . $key . '][' . $variation->id . '][' . $sub_key . ']',
                                                            @format_quantity($pieces),
                                                            [
                                                                'class' => 'form-control input-sm input_number pieces_quantity',
                                                                'data-weight-per-piece' => $weightPerPiece,
                                                                'placeholder' => __('product.pieces'),
                                                                'autocomplete' => 'off',
                                                            ],
                                                        ) !!}

                                                        <small class="help-block">
                                                            {{ @num_format($weightPerPiece) }}
                                                            @lang('product.kg_per_piece')
                                                        </small>
                                                    </td>
                                                @endif

                                                <td>
                                                    <div class="input-group">
                                                        {!! Form::text(
                                                            'stocks[' . $key . '][' . $variation->id . '][' . $sub_key . '][quantity]',
                                                            @format_quantity($qty),
                                                            $quantityAttributes,
                                                        ) !!}

                                                        <span class="input-group-addon">
                                                            {{ $product->unit->short_name }}
                                                        </span>
                                                    </div>
                                                </td>

                                                <td>
                                                    {!! Form::text(
                                                        'stocks[' . $key . '][' . $variation->id . '][' . $sub_key . '][purchase_price]',
                                                        @num_format($purchase_price),
                                                        [
                                                            'class' => 'form-control input-sm input_number unit_price',
                                                            'required',
                                                        ],
                                                    ) !!}
                                                </td>

                                                @if ($enable_expiry == 1 && $product->enable_stock == 1)
                                                    <td>
                                                        {!! Form::text(
                                                            'stocks[' . $key . '][' . $variation->id . '][' . $sub_key . '][exp_date]',
                                                            !empty($var['exp_date']) ? @format_date($var['exp_date']) : null,
                                                            [
                                                                'class' => 'form-control input-sm os_exp_date',
                                                                'readonly',
                                                            ],
                                                        ) !!}
                                                    </td>
                                                @endif

                                                @if ($enable_lot == 1)
                                                    <td>
                                                        {!! Form::text('stocks[' . $key . '][' . $variation->id . '][' . $sub_key . '][lot_number]', $lot_number, [
                                                            'class' => 'form-control input-sm',
                                                        ]) !!}
                                                    </td>
                                                @endif

                                                <td>
                                                    <span class="row_subtotal_before_tax">
                                                        {{ @num_format($row_total) }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                </tbody>

                                <tfoot>
                                    <tr>
                                        <td colspan="{{ $footerColspan }}"></td>

                                        <td>
                                            <strong>
                                                @lang('lang_v1.total_amount_exc_tax'):
                                            </strong>

                                            <span id="total_subtotal">
                                                {{ @num_format($subtotal) }}
                                            </span>

                                            <input type="hidden" id="total_subtotal_hidden" value="0">
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
