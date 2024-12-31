<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\BusinessLocation;
use App\Transaction;
use App\TaxRate;
use App\Business;

use App\Utils\ProductUtil;
use App\Utils\TransactionUtil;
use App\Utils\ContactUtil;
use App\Utils\BusinessUtil;
use App\Utils\ModuleUtil;
use App\Utils\CashRegisterUtil;


use Afip;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\{Auth,  Response, Session, Validator};

class SellReturnController extends Controller
{
    /**
     * All Utils instance.
     *
     */
    protected $productUtil;
    protected $transactionUtil;
    protected $contactUtil;
    protected $businessUtil;
    protected $moduleUtil;
    protected $cashRegisterUtil;

    /**
     * Constructor
     *
     * @param ProductUtils $product
     * @return void
     */
    public function __construct(
        ProductUtil $productUtil,
        TransactionUtil $transactionUtil,
        ContactUtil $contactUtil,
        BusinessUtil $businessUtil,
        ModuleUtil $moduleUtil,
        CashRegisterUtil $cashRegisterUtil
    ) {
        $this->productUtil = $productUtil;
        $this->transactionUtil = $transactionUtil;
        $this->contactUtil = $contactUtil;
        $this->businessUtil = $businessUtil;
        $this->moduleUtil = $moduleUtil;
        $this->cashRegisterUtil = $cashRegisterUtil;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!auth()->user()->can('sell.view') && !auth()->user()->can('sell.create')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            $business_id = request()->session()->get('user.business_id');

            $sells = Transaction::leftJoin('contacts', 'transactions.contact_id', '=', 'contacts.id')

                ->join(
                    'business_locations AS bl',
                    'transactions.location_id',
                    '=',
                    'bl.id'
                )
                ->join(
                    'transactions as T1',
                    'transactions.return_parent_id',
                    '=',
                    'T1.id'
                )
                ->leftJoin(
                    'transaction_payments AS TP',
                    'transactions.id',
                    '=',
                    'TP.transaction_id'
                )
                ->where('transactions.business_id', $business_id)
                ->where('transactions.type', 'sell_return')
                ->where('transactions.status', 'final')
                ->select(
                    'transactions.id',
                    'transactions.transaction_date',
                    'transactions.invoice_no',
                    'contacts.name',
                    'transactions.final_total',
                    'transactions.payment_status',
                    'bl.name as business_location',
                    'T1.invoice_no as parent_sale',
                    'T1.id as parent_sale_id',
                    DB::raw('SUM(TP.amount) as amount_paid')
                );

            $permitted_locations = auth()->user()->permitted_locations();
            if ($permitted_locations != 'all') {
                $sells->whereIn('transactions.location_id', $permitted_locations);
            }

            //Add condition for created_by,used in sales representative sales report
            if (request()->has('created_by')) {
                $created_by = request()->get('created_by');
                if (!empty($created_by)) {
                    $sells->where('transactions.created_by', $created_by);
                }
            }

            //Add condition for location,used in sales representative expense report
            if (request()->has('location_id')) {
                $location_id = request()->get('location_id');
                if (!empty($location_id)) {
                    $sells->where('transactions.location_id', $location_id);
                }
            }

            if (!empty(request()->customer_id)) {
                $customer_id = request()->customer_id;
                $sells->where('contacts.id', $customer_id);
            }
            if (!empty(request()->start_date) && !empty(request()->end_date)) {
                $start = request()->start_date;
                $end =  request()->end_date;
                $sells->whereDate('transactions.transaction_date', '>=', $start)
                    ->whereDate('transactions.transaction_date', '<=', $end);
            }

            $sells->groupBy('transactions.id');

            return Datatables::of($sells)
                ->addColumn(
                    'action',
                    '<div class="btn-group">
                    <button type="button" class="btn btn-info dropdown-toggle btn-xs" 
                        data-toggle="dropdown" aria-expanded="false">' .
                        __("messages.actions") .
                        '<span class="caret"></span><span class="sr-only">Toggle Dropdown
                        </span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-right" role="menu">
                    @if(auth()->user()->can("sell.view") || auth()->user()->can("direct_sell.access") )
                        <li><a href="#" class="btn-modal" data-container=".view_modal" data-href="{{action(\'SellReturnController@show\', [$parent_sale_id])}}"><i class="fa fa-external-link" aria-hidden="true"></i> @lang("messages.view")</a></li>
                        <li><a href="{{action(\'SellReturnController@add\', [$parent_sale_id])}}" ><i class="fa fa-edit" aria-hidden="true"></i> @lang("messages.edit")</a></li>
                    @endif

                    @if(auth()->user()->can("sell.view") || auth()->user()->can("direct_sell.access") )
                        <li><a href="#" class="print-invoice" data-href="{{action(\'SellReturnController@printInvoice\', [$id])}}"><i class="fa fa-print" aria-hidden="true"></i> @lang("messages.print")</a></li>
                    @endif
                    </ul>
                    </div>'
                )
                ->removeColumn('id')
                ->editColumn(
                    'final_total',
                    '<span class="display_currency final_total" data-currency_symbol="true" data-orig-value="{{$final_total}}">{{$final_total}}</span>'
                )
                ->editColumn('parent_sale', function ($row) {
                    return '<button type="button" class="btn btn-link btn-modal" data-container=".view_modal" data-href="' . action('SellController@show', [$row->parent_sale_id]) . '">' . $row->parent_sale . '</button>';
                })
                ->editColumn('transaction_date', '{{@format_date($transaction_date)}}')
                ->editColumn(
                    'payment_status',
                    '<a href="{{ action("TransactionPaymentController@show", [$id])}}" class="view_payment_modal payment-status payment-status-label" data-orig-value="{{$payment_status}}" data-status-name="{{__(\'lang_v1.\' . $payment_status)}}"><span class="label @payment_status($payment_status)">{{__(\'lang_v1.\' . $payment_status)}}</span></a>'
                )
                ->addColumn('payment_due', function ($row) {
                    $due = $row->final_total - $row->amount_paid;
                    return '<span class="display_currency payment_due" data-currency_symbol="true" data-orig-value="' . $due . '">' . $due . '</sapn>';
                })
                ->setRowAttr([
                    'data-href' => function ($row) {
                        if (auth()->user()->can("sell.view")) {
                            return  action('SellReturnController@show', [$row->parent_sale_id]);
                        } else {
                            return '';
                        }
                    }
                ])
                ->rawColumns(['final_total', 'action', 'parent_sale', 'payment_status', 'payment_due'])
                ->make(true);
        }

