@extends('layouts.app')
@section('title', __('report.cheque_report'))

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>{{ __('report.cheque_report') }}</h1>
    </section>

    <!-- Main content -->
    <section class="content no-print">
        <div class="row">
            <div class="col-md-12">
                @component('components.filters', ['title' => __('report.filters')])
                    {!! Form::open(['url' => '#', 'method' => 'get', 'id' => 'cheques_report_form']) !!}
                    <div class="col-md-2">
                        <div class="form-group">
                            {!! Form::label('customer_id', __('contact.customer') . ':') !!}
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-user"></i>
                                </span>
                                {!! Form::select('customer_id', $customers, null, [
                                    'class' => 'form-control select2',
                                    'placeholder' => __('messages.please_select'),
                                    'required',
                                ]) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            {!! Form::label('cheque_direction', __('report.cheque_direction') . ':') !!}
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-clipboard"></i>
                                </span>
                                {!! Form::select(
                                    'cheque_direction',
                                    [
                                        'emitido' => __('Emitido'),
                                        'recibido' => __('Recibido'),
                                    ],
                                    null,
                                    [
                                        'class' => 'form-control select2',
                                        'placeholder' => __('messages.please_select'),
                                        'required',
                                    ],
                                ) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            {!! Form::label('cheque_state', __('report.cheque_status') . ':') !!}
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-clipboard"></i>
                                </span>
                                {!! Form::select(
                                    'cheque_state',
                                    [
                                        'en_cartera' => __('En Cartera'),
                                        'endosado' => __('Endosado'),
                                        'depositado' => __('Depositado'),
                                        'aceptado_pendiente' => __('Aceptado Pendiente'),
                                        'en_custodia' => __('En Custodia'),
                                        'rechazado' => __('Rechazado')
                                    ],
                                    null,
                                    [
                                        'class' => 'form-control select2',
                                        'placeholder' => __('messages.please_select'),
                                        'required',
                                    ],
                                ) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            {!! Form::label('cheque_type', __('report.cheque_type') . ':') !!}
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-clipboard"></i>
                                </span>
                                {!! Form::select(
                                    'cheque_type',
                                    [
                                        'comun' => __('Comun'),
                                        'diferido' => __('Diferido'),
                                        'electronico' => __('Electronico'),
                                    ],
                                    null,
                                    [
                                        'class' => 'form-control select2',
                                        'placeholder' => __('messages.please_select'),
                                        'required',
                                    ],
                                ) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('spr_date_filter', __('report.date_range') . ':') !!}
                            {!! Form::text('date_range', null, [
                                'placeholder' => __('lang_v1.select_a_date_range'),
                                'class' => 'form-control',
                                'id' => 'spr_date_filter',
                                'readonly',
                            ]) !!}
                        </div>
                    </div>
                    {!! Form::close() !!}
                @endcomponent
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                @component('components.widget', ['class' => 'box-primary'])
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="cheques_report_table">
                            <thead>
                                <tr>
                 
                                    <th>@lang('ID')</th>
                                    <th>@lang('report.transaction_number')</th>
                                    <th>@lang('lang_v1.paid_on')</th>
                                    <th>@lang('sale.amount')</th>
                                    <th>@lang('contact.customer')</th>
                                    <th>@lang('report.cheque_no')</th>
                                    <th>@lang('report.cheque_type')</th>
                                    <th>@lang('report.cheque_bank')</th>
                                    <th>@lang('report.cheque_direction')</th>
                                    <th>@lang('report.cheque_status')</th>
                                    <th>@lang('report.cheque_issue_date')</th>
                                    <th>@lang('report.cheque_deferral_date')</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                @endcomponent
            </div>
        </div>
    </section>

    <!-- /.content -->
    <div class="modal fade view_register" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
    </div>
@endsection

@section('javascript')
    <script src="{{ asset('js/report.js?v=' . $asset_v) }}"></script>
    <script src="{{ asset('js/payment.js?v=' . $asset_v) }}"></script>
    <script>
        $(document).on('change', '.update-state', function() {
            var state = $(this).val();
            var id = $(this).data('id');

            $.ajax({
                url: '/update-check-state',
                method: 'PATCH',
                data: {
                    id: id,
                    state: state
                },
                success: function(response) {
                    if (response.success) {
                        alert('Estado actualizado correctamente');
                    } else {
                        alert('Error al actualizar el estado');
                    }
                },
                error: function() {
                    alert('Hubo un error en la solicitud');
                }
            });
        });
    </script>
@endsection
