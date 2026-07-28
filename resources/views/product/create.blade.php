@extends('layouts.app')
@section('title', __('product.add_new_product'))

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>@lang('product.add_new_product')</h1>
        <!-- <ol class="breadcrumb">
                                                                                    <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
                                                                                    <li class="active">Here</li>
                                                                                </ol> -->
    </section>

    <!-- @if ($errors->any())
                                                            <div style="color: red;">
                                                                                    <ul>
                                                                                        @foreach ($errors->all() as $error)
    <li>{{ $error }}</li>
    @endforeach
                                                                                    </ul>
                                                                                </div>
                                                            @endif -->

    <!-- Main content -->
    <section class="content">
        {!! Form::open([
            'url' => action('ProductController@store'),
            'method' => 'post',
            'id' => 'product_add_form',
            'class' => 'product_form',
            'files' => true,
        ]) !!}
        @component('components.widget', ['class' => 'box-primary'])
            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group">
                        {!! Form::label('name', __('product.product_name') . ':*') !!}
                        {!! Form::text('name', !empty($duplicate_product->name) ? $duplicate_product->name : null, [
                            'class' => 'form-control',
                            'required',
                            'placeholder' => __('product.product_name'),
                        ]) !!}
                    </div>
                </div>
                <div class="col-sm-4 @if (!session('business.enable_brand')) hide @endif">
                    <div class="form-group">
                        {!! Form::label('brand_id', __('product.brand') . ':') !!}
                        <div class="input-group">
                            {!! Form::select(
                                'brand_id',
                                $brands,
                                !empty($duplicate_product->brand_id) ? $duplicate_product->brand_id : null,
                                ['placeholder' => __('messages.please_select'), 'class' => 'form-control select2'],
                            ) !!}
                            <span class="input-group-btn">
                                <button type="button" @if (!auth()->user()->can('brand.create')) disabled @endif
                                    class="btn btn-default bg-white btn-flat btn-modal"
                                    data-href="{{ action('BrandController@create', ['quick_add' => true]) }}"
                                    title="@lang('brand.add_brand')" data-container=".view_modal"><i
                                        class="fa fa-plus-circle text-primary fa-lg"></i></button>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        {!! Form::label('unit_id', __('product.unit') . ':*') !!}
                        <div class="input-group">
                            {!! Form::select(
                                'unit_id',
                                $units,
                                !empty($duplicate_product->unit_id) ? $duplicate_product->unit_id : session('business.default_unit'),
                                ['class' => 'form-control select2', 'required'],
                            ) !!}
                            <span class="input-group-btn">
                                <button type="button" @if (!auth()->user()->can('unit.create')) disabled @endif
                                    class="btn btn-default bg-white btn-flat btn-modal"
                                    data-href="{{ action('UnitController@create', ['quick_add' => true]) }}"
                                    title="@lang('unit.add_unit')" data-container=".view_modal"><i
                                        class="fa fa-plus-circle text-primary fa-lg"></i></button>
                            </span>
                        </div>

                    </div>
                </div>

                <div class="clearfix"></div>

                <div class="col-sm-4 @if (!session('business.enable_category')) hide @endif">
                    <div class="form-group">
                        {!! Form::label('category_id', __('product.category') . ':') !!}
                        {!! Form::select(
                            'category_id',
                            $categories,
                            !empty($duplicate_product->category_id) ? $duplicate_product->category_id : null,
                            ['placeholder' => __('messages.please_select'), 'class' => 'form-control select2'],
                        ) !!}
                    </div>
                </div>

                <div class="col-sm-4 @if (!(session('business.enable_category') && session('business.enable_sub_category'))) hide @endif">
                    <div class="form-group">
                        {!! Form::label('sub_category_id', __('product.sub_category') . ':') !!}
                        {!! Form::select(
                            'sub_category_id',
                            $sub_categories,
                            !empty($duplicate_product->sub_category_id) ? $duplicate_product->sub_category_id : null,
                            ['placeholder' => __('messages.please_select'), 'class' => 'form-control select2'],
                        ) !!}
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="form-group">
                        {!! Form::label('sku', __('product.sku') . ':') !!} @show_tooltip(__('tooltip.sku'))
                        {!! Form::text('sku', null, ['class' => 'form-control', 'placeholder' => __('product.sku')]) !!}
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="col-sm-4">
                    <div class="form-group">
                        {!! Form::label('barcode_type', __('product.barcode_type') . ':*') !!}
                        {!! Form::select(
                            'barcode_type',
                            $barcode_types,
                            !empty($duplicate_product->barcode_type) ? $duplicate_product->barcode_type : $barcode_default,
                            ['class' => 'form-control select2', 'required'],
                        ) !!}
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <br>
                        <label>
                            {!! Form::checkbox('enable_stock', 1, !empty($duplicate_product) ? $duplicate_product->enable_stock : true, [
                                'class' => 'input-icheck',
                                'id' => 'enable_stock',
                            ]) !!} <strong>@lang('product.manage_stock')</strong>
                        </label>@show_tooltip(__('tooltip.enable_stock')) <p class="help-block"><i>@lang('product.enable_stock_help')</i></p>
                    </div>
                </div>
                <div class="col-sm-4 @if (!empty($duplicate_product) && $duplicate_product->enable_stock == 0) hide @endif" id="alert_quantity_div">
                    <div class="form-group">
                        {!! Form::label('alert_quantity', __('product.alert_quantity') . ':*') !!} @show_tooltip(__('tooltip.alert_quantity'))
                        {!! Form::number(
                            'alert_quantity',
                            !empty($duplicate_product->alert_quantity) ? $duplicate_product->alert_quantity : null,
                            ['class' => 'form-control', 'required', 'placeholder' => __('product.alert_quantity'), 'min' => '0'],
                        ) !!}
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="col-sm-8">
                    <div class="form-group">
                        {!! Form::label('product_description', __('lang_v1.product_description') . ':') !!}
                        {!! Form::textarea(
                            'product_description',
                            !empty($duplicate_product->product_description) ? $duplicate_product->product_description : null,
                            ['class' => 'form-control'],
                        ) !!}
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        {!! Form::label('image', __('lang_v1.product_image') . ':') !!}
                        {!! Form::file('image', ['id' => 'upload_image', 'accept' => 'image/*']) !!}
                        <small>
                            <p class="help-block">@lang('purchase.max_file_size', ['size' => config('constants.document_size_limit') / 1000000]) <br> @lang('lang_v1.aspect_ratio_should_be_1_1')</p>
                        </small>
                    </div>
                </div>
            </div>
        @endcomponent

        @component('components.widget', ['class' => 'box-primary'])
            <div class="row">
                @if (session('business.enable_product_expiry'))
                    @if (session('business.expiry_type') == 'add_expiry')
                        @php
                            $expiry_period = 12;
                            $hide = true;
                        @endphp
                    @else
                        @php
                            $expiry_period = null;
                            $hide = false;
                        @endphp
                    @endif
                    <div class="col-sm-4 @if ($hide) hide @endif">
                        <div class="form-group">
                            <div class="multi-input">
                                {!! Form::label('expiry_period', __('product.expires_in') . ':') !!}<br>
                                {!! Form::text(
                                    'expiry_period',
                                    !empty($duplicate_product->expiry_period) ? @num_format($duplicate_product->expiry_period) : $expiry_period,
                                    [
                                        'class' => 'form-control pull-left input_number',
                                        'placeholder' => __('product.expiry_period'),
                                        'style' => 'width:60%;',
                                    ],
                                ) !!}
                                {!! Form::select(
                                    'expiry_period_type',
                                    ['months' => __('product.months'), 'days' => __('product.days'), '' => __('product.not_applicable')],
                                    !empty($duplicate_product->expiry_period_type) ? $duplicate_product->expiry_period_type : 'months',
                                    ['class' => 'form-control select2 pull-left', 'style' => 'width:40%;', 'id' => 'expiry_period_type'],
                                ) !!}
                            </div>
                        </div>
                    </div>
                @endif





                <div class="clearfix"></div>

                <!-- Rack, Row & position number -->
                @if (session('business.enable_racks') || session('business.enable_row') || session('business.enable_position'))
                    <div class="col-md-12">
                        <h4>@lang('lang_v1.rack_details'):
                            @show_tooltip(__('lang_v1.tooltip_rack_details'))
                        </h4>
                    </div>
                    @foreach ($business_locations as $id => $location)
                        <div class="col-sm-3">
                            <div class="form-group">
                                {!! Form::label('rack_' . $id, $location . ':') !!}

                                @if (session('business.enable_racks'))
                                    {!! Form::text(
                                        'product_racks[' . $id . '][rack]',
                                        !empty($rack_details[$id]['rack']) ? $rack_details[$id]['rack'] : null,
                                        ['class' => 'form-control', 'id' => 'rack_' . $id, 'placeholder' => __('lang_v1.rack')],
                                    ) !!}
                                @endif

                                @if (session('business.enable_row'))
                                    {!! Form::text(
                                        'product_racks[' . $id . '][row]',
                                        !empty($rack_details[$id]['row']) ? $rack_details[$id]['row'] : null,
                                        ['class' => 'form-control', 'placeholder' => __('lang_v1.row')],
                                    ) !!}
                                @endif

                                @if (session('business.enable_position'))
                                    {!! Form::text(
                                        'product_racks[' . $id . '][position]',
                                        !empty($rack_details[$id]['position']) ? $rack_details[$id]['position'] : null,
                                        ['class' => 'form-control', 'placeholder' => __('lang_v1.position')],
                                    ) !!}
                                @endif
                            </div>
                        </div>
                    @endforeach
                @endif

                @php
                    $currentProduct = $product ?? ($duplicate_product ?? null);

                    $measurementType = old('measurement_type', $currentProduct->measurement_type ?? 'linear');
                @endphp

                <div class="col-sm-3">
                    <div class="form-group">
                        {!! Form::label('measurement_type', 'Tipo de medida:') !!}

                        {!! Form::select(
                            'measurement_type',
                            [
                                'linear' => 'Metro lineal',
                                'surface' => 'Metro cuadrado',
                            ],
                            $measurementType,
                            [
                                'class' => 'form-control select2',
                                'id' => 'measurement_type',
                            ],
                        ) !!}
                    </div>
                </div>

                <div class="col-sm-3">
                    <div class="form-group">
                        {!! Form::label('weight', 'Peso por metro (kg/m):', ['id' => 'weight_label']) !!}

                        {!! Form::text('weight', old('weight', $currentProduct->weight ?? null), [
                            'class' => 'form-control input_number',
                            'id' => 'weight',
                            'placeholder' => 'Ej: 2,23',
                        ]) !!}
                    </div>
                </div>

                <div class="col-sm-3">
                    <div class="form-group">
                        {!! Form::label('length', 'Largo de la pieza (m):', ['id' => 'length_label']) !!}

                        {!! Form::text('length', old('length', $currentProduct->length ?? null), [
                            'class' => 'form-control input_number',
                            'id' => 'length',
                            'placeholder' => 'Ej: 6 o 12',
                        ]) !!}

                        <p class="help-block" id="measurement_help">
                            Peso de la pieza = kg/m × metros.
                        </p>
                    </div>
                </div>

                <div class="col-sm-3">
                    <div class="form-group">
                        <br>

                        <label>
                            {!! Form::checkbox(
                                'manages_packages',
                                1,
                                !empty($currentProduct) ? (bool) $currentProduct->manages_packages : false,
                                [
                                    'class' => 'input-icheck',
                                    'id' => 'manages_packages',
                                ],
                            ) !!}

                            <strong>Maneja piezas</strong>
                        </label>

                        <p class="help-block">
                            <i>
                                Calcula el peso teórico de cada pieza usando
                                el peso por unidad y su medida.
                            </i>
                        </p>
                    </div>
                </div>

                <div class="clearfix"></div>
                <!--custom fields-->
                <div class="clearfix"></div>

                <!--custom fields-->
                <div class="clearfix"></div>
                @include('layouts.partials.module_form_part')
            </div>
        @endcomponent

        @component('components.widget', ['class' => 'box-primary'])
            <div class="row">

                <div class="col-sm-4 @if (!session('business.enable_price_tax')) hide @endif">
                    <div class="form-group">
                        {!! Form::label('tax', __('product.applicable_tax') . ':') !!}
                        {!! Form::select(
                            'tax',
                            $taxes,
                            !empty($duplicate_product->tax) ? $duplicate_product->tax : null,
                            ['placeholder' => __('messages.please_select'), 'class' => 'form-control select2'],
                            $tax_attributes,
                        ) !!}
                    </div>
                </div>

                <div class="col-sm-4 @if (!session('business.enable_price_tax')) hide @endif">
                    <div class="form-group">
                        {!! Form::label('tax_type', __('product.selling_price_tax_type') . ':*') !!}
                        {!! Form::select(
                            'tax_type',
                            ['inclusive' => __('product.inclusive'), 'exclusive' => __('product.exclusive')],
                            !empty($duplicate_product->tax_type) ? $duplicate_product->tax_type : 'exclusive',
                            ['class' => 'form-control select2', 'required'],
                        ) !!}
                    </div>
                </div>

                <div class="clearfix"></div>

                <div class="col-sm-4">
                    <div class="form-group">
                        {!! Form::label('type', __('product.product_type') . ':*') !!} @show_tooltip(__('tooltip.product_type'))
                        {!! Form::select(
                            'type',
                            ['single' => __('lang_v1.single'), 'variable' => __('lang_v1.variable')],
                            !empty($duplicate_product->type) ? $duplicate_product->type : null,
                            [
                                'class' => 'form-control select2',
                                'required',
                                'data-action' => !empty($duplicate_product) ? 'duplicate' : 'add',
                                'data-product_id' => !empty($duplicate_product) ? $duplicate_product->id : '0',
                            ],
                        ) !!}
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="form-group">
                        <br>
                        {!! Form::checkbox('hasMayorista', 1, false, ['class' => 'input-icheck', 'id' => 'hasMayorista']) !!} <strong>¿Diferenciar precio Mayorista?</strong>
                        <label>
                        </label>@show_tooltip('Habilita o deshabilita la opcion de tener precio mayorista.') <p
                            class="help-block"><i>Habilitar el cambio de precio para este producto a partir de cierta cantidad
                                vendida</i></p>
                    </div>
                </div>

                <div class="form-group col-sm-12" id="product_form_part"></div>

                <input type="hidden" id="variation_counter" value="1">
                <input type="hidden" id="default_profit_percent" value="{{ $default_profit_percent }}">

            </div>
        @endcomponent
        <div class="row">
            <div class="col-sm-12">
                <input type="hidden" name="submit_type" id="submit_type">
                <div class="text-center">
                    <div class="btn-group">
                        @if ($selling_price_group_count)
                            <button type="submit" value="submit_n_add_selling_prices"
                                class="btn btn-warning submit_product_form">@lang('lang_v1.save_n_add_selling_price_group_prices')</button>
                        @endif

                        <button id="opening_stock_button" @if (!empty($duplicate_product) && $duplicate_product->enable_stock == 0) disabled @endif
                            type="submit" value="submit_n_add_opening_stock"
                            class="btn bg-purple submit_product_form">@lang('lang_v1.save_n_add_opening_stock')</button>

                        <button type="submit" value="save_n_add_another"
                            class="btn bg-maroon submit_product_form">@lang('lang_v1.save_n_add_another')</button>

                        <button type="submit" value="submit"
                            class="btn btn-primary submit_product_form">@lang('messages.save')</button>
                    </div>

                </div>
            </div>
        </div>
        {!! Form::close() !!}

    </section>
    <!-- /.content -->