        return view('sell_return.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function create()
    // {
    //     if (!auth()->user()->can('sell.create')) {
    //         abort(403, 'Unauthorized action.');
    //     }

    //     $business_id = request()->session()->get('user.business_id');

    //     //Check if subscribed or not
    //     if (!$this->moduleUtil->isSubscribed($business_id)) {
    //         return $this->moduleUtil->expiredResponse(action('SellReturnController@index'));
    //     }

    //     $business_locations = BusinessLocation::forDropdown($business_id);
    //     //$walk_in_customer = $this->contactUtil->getWalkInCustomer($business_id);

    //     return view('sell_return.create')
    //         ->with(compact('business_locations'));
    // }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function add($id)
    {
        if (!auth()->user()->can('sell.create')) {
            abort(403, 'Unauthorized action.');
        }

        //Check if there is a open register, if no then redirect to Create Register screen.
        if ($this->cashRegisterUtil->countOpenedRegister() == 0) {
            return redirect()->action('CashRegisterController@create_cash_register_return');
        }

        $business_id = request()->session()->get('user.business_id');
        //Check if subscribed or not
        if (!$this->moduleUtil->isSubscribed($business_id)) {
            return $this->moduleUtil->expiredResponse();
        }

        $sell = Transaction::where('business_id', $business_id)
            ->with(['payment_lines', 'sell_lines', 'location', 'return_parent', 'contact', 'tax', 'sell_lines.sub_unit', 'sell_lines.product', 'sell_lines.product.unit'])
            ->find($id);

        //dd($sell->payment_lines);    

        foreach ($sell->sell_lines as $key => $value) {
            if (!empty($value->sub_unit_id)) {
                $formated_sell_line = $this->transactionUtil->recalculateSellLineTotals($business_id, $value);
                $sell->sell_lines[$key] = $formated_sell_line;
            }

            $sell->sell_lines[$key]->formatted_qty = $this->transactionUtil->num_f($value->quantity, false, null, true);
        }

        return view('sell_return.add')
            ->with(compact('sell'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!auth()->user()->can('sell.create')) {
            abort(403, 'Unauthorized action.');
        }



        try {
            $input = $request->except('_token');

            Log::info($input);

            if (!empty($input['products'])) {
                $business_id = $request->session()->get('user.business_id');

                //Check if subscribed or not
                if (!$this->moduleUtil->isSubscribed($business_id)) {
                    return $this->moduleUtil->expiredResponse(action('SellReturnController@index'));
                }

                $user_id = $request->session()->get('user.id');

                $discount = [
                    'discount_type' => $input['discount_type'],
                    'discount_amount' => $input['discount_amount']
                ];
                $invoice_total = $this->productUtil->calculateInvoiceTotal($input['products'], $input['tax_id'], $discount);

                //Get parent sale
                $sell = Transaction::where('business_id', $business_id)
                    ->with(['sell_lines', 'sell_lines.sub_unit'])
                    ->findOrFail($input['transaction_id']);

                //Check if any sell return exists for the sale
                $sell_return = Transaction::where('business_id', $business_id)
                    ->where('type', 'sell_return')
                    ->where('return_parent_id', $sell->id)
                    ->first();

                if (!empty($sell->num_invoice_afip)) {
                    $ref_invoice_no = $sell->num_invoice_afip;
                } else {
                    $ref_invoice_no = $sell->invoice_no;
                }

                $sell_return_data = [
                    'transaction_date' => $this->productUtil->uf_date($request->input('transaction_date')),
                    'invoice_no' => $input['invoice_no'],
                    'discount_type' => $discount['discount_type'],
                    'discount_amount' => $this->productUtil->num_uf($input['discount_amount']),
                    'tax_id' => $input['tax_id'],
                    'tax_amount' => $invoice_total['tax'],
                    'total_before_tax' => $invoice_total['total_before_tax'],
                    'final_total' => $invoice_total['final_total'],
                    'ref_no' => $ref_invoice_no
                ];

                DB::beginTransaction();

                //Generate reference number
                if (empty($sell_return_data['invoice_no'])) {
                    //Update reference count
                    $ref_count = $this->productUtil->setAndGetReferenceCount('sell_return');
                    $sell_return_data['invoice_no'] = $this->productUtil->generateReferenceNumber('sell_return', $ref_count);
                }

                if (empty($sell_return)) {
                    $sell_return_data['business_id'] = $business_id;
                    $sell_return_data['location_id'] = $sell->location_id;
                    $sell_return_data['contact_id'] = $sell->contact_id;
                    $sell_return_data['customer_group_id'] = $sell->customer_group_id;
                    $sell_return_data['type'] = 'sell_return';
                    $sell_return_data['status'] = 'final';
                    $sell_return_data['created_by'] = $user_id;
                    $sell_return_data['return_parent_id'] = $sell->id;
                    $sell_return = Transaction::create($sell_return_data);
                } else {
                    $sell_return->update($sell_return_data);
                }

                //Update payment status
                $this->transactionUtil->updatePaymentStatus($sell_return->id, $sell_return->final_total);

                //Update quantity returned in sell line
                $returns = [];
                $product_lines = $request->input('products');

                $neto10 = 0; // por algun motivo desconocido, estaban inicializados en null kkkk
                $neto21 = 0;

                foreach ($product_lines as $product_line) {
                    $returns[$product_line['sell_line_id']] = $product_line['quantity'];

                    if ($product_line['tax_id'] == '1') {

                        //$unit_price = $this->productUtil->num_uf($product_line['unit_price']);
                        $monto21 = $this->productUtil->num_uf($product_line['unit_price']);
                        $monto21 = $monto21 * $product_line['quantity'];
                        $neto21 = $neto21 + $monto21;
                    } else if ($product_line['tax_id'] == '2') {
                        //$unit_price = $this->productUtil->num_uf($product_line['unit_price_inc_tax'] / 1.105);
                        $monto10 = $this->productUtil->num_uf($product_line['unit_price']);
                        $monto10 = $monto10 * $product_line['quantity'];
                        $neto10 = $neto10 + $monto10;
                    }
                }

                if ($input['discount_amount'] != '0.00' &&  $input['discount_type'] == 'percentage') {

                    $neto21  = $neto21 - ($neto21 * $input['discount_amount'] / 100);
                    $neto10  = $neto10 - ($neto10 * $input['discount_amount'] / 100);
                } else if ($input['discount_amount'] != '0.00' &&  $input['discount_type'] == 'fixed') {

                    $discount = ($this->productUtil->num_uf($input['discount_amount']) * 100) /   $this->productUtil->num_uf($input['price_total']);;
                    $neto21 = $neto21 - ($neto21 * $discount / 100);
                    $neto10  = $neto10 - ($neto10 * $discount / 100);
                }



                $sell_return_data['iva10'] = $neto10;
                $sell_return_data['iva21'] = $neto21;
                $sell_return->update($sell_return_data);

                Log::info('Usuario: ' . Auth::user()->id . ' transacción (devolucion): ' . $sell_return);

                foreach ($sell->sell_lines as $sell_line) {
                    if (array_key_exists($sell_line->id, $returns)) {
                        $multiplier = 1;
                        if (!empty($sell_line->sub_unit)) {
                            $multiplier = $sell_line->sub_unit->base_unit_multiplier;
                        }

                        $quantity = $this->transactionUtil->num_uf($returns[$sell_line->id]) * $multiplier;

                        $quantity_before = $this->transactionUtil->num_f($sell_line->quantity_returned);
                        $quantity_formated = $this->transactionUtil->num_f($quantity);

                        $sell_line->quantity_returned = $quantity;
                        $sell_line->save();

                        //update quantity sold in corresponding purchase lines
                        $this->transactionUtil->updateQuantitySoldFromSellLine($sell_line, $quantity_formated, $quantity_before);

                        // Update quantity in variation location details
                        $this->productUtil->updateProductQuantity($sell_return->location_id, $sell_line->product_id, $sell_line->variation_id, $quantity_formated, $quantity_before);
                    }
                }


                if (!empty($sell->cae)) {
                    $this->registroAfip($sell_return->id, $sell->id);
                }

                //Add payments to Cash Register

                $payments = $request->input('payment');

                if ($payments) {

                    $this->cashRegisterUtil->refundSell($sell_return, $payments);
                }

                $receipt = $this->receiptContent($business_id, $sell_return->location_id, $sell_return->id);
                DB::commit();



                $output = [
                    'success' => 1,
                    'msg' => __('lang_v1.success'),
                    'receipt' => $receipt
                ];
            }
        } catch (\Exception $e) {
            DB::rollBack();

            if (get_class($e) == \App\Exceptions\PurchaseSellMismatch::class) {
                $msg = $e->getMessage();
            } else {
                \Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());
                $msg = __('messages.something_went_wrong');
            }

            $output = [
                'success' => 0,
                'msg' => $msg
            ];
        }

        return $output;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!auth()->user()->can('sell.view')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = request()->session()->get('user.business_id');
        $sell = Transaction::where('business_id', $business_id)
            ->where('id', $id)
            ->with(
                'contact',
                'return_parent',
                'tax',
                'sell_lines',
                'sell_lines.product',
                'sell_lines.variations',
                'sell_lines.sub_unit',
                'sell_lines.product',
                'sell_lines.product.unit',
                'location'
            )
            ->first();

        foreach ($sell->sell_lines as $key => $value) {
            if (!empty($value->sub_unit_id)) {
                $formated_sell_line = $this->transactionUtil->recalculateSellLineTotals($business_id, $value);
                $sell->sell_lines[$key] = $formated_sell_line;
            }
        }

        $sell_taxes = [];
        if (!empty($sell->return_parent->tax)) {
            if ($sell->return_parent->tax->is_tax_group) {
                $sell_taxes = $this->transactionUtil->sumGroupTaxDetails($this->transactionUtil->groupTaxDetails($sell->return_parent->tax, $sell->return_parent->tax_amount));
            } else {
                $sell_taxes[$sell->return_parent->tax->name] = $sell->return_parent->tax_amount;
            }
        }

        $total_discount = 0;
        if ($sell->return_parent->discount_type == 'fixed') {
            $total_discount = $sell->return_parent->discount_amount;
        } elseif ($sell->return_parent->discount_type == 'percentage') {
            $total_after_discount = $sell->return_parent->final_total - $sell->return_parent->tax_amount;
            $total_before_discount = $total_after_discount * 100 / (100 - $sell->return_parent->discount_amount);
            $total_discount = $total_before_discount - $total_after_discount;
        }

        return view('sell_return.show')
            ->with(compact('sell', 'sell_taxes', 'total_discount'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Return the row for the product
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getProductRow()
    {
    }

    /**
     * Returns the content for the receipt
     *
     * @param  int  $business_id
     * @param  int  $location_id
     * @param  int  $transaction_id
     * @param string $printer_type = null
     *
     * @return array
     */
    private function receiptContent(
        $business_id,
        $location_id,
        $transaction_id,
        $printer_type = null
    ) {
        $output = [
            'is_enabled' => false,
            'print_type' => 'browser',
            'html_content' => null,
            'printer_config' => [],
            'data' => []
        ];

        $business_details = $this->businessUtil->getDetails($business_id);
        $location_details = BusinessLocation::find($location_id);

        //Check if printing of invoice is enabled or not.
        if ($location_details->print_receipt_on_invoice == 1) {
            //If enabled, get print type.
            $output['is_enabled'] = true;

            $invoice_layout = $this->businessUtil->invoiceLayout($business_id, $location_id, $location_details->invoice_layout_id);

            //Check if printer setting is provided.
            $receipt_printer_type = is_null($printer_type) ? $location_details->receipt_printer_type : $printer_type;

            $receipt_details = $this->transactionUtil->getReceiptDetails($transaction_id, $location_id, $invoice_layout, $business_details, $location_details, $receipt_printer_type);

            //If print type browser - return the content, printer - return printer config data, and invoice format config
            if ($receipt_printer_type == 'printer') {
                $output['print_type'] = 'printer';
                $output['printer_config'] = $this->businessUtil->printerConfig($business_id, $location_details->printer_id);
                $output['data'] = $receipt_details;
            } else {
                $output['html_content'] = view('sell_return.receipt', compact('receipt_details'))->render();
            }
        }

        return $output;
    }

    /**
     * Prints invoice for sell
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function printInvoice(Request $request, $transaction_id)
    {
        if (request()->ajax()) {
            try {
                $output = [
                    'success' => 0,
                    'msg' => trans("messages.something_went_wrong")
                ];

                $business_id = $request->session()->get('user.business_id');

                $transaction = Transaction::where('business_id', $business_id)
                    ->where('id', $transaction_id)
                    ->first();

                if (empty($transaction)) {
                    return $output;
                }

                $receipt = $this->receiptContent($business_id, $transaction->location_id, $transaction_id, 'browser');

                if (!empty($receipt)) {
                    $output = ['success' => 1, 'receipt' => $receipt];
                }
            } catch (\Exception $e) {
                $output = [
                    'success' => 0,
                    'msg' => trans("messages.something_went_wrong")
                ];
            }

            return $output;
        }
    }

    public function registroAfip($sell_return_id, $sell_id)
    {

        $business_id = request()->session()->get('user.business_id');
        $taxes = TaxRate::where('business_id', $business_id)
            ->pluck('name', 'id');

        $sell_return = Transaction::where('business_id', $business_id)
            ->where('id', $sell_return_id)
            ->with(['contact', 'sell_lines' => function ($q) {
                $q->whereNull('parent_sell_line_id');
            }, 'sell_lines.product', 'sell_lines.product.unit', 'sell_lines.variations', 'sell_lines.variations.product_variation', 'payment_lines', 'sell_lines.modifiers', 'sell_lines.lot_details', 'tax', 'sell_lines.sub_unit', 'table', 'service_staff', 'sell_lines.service_staff'])
            ->first();

        //Log::emergency($sell_return);

        $sell = Transaction::where('business_id', $business_id)
            ->where('id', $sell_id)
            ->with(['contact', 'sell_lines' => function ($q) {
                $q->whereNull('parent_sell_line_id');
            }, 'sell_lines.product', 'sell_lines.product.unit', 'sell_lines.variations', 'sell_lines.variations.product_variation', 'payment_lines', 'sell_lines.modifiers', 'sell_lines.lot_details', 'tax', 'sell_lines.sub_unit', 'table', 'service_staff', 'sell_lines.service_staff'])
            ->first();


        if ($sell->type_invoice == 'A') {
            $type_invoice = '1';
        } else if ($sell->type_invoice == 'B') {
            $type_invoice = '6';
        } else if ($sell->type_invoice == 'C') {
            $type_invoice = '11';
        }

        $business_locations = BusinessLocation::where('id', $sell->location_id)->first();

        $cuit = $business_locations->cuit;
        $punto_venta = $business_locations->punto_venta;

        $ImpNeto = $sell->total_before_tax;

        if ($business_locations->tax_label_1 == 'MONOTRIBUTO') {

            $ImpTotal =  $ImpNeto;
            $ImpTotal = number_format((float)$ImpTotal, 2, '.', '');
            $ImpIVA = 0;
            $CbteTipo = '13';
            $letra = 'C';
        } else {

            if ($sell_return->contact->iva == 'RESPONSABLE INSCRIPTO') {
                $CbteTipo = '3';
                $letra = 'A';
            } else {
                $CbteTipo = '8';
                $letra = 'B';
            }
            //$ImpTotal = $ImpNeto * 1.21;
            //$ImpTotal= number_format((float)$ImpTotal, 2, '.', '');
            //$ImpNeto = number_format((float)$ImpNeto, 2, '.', '');
            //$ImpIVA = $ImpTotal - $ImpNeto;
            //$ImpIVA = number_format((float)$ImpIVA, 2, '.', '');
        }

        $total10 = $sell_return->iva10 * 1.105;
        $iva10 = $total10 - $sell_return->iva10;

        $total21 = $sell_return->iva21 * 1.21;
        $iva21 = $total21 - $sell_return->iva21;

        $iva21 = number_format((float)$iva21, 2, '.', '');
        $iva10 = number_format((float)$iva10, 2, '.', '');

        $ImpIVA = $iva10 + $iva21;


        $ImpTotal = $total21 +  $total10;

        $ImpNeto =  $ImpTotal - $ImpIVA;

        $ImpTotal = number_format((float)$ImpTotal, 2, '.', '');

        $ImpNeto = number_format((float)$ImpNeto, 2, '.', '');

        $certPath = base_path($business_locations->url_cert);
        $keyPath = base_path($business_locations->url_key);

        $options = [                   
            'CUIT' => $cuit,
            'production' => True,
            'cert' => $certPath,
            'key' => $keyPath
        ];

        $afip = new Afip($options);
        $last_voucher = $afip->ElectronicBilling->GetLastVoucher($punto_venta, $CbteTipo);
        $numComp = $last_voucher + 1;

        $date = Carbon::now('America/Argentina/Buenos_Aires');
        $date2 = $date->format('Ymd');
        $dateqr = $date->format('Y-m-d');

        if ($sell_return->contact->id == 1) {
            $doctipo = 99;
        } else {
            $doctipo = 80;
        }

        if ($business_locations->tax_label_1 == 'MONOTRIBUTO') {


            $data = array(
                'CantReg'     => 1,  // Cantidad de comprobantes a registrar
                'PtoVta'     => $punto_venta,  // Punto de venta
                'CbteTipo'     => $CbteTipo,  // Tipo de comprobante (ver tipos disponibles) 
                'Concepto'     => 1,  // Concepto del Comprobante: (1)Productos, (2)Servicios, (3)Productos y Servicios
                'DocTipo'     => $doctipo, // Tipo de documento del comprador (99 consumidor final, ver tipos disponibles)
                'DocNro'     => intval($sell_return->contact->tax_number),  // Número de documento del comprador (0 consumidor final)
                'CbteDesde'     => $numComp,  // Número de comprobante o numero del primer comprobante en caso de ser mas de uno
                'CbteHasta'     => $numComp,  // Número de comprobante o numero del último comprobante en caso de ser mas de uno
                'CbteFch'         => intval($date2), // (Opcional) Fecha del comprobante (yyyymmdd) o fecha actual si es nulo
                'ImpTotal'     => $ImpTotal, // Importe total del comprobante
                'ImpTotConc'     => 0,   // Importe neto no gravado
                'ImpNeto'     => $ImpTotal, // Importe neto gravado
                'ImpOpEx'     => 0,   // Importe exento de IVA
                'ImpIVA'     => 0,  //Importe total de IVA
                'ImpTrib'     => 0,   //Importe total de tributos
                'MonId'     => 'PES', //Tipo de moneda usada en el comprobante (ver tipos disponibles)('PES' para pesos argentinos) 
                'MonCotiz'     => 1,     // Cotización de la moneda usada (1 para pesos argentinos)  
                'CbtesAsoc' => array(
                    'CbteAsoc' =>
                    array(
                        'Tipo' => $type_invoice,
                        'PtoVta' => $punto_venta,
                        'Nro' => $sell->num_invoice_afip,
                    )
                ),
            );
        } else {



            //ambas alicuotas//
            if ($sell_return->iva10 > 0 and $sell_return->iva21 > 0) {

                $data = array(
                    'CantReg'     => 1,  // Cantidad de comprobantes a registrar
                    'PtoVta'     => $punto_venta,  // Punto de venta
                    'CbteTipo'     => $CbteTipo,  // Tipo de comprobante (ver tipos disponibles) 
                    'Concepto'     => 1,  // Concepto del Comprobante: (1)Productos, (2)Servicios, (3)Productos y Servicios
                    'DocTipo'     => $doctipo, // Tipo de documento del comprador (99 consumidor final, ver tipos disponibles)
                    'DocNro'     => intval($sell_return->contact->tax_number),  // Número de documento del comprador (0 consumidor final)
                    'CbteDesde'     => $numComp,  // Número de comprobante o numero del primer comprobante en caso de ser mas de uno
                    'CbteHasta'     => $numComp,  // Número de comprobante o numero del último comprobante en caso de ser mas de uno
                    'CbteFch'         => intval($date2), // (Opcional) Fecha del comprobante (yyyymmdd) o fecha actual si es nulo
                    'ImpTotal'     => $ImpTotal, // Importe total del comprobante
                    'ImpTotConc'     => 0,   // Importe neto no gravado
                    'ImpNeto'     => $ImpNeto, // Importe neto gravado
                    'ImpOpEx'     => 0,   // Importe exento de IVA
                    'ImpIVA'     => $ImpIVA,  //Importe total de IVA
                    'ImpTrib'     => 0,   //Importe total de tributos
                    'MonId'     => 'PES', //Tipo de moneda usada en el comprobante (ver tipos disponibles)('PES' para pesos argentinos) 
                    'MonCotiz'     => 1,     // Cotización de la moneda usada (1 para pesos argentinos) 
                    'CbtesAsoc' => array(
                        'CbteAsoc' =>
                        array(
                            'Tipo' => $type_invoice,
                            'PtoVta' => $punto_venta,
                            'Nro' => $sell->num_invoice_afip,
                        )
                    ),
                    'Iva'         => array( // (Opcional) Alícuotas asociadas al comprobante

                        array(
                            'Id'         => 4,    // IVA 10.5%
                            'BaseImp'     => $sell->iva10, // Base imponible
                            'Importe'     => $iva10 // Importe 
                        ),

                        array(
                            'Id'         => 5,  // IVA 21% 
                            'BaseImp'     => $sell->iva21, // Base imponible
                            'Importe'     => $iva21 // Importe 
                        )

                    ),
                );
            }


            //Solo 21%//
            if ($sell_return->iva10 == 0 and $sell_return->iva21 > 0) {

                $data = array(
                    'CantReg'     => 1,  // Cantidad de comprobantes a registrar
                    'PtoVta'     => $punto_venta,  // Punto de venta
                    'CbteTipo'     => $CbteTipo,  // Tipo de comprobante (ver tipos disponibles) 
                    'Concepto'     => 1,  // Concepto del Comprobante: (1)Productos, (2)Servicios, (3)Productos y Servicios
                    'DocTipo'     => $doctipo, // Tipo de documento del comprador (99 consumidor final, ver tipos disponibles)
                    'DocNro'     => intval($sell_return->contact->tax_number),  // Número de documento del comprador (0 consumidor final)
                    'CbteDesde'     => $numComp,  // Número de comprobante o numero del primer comprobante en caso de ser mas de uno
                    'CbteHasta'     => $numComp,  // Número de comprobante o numero del último comprobante en caso de ser mas de uno
                    'CbteFch'         => intval($date2), // (Opcional) Fecha del comprobante (yyyymmdd) o fecha actual si es nulo
                    'ImpTotal'     => $ImpTotal, // Importe total del comprobante
                    'ImpTotConc'     => 0,   // Importe neto no gravado
                    'ImpNeto'     => $ImpNeto, // Importe neto gravado
                    'ImpOpEx'     => 0,   // Importe exento de IVA
                    'ImpIVA'     => $ImpIVA,  //Importe total de IVA
                    'ImpTrib'     => 0,   //Importe total de tributos
                    'MonId'     => 'PES', //Tipo de moneda usada en el comprobante (ver tipos disponibles)('PES' para pesos argentinos) 
                    'MonCotiz'     => 1,     // Cotización de la moneda usada (1 para pesos argentinos) 
                    'CbtesAsoc' => array(
                        'CbteAsoc' =>
                        array(
                            'Tipo' => $type_invoice,
                            'PtoVta' => $punto_venta,
                            'Nro' => $sell->num_invoice_afip,
                        )
                    ),
                    'Iva'         => array( // (Opcional) Alícuotas asociadas al comprobante


                        array(
                            'Id'         => 5,  // IVA 21% 
                            'BaseImp'     => $sell->iva21, // Base imponible
                            'Importe'     => $iva21 // Importe 
                        )

                    )
                );
            }
        }



        //Solo 10.5%//
        if ($sell_return->iva21 == 0 and $sell_return->iva10 > 0) {

            $data = array(
                'CantReg'     => 1,  // Cantidad de comprobantes a registrar
                'PtoVta'     => $punto_venta,  // Punto de venta
                'CbteTipo'     => $CbteTipo,  // Tipo de comprobante (ver tipos disponibles) 
                'Concepto'     => 1,  // Concepto del Comprobante: (1)Productos, (2)Servicios, (3)Productos y Servicios
                'DocTipo'     => $doctipo, // Tipo de documento del comprador (99 consumidor final, ver tipos disponibles)
                'DocNro'     => intval($sell_return->contact->tax_number),  // Número de documento del comprador (0 consumidor final)
                'CbteDesde'     => $numComp,  // Número de comprobante o numero del primer comprobante en caso de ser mas de uno
                'CbteHasta'     => $numComp,  // Número de comprobante o numero del último comprobante en caso de ser mas de uno
                'CbteFch'         => intval($date2), // (Opcional) Fecha del comprobante (yyyymmdd) o fecha actual si es nulo
                'ImpTotal'     => $ImpTotal, // Importe total del comprobante
                'ImpTotConc'     => 0,   // Importe neto no gravado
                'ImpNeto'     => $ImpNeto, // Importe neto gravado
                'ImpOpEx'     => 0,   // Importe exento de IVA
                'ImpIVA'     => $ImpIVA,  //Importe total de IVA
                'ImpTrib'     => 0,   //Importe total de tributos
                'MonId'     => 'PES', //Tipo de moneda usada en el comprobante (ver tipos disponibles)('PES' para pesos argentinos) 
                'MonCotiz'     => 1,     // Cotización de la moneda usada (1 para pesos argentinos) 
                'CbtesAsoc' => array(
                    'CbteAsoc' =>
                    array(
                        'Tipo' => $type_invoice,
                        'PtoVta' => $punto_venta,
                        'Nro' => $sell->num_invoice_afip,
                    )
                ),
                'Iva'         => array( // (Opcional) Alícuotas asociadas al comprobante

                    array(
                        'Id'         => 4,    // IVA 10.5%
                        'BaseImp'     => $sell->iva10, // Base imponible
                        'Importe'     => $iva10 // Importe 
                    ),

                ),
            );
        }

        Log::info($data);

        $res = $afip->ElectronicBilling->CreateVoucher($data);

        $cae = $res['CAE']; //CAE asignado el comprobante
        $vtocae = $res['CAEFchVto']; //Fecha de vencimiento del CAE (yyyy-mm-dd)

        $sell_return->cae = $cae;
        $sell_return->afip_invoice_date = $date2;
        $sell_return->exp_cae = $vtocae;
        $num_fac = $last_voucher + 1;
        $sell_return->num_invoice_afip = str_pad($punto_venta, 4, "0", STR_PAD_LEFT) . '-' . str_pad($num_fac, 8, "0", STR_PAD_LEFT);
        $data = '{"ver":1,"fecha":' . $dateqr . ',"cuit":' . $cuit . ',"ptoVta":' . $punto_venta . ',"tipoCmp":11,"nroCmp":' . $num_fac . ',"importe":' . $ImpTotal . ',"moneda":"PES","ctz":1,"tipoDocRec":' . $doctipo . ',"nroDocRec":' . $sell->contact->tax_number . ',"tipoCodAut":"E","codAut":' . $cae . '}';
        $data64 = "https://www.afip.gob.ar/fe/qr/?p=" . base64_encode($data);
        $sell_return->qrCode = $data64;
        $sell_return->type_invoice = $letra;

        $sell_return->update();

        return (["res" => $res]);
    }
}
