@if(!session('business.enable_price_tax'))
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

<tr class="variation_row">
    <td>
        {!! Form::select('product_variation[' . $row_index .'][variation_template_id]', $variation_templates, null, ['class' => 'form-control input-sm variation_template', 'required']); !!}
        <input type="hidden" class="row_index" value="{{$row_index}}">
    </td>

    <td>
        <table class="table table-condensed table-bordered blue-header variation_value_table">
            <thead>
                <tr>
                    <th>@lang('product.sku') @show_tooltip(__('tooltip.sub_sku'))</th>
                    <th>@lang('product.value')</th>
                    <th class="{{$class}}">@lang('product.default_purchase_price')
                        <br />
                        <span class="pull-left"><small><i>@lang('product.exc_of_tax')</i></small></span>

                        <span class="pull-right"><small><i>@lang('product.inc_of_tax')</i></small></span>
                    </th>
                    <th class="{{$class}}">@lang('product.profit_percent')</th>
                    <th class="{{$class}}">@lang('product.default_selling_price')
                        <br />
                        <small><i><span class="dsp_label"></span></i></small>
                        <!-- &nbsp;&nbsp;<b><i class="fa fa-info-circle" aria-hidden="true" data-toggle="popover" data-html="true" data-trigger="hover" data-content="<p class='text-primary'>Drag the mouse over the table cells to copy input values</p>" data-placement="top"></i></b> -->
                    </th>
                    <th class="{{$class}}">Cantidad Mayorista</th>
                    <th class="{{$class}}">Precio Mayorista</th>
                    <th><button type="button" class="btn btn-success btn-xs add_variation_value_row">+</button></th>
                </tr>
            </thead>

            <tbody>
                <tr>
         
                   
                </tr>
            </tbody>
        </table>
    </td>
</tr>