@endsection

@section('javascript')
    @php $asset_v = env('APP_VERSION'); @endphp

    <script src="{{ asset('js/product.js?v=' . $asset_v) }}"></script>

@section('javascript')
    @php
        $asset_v = env('APP_VERSION');
    @endphp

    <script src="{{ asset('js/product.js?v=' . $asset_v) }}"></script>

    <script>
        $(document).ready(function() {


            function updateMeasurementFields() {
                var type = $('#measurement_type').val();
                var isSurface = type === 'surface';

                $('#weight_label').text(
                    isSurface ?
                    'Peso por metro cuadrado (kg/m²):' :
                    'Peso por metro lineal (kg/m):'
                );

                $('#length_label').text(
                    isSurface ?
                    'Superficie de la pieza (m²):' :
                    'Largo de la pieza (m):'
                );

                $('#weight').attr(
                    'placeholder',
                    isSurface ?
                    'Ej: 5,60' :
                    'Ej: 2,23'
                );

                $('#length').attr(
                    'placeholder',
                    isSurface ?
                    'Ej: 2,97 o 4,50' :
                    'Ej: 6 o 12'
                );

                $('#measurement_help').text(
                    isSurface ?
                    'Peso de la pieza = kg/m² × superficie.' :
                    'Peso de la pieza = kg/m × largo.'
                );
            }

            $(document).on(
                'change',
                '#measurement_type',
                updateMeasurementFields
            );

            updateMeasurementFields();

            /**
             * Lee un número utilizando las funciones originales
             * del sistema cuando están disponibles.
             */
            function readNumber($input) {
                if (typeof __read_number === 'function') {
                    return __read_number($input) || 0;
                }

                var value = String($input.val() || '')
                    .trim()
                    .replace(/\./g, '')
                    .replace(',', '.');

                return parseFloat(value) || 0;
            }

            /**
             * Escribe un número utilizando el formato original
             * del sistema cuando está disponible.
             */
            function writeNumber($input, value) {
                if (typeof __write_number === 'function') {
                    __write_number($input, value);
                    return;
                }

                $input.val(Number(value).toFixed(2));
            }

            /**
             * Ejecuta los eventos que utiliza product.js
             * para calcular impuestos y precio de venta.
             */
            function triggerOriginalPriceCalculation() {
                var $purchasePrice = $('#single_dpp');
                var $profitPercent = $('#profit_percent');

                $purchasePrice
                    .trigger('input')
                    .trigger('keyup')
                    .trigger('change');

                $profitPercent
                    .trigger('input')
                    .trigger('keyup')
                    .trigger('change');
            }

            /**
             * Convierte el precio de compra USD a ARS.
             */
            function calculatePurchasePriceFromUsd() {
                var $usdCheckbox = $('#purchase_price_in_usd');

                if (
                    !$usdCheckbox.length ||
                    !$usdCheckbox.is(':checked')
                ) {
                    return;
                }

                var exchangeRate = parseFloat(
                    $('#usd_exchange_rate_raw').val()
                ) || 0;

                var purchasePriceUsd = readNumber(
                    $('#single_dpp_usd')
                );

                if (exchangeRate <= 0) {
                    if (typeof toastr !== 'undefined') {
                        toastr.error(
                            'Debes configurar una cotización del dólar válida.'
                        );
                    }

                    return;
                }

                var purchasePriceArs =
                    purchasePriceUsd * exchangeRate;

                writeNumber(
                    $('#single_dpp'),
                    purchasePriceArs
                );

                /*
                 * Se ejecuta después de escribir el valor para que
                 * product.js calcule:
                 *
                 * - single_dpp_inc_tax
                 * - single_dsp
                 * - single_dsp_inc_tax
                 */
                setTimeout(function() {
                    triggerOriginalPriceCalculation();
                }, 0);
            }

            /**
             * Activa o desactiva el modo USD.
             */
            function toggleUsdPurchaseMode(enabled) {
                var $usdColumns = $('.usd-purchase-column');
                var $usdInput = $('#single_dpp_usd');

                var $purchasePrice =
                    $('#single_dpp');

                var $purchasePriceIncTax =
                    $('#single_dpp_inc_tax');

                $usdColumns.toggleClass(
                    'hide',
                    !enabled
                );

                $usdInput
                    .prop('disabled', !enabled)
                    .prop('required', enabled);

                /*
                 * En modo USD el costo ARS se calcula.
                 * En modo normal vuelve a ser editable.
                 */
                $purchasePrice.prop(
                    'readonly',
                    enabled
                );

                $purchasePriceIncTax.prop(
                    'readonly',
                    enabled
                );

                if (enabled) {
                    calculatePurchasePriceFromUsd();
                }
            }

            /**
             * Eventos de iCheck.
             */
            $(document).on(
                'ifChecked',
                '#purchase_price_in_usd',
                function() {
                    toggleUsdPurchaseMode(true);
                }
            );

            $(document).on(
                'ifUnchecked',
                '#purchase_price_in_usd',
                function() {
                    toggleUsdPurchaseMode(false);
                }
            );

            /**
             * Respaldo para checkbox sin iCheck.
             */
            $(document).on(
                'change',
                '#purchase_price_in_usd',
                function() {
                    toggleUsdPurchaseMode(
                        $(this).is(':checked')
                    );
                }
            );

            /**
             * Recalcula cuando cambia el precio USD.
             */
            $(document).on(
                'input keyup change',
                '#single_dpp_usd',
                function() {
                    calculatePurchasePriceFromUsd();
                }
            );

            /**
             * Recalcula si cambia el porcentaje de ganancia.
             */
            $(document).on(
                'input keyup change',
                '#profit_percent',
                function() {
                    if (
                        $('#purchase_price_in_usd')
                        .is(':checked')
                    ) {
                        $('#single_dpp')
                            .trigger('keyup')
                            .trigger('change');
                    }
                }
            );

            /**
             * El formulario de precios se carga dinámicamente
             * dentro de #product_form_part.
             */
            var productFormContainer =
                document.getElementById(
                    'product_form_part'
                );

            if (productFormContainer) {
                var observer = new MutationObserver(
                    function() {
                        var $checkbox =
                            $('#purchase_price_in_usd');

                        if (!$checkbox.length) {
                            return;
                        }

                        toggleUsdPurchaseMode(
                            $checkbox.is(':checked')
                        );
                    }
                );

                observer.observe(
                    productFormContainer, {
                        childList: true,
                        subtree: true
                    }
                );
            }

        });
    </script>
@endsection
