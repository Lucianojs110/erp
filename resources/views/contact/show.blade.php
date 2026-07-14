@extends('layouts.app')
@section('title', __('contact.view_contact'))

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header no-print">
        <h1>{{ __('contact.view_contact') }}</h1>
    </section>

    <!-- Main content -->
    <section class="content no-print">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">
                    <i class="fa fa-user margin-r-5"></i>
                    @if ($contact->type == 'both')
                        @lang('contact.contact_info', ['contact' => __('contact.contact')])
                    @else
                        @lang('contact.contact_info', ['contact' => ucfirst($contact->type)])
                    @endif
                </h3>
            </div>
            <div class="box-body">
                <span id="view_contact_page"></span>
                <div class="row">
                    <div class="col-sm-3">
                        <div class="well well-sm">
                            <strong>{{ $contact->name }}</strong><br><br>
                            <strong><i class="fa fa-map-marker margin-r-5"></i> @lang('business.address')</strong>
                            <p class="text-muted">
                                @if ($contact->landmark)
                                    {{ $contact->landmark }}
                                @endif

                                {{ ', ' . $contact->city }}

                                @if ($contact->state)
                                    {{ ', ' . $contact->state }}
                                @endif
                                <br>
                                @if ($contact->country)
                                    {{ $contact->country }}
                                @endif
                                
                            </p>
                            @if ($contact->supplier_business_name)
                                <strong><i class="fa fa-briefcase margin-r-5"></i>
                                    @lang('business.business_name')</strong>
                                <p class="text-muted">
                                    {{ $contact->supplier_business_name }}
                                </p>
                            @endif
                                        
                        </div>
                       
                    </div>
                    <div class="col-sm-3">
                        <div class="well well-sm">
                            <strong><i class="fa fa-mobile margin-r-5"></i> @lang('contact.mobile')</strong>
                            <p class="text-muted">
                                {{ $contact->mobile }}
                            </p>
                            @if ($contact->landline)
                                <strong><i class="fa fa-phone margin-r-5"></i> @lang('contact.landline')</strong>
                                <p class="text-muted">
                                    {{ $contact->landline }}
                                </p>
                            @endif
                            @if ($contact->alternate_number)
                                <strong><i class="fa fa-phone margin-r-5"></i> @lang('contact.alternate_contact_number')</strong>
                                <p class="text-muted">
                                    {{ $contact->alternate_number }}
                                </p>
                            @endif

                            @if (!empty($contact->custom_field1))
                                <strong>@lang('lang_v1.custom_field', ['number' => 1])</strong>
                                <p class="text-muted">
                                    {{ $contact->custom_field1 }}
                                </p>
                            @endif

                            @if (!empty($contact->custom_field2))
                                <strong>@lang('lang_v1.custom_field', ['number' => 2])</strong>
                                <p class="text-muted">
                                    {{ $contact->custom_field2 }}
                                </p>
                            @endif

                            @if (!empty($contact->custom_field3))
                                <strong>@lang('lang_v1.custom_field', ['number' => 3])</strong>
                                <p class="text-muted">
                                    {{ $contact->custom_field3 }}
                                </p>
                            @endif

                            @if (!empty($contact->custom_field4))
                                <strong>@lang('lang_v1.custom_field', ['number' => 4])</strong>
                                <p class="text-muted">
                                    {{ $contact->custom_field4 }}
                                </p>
                            @endif
                        </div>
                    </div>
                    @if ($contact->type != 'customer')
                        <div class="col-sm-3">
                            <div class="well well-sm">
                                <strong><i class="fa fa-info margin-r-5"></i>{{ $contact->tipo_doc }}</strong>
                                <p class="text-muted">
                                    {{ $contact->tax_number }}
                                </p>
                                @if ($contact->pay_term_type)
                                    <strong><i class="fa fa-calendar margin-r-5"></i> @lang('contact.pay_term_period')</strong>
                                    <p class="text-muted">
                                        {{ ucfirst($contact->pay_term_type) }}
                                    </p>
                                @endif
                                @if ($contact->pay_term_number)
                                    <strong><i class="fa fa-handshake-o margin-r-5"></i> @lang('contact.pay_term')</strong>
                                    <p class="text-muted">
                                        {{ $contact->pay_term_number }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    @endif
                    <div class="col-sm-3">
                        <div class="well well-sm">
                            @if ($contact->type == 'supplier' || $contact->type == 'both')
                                <strong>@lang('report.total_purchase')</strong>
                                <p class="text-muted">
                                    <span class="display_currency" data-currency_symbol="true">
                                        {{ $contact->total_purchase }}</span>
                                </p>
                                <strong>@lang('contact.total_purchase_paid')</strong>
                                <p class="text-muted">
                                    <span class="display_currency" data-currency_symbol="true">
                                        {{ $contact->purchase_paid }}</span>
                                </p>
                                <strong>@lang('contact.total_purchase_due')</strong>
                                <p class="text-muted">
                                    <span class="display_currency" data-currency_symbol="true">
                                        {{ $contact->total_purchase - $contact->purchase_paid }}</span>
                                </p>
                            @endif
                            @if ($contact->type == 'customer' || $contact->type == 'both')
                                <strong>@lang('report.total_sell')</strong>
                                <p class="text-muted">
                                    <span class="display_currency" data-currency_symbol="true">
                                        {{ $contact->total_invoice }}</span>
                                </p>
                                <strong>@lang('contact.total_sale_paid')</strong>
                                <p class="text-muted">
                                    <span class="display_currency" data-currency_symbol="true">
                                        {{ $contact->invoice_received }}</span>
                                </p>
                                <strong>@lang('contact.total_sale_due')</strong>
                                <p class="text-muted">
                                    <span class="display_currency text-red text-bold" data-currency_symbol="true">
                                        {{ $contact->total_invoice - $contact->invoice_received }}</span>
                                </p>
                            @endif
                            @if (!empty($contact->opening_balance) && $contact->opening_balance != '0.00')
                                <strong>@lang('lang_v1.opening_balance')</strong>
                                <p class="text-muted">
                                    <span class="display_currency" data-currency_symbol="true">
                                        {{ $contact->opening_balance }}</span>
                                </p>
                                <strong>@lang('lang_v1.opening_balance_due')</strong>
                                <p class="text-muted">
                                    <span class="display_currency" data-currency_symbol="true">
                                        {{ $contact->opening_balance - $contact->opening_balance_paid }}</span>
                                </p>
                            @endif
                        </div>
                    </div>
                    @if ($contact->type == 'supplier' || $contact->type == 'both')
                        <div class="clearfix"></div>
                        <div class="col-sm-12">
                            @if ($contact->total_purchase - $contact->purchase_paid > 0)
                                <a href="{{ action('TransactionPaymentController@getPayContactDue', [$contact->id]) }}?type=purchase"
                                    class="pay_purchase_due btn btn-primary btn-sm pull-right"><i class="fa fa-money"
                                        aria-hidden="true"></i> @lang('contact.pay_due_amount')</a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
   
       
   
        @component('components.widget', ['class' => 'box-primary', 'title' => __('lang_v1.bank_details')])
            @slot('tool')
                <div class="box-tools">
                <button type="button" data-container=".contact_modal" class="btn btn-primary btn-modal" data-toggle="modal" data-target="#exampleModal">
                <i class="fa fa-plus"></i> Agregar
           </button>
                </div>
            @endslot
       
        <div class="box-body">
          
           <div class="table-responsive">
                
            @if($contact->bankDetails->isEmpty())
          
                              <p class="text-center">No hay detalles bancarios</p>
                        
            @else
                    <table class="table table-bordered table-striped ajax_view" id="bank_details_table">
                        <thead>
                            <tr>
                                <th>@lang('contact.bank')</th>   
                                <th>@lang('contact.bank_account_number')</th>
                                <th>@lang('contact.cbu')</th>
                                <th>@lang('contact.alias')</th>
                                <th>@lang('lang_v1.actions')</th>
                            </tr>
                        </thead>
                        <tbody>
                       
                              @foreach ($contact->bankDetails as $details)   
                                <tr>
                                    <td>{{$details->bank? $details->bank : '-'}}</td>
                                    <td>{{$details->bank_account_number? $details->bank_account_number : '-'}}</td>
                                    <td>{{$details->cbu? $details->cbu : '-'}}</td>
                                    <td>{{$details->alias? $details->alias : '-'}}</td>
                                    <td><div><button 
                                        type="button" 
                                        class="btn btn-primary edit-btn" 
                                        data-toggle="modal" 
                                        data-target="#bankDetailsEditModal" 
                                        data-id="{{$details->id}}"
                                        data-bank="{{$details->bank}}"
                                        data-bank_account_number="{{$details->bank_account_number}}"
                                        data-cbu="{{$details->cbu}}"
                                        data-alias="{{$details->alias}}"
                                        ><i class="fa fa-pencil"></i></button>
                                        <button 
                                        type="button" 
                                        class="btn btn-danger delete-btn" 
                                        data-toggle="modal" 
                                        data-target="#bankDetailsDeleteModal" 
                                        data-id="{{$details->id}}"
                                        ><i class="fa fa-trash"></i></button>
                                    </div></td>
                                </tr>
                              @endforeach
                              
                        </tbody>
                    </table>
                
            @endif     
            </div>
            @endcomponent
      
</div>
<!-- bank details Modal  -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title" id="exampleModalLabel">@lang('lang_v1.add_bank_details')</h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="POST" action="/contacts/bank-details">
            @csrf
        <div class="mb-3">
            <label for="recipient-name" class="col-form-label">@lang('contact.bank'):</label>
            <input type="text" name="bank" class="form-control" id="recipient-name">
            <label for="recipient-name" name=""  class="col-form-label">@lang('contact.bank_account_number'):</label>
            <input type="text" name="bank_account_number"  class="form-control" id="recipient-name">
            <label for="recipient-name" class="col-form-label">@lang('contact.cbu'):</label>
            <input type="text" name="cbu"  class="form-control" id="recipient-name">
            <label for="recipient-name" class="col-form-label">@lang('contact.alias'):</label>
            <input type="text" name="alias"  class="form-control" id="recipient-name">
            <input type="hidden" name="contact_id" value="{{$contact->id}}" readonly> 
        </div>
          <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('lang_v1.close')</button>
        <button type="submit" class="btn btn-primary">@lang('lang_v1.save')</button>
      </div>

        </form>
      </div>
      
    </div>
  </div>
</div>

<!-- bank details edit Modal  -->
<div class="modal fade" id="bankDetailsEditModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title" id="exampleModalLabel">@lang('lang_v1.edit_bank_details')</h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="POST" action="/contacts/bank-details/edit">
            @csrf
        <div class="mb-3">
            <label for="recipient-name" class="col-form-label">@lang('contact.bank'):</label>
            <input type="text" name="bank" class="form-control" id="bank">
            <label for="recipient-name" name=""  class="col-form-label">@lang('contact.bank_account_number'):</label>
            <input type="text" name="bank_account_number"  class="form-control" id="bank_account_number">
            <label for="recipient-name" class="col-form-label">@lang('contact.cbu'):</label>
            <input type="text" name="cbu"  class="form-control" id="cbu">
            <label for="recipient-name" class="col-form-label">@lang('contact.alias'):</label>
            <input type="text" name="alias"  class="form-control" id="alias">
            <input type="hidden" name="contact_id" value="{{$contact->id}}" readonly> 
            <input type="hidden" id="detailsId" name="id" readonly> 
        </div>
          <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('lang_v1.close')</button>
        <button type="submit" class="btn btn-primary">@lang('lang_v1.save_changes')</button>
      </div>

        </form>
      </div>
      
    </div>
  </div>
</div>     


<!-- bank details delete Modal  -->
<div class="modal fade" id="bankDetailsDeleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title" id="exampleModalLabel">@lang('lang_v1.question_delete_bank_details')</h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="POST" action="/contacts/bank-details/delete">
            @csrf
        <div class="mb-3">
            <input type="hidden" name="contact_id" value="{{$contact->id}}" readonly> 
            <input type="hidden" id="deleteDetailsId" name="id" readonly> 
        </div>
          <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('lang_v1.negacion')</button>
        <button type="submit" class="btn btn-danger">@lang('lang_v1.afirmacion')</button>
      </div>

        </form>
      </div>
      
    </div>
  </div>
</div>              

<script>
    document.querySelectorAll('.edit-btn').forEach(button=>{
        button.addEventListener('click',function(){
            const detailsId = this.getAttribute('data-id');
            const detailsBank = this.getAttribute('data-bank');
            const detailsBankAccountNumber = this.getAttribute('data-bank_account_number');
            const detailsCBU = this.getAttribute('data-cbu');
            const detailsAlias = this.getAttribute('data-alias');
            document.getElementById('detailsId').value = detailsId;
            document.getElementById('bank').value = detailsBank;
            document.getElementById('bank_account_number').value = detailsBankAccountNumber;
            document.getElementById('cbu').value = detailsCBU;
            document.getElementById('alias').value = detailsAlias;
        })
    })
</script>
<script>
    document.querySelectorAll('.delete-btn').forEach(button=>{
        button.addEventListener('click',function(){
            const detailsId = this.getAttribute('data-id');
            document.getElementById('deleteDetailsId').value = detailsId;
        })
    })
</script>
        <!-- list sales -->
        @if (in_array($contact->type, ['customer', 'both']))
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">
                        <i class="fa fa-money margin-r-5"></i>
                        @lang('contact.all_sells_linked_to_this_contact')
                    </h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <div class="input-group">
                                    <button type="button" class="btn btn-primary" id="sells-daterange-btn">
                                        <span>
                                            <i class="fa fa-calendar"></i> {{ __('messages.filter_by_date') }}
                                        </span>
                                        <i class="fa fa-caret-down"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <table class="table table-bordered table-striped ajax_view" id="sell_table">
                                <thead>
                                    <tr>
                                        <th>@lang('messages.date')</th>
                                        <th>@lang('sale.invoice_no')</th>
                                        <th>@lang('sale.customer_name')</th>
                                        <th>@lang('sale.payment_status')</th>
                                        <th>@lang('sale.total_amount')</th>
                                        <th>@lang('sale.total_paid')</th>
                                        <th>@lang('sale.total_remaining')</th>
                                        <th>@lang('messages.action')</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @component('components.widget', ['class' => 'box-primary', 'title' => __('lang_v1.sell_return')])
                @can('sell.view')
                    <div class="form-group">
                        <div class="input-group">
                            <button type="button" class="btn btn-primary" id="daterange-btn-sr">
                                <span>
                                    <i class="fa fa-calendar"></i> {{ __('messages.filter_by_date') }}
                                </span>
                                <i class="fa fa-caret-down"></i>
                            </button>
                        </div>
                    </div>
                    @include('sell_return.partials.sell_return_list')
                @endcan
            @endcomponent
            <div class= "box">
                <div class="box-header">
                    <h3 class="box-title">
                        <i class="fa fa-money margin-r-5"></i>
                        @lang('contact.all_cheques_linked_to_this_contact')
                    </h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <table class="table table-bordered table-striped ajax_view" id="cheques_table">
                                <thead>
                                    <tr>
                                        <th>@lang('report.cheque_issue_date')</th>
                                        <th>@lang('report.cheque_no')</th>
                                        <th>@lang('report.cheque_bank')</th>
                                        <th>@lang('sale.amount')</th>
                                        <th>@lang('report.cheque_status')</th>
                                        <th>@lang('report.cheque_type')</th>
                                        <th>@lang('report.cheque_deferral_date')</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        <!-- list purchases -->
        @if (in_array($contact->type, ['supplier', 'both']))
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">
                        <i class="fa fa-money margin-r-5"></i>
                        @lang('contact.all_purchases_linked_to_this_contact')
                    </h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <div class="input-group">
                                    <button type="button" class="btn btn-primary" id="daterange-btn">
                                        <span>
                                            <i class="fa fa-calendar"></i> {{ __('messages.filter_by_date') }}
                                        </span>
                                        <i class="fa fa-caret-down"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <table class="table table-bordered table-striped ajax_view" id="purchase_table">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Ref No.</th>
                                        <th>Supplier</th>
                                        <th>Purchase Status</th>
                                        <th>Payment Status</th>
                                        <th>Grand Total</th>
                                        <th>@lang('purchase.payment_due') &nbsp;&nbsp;<i class="fa fa-info-circle text-info"
                                                data-toggle="tooltip" data-placement="bottom" data-html="true"
                                                data-original-title="{{ __('messages.purchase_due_tooltip') }}"
                                                aria-hidden="true"></i></th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            @component('components.widget', ['class' => 'box-primary', 'title' => __('lang_v1.all_purchase_returns')])
                @can('purchase.view')
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <div class="input-group">
                                    <button type="button" class="btn btn-primary" id="daterange-btn-pr">
                                        <span>
                                            <i class="fa fa-calendar"></i> {{ __('messages.filter_by_date') }}
                                        </span>
                                        <i class="fa fa-caret-down"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @include('purchase_return.partials.purchase_return_list')
                @endcan
            @endcomponent
            <div class= "box">
                <div class="box-header">
                    <h3 class="box-title">
                        <i class="fa fa-money margin-r-5"></i>
                        @lang('contact.all_cheques_linked_to_this_contact')
                    </h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <table class="table table-bordered table-striped ajax_view" id="cheques_table">
                                <thead>
                                    <tr>
                                        <th>@lang('report.cheque_issue_date')</th>
                                        <th>@lang('report.cheque_no')</th>
                                        <th>@lang('report.cheque_bank')</th>
                                        <th>@lang('sale.amount')</th>
                                        <th>@lang('report.cheque_status')</th>
                                        <th>@lang('report.cheque_type')</th>
                                        <th>@lang('report.cheque_deferral_date')</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if (auth()->user()->can('purchase.payments') || auth()->user()->can('sell.payments'))
            @component('components.widget', ['class' => 'box-primary', 'title' => __('lang_v1.opening_balance_payments')])
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="ob_payment_table">
                        <thead>
                            <tr>
                                <th>@lang('purchase.ref_no')</th>
                                <th>@lang('lang_v1.paid_on')</th>
                                <th>@lang('sale.amount')</th>
                                <th>@lang('lang_v1.payment_method')</th>
                                <th>@lang('messages.action')</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            @endcomponent
        @endif
    </section>
    <!-- /.content -->
    <div class="modal fade payment_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
    </div>
    <div class="modal fade edit_payment_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
    </div>
    <div class="modal fade pay_contact_due_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
    </div>
@stop
@section('javascript')
    <script type="text/javascript">
        $(document).ready(function() {
            //Purchase table
            purchase_table = $('#purchase_table').DataTable({
                processing: true,
                serverSide: true,
                aaSorting: [
                    [0, 'desc']
                ],
                ajax: '/purchases?supplier_id={{ $contact->id }}',
                columnDefs: [{
                    "targets": 6,
                    "orderable": false,
                    "searchable": false
                }],
                columns: [{
                        data: 'transaction_date',
                        name: 'transaction_date'
                    },
                    {
                        data: 'ref_no',
                        name: 'ref_no'
                    },
                    {
                        data: 'name',
                        name: 'contacts.name'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'payment_status',
                        name: 'payment_status'
                    },
                    {
                        data: 'final_total',
                        name: 'final_total'
                    },
                    {
                        data: 'payment_due',
                        name: 'payment_due'
                    },
                    {
                        data: 'action',
                        name: 'action'
                    }
                ],
                "fnDrawCallback": function(oSettings) {
                    __currency_convert_recursively($('#purchase_table'));
                },
                createdRow: function(row, data, dataIndex) {
                    $(row).find('td:eq(4)').attr('class', 'clickable_td');
                }
            });
            //CHeques table
            let cheque_table = $('#cheques_table').DataTable({
                processing: true,
                serverSide: true,
                aaSorting: [
                    [0, 'desc']
                ], // Ordena por la primera columna (fecha de emisión)
                ajax: '/cheques-resume?customer_id={{ $contact->id }}',
                columnDefs: [{
                    "targets": 6, // La columna de acciones
                    "orderable": false,
                    "searchable": false
                }],
                columns: [{
                        data: 'issue_date',
                        name: 'issue_date'
                    }, // Fecha de emisión
                    {
                        data: 'number',
                        name: 'number'
                    }, // Número de cheque
                    {
                        data: 'bank',
                        name: 'bank'
                    }, // Banco
                    {
                        data: 'amount',
                        name: 'amount'
                    }, // Monto
                    {
                        data: 'state',
                        name: 'state'
                    },
                    {
                        data: 'type',
                        name: 'type'
                    },
                    {
                        data: 'deferral_date',
                        name: 'deferral_date'
                    },
                ],
                "fnDrawCallback": function(oSettings) {
                    __currency_convert_recursively($(
                        '#cheque_table')); // Convierte los montos a formato moneda
                },
                createdRow: function(row, data, dataIndex) {
                    $(row).find('td:eq(4)').attr('class',
                        'clickable_td'); // Agrega clase `clickable_td` a la columna de estado
                }
            });

            // Configuración del rango de fechas para filtrar los cheques
            $('#cheques-daterange-btn').daterangepicker(
                dateRangeSettings,
                function(start, end) {
                    $('#cheques-daterange-btn span').html(start.format('DD/MM/YYYY') + ' - ' + end.format(
                        'DD/MM/YYYY'));
                    cheque_table.ajax.url('/cheques-resume?customer_id={{ $contact->id }}&start_date=' + start
                        .format('YYYY-MM-DD') +
                        '&end_date=' + end.format('YYYY-MM-DD')).load();
                }
            );

            $('#cheques-daterange-btn').on('cancel.daterangepicker', function(ev, picker) {
                cheque_table.ajax.url('/cheques-resume?customer_id={{ $contact->id }}').load();
                $('#cheques-daterange-btn span').html(
                    '<i class="fa fa-calendar"></i> {{ __('messages.filter_by_date') }}');
            });

            sell_table = $('#sell_table').DataTable({
                processing: true,
                serverSide: true,
                aaSorting: [
                    [0, 'desc']
                ],
                ajax: '/sells?customer_id={{ $contact->id }}&payment_status={{ $payment_status }}',
                columnDefs: [{
                    "targets": 7,
                    "orderable": false,
                    "searchable": false
                }],
                columns: [{
                        data: 'transaction_date',
                        name: 'transaction_date'
                    },
                    {
                        data: 'invoice_no',
                        name: 'invoice_no'
                    },
                    {
                        data: 'name',
                        name: 'contacts.name'
                    },
                    {
                        data: 'payment_status',
                        name: 'payment_status'
                    },
                    {
                        data: 'final_total',
                        name: 'final_total'
                    },
                    {
                        data: 'total_paid',
                        searchable: false
                    },
                    {
                        data: 'total_remaining',
                        name: 'total_remaining'
                    },
                    {
                        data: 'action',
                        name: 'action'
                    }
                ],
                "fnDrawCallback": function(oSettings) {
                    __currency_convert_recursively($('#sell_table'));
                },
                createdRow: function(row, data, dataIndex) {
                    $(row).find('td:eq(3)').attr('class', 'clickable_td');
                }
            });
            $('#sells-daterange-btn').daterangepicker(
                dateRangeSettings,
                function(start, end) {
                    $('#sells-daterange-btn span').html(start.format('DD/MM/YYYY') + ' - ' + end.format(
                        'DD/MM/YYYY'));
                    sell_table.ajax.url('/sells?customer_id={{ $contact->id }}&start_date=' + start.format(
                            'YYYY-MM-DD') +
                        '&end_date=' + end.format('YYYY-MM-DD')).load();
                }
            );
            $('#sells-daterange-btn').on('cancel.daterangepicker', function(ev, picker) {
                sell_table.ajax.url('/sells?customer_id={{ $contact->id }}').load();
                $('#daterange-btn span').html(
                    '<i class="fa fa-calendar"></i> {{ __('messages.filter_by_date') }}');
            });

            //Opening balance payment

            ob_payment_table = $('#ob_payment_table').DataTable({
                processing: true,
                serverSide: true,
                aaSorting: [
                    [0, 'desc']
                ],
                // ajax: '{{ action('TransactionPaymentController@getOpeningBalancePayments', $contact->id) }}',
                ajax: '/payments/opening-balance/{{ $contact->id }}',

                columns: [{
                        data: 'payment_ref_no',
                        name: 'payment_ref_no'
                    },
                    {
                        data: 'paid_on',
                        name: 'paid_on'
                    },
                    {
                        data: 'amount',
                        name: 'transaction_payments.amount'
                    },
                    {
                        data: 'method',
                        name: 'method'
                    },
                    {
                        data: 'action',
                        "orderable": false,
                        "searchable": false
                    },
                ],
                "fnDrawCallback": function(oSettings) {
                    __currency_convert_recursively($('#ob_payment_table'));
                }
            });


            @if (in_array($contact->type, ['supplier', 'both']))
                //Purchase return table
                purchase_return_table = $('#purchase_return_datatable').DataTable({
                    processing: true,
                    serverSide: true,
                    aaSorting: [
                        [0, 'desc']
                    ],
                    ajax: {
                        url: '/purchase-return',
                        data: function(d) {
                            d.supplier_id = {{ $contact->id }}
                        }
                    },
                    columnDefs: [{
                        "targets": [7, 8],
                        "orderable": false,
                        "searchable": false
                    }],
                    columns: [{
                            data: 'transaction_date',
                            name: 'transaction_date'
                        },
                        {
                            data: 'ref_no',
                            name: 'ref_no'
                        },
                        {
                            data: 'parent_purchase',
                            name: 'T.ref_no'
                        },
                        {
                            data: 'location_name',
                            name: 'BS.name'
                        },
                        {
                            data: 'name',
                            name: 'contacts.name'
                        },
                        {
                            data: 'payment_status',
                            name: 'payment_status'
                        },
                        {
                            data: 'final_total',
                            name: 'final_total'
                        },
                        {
                            data: 'payment_due',
                            name: 'payment_due'
                        },
                        {
                            data: 'action',
                            name: 'action'
                        }
                    ],
                    "fnDrawCallback": function(oSettings) {
                        var total_purchase = sum_table_col($('#purchase_return_datatable'),
                            'final_total');
                        $('#footer_purchase_return_total').text(total_purchase);

                        $('#footer_payment_status_count').html(__sum_status_html($(
                            '#purchase_return_datatable'), 'payment-status-label'));

                        var total_due = sum_table_col($('#purchase_return_datatable'), 'payment_due');
                        $('#footer_total_due').text(total_due);

                        __currency_convert_recursively($('#purchase_return_datatable'));
                    },
                    createdRow: function(row, data, dataIndex) {
                        $(row).find('td:eq(5)').attr('class', 'clickable_td');
                    }
                });
                //Date range as a button
                $('#daterange-btn-pr').daterangepicker(
                    dateRangeSettings,
                    function(start, end) {
                        $('#daterange-btn-pr span').html(start.format(moment_date_format) + ' ~ ' + end.format(
                            moment_date_format));
                        purchase_return_table.ajax.url('/purchase-return?start_date=' + start.format(
                                'YYYY-MM-DD') +
                            '&end_date=' + end.format('YYYY-MM-DD')).load();
                    }
                );
                $('#daterange-btn-pr').on('cancel.daterangepicker', function(ev, picker) {
                    purchase_return_table.ajax.url('/purchase-return').load();
                    $('#daterange-btn-pr span').html(
                        '<i class="fa fa-calendar"></i> {{ __('messages.filter_by_date') }}');
                });
            @endif

            @if (in_array($contact->type, ['customer', 'both']))

                //Date range as a button
                $('#daterange-btn-sr').daterangepicker(
                    dateRangeSettings,
                    function(start, end) {
                        $('#daterange-btn-sr span').html(start.format(moment_date_format) + ' ~ ' + end.format(
                            moment_date_format));
                        sell_return_table.ajax.url('/sell-return?start_date=' + start.format('YYYY-MM-DD') +
                            '&end_date=' + end.format('YYYY-MM-DD')).load();
                    }
                );
                $('#daterange-btn-sr').on('cancel.daterangepicker', function(ev, picker) {
                    sell_return_table.ajax.url('/sell-return').load();
                    $('#daterange-btn-sr span').html(
                        '<i class="fa fa-calendar"></i> {{ __('messages.filter_by_date') }}');
                });
                sell_return_table = $('#sell_return_table').DataTable({
                    processing: true,
                    serverSide: true,
                    aaSorting: [
                        [0, 'desc']
                    ],
                    "ajax": {
                        "url": "/sell-return",
                        "data": function(d) {
                            var start = $('#daterange-btn-sr').data('daterangepicker').startDate.format(
                                'YYYY-MM-DD');
                            var end = $('#daterange-btn-sr').data('daterangepicker').endDate.format(
                                'YYYY-MM-DD');
                            d.start_date = start;
                            d.end_date = end;
                            d.customer_id = {{ $contact->id }}
                        }
                    },
                    columnDefs: [{
                        "targets": [7, 8],
                        "orderable": false,
                        "searchable": false
                    }],
                    columns: [{
                            data: 'transaction_date',
                            name: 'transaction_date'
                        },
                        {
                            data: 'invoice_no',
                            name: 'invoice_no'
                        },
                        {
                            data: 'parent_sale',
                            name: 'T1.invoice_no'
                        },
                        {
                            data: 'name',
                            name: 'contacts.name'
                        },
                        {
                            data: 'business_location',
                            name: 'bl.name'
                        },
                        {
                            data: 'payment_status',
                            name: 'payment_status'
                        },
                        {
                            data: 'final_total',
                            name: 'final_total'
                        },
                        {
                            data: 'payment_due',
                            name: 'payment_due'
                        },
                        {
                            data: 'action',
                            name: 'action'
                        }
                    ],
                    "fnDrawCallback": function(oSettings) {
                        var total_sell = sum_table_col($('#sell_return_table'), 'final_total');
                        $('#footer_sell_return_total').text(total_sell);

                        $('#footer_payment_status_count_sr').html(__sum_status_html($(
                            '#sell_return_table'), 'payment-status-label'));

                        var total_due = sum_table_col($('#sell_return_table'), 'payment_due');
                        $('#footer_total_due_sr').text(total_due);

                        __currency_convert_recursively($('#sell_return_table'));
                    },
                    createdRow: function(row, data, dataIndex) {
                        $(row).find('td:eq(2)').attr('class', 'clickable_td');
                    }
                });
            @endif
        });

        $('table#purchase_table').on('click', 'a.delete-purchase', function(e) {
            e.preventDefault();
            swal({
                title: LANG.sure,
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    var href = $(this).attr('href');
                    $.ajax({
                        method: "DELETE",
                        url: href,
                        dataType: "json",
                        success: function(result) {
                            if (result.success == true) {
                                toastr.success(result.msg);
                                purchase_table.ajax.reload();
                            } else {
                                toastr.error(result.msg);
                            }
                        }
                    });
                }
            });
        });
    </script>
    <script src="{{ asset('js/payment.js?v=' . $asset_v) }}"></script>
 
   


@endsection
