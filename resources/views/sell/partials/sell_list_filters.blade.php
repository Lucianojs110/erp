<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('sell_list_filter_location_id',  __('purchase.business_location') . ':') !!}

        {!! Form::select('sell_list_filter_location_id', $business_locations, null, ['class' => 'form-control select2', 'style' => 'width:100%', 'placeholder' => __('lang_v1.all') ]); !!}
    </div>
</div>
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('sell_list_filter_customer_id',  __('contact.customer') . ':') !!}
        {!! Form::select('sell_list_filter_customer_id', $customers, null, ['class' => 'form-control select2', 'style' => 'width:100%', 'placeholder' => __('lang_v1.all')]); !!}
    </div>
</div>
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('sell_list_filter_payment_status',  __('purchase.payment_status') . ':') !!}
        {!! Form::select('sell_list_filter_payment_status', ['paid' => __('lang_v1.paid'), 'due' => __('lang_v1.due'), 'partial' => __('lang_v1.partial')], null, ['class' => 'form-control select2', 'style' => 'width:100%', 'placeholder' => __('lang_v1.all')]); !!}
    </div>
</div>
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('sell_list_filter_date_range', __('report.date_range') . ':') !!}
        {!! Form::text('sell_list_filter_date_range', null, ['placeholder' => __('lang_v1.select_a_date_range'), 'class' => 'form-control', 'readonly']); !!}
    </div>
</div>

<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('filtro_factura', __('Tipo Comprobante') . ':') !!}
        {!! Form::select('filtro_factura', ['A' => 'A', 'B' => 'B', 'C' => 'C', null => 'Todos'], null, ['class' => 'form-control']) !!}
    </div>
</div>
{{-- filtro de usuarios --}}
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('sell_list_filter_cr_user', __('Usuario') . ':') !!}
        {!! Form::select('sell_list_filter_cr_user', $users, null, ['class' => 'form-control select2', 'style' => 'width:100%', 'placeholder' => __('lang_v1.all')]); !!}
    </div>
</div> 
{{-- filtro de cajas --}}
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('sell_list_filter_cash_register_id', __('cash_register.cash_register') . ':') !!}
        {!! Form::select('sell_list_filter_cash_register_id', $cash_registers, null, ['class' => 'form-control select2', 'style' => 'width:100%', 'placeholder' => __('lang_v1.all')]); !!}
    </div>
</div>
