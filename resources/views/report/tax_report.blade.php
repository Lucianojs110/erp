@extends('layouts.app')
@section('title', __( 'report.tax_report' ))

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>@lang( 'report.tax_report' )
        <small>@lang( 'report.tax_report_msg' )</small>
    </h1>
</section>

<!-- Main content -->
<section class="content">
    <div class="print_section"><h2>{{session()->get('business.name')}} - @lang( 'report.tax_report' )</h2></div>
    <div class="row no-print">
        <div class="col-md-3 col-md-offset-7 col-xs-6">
            <div class="input-group">
                <span class="input-group-addon bg-light-blue"><i class="fa fa-map-marker"></i></span>
                 <select class="form-control select2" id="tax_report_location_filter">
                    @foreach($business_locations as $key => $value)
                        <option value="{{ $key }}">{{ $value }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-2 col-xs-6">
            <div class="form-group">
                <div class="input-group pull-right">
                  <button type="button" class="btn btn-primary" id="tax_report_date_filter">
                    <span>
                      <i class="fa fa-calendar"></i> {{ __('messages.filter_by_date') }}
                    </span>
                    <i class="fa fa-caret-down"></i>
                  </button>
                </div>
              </div>
        </div>
    </div>
    <br>
    <div class="row">

    <div class="col-xs-6">
            @component('components.widget')
                @slot('title')
                    IVA ventas
                @endslot
                <b>Ventas Totales: </b>
                <span class="total_ventas">
                        <i class="fa fa-refresh fa-spin fa-fw"></i>
                </span>
                <br>
                <br>
                <b>Total Facturado: </b>
                <span class="total_facturado">
                        <i class="fa fa-refresh fa-spin fa-fw"></i>
                </span>
                <br>
                <br>
                <b>Monto Factura B: </b>
                <span class="factura_b">
                        <i class="fa fa-refresh fa-spin fa-fw"></i>
                </span>
                <br>
                <br>
                <b>Monto Factura A: </b>
                <span class="factura_a">
                        <i class="fa fa-refresh fa-spin fa-fw"></i>
                </span>
                <br>

                <br>
                <b>Monto IVA: </b>
                <span class="monto_iva">
                        <i class="fa fa-refresh fa-spin fa-fw"></i>
                </span>
                <br>
                <br>
                
                <button class="btn btn-primary" id="descargar">Exportar reporte Ventas en TXT</button>
                <button class="btn btn-primary" id="descargar_nc">Exportar reporte Notas Credito en TXT</button>
            @endcomponent
        </div>
        
       

        <div class="col-xs-6">
            @component('components.widget')
                @slot('title')
                    IVA compras
                @endslot
             
                <b>Monto Compras: </b>
                <span class="monto_compras">
                        <i class="fa fa-refresh fa-spin fa-fw"></i>
                </span>
                <br>

                <br>
                <b>Monto IVA: </b>
                <span class="monto_iva_compras">
                        <i class="fa fa-refresh fa-spin fa-fw"></i>
                </span>
                <br>
                <br>
            @endcomponent
        </div>

        <div class="col-xs-6">
            @component('components.widget')
                @slot('title')
                    Otros Impuestos @show_tooltip(__('tooltip.input_tax'))
                @endslot

                <b>Percepciones totales: </b>
                <span class="withholding_perceptions_total">
                        <i class="fa fa-refresh fa-spin fa-fw"></i>
                </span>
                <br>
                <br>

                <b>Retenciones totales: </b>
                <span class="withholding_retentions_total">
                        <i class="fa fa-refresh fa-spin fa-fw"></i>
                </span>
                <br>
                <br>
                <div class="input_tax">
                    <i class="fa fa-refresh fa-spin fa-fw"></i>
                </div>
            @endcomponent
        </div>


       
       
    </div>

   
    <div class="row no-print">
        <div class="col-sm-12">
            <button type="button" class="btn btn-primary pull-right" 
            aria-label="Print" onclick="window.print();"
            ><i class="fa fa-print"></i> @lang( 'messages.print' )</button>
        </div>
    </div>


</section>
<!-- /.content -->
@stop
@section('javascript')
<script src="{{ asset('js/report.js?v=' . $asset_v) }}">

</script>
@endsection