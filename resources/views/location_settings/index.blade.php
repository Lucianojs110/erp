@extends('layouts.app')
@section('title', __('messages.business_location_settings'))

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>@lang( 'messages.business_location_settings' ) - {{$location->name}}</h1>
    <!-- <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
        <li class="active">Here</li>
    </ol> -->
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <!-- Custom Tabs -->
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="true">@lang('receipt.receipt_settings')</a></li>
                    <li><a href="#tab_2" data-toggle="tab" aria-expanded="true">Facturación</a></li>
                </ul>

                <div class="tab-content">
                    <div class="tab-pane active" id="tab_1">
                        <div class="row">
                            <div class="col-md-12">
                                <h4>@lang( 'receipt.receipt_settings')
                                    <small>@lang( 'receipt.receipt_settings_mgs')</small>
                                </h4>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-12">
                                {!! Form::open(['url' => route('location.settings_update', [$location->id]), 'method' => 'post', 'id' => 'bl_receipt_setting_form']) !!}

                                <div class="col-sm-4">
                                    <div class="form-group">
                                        {!! Form::label('print_receipt_on_invoice', __('receipt.print_receipt_on_invoice') . ':') !!}
                                        @show_tooltip(__('tooltip.print_receipt_on_invoice'))
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="fa fa-file-text-o"></i>
                                            </span>
                                            {!! Form::select('print_receipt_on_invoice', $printReceiptOnInvoice, $location->print_receipt_on_invoice, ['class' => 'form-control select2', 'required']); !!}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-4">
                                    <div class="form-group">
                                        {!! Form::label('receipt_printer_type', __('receipt.receipt_printer_type') . ':*') !!} @show_tooltip(__('tooltip.receipt_printer_type'))
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="fa fa-print"></i>
                                            </span>
                                            {!! Form::select('receipt_printer_type', $receiptPrinterType, $location->receipt_printer_type, ['class' => 'form-control select2', 'required']); !!}
                                        </div>
                                        @if(config('app.env') == 'demo')
                                        <span class="help-block">Only Browser based option is enabled in demo.</span>
                                        @endif

                                    </div>
                                </div>

                                <div class="col-sm-4" id="location_printer_div">
                                    <div class="form-group">
                                        {!! Form::label('printer_id', __('printer.receipt_printers') . ':*') !!}
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="fa fa-share-alt"></i>
                                            </span>
                                            {!! Form::select('printer_id', $printers, $location->printer_id, ['class' => 'form-control select2', 'required']); !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <br />

                                <div class="col-sm-4">
                                    <div class="form-group">
                                        {!! Form::label('invoice_layout_id', __('invoice.invoice_layout') . ':*') !!} @show_tooltip(__('tooltip.invoice_layout'))
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="fa fa-info"></i>
                                            </span>
                                            {!! Form::select('invoice_layout_id', $invoice_layouts, $location->invoice_layout_id, ['class' => 'form-control select2', 'required']); !!}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-4">
                                    <div class="form-group">
                                        {!! Form::label('invoice_scheme_id', __('invoice.invoice_scheme') . ':*') !!} @show_tooltip(__('tooltip.invoice_scheme'))
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="fa fa-info"></i>
                                            </span>
                                            {!! Form::select('invoice_scheme_id', $invoice_schemes, $location->invoice_scheme_id, ['class' => 'form-control select2', 'required']); !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <button class="btn btn-primary pull-right" type="submit">@lang('messages.update')</button>
                                    </div>
                                </div>
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="tab_2">
                        <div class="row">
                            {!! Form::open(['url' => action('BusinessLocationController@updateTaxConfig', [$location->id]), 'method' => 'PUT', 'id' => 'business_location_add_form', 'enctype' => 'multipart/form-data']) !!}
                            {!! Form::hidden('hidden_id', $location->id, ['id' => 'hidden_id']); !!}
                            <div class="col-md-12">
                                <!-- Contenido de la pestaña "Facturación" -->

                                <div class="row">
                                    @if($location->url_cert)
                                    <div class="col-md-6">
                                        <div class="alert alert-info">
                                            Certificado de AFIP cargado
                                        </div>
                                    </div>
                                    @endif

                                    @if($location->url_key)
                                    <div class="col-md-6">
                                        <div class="alert alert-info">
                                            Llave de AFIP cargada
                                        </div>
                                    </div>
                                    @endif
                                </div>



                                <div class="col-sm-6">
                                    <div class="form-group">
                                        {!! Form::label('Nombre impuesto 1:') !!}
                                        <select name="tax_label_1" class="form-control">
                                            @if($location->tax_label_1 == 'MONOTRIBUTO')
                                            <option selected value="MONOTRIBUTO">Monotributo</option>
                                            <option value="RESPONSABLE INSCRIPTO">Resp. Inscripto</option>
                                            @else
                                            <option selected value="RESPONSABLE INSCRIPTO">Resp. Inscripto</option>
                                            <option value="MONOTRIBUTO">Monotributo</option>
                                            @endif
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        {!! Form::label('Cuit') !!}
                                        {!! Form::text('cuit', $location->cuit, ['class' => 'form-control', 'placeholder' => 'Ingrese cuit']); !!}
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        {!! Form::label('Nombre impuesto 2 (Impuesto Provincial):') !!}
                                        {!! Form::text('tax_label_2', $location->tax_label_2, ['class' => 'form-control', 'placeholder' => 'Nombre impuesto 2']); !!}
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        {!! Form::label('Numero Impuesto 2') !!}
                                        {!! Form::text('tax_number_2', $location->tax_number_2, ['class' => 'form-control', 'placeholder' => 'Numero Impuesto 2']); !!}
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        {!! Form::label('Punto de venta:') !!}
                                        {!! Form::text('punto_venta', $location->punto_venta, ['class' => 'form-control', 'placeholder' => 'punto de venta']); !!}
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        {!! Form::label('Certificado de AFIP:*') !!}
                                        {!! Form::file('cert', ['class' => 'form-control']) !!}
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        {!! Form::label('Llave de AFIP:*') !!}
                                        {!! Form::file('key', ['class' => 'form-control']) !!}
                                    </div>
                                </div>




                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">@lang( 'messages.save' )</button>

                            </div>

                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>


                <!-- /.tab-content -->
            </div>
            <!-- nav-tabs-custom -->
        </div>
    </div>



</section>
<!-- /.content -->

@endsection