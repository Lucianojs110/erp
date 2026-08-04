<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        {!! Form::open([
            'url' => action('ProductController@saveQuickProduct'),
            'method' => 'post',
            'id' => 'quick_add_product_form',
        ]) !!}

        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <h4 class="modal-title" id="modalTitle">@lang('product.add_new_product')</h4>
        </div>

        <div class="modal-body">
            <div class="row">

                <div class="col-md-4">
                    <div class="form-group">
                        {!! Form::label('name', __('product.product_name') . ':*') !!}
                        {!! Form::text('name', $product_name, [
                            'class' => 'form-control',
                            'required',
                            'placeholder' => __('product.product_name'),
                        ]) !!}

                        {!! Form::select('type', ['single' => 'Single', 'variable' => 'Variable'], 'single', [
                            'class' => 'hide',
                            'id' => 'type',
                        ]) !!}
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="form-group">
                        {!! Form::label('brand_id', __('product.brand') . ':') !!}
                        {!! Form::select('brand_id', $brands, null, [
                            'placeholder' => __('messages.please_select'),
                            'class' => 'form-control select2',
                        ]) !!}
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="form-group">
                        {!! Form::label('unit_id', __('product.unit') . ':*') !!}
                        {!! Form::select('unit_id', $units, null, [
                            'placeholder' => __('messages.please_select'),
                            'class' => 'form-control select2',
                            'required',
                        ]) !!}
                    </div>
                </div>

                <div class="clearfix"></div>

                <div class="col-sm-4">
                    <div class="form-group">
                        {!! Form::label('category_id', __('product.category') . ':') !!}
                        {!! Form::select('category_id', $categories, null, [
                            'placeholder' => __('messages.please_select'),
                            'class' => 'form-control select2',
                        ]) !!}
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="form-group">
                        {!! Form::label('sku', __('product.sku') . ':') !!}
                        @show_tooltip(__('tooltip.sku'))

                        {!! Form::text('sku', null, [
                            'class' => 'form-control',
                            'placeholder' => __('product.sku'),
                        ]) !!}
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="form-group">
                        {!! Form::label('barcode_type', __('product.barcode_type') . ':*') !!}
                        {!! Form::select('barcode_type', $barcode_types, 'C128', [
                            'class' => 'form-control select2',
                            'required',
                        ]) !!}
                    </div>
                </div>

                <div class="clearfix"></div>

                <div class="col-sm-4">
                    <div class="form-group">
                        <br>
                        <label>
                            {!! Form::checkbox('enable_stock', 1, true, [
                                'class' => 'input-icheck',
                                'id' => 'enable_stock',
                            ]) !!}
                            <strong>@lang('product.manage_stock')</strong>
                        </label>

                        @show_tooltip(__('tooltip.enable_stock'))

                        <p class="help-block">
                            <i>@lang('product.enable_stock_help')</i>
                        </p>
                    </div>
                </div>

                <div class="col-sm-4" id="alert_quantity_div">
                    <div class="form-group">
                        {!! Form::label('alert_quantity', __('product.alert_quantity') . ':*') !!}
                        {!! Form::number('alert_quantity', null, [
                            'class' => 'form-control',
                            'required',
                            'placeholder' => __('product.alert_quantity'),
                            'min' => '0',
                        ]) !!}
                    </div>
                </div>

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
                                {!! Form::label('expiry_period', __('product.expires_in') . ':') !!}
                                <br>

                                {!! Form::text('expiry_period', $expiry_period, [
                                    'class' => 'form-control pull-left input_number',
                                    'placeholder' => __('product.expiry_period'),
                                    'style' => 'width:60%;',
                                ]) !!}

                                {!! Form::select(
                                    'expiry_period_type',
                                    [
                                        'months' => __('product.months'),
                                        'days' => __('product.days'),
                                        '' => __('product.not_applicable'),
                                    ],
                                    'months',
                                    [
                                        'class' => 'form-control select2 pull-left',
                                        'style' => 'width:40%;',
                                        'id' => 'expiry_period_type',
                                    ],
                                ) !!}
                            </div>
                        </div>
                    </div>
                @endif

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

                <div class="clearfix"></div>

                {{-- CAMPOS DE PESO, LARGO Y PIEZAS --}}
                <div class="col-sm-3">
                    <div class="form-group">
                        {!! Form::label('measurement_type', 'Tipo de medida:') !!}

                        {!! Form::select(
                            'measurement_type',
                            [
                                'linear' => 'Metro lineal',
                                'surface' => 'Metro cuadrado',
                            ],
                            old('measurement_type', 'linear'),
                            [
                                'class' => 'form-control select2',
                                'id' => 'measurement_type',
                            ],
                        ) !!}
                    </div>
                </div>

                <div class="col-sm-3">
                    <div class="form-group">
                        {!! Form::label('weight', 'Peso por metro lineal (kg/m):', ['id' => 'weight_label']) !!}

                        {!! Form::text('weight', old('weight'), [
                            'class' => 'form-control input_number',
                            'id' => 'weight',
                            'placeholder' => 'Ej: 2,23',
                        ]) !!}
                    </div>
                </div>

                <div class="col-sm-3">
                    <div class="form-group">
                        {!! Form::label('length', 'Largo de la pieza (m):', ['id' => 'length_label']) !!}

                        {!! Form::text('length', old('length'), [
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
                            {!! Form::checkbox('manages_packages', 1, old('manages_packages', false), [
                                'class' => 'input-icheck',
                                'id' => 'manages_packages',
                            ]) !!}

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

                <div class="col-sm-4">
                    <div class="form-group">
                        {!! Form::label('tax', __('product.applicable_tax') . ':') !!}

                        {!! Form::select(
                            'tax',
                            $taxes,
                            null,
                            [
                                'placeholder' => __('messages.please_select'),
                                'class' => 'form-control select2',
                            ],
                            $tax_attributes,
                        ) !!}
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="form-group">
                        {!! Form::label('tax_type', __('product.selling_price_tax_type') . ':*') !!}

                        {!! Form::select(
                            'tax_type',
                            [
                                'inclusive' => __('product.inclusive'),
                                'exclusive' => __('product.exclusive'),
                            ],
                            'exclusive',
                            [
                                'class' => 'form-control select2',
                                'required',
                            ],
                        ) !!}
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="checkbox">
                        <br>

                        <label>
                            {!! Form::checkbox('enable_sr_no', 1, false, [
                                'class' => 'input-icheck',
                            ]) !!}

                            <strong>@lang('lang_v1.enable_imei_or_sr_no')</strong>
                        </label>

                        @show_tooltip(__('lang_v1.tooltip_sr_no'))
                    </div>
                </div>

                <div class="clearfix"></div>

                @if (!empty($module_form_parts))
                    @foreach ($module_form_parts as $key => $value)
                        @if (!empty($value['template_path']))
                            @php
                                $template_data = $value['template_data'] ?: [];
                            @endphp

                            @include($value['template_path'], $template_data)
                        @endif
                    @endforeach
                @endif

            </div>

            {{-- Este partial conserva los precios originales,
           incluido el bloque de compra en dólares. --}}
            <div class="row">
                <div class="form-group col-sm-11 col-sm-offset-1">
                    @include('product.partials.single_product_form_part', [
                        'profit_percent' => $default_profit_percent,
                    ])
                </div>
            </div>

            @if (!empty($product_for) && $product_for == 'pos')
                @include('product.partials.quick_product_opening_stock', [
                    'locations' => $locations,
                ])
            @endif
        </div>

        <div class="modal-footer">
            <button type="submit" class="btn btn-primary" id="submit_quick_product">
                @lang('messages.save')
            </button>

            <button type="button" class="btn btn-default" data-dismiss="modal">
                @lang('messages.close')
            </button>
        </div>

        {!! Form::close() !!}
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        var $form = $('form#quick_add_product_form');

        /*
         * CKEditor.
         */
        if (
            typeof CKEDITOR !== 'undefined' &&
            document.getElementById('product_description')
        ) {
            CKEDITOR.config.height = 60;

            if (CKEDITOR.instances.product_description) {
                CKEDITOR.instances.product_description.destroy(true);
            }

            CKEDITOR.replace('product_description');
        }

        var $quickProductForm = $('form#quick_add_product_form');

        function readOpeningStockNumber($input) {
            if (typeof __read_number === 'function') {
                return __read_number($input) || 0;
            }

            var value = String($input.val() || '')
                .trim()
                .replace(/\./g, '')
                .replace(',', '.');

            return parseFloat(value) || 0;
        }

        function writeOpeningStockNumber($input, value) {
            if (typeof __write_number === 'function') {
                __write_number($input, value);
                return;
            }

            $input.val(Number(value).toFixed(2));
        }

        /**
         * Calcula cuántos kilos tiene una pieza.
         *
         * Metro lineal:
         * peso kg/m × largo
         *
         * Metro cuadrado:
         * peso kg/m² × superficie
         */
        function getWeightPerPackage() {
            var weight = readOpeningStockNumber(
                $quickProductForm.find('#weight')
            );

            var length = readOpeningStockNumber(
                $quickProductForm.find('#length')
            );

            return weight * length;
        }

        /**
         * Recalcula una fila del stock inicial.
         */
        function updateOpeningStockRow($row) {
            var packages = readOpeningStockNumber(
                $row.find('.opening-stock-packages')
            );

            var weightPerPackage = getWeightPerPackage();

            var totalWeight = packages * weightPerPackage;

            writeOpeningStockNumber(
                $row.find('.opening-stock-package-weight'),
                weightPerPackage
            );

            writeOpeningStockNumber(
                $row.find('.opening-stock-quantity'),
                totalWeight
            );

            /*
             * Dispara los eventos originales para recalcular el subtotal:
             * kilos totales × costo por kilo.
             */
            $row.find('.opening-stock-quantity')
                .trigger('input')
                .trigger('keyup')
                .trigger('change');
        }

        /**
         * Recalcula todas las ubicaciones.
         */
        function updateAllOpeningStockRows() {
            $quickProductForm
                .find('#quick_product_opening_stock_table tbody tr')
                .each(function() {
                    updateOpeningStockRow($(this));
                });
        }

        /**
         * Activa o desactiva la carga por piezas.
         */
        function togglePackageOpeningStock() {
            var managesPackages = $quickProductForm
                .find('#manages_packages')
                .is(':checked');

            $quickProductForm
                .find('.package-stock-column')
                .toggle(managesPackages);

            $quickProductForm
                .find('.stock-quantity-unit')
                .text(managesPackages ? '(kg)' : '');

            /*
             * Si maneja piezas, los kilos se calculan automáticamente.
             * Si no maneja piezas, quantity vuelve a ser editable.
             */
            $quickProductForm
                .find('.opening-stock-quantity')
                .prop('readonly', managesPackages);

            if (managesPackages) {
                updateAllOpeningStockRows();
            }
        }

        /**
         * Cuando cambia la cantidad de piezas.
         */
        $quickProductForm.on(
            'input change',
            '.opening-stock-packages',
            function() {
                updateOpeningStockRow(
                    $(this).closest('tr')
                );
            }
        );

        /**
         * Cuando cambia peso, largo o superficie.
         */
        $quickProductForm.on(
            'input change',
            '#weight, #length',
            function() {
                updateAllOpeningStockRows();
            }
        );

        /**
         * Eventos de iCheck.
         */
        $(document)
            .off(
                'ifChecked.quickOpeningStock',
                '#quick_add_product_form #manages_packages'
            )
            .on(
                'ifChecked.quickOpeningStock',
                '#quick_add_product_form #manages_packages',
                function() {
                    togglePackageOpeningStock();
                }
            );

        $(document)
            .off(
                'ifUnchecked.quickOpeningStock',
                '#quick_add_product_form #manages_packages'
            )
            .on(
                'ifUnchecked.quickOpeningStock',
                '#quick_add_product_form #manages_packages',
                function() {
                    togglePackageOpeningStock();
                }
            );

        /**
         * Respaldo para checkbox normal.
         */
        $quickProductForm.on(
            'change',
            '#manages_packages',
            function() {
                togglePackageOpeningStock();
            }
        );

        togglePackageOpeningStock();

        /*
         * Cambia las etiquetas según sea metro lineal o metro cuadrado.
         */
        function updateMeasurementFields() {
            var type = $form.find('#measurement_type').val();
            var isSurface = type === 'surface';

            $form.find('#weight_label').text(
                isSurface ?
                'Peso por metro cuadrado (kg/m²):' :
                'Peso por metro lineal (kg/m):'
            );

            $form.find('#length_label').text(
                isSurface ?
                'Superficie de la pieza (m²):' :
                'Largo de la pieza (m):'
            );

            $form.find('#weight').attr(
                'placeholder',
                isSurface ?
                'Ej: 5,60' :
                'Ej: 2,23'
            );

            $form.find('#length').attr(
                'placeholder',
                isSurface ?
                'Ej: 2,97 o 4,50' :
                'Ej: 6 o 12'
            );

            $form.find('#measurement_help').text(
                isSurface ?
                'Peso de la pieza = kg/m² × superficie.' :
                'Peso de la pieza = kg/m × largo.'
            );
        }

        $form
            .off('change.quickMeasurement', '#measurement_type')
            .on(
                'change.quickMeasurement',
                '#measurement_type',
                updateMeasurementFields
            );

        updateMeasurementFields();

        /*
         * Funciones para compra en dólares.
         * Los inputs se renderizan dentro de single_product_form_part.
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

        function writeNumber($input, value) {
            if (typeof __write_number === 'function') {
                __write_number($input, value);
                return;
            }

            $input.val(Number(value).toFixed(2));
        }

        function triggerOriginalPriceCalculation() {
            $form.find('#single_dpp')
                .trigger('input')
                .trigger('keyup')
                .trigger('change');

            $form.find('#profit_percent')
                .trigger('input')
                .trigger('keyup')
                .trigger('change');
        }

        function calculatePurchasePriceFromUsd() {
            var $usdCheckbox = $form.find('#purchase_price_in_usd');

            if (
                !$usdCheckbox.length ||
                !$usdCheckbox.is(':checked')
            ) {
                return;
            }

            var exchangeRate = parseFloat(
                $form.find('#usd_exchange_rate_raw').val()
            ) || 0;

            var purchasePriceUsd = readNumber(
                $form.find('#single_dpp_usd')
            );

            if (exchangeRate <= 0) {
                if (typeof toastr !== 'undefined') {
                    toastr.error(
                        'Debes configurar una cotización del dólar válida.'
                    );
                }

                return;
            }

            writeNumber(
                $form.find('#single_dpp'),
                purchasePriceUsd * exchangeRate
            );

            setTimeout(function() {
                triggerOriginalPriceCalculation();
            }, 0);
        }

        function toggleUsdPurchaseMode(enabled) {
            var $usdInput = $form.find('#single_dpp_usd');
            var $purchasePrice = $form.find('#single_dpp');
            var $purchasePriceIncTax = $form.find('#single_dpp_inc_tax');

            $form.find('.usd-purchase-column').toggleClass(
                'hide',
                !enabled
            );

            $usdInput
                .prop('disabled', !enabled)
                .prop('required', enabled);

            $purchasePrice.prop('readonly', enabled);
            $purchasePriceIncTax.prop('readonly', enabled);

            if (enabled) {
                calculatePurchasePriceFromUsd();
            }
        }

        $(document)
            .off('ifChecked.quickProductUsd', '#purchase_price_in_usd')
            .on(
                'ifChecked.quickProductUsd',
                '#purchase_price_in_usd',
                function() {
                    toggleUsdPurchaseMode(true);
                }
            );

        $(document)
            .off('ifUnchecked.quickProductUsd', '#purchase_price_in_usd')
            .on(
                'ifUnchecked.quickProductUsd',
                '#purchase_price_in_usd',
                function() {
                    toggleUsdPurchaseMode(false);
                }
            );

        $form
            .off('change.quickProductUsd', '#purchase_price_in_usd')
            .on(
                'change.quickProductUsd',
                '#purchase_price_in_usd',
                function() {
                    toggleUsdPurchaseMode($(this).is(':checked'));
                }
            );

        $form
            .off(
                'input.quickProductUsd keyup.quickProductUsd change.quickProductUsd',
                '#single_dpp_usd'
            )
            .on(
                'input.quickProductUsd keyup.quickProductUsd change.quickProductUsd',
                '#single_dpp_usd',
                calculatePurchasePriceFromUsd
            );

        $form
            .off(
                'input.quickProductProfit keyup.quickProductProfit change.quickProductProfit',
                '#profit_percent'
            )
            .on(
                'input.quickProductProfit keyup.quickProductProfit change.quickProductProfit',
                '#profit_percent',
                function() {
                    if (
                        $form.find('#purchase_price_in_usd').is(':checked')
                    ) {
                        $form.find('#single_dpp')
                            .trigger('keyup')
                            .trigger('change');
                    }
                }
            );

        if ($form.find('#purchase_price_in_usd').length) {
            toggleUsdPurchaseMode(
                $form.find('#purchase_price_in_usd').is(':checked')
            );
        }

        /*
         * Validación y guardado AJAX.
         */
        $form.validate({
            rules: {
                sku: {
                    remote: {
                        url: '/products/check_product_sku',
                        type: 'post',
                        data: {
                            sku: function() {
                                return $form.find('#sku').val();
                            },
                            product_id: function() {
                                if ($('#product_id').length > 0) {
                                    return $('#product_id').val();
                                }

                                return '';
                            }
                        }
                    }
                },
                expiry_period: {
                    required: {
                        depends: function() {
                            var value = $form.find('#expiry_period_type').val();

                            return value && value.trim() !== '';
                        }
                    }
                }
            },
            messages: {
                sku: {
                    remote: LANG.sku_already_exists
                }
            },
            submitHandler: function() {
                if (
                    typeof CKEDITOR !== 'undefined' &&
                    CKEDITOR.instances.product_description
                ) {
                    CKEDITOR.instances.product_description.updateElement();
                }

                var url = $form.attr('action');
                var $submitButton = $form.find('button[type="submit"]');

                $submitButton.prop('disabled', true);

                $.ajax({
                    method: 'POST',
                    url: url,
                    dataType: 'json',
                    data: $form.serialize(),

                    success: function(data) {
                        if (data.success) {
                            $('.quick_add_product_modal').modal('hide');
                            toastr.success(data.msg);

                            if (
                                typeof get_purchase_entry_row !== 'undefined'
                            ) {
                                get_purchase_entry_row(data.product.id, 0);
                            }

                            $(document).trigger({
                                type: 'quickProductAdded',
                                product: data.product,
                                variation: data.variation
                            });

                            return;
                        }

                        toastr.error(
                            data.msg || 'No se pudo guardar el producto'
                        );
                    },

                    error: function(xhr) {
                        console.error(xhr.responseText);

                        if (
                            xhr.status === 422 &&
                            xhr.responseJSON &&
                            xhr.responseJSON.errors
                        ) {
                            $.each(
                                xhr.responseJSON.errors,
                                function(field, messages) {
                                    $.each(messages, function(index, message) {
                                        toastr.error(message);
                                    });
                                }
                            );

                            return;
                        }

                        toastr.error(
                            'Ocurrió un problema al guardar el producto'
                        );
                    },

                    complete: function() {
                        $submitButton.prop('disabled', false);
                    }
                });

                return false;
            }
        });
    });
</script>
