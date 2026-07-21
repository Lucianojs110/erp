<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-default">
            <div class="panel-body bg-gray disabled" style="margin-bottom: 0px !important">
                <table class="table table-condensed" style="margin-bottom: 0px !important">
                    <tbody>


                        <tr>
                            <td>
                                <div class="col-sm-1 col-xs-3 d-inline-table">
                                    <b>@lang('sale.item'):</b><br />
                                    <span class="total_quantity">0</span>
                                </div>

                                <div class="col-sm-1 col-xs-3 d-inline-table">
                                    <b>@lang('sale.total'):</b><br />
                                    <span class="price_total">0</span>
                                    <input type="hidden" name="price_total" id="price_total" value="0">
                                </div>

                                <div class="col-sm-2 col-xs-6 d-inline-table">
                                    <b>@lang('sale.discount')(-):</b><br />
                                    <i class="fa fa-pencil-square-o cursor-pointer" id="pos-edit-discount"
                                        title="@lang('sale.edit_discount')" aria-hidden="true" data-toggle="modal"
                                        data-target="#posEditDiscountModal"></i>
                                    <span id="total_discount">0</span>
                                    <input type="hidden" name="discount_type" id="discount_type" value="percentage"
                                        data-default="percentage">
                                    <input type="hidden" name="discount_amount" id="discount_amount" value="0"
                                        data-default="0">
                                </div>

                                <div class="col-sm-2 col-xs-6 d-inline-table">
                                    <b>@lang('sale.order_tax')(+):</b><br />
                                    <i class="fa fa-pencil-square-o cursor-pointer" title="@lang('sale.edit_order_tax')"
                                        aria-hidden="true" data-toggle="modal" data-target="#posEditOrderTaxModal"
                                        id="pos-edit-tax"></i>
                                    <span id="order_tax">0</span>
                                    <input type="hidden" name="tax_rate_id" id="tax_rate_id" value=""
                                        data-default="">
                                    <input type="hidden" name="tax_calculation_amount" id="tax_calculation_amount"
                                        value="0" data-default="0">
                                </div>

                                <div class="col-sm-2 col-xs-6 d-inline-table">
                                    <b>Cargos(+):</b><br />
                                    <i class="fa fa-pencil-square-o cursor-pointer" title="Cargos" aria-hidden="true"
                                        data-toggle="modal" data-target="#posShippingModal"></i>
                                    <span id="shipping_charges_amount">0</span>
                                    <input type="hidden" name="shipping_details" id="shipping_details" value=""
                                        data-default="">
                                    <input type="hidden" name="shipping_charges" id="shipping_charges" value="0.00"
                                        data-default="0.00">
                                </div>

                                <input type="hidden" name="transaction_details" id="transaction_details">

                                <div class="col-sm-2 col-xs-12 d-inline-table">
                                    <b>@lang('sale.total_payable'):</b><br />
                                    <input type="hidden" name="final_total" id="final_total_input" value="0">
                                    <span id="total_payable" class="text-success lead text-bold">0</span>
                                </div>

                                <input type="hidden" id="total_without_tax" name="total_without_tax">
                                <input type="hidden" id="modal_cambio" value="0">

                                <div class="col-sm-2 col-xs-12 d-inline-table">
                                    <div class="d-flex flex-wrap align-items-center gap-3">
                                        <div class="form-check mb-0">
                                            <input class="form-check-input" type="checkbox"name="registrar"
                                                {{ $pos_settings['check_afip'] ? 'checked' : '' }} id="registrar"
                                                style="width:25px; height:25px;">
                                            <label class="form-check-label" for="registrar">Registrar en AFIP</label>
                                        </div>
                                        @if (($pos_settings['carries_a_bag'] ?? 0) == 1)
                                            <div class="form-check mb-0">
                                                <input class="form-check-input" type="checkbox" name="carries_a_bag"
                                                    id="carries_a_bag" value="1"
                                                    style="width: 25px; height: 25px;">

                                                <label class="form-check-label" for="carries_a_bag">
                                                    @lang('lang_v1.carries_a_bag')
                                                </label>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <div class="col-sm-2 col-xs-6 col-2px-padding">
                                    <button type="button" class="btn btn-warning btn-block btn-flat"
                                        id="pos-draft">@lang('sale.draft')</button>
                                    <button type="button" class="btn btn-info btn-block btn-flat"
                                        id="pos-quotation">@lang('lang_v1.quotation')</button>
                                </div>

                                <div class="col-sm-3 col-xs-6 col-2px-padding">
                                    <button type="button"
                                        class="btn bg-maroon btn-block btn-flat no-print pos-express-finalize"
                                        data-pay_method="card">
                                        <div class="text-center">
                                            <i class="fa fa-check"></i>
                                            <b>@lang('lang_v1.express_checkout_card')</b>
                                        </div>
                                    </button>

                                    <button type="button" id="pos-suspend"
                                        class="btn bg-red btn-block btn-flat no-print pos-suspend"
                                        data-pay_method="suspend">
                                        <div class="text-center">
                                            <i class="fa fa-check"></i>
                                            <b>Cuenta Corriente</b>
                                        </div>
                                    </button>
                                </div>

                                <div class="col-sm-3 col-xs-6 col-2px-padding">
                                    <button type="button"
                                        class="btn bg-purple btn-block btn-flat no-print pos-express-finalize"
                                        data-pay_method="cheque">
                                        <div class="text-center">
                                            <i class="fa fa-check"></i>
                                            <b>@lang('lang_v1.cheque')</b>
                                        </div>
                                    </button>
                                    <button type="button"
                                        class="btn bg-navy btn-block btn-flat no-print pago_multiple"
                                        id="pos-finalize">
                                        <div class="text-center">
                                            <i class="fa fa-check"></i>
                                            <b>@lang('lang_v1.checkout_multi_pay')</b>
                                        </div>
                                    </button>
                                </div>

                                <div class="col-sm-3 col-xs-12 col-2px-padding">
                                    <button type="button"
                                        class="btn bg-blue btn-block btn-flat no-print pos-express-finalize">
                                        <div class="text-center">
                                            <i class="fa fa-check"></i>
                                            <b>@lang('lang_v1.mp')</b>
                                        </div>
                                    </button>
                                    <button type="button"
                                        class="btn btn-success btn-block btn-flat no-print pos-express-finalize"
                                        data-pay_method="cash">
                                        <div class="text-center">
                                            <i class="fa fa-check"></i>
                                            <b>@lang('lang_v1.express_checkout_cash')</b>
                                        </div>
                                    </button>
                                </div>

                                <div class="div-overlay pos-processing"></div>

                                <div class="col-sm-1 col-xs-6 col-2px-padding text-right">
                                    <button type="button" class="btn btn-danger btn-xs btn-flat no-print"
                                        id="pos-cancel">
                                        @lang('sale.cancel')
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


@if (isset($transaction))
    @include('sale_pos.partials.edit_discount_modal', [
        'sales_discount' => $transaction->discount_amount,
        'discount_type' => $transaction->discount_type,
    ])
@else
    @include('sale_pos.partials.edit_discount_modal', [
        'sales_discount' => $business_details->default_sales_discount,
        'discount_type' => 'percentage',
    ])
@endif

@if (isset($transaction))
    @include('sale_pos.partials.edit_order_tax_modal', ['selected_tax' => $transaction->tax_id])
@else
    @include('sale_pos.partials.edit_order_tax_modal', [
        'selected_tax' => $business_details->default_sales_tax,
    ])
@endif

@if (isset($transaction))
    @include('sale_pos.partials.edit_shipping_modal', [
        'shipping_charges' => $transaction->shipping_charges,
        'shipping_details' => $transaction->shipping_details,
    ])
@else
    @include('sale_pos.partials.edit_shipping_modal', [
        'shipping_charges' => '0.00',
        'shipping_details' => '',
    ])
@endif

@include('sale_pos.partials.edit_detail_modal')
