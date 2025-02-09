@extends('layouts.app')
@section('title', __('home.home'))
<!--
@section('css')
    
@endsection
-->
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>{{ __('home.welcome_message', ['name' => Session::get('user.first_name')]) }}
  </h1>
</section>
@if(auth()->user()->can('dashboard.data'))
<!-- Main content -->
<section class="content no-print">
  <div class="row">
    <div class="col-md-12 col-xs-12">
      <div class="btn-group pull-right" data-toggle="buttons">
        <label class="btn btn-info active">
          <input type="radio" name="date-filter" data-start="{{ date('Y-m-d') }}" data-end="{{ date('Y-m-d') }}" checked> {{ __('home.today') }}
        </label>
        <label class="btn btn-info">
          <input type="radio" name="date-filter" data-start="{{ $date_filters['this_week']['start']}}" data-end="{{ $date_filters['this_week']['end']}}"> {{ __('home.this_week') }}
        </label>
        <label class="btn btn-info">
          <input type="radio" name="date-filter" data-start="{{ $date_filters['this_month']['start']}}" data-end="{{ $date_filters['this_month']['end']}}"> {{ __('home.this_month') }}
        </label>
        <label class="btn btn-info">
          <input type="radio" name="date-filter" data-start="{{ $date_filters['this_fy']['start']}}" data-end="{{ $date_filters['this_fy']['end']}}"> {{ __('home.this_fy') }}
        </label>
      </div>
    </div>
  </div>
  <br>
  <div class="row">
    <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="info-box">
        <span class="info-box-icon bg-aqua"><i class="ion ion-cash"></i></span>

        <div class="info-box-content">
          <span class="info-box-text">{{ __('home.total_purchase') }}</span>
          <span class="info-box-number total_purchase"><i class="fa fa-refresh fa-spin fa-fw margin-bottom"></i></span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div>
    <!-- /.col -->
    <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="info-box">
        <span class="info-box-icon bg-aqua"><i class="ion ion-ios-cart-outline"></i></span>

        <div class="info-box-content">
          <span class="info-box-text">{{ __('home.total_sell') }}</span>
          <span class="info-box-number total_sell"><i class="fa fa-refresh fa-spin fa-fw margin-bottom"></i></span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div>
    <!-- /.col -->
    <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="info-box">
        <span class="info-box-icon bg-yellow">
          <i class="fa fa-dollar"></i>
          <i class="fa fa-exclamation"></i>
        </span>

        <div class="info-box-content">
          <span class="info-box-text">{{ __('home.purchase_due') }}</span>
          <span class="info-box-number purchase_due"><i class="fa fa-refresh fa-spin fa-fw margin-bottom"></i></span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div>
    <!-- /.col -->



    <!-- fix for small devices only -->
    <!-- <div class="clearfix visible-sm-block"></div> -->
    <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="info-box">
        <span class="info-box-icon bg-yellow">
          <i class="ion ion-ios-paper-outline"></i>
          <i class="fa fa-exclamation"></i>
        </span>

        <div class="info-box-content">
          <span class="info-box-text">{{ __('home.invoice_due') }}</span>
          <span class="info-box-number invoice_due"><i class="fa fa-refresh fa-spin fa-fw margin-bottom"></i></span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div>


    <!-- /.col -->
  </div>


  <!-- sales chart start -->
  <div class="row">
    <div class="col-sm-6">
      <div class="box box-primary">
        <div class="box-header">
          <h3 class="box-title">Ventas de los últimos 30 días</h3>
        </div>
        <div class="box-body">
          <figure class="highcharts-figure">
            <div id="sells_30days"></div>
            <p class="highcharts-description">
            </p>
          </figure>
        </div>
        <!-- /.box-body -->
      </div>
    </div>
    {{-- </div> --}}

    {{-- <div class="row"> --}}
    <div class="col-sm-6">
      <div class="box box-primary">
        <div class="box-header">
          <h3 class="box-title">Ventas del año fiscal actual</h3>
        </div>
        <div class="box-body">
          <figure class="highcharts-figure">
            <div id="sells_year"></div>
            <p class="highcharts-description">
            </p>
          </figure>
        </div>
        <!-- /.box-body -->
      </div>
    </div>
  </div>
  <!-- sales chart end -->
  <br>
  @if(!empty($widgets['after_sale_purchase_totals']))
  @foreach($widgets['after_sale_purchase_totals'] as $widget)
  {!! $widget !!}
  @endforeach
  @endif

  @if(!empty($widgets['after_sales_current_fy']))
  @foreach($widgets['after_sales_current_fy'] as $widget)
  {!! $widget !!}
  @endforeach
  @endif
  <!-- products less than alert quntity -->
  <div class="row">

    <div class="col-sm-6">
      @component('components.widget', ['class' => 'box-warning'])
      @slot('icon')
      <i class="fa fa-exclamation-triangle text-yellow" aria-hidden="true"></i>
      @endslot
      @slot('title')
      {{ __('lang_v1.sales_payment_dues') }} @show_tooltip(__('lang_v1.tooltip_sales_payment_dues'))
      @endslot
      <table class="table table-bordered table-striped" id="sales_payment_dues_table">
        <thead>
          <tr>
            <th>@lang( 'contact.customer' )</th>
            <th>@lang( 'sale.invoice_no' )</th>
            <th>@lang( 'home.due_amount' )</th>
          </tr>
        </thead>
      </table>
      @endcomponent
    </div>

    <div class="col-sm-6">

      @component('components.widget', ['class' => 'box-warning'])
      @slot('icon')
      <i class="fa fa-exclamation-triangle text-yellow" aria-hidden="true"></i>
      @endslot
      @slot('title')
      {{ __('lang_v1.purchase_payment_dues') }} @show_tooltip(__('tooltip.payment_dues'))
      @endslot
      <table class="table table-bordered table-striped" id="purchase_payment_dues_table">
        <thead>
          <tr>
            <th>@lang( 'purchase.supplier' )</th>
            <th>@lang( 'purchase.ref_no' )</th>
            <th>@lang( 'home.due_amount' )</th>
          </tr>
        </thead>
      </table>
      @endcomponent

    </div>
  </div>

  <div class="row">

    <div class="col-sm-6">
      @component('components.widget', ['class' => 'box-warning'])
      @slot('icon')
      <i class="fa fa-exclamation-triangle text-yellow" aria-hidden="true"></i>
      @endslot
      @slot('title')
      {{ __('home.product_stock_alert') }} @show_tooltip(__('tooltip.product_stock_alert'))
      @endslot
      <table class="table table-bordered table-striped" id="stock_alert_table">
        <thead>
          <tr>
            <th>@lang( 'sale.product' )</th>
            <th>@lang( 'business.location' )</th>
            <th>@lang( 'report.current_stock' )</th>
          </tr>
        </thead>
      </table>
      @endcomponent
    </div>
    @can('stock_report.view')
    @if(session('business.enable_product_expiry') == 1)
    <div class="col-sm-6">
      @component('components.widget', ['class' => 'box-warning'])
      @slot('icon')
      <i class="fa fa-exclamation-triangle text-yellow" aria-hidden="true"></i>
      @endslot
      @slot('title')
      {{ __('home.stock_expiry_alert') }} @show_tooltip( __('tooltip.stock_expiry_alert', [ 'days' =>session('business.stock_expiry_alert_days', 30) ]) )
      @endslot
      <input type="hidden" id="stock_expiry_alert_days" value="{{ \Carbon::now()->addDays(session('business.stock_expiry_alert_days', 30))->format('Y-m-d') }}">
      <table class="table table-bordered table-striped" id="stock_expiry_alert_table">
        <thead>
          <tr>
            <th>@lang('business.product')</th>
            <th>@lang('business.location')</th>
            <th>@lang('report.stock_left')</th>
            <th>@lang('product.expires_in')</th>
          </tr>
        </thead>
      </table>
      @endcomponent
    </div>
    @endif
    @endcan
  </div>

  @if(!empty($widgets['after_dashboard_reports']))
  @foreach($widgets['after_dashboard_reports'] as $widget)
  {!! $widget !!}
  @endforeach
  @endif
</section>
<!-- /.content -->
@stop
@section('javascript')
<script src="{{ asset('js/home.js?v=' . $asset_v) }}"></script>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/data.js"></script>
<script src="https://code.highcharts.com/modules/drilldown.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>

<script>
  $(document).ready(function() {


    // chart de ventas 30 dias
    Highcharts.chart('sells_30days', {
      chart: {
        type: 'column'
      },
      colors: ['#48c9b0'],
      title: {
        text: ''
      },
      subtitle: {
        text: ''
      },
      lang: {
        viewFullscreen: "Ver en pantalla completa",
        printChart: "Imprimir Graficos",

        downloadPNG: "Descargar Imagen PNG",
        downloadJPEG: "Descargar Imagen JPEG",
        downloadPDF: "Descargar PDF",
        downloadSVG: "Descargar Vector SVG",

        downloadCSV: "Descargar CSV",
        downloadXLS: "Descargar XLS",
        viewData: "Ver Lista",
        hideData: "Esconder Lista"

      },
      credits: {
        enabled: false
      },

      accessibility: {
        announceNewData: {
          enabled: true
        }
      },
      xAxis: {
        type: 'category'
      },
      yAxis: {
        title: {
          text: 'Ventas Totales ARS'
        }

      },
      legend: {
        enabled: false
      },
      plotOptions: {
        series: {
          borderWidth: 0,
          dataLabels: {

            format: '${point.y:.1f}'
          }
        }
      },

      tooltip: {
        headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
        pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>${point.y:.2f}</b> <br/>'
      },

      series: [{
        name: "Ventas",
        colorByPoint: true,
        data: <?= $json_sells_30_days ?>
      }],

    });


    // chart de ventas año fiscal
    Highcharts.chart('sells_year', {
      chart: {
        type: 'column'
      },
      colors: ['#48c9b0'],
      title: {
        text: ''
      },
      subtitle: {
        text: ''
      },
      credits: {
            enabled: false
          },

      lang: {
        viewFullscreen: "Ver en pantalla completa",
        printChart: "Imprimir Graficos",

        downloadPNG: "Descargar Imagen PNG",
        downloadJPEG: "Descargar Imagen JPEG",
        downloadPDF: "Descargar PDF",
        downloadSVG: "Descargar Vector SVG",

        downloadCSV: "Descargar CSV",
        downloadXLS: "Descargar XLS",
        viewData: "Ver Lista",
        hideData: "Esconder Lista"

      },
      accessibility: {
        announceNewData: {
          enabled: true
        }
      },
      xAxis: {
        type: 'category'
      },
      yAxis: {
        title: {
          text: 'Ventas Totales ARS'
        }

      },
      legend: {
        enabled: false
      },
      plotOptions: {
        series: {
          borderWidth: 0,
          dataLabels: {

            format: '${point.y:.1f}'
          }
        }
      },

      tooltip: {
        headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
        pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>${point.y:.2f}</b> <br/>'
      },

      series: [{
        name: "Ventas",
        colorByPoint: true,
        data: <?= $json_sells_per_year ?>
      }],

    });

  });
</script>





@endif
@endsection