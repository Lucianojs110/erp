<?php

namespace App\Http\Controllers;

use App\AgentTemporalStock;
use App\BusinessLocation;
use App\Contact;
use App\CustomerGroup;
use App\Delivery;
use App\Product;
use App\SalesCommissionAgent;
use App\Transaction;
use App\User;
use App\Utils\ProductUtil;
use App\Variation;
use App\VariationLocationDetails;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class DeliveryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        if (!auth()->user()->can('delivery.view')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            $business_id = request()->session()->get('user.business_id');

            $deliveries = Delivery::join('business_locations as bl', 'deliveries.business_location_id', '=', 'bl.id')
                ->join('contacts as c', 'deliveries.contact_id', '=', 'c.id')
                ->join('sales_commission_agents as sca', 'deliveries.sales_commission_agent_id', '=', 'sca.id')
                ->join('users as u', 'sca.user_id', '=', 'u.id')
                ->join('transactions as t', 'deliveries.transaction_id', '=', 't.id')
                ->where('sca.business_id', $business_id)
                ->select([
                    'delivered_at',
                    'bl.name as business_location',
                    'deliveries.id',
                    DB::raw("CONCAT(COALESCE(u.surname, ''), ' ', COALESCE(u.first_name, ''), ' ', COALESCE(u.last_name, '')) as agent"),
                    'c.name as customer',
                    't.id as transaction_id',
                    't.cae as tcae'
                ])->orderBy('deliveries.id', 'desc');

            // if user role is agent
            if (auth()->user()->roles->first()->name == 'Repartidor#1') {
                $deliveries->where('sca.user_id', auth()->user()->id);
            }

            return Datatables::of($deliveries)
                ->addColumn(
                    'action',
                    '<a href="{{action(\'DeliveryController@show\', [$id])}}" class="btn btn-xs btn-success view_delivery_button"><i class="glyphicon glyphicon-eye-open"></i> @lang("messages.view")</a>
                            &nbsp;

                        @if(\App\Transaction::where(\'id\', $transaction_id)->first()->status != \'final\')
                            <button data-href="{{action(\'DeliveryController@destroy\', [$id])}}" class="btn btn-xs btn-danger delete_delivery_button"><i class="glyphicon glyphicon-trash"></i> @lang("messages.delete")</button>
                        @endif

                        @if(\App\Transaction::where(\'return_parent_id\', $transaction_id)->exists())
                            <a data-container=".view_modal" data-href="{{action(\'SellReturnController@show\', [$transaction_id])}}" class="btn btn-modal btn-xs btn-info" href="#"><i class="glyphicon glyphicon-eye-open"></i> @lang("lang_v1.sell_return")</a>
                        @else
                            <a href="{{action(\'SellReturnController@add\', [$transaction_id])}}" class="btn btn-xs btn-info"><i class="fa fa-undo"></i> @lang("lang_v1.sell_return")</a>
                            @endif

                        @if(empty($tcae))
                            <a href="{{action(\'SellController@afipInvoice\', [$transaction_id])}}" class="btn btn-xs btn-info"><i class="glyphicon  glyphicon-ok-sign"></i>Facturar Venta</a>
                        @endif
                    '
                )
                ->editColumn('delivered_at', '{{@format_date($delivered_at)}}')
                ->removeColumn('id')
                ->removeColumn('transaction_id')
                ->removeColumn('tcae')
                ->rawColumns([4])
                ->make(false);
        }

        return view('delivery.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!auth()->user()->can('delivery.create')) {
            abort(403, 'Unauthorized action.');
        }

        $business_locations = BusinessLocation::forDropdown(request()->session()->get('user.business_id'));

       

        $products = Product::where('business_id', request()->session()->get('user.business_id'))
            ->join('variations as v', 'products.id', '=', 'v.product_id')
            ->join('variation_location_details as vld', 'v.id', '=', 'vld.variation_id')
            ->select(
                'products.name as product_name',
                'products.id as p_id',
                'v.id as v_id',
                'vld.qty_available as qty_available',
                'vld.location_id as l_id',
                'v.sell_price_inc_tax as price'
            )
            ->groupBy('products.id')->get();



        $customers = Contact::where('business_id', request()->session()->get('user.business_id'))
            ->where('type', 'customer')
            ->pluck('name', 'id');

        $agents = SalesCommissionAgent::where('sales_commission_agents.business_id', request()->session()->get('user.business_id'))
            ->whereNull('sales_commission_agents.deleted_at')
            ->join('users as u', 'sales_commission_agents.user_id', '=', 'u.id')
            ->select(['sales_commission_agents.id', DB::raw("CONCAT(COALESCE(u.surname, ''), ' ', COALESCE(u.first_name, ''), ' ', COALESCE(u.last_name, '')) as full_name"), 'sales_commission_agents.commission_percentage'])
            ->get();

        return view('delivery.create')
            ->with(compact('business_locations', 'products', 'customers', 'agents'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!auth()->user()->can('delivery.create')) {
            abort(403, 'Unauthorized action.');
        }

        try {
            
            $input = $request->only(['user_id', 'delivered_at', 'location_id', 'delivery_date', 'contact_id', 'total_commission']);
            
            $agent = SalesCommissionAgent::where('id', $input['user_id'])->first();
            $input['business_id'] = $request->session()->get('user.business_id');
            $input['created_by'] = $request->session()->get('user.id');
            $input['sales_commission_agent_id'] = $agent->id;
            $input['business_location_id'] = $input['location_id'];
            $input['final_total'] = $request->total;
            $input['contact_id'] = $input['contact_id'];
            unset($input['location_id']);
            $input['delivered_at'] = !empty($input['delivered_at']) ? Carbon::createFromFormat('Y-m-d', $input['delivered_at']) : date_create()->format('Y-m-d');

            // check if the variation detail has enough quantity for some products
            $productUtil = new ProductUtil();
            foreach ($request->product_id as $key => $product_id) {
                $variation_detail = DB::table('variation_location_details')->where('product_id', $product_id)
                    ->where('variation_id', $request->v_id[$key])
                    ->where('location_id', $input['business_location_id'])
                    ->first();

                if (!empty($variation_detail) && $variation_detail->qty_available < $request->quantity[$key]) {
                    return [
                        'success' => false,
                        'msg' => "No hay suficiente stock", ['product' => Product::find($product_id)->name]
                    ];
                }
            }

                $input['iva21'] = 0;
                $input['iva27'] = 0;
                $input['iva10'] = 0;

                foreach ($request->product_id as $key => $product_id) {

                    $product = Product::find($product_id);

                    $variation = Variation::find($request->v_id[$key]);

                    $tax_amount = $productUtil->num_uf($variation->sell_price_inc_tax - $variation->default_sell_price);
                    $tax_amount = $tax_amount * $request->quantity[$key];

                    if ($product->tax == 1) {
                        $input['iva21'] = $input['iva21'] + $tax_amount;
                    } else if ($product->tax == 2) {
                        $input['iva10'] = $input['iva10'] + $tax_amount;
                    } else if ($product->tax == 3) {
                        $input['iva27'] = $input['iva27'] + $tax_amount;
                    }
                }


            // Creates transaction
            $transaction = Transaction::create([
                'type' => 'sell',
                'status' => 'draft',
                'is_quotation' => 0,
                'contact_id' => $input['contact_id'],
                'commission_agent' => $input['sales_commission_agent_id'],
                'business_id' => $input['business_id'],
                'location_id' => $input['business_location_id'],
                'transaction_date' => $input['delivered_at'],
                'total_before_tax' => $request->total,
                'discount_type' => 'percentage',
                'final_total' => $request->total,
                'created_by' => $agent->user_id,
                'iva21' => $input['iva21'],
                'iva27' => $input['iva27'],
                'iva10' => $input['iva10'],
            ]);

            $input['transaction_id'] = $transaction->id;

            $delivery = Delivery::create($input);

            // get and merge product_id[], quantity[] and unit_price[] from request
            $products = [];
            $sell_lines = [];
            foreach ($request->product_id as $key => $product_id) {
                $products[] = [
                    'delivery_id' => $delivery->id,
                    'product_id' => $product_id,
                    'quantity' => $request->quantity[$key],
                    'variation_id' => $request->v_id[$key],
                ];

                $sell_lines[] = [
                    'product_id' => $product_id,
                    'variation_id' => $request->v_id[$key],
                    'quantity' => $request->quantity[$key],
                    'unit_price' => $request->unit_price[$key],
                    'unit_price_inc_tax' => $request->unit_price[$key],
                    'tax_id' => 1,
                    'discount_type' => 'percentage',
                    'discount_amount' => 0,
                    'discount_amount_type' => 'percentage',
                ];
            }

            $delivery_details = $delivery->deliveryDetails()->createMany($products);

            $transaction->sell_lines()->createMany($sell_lines);

            // decrease product quantity
            foreach ($delivery_details as $delivery_detail) {
                $agent_temporal_stock = AgentTemporalStock::where('product_id', $delivery_detail->product_id)
                    ->where('variation_id', $delivery_detail->variation_id)
                    ->where('sales_commission_agent_id', $delivery->sales_commission_agent_id)
                    ->first();

                /* $productUtil = new ProductUtil();
                $productUtil->decreaseProductQuantity(
                    $delivery_detail->product_id,
                    $delivery_detail->variation_id,
                    $delivery->business_location_id,
                    $delivery_detail->quantity
                ); */
                VariationLocationDetails::where('variation_id', $delivery_detail->variation_id)
                    ->where('location_id', $delivery->business_location_id)
                    ->where('product_id', $delivery_detail->product_id)
                    ->decrement('qty_available', $delivery_detail->quantity);

                if (!empty($agent_temporal_stock)) {
                    $agent_temporal_stock->quantity += $delivery_detail->quantity;
                    $agent_temporal_stock->save();
                } else {
                    AgentTemporalStock::create([
                        'product_id' => $delivery_detail->product_id,
                        'variation_id' => $delivery_detail->variation_id,
                        'quantity' => $delivery_detail->quantity,
                        'location_id' => $delivery->business_location_id,
                        'sales_commission_agent_id' => $delivery->sales_commission_agent_id,
                    ]);
                }
            }


            $output = [
                'success' => true,
                'data' => $delivery,
                'msg' => __("lang_v1.success")
            ];
        } catch (\Exception $e) {
            \Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());

            $output = [
                'success' => false,
                'msg' => __("messages.something_went_wrong")
            ];
        }

        return $output;
    }

    public function show($id)
    {
        if (!auth()->user()->can('delivery.view')) {
            abort(403, 'Unauthorized action.');
        }

        
            $delivery = Delivery::where('id', $id)
                ->first();

            $customer = Contact::find($delivery->contact_id);

            $agent = SalesCommissionAgent::withTrashed()->find($delivery->sales_commission_agent_id);

            return view('delivery.show')
                ->with(compact('delivery', 'customer', 'agent'));
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Delivery $delivery
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!auth()->user()->can('delivery.edit')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            $delivery = Delivery::find($id);


            $agent = SalesCommissionAgent::find($delivery->sales_commission_agent_id);

            $agent = User::where('id', $agent->user_id)
                ->select('first_name', 'last_name')
                ->first();

            $business_locations = BusinessLocation::forDropdown(request()->session()->get('user.business_id'));

            $customers = Contact::where('business_id', request()->session()->get('user.business_id'))
                ->where('type', 'customer')
                ->pluck('name', 'id');


            $shipping_statuses = Delivery::SHIPPING_STATUSES;

            $products = Product::where('business_id', request()->session()->get('user.business_id'))
                ->join('variations as v', 'products.id', '=', 'v.product_id')
                ->select('products.name as product_name', 'products.id as p_id', 'v.id as v_id', 'v.sell_price_inc_tax as price')
                ->groupBy('products.id')->get();

            return view('delivery.edit')
                ->with(compact('delivery', 'business_locations', 'products', 'shipping_statuses', 'customers', 'agent'));
        }
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
        if (!auth()->user()->can('delivery.edit')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            try {


                $output = [
                    'success' => true,
                    'msg' => __("lang_v1.success")
                ];
            } catch (\Exception $e) {
                \Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());

                $output = [
                    'success' => false,
                    'msg' => __("messages.something_went_wrong")
                ];
            }

            return $output;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!auth()->user()->can('delivery.delete')) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $delivery = Delivery::find($id);
            

            // delete transaction
            $transaction = Transaction::find($delivery->transaction_id);

            if (!empty($transaction)) {
                $transaction->delete();
                $transaction->sell_lines()->delete();
            }

            // increase product quantity
            foreach ($delivery->deliveryDetails as $delivery_detail) {

                $variation_location_detail = VariationLocationDetails::where('product_id', $delivery_detail->product_id)
                    ->where('variation_id', $delivery_detail->variation_id)
                    ->where('location_id', $delivery->business_location_id)
                    ->first();
                
                if (!empty($variation_location_detail)) {
                    $variation_location_detail->qty_available += $delivery_detail->quantity;
                    $variation_location_detail->save();
                }

                $agent_temporal_stock = AgentTemporalStock::where('product_id', $delivery_detail->product_id)
                    ->where('variation_id', $delivery_detail->variation_id)
                    ->where('location_id', $delivery->business_location_id)
                    ->where('sales_commission_agent_id', $delivery->sales_commission_agent_id)
                    ->first();

                if (!empty($agent_temporal_stock)) {
                    $agent_temporal_stock->quantity -= $delivery_detail->quantity;
                    $agent_temporal_stock->save();

                    if ($agent_temporal_stock->quantity <= 0) {
                        $agent_temporal_stock->delete();
                    }
                }
            }

            $delivery->delete();
            
            $output = [
                'success' => true,
                'msg' => __("lang_v1.success")
            ];
        } catch (\Exception $e) {
            \Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());

            $output = [
                'success' => false,
                'msg' => __("messages.something_went_wrong")
            ];
        }

        return $output;
    }

    public function addReturn($id)
    {
        if (!auth()->user()->can('return.create')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            $delivery = Delivery::find($id);

            $agent = SalesCommissionAgent::withTrashed()->find($delivery->sales_commission_agent_id);

            $agent = User::where('id', $agent->user_id)
                ->select('first_name', 'last_name')
                ->first();


            $business_locations = BusinessLocation::forDropdown(request()->session()->get('user.business_id'));

            $customers = Contact::where('business_id', request()->session()->get('user.business_id'))
                ->where('type', 'customer')
                ->pluck('name', 'id');


            $shipping_statuses = Delivery::SHIPPING_STATUSES;

            $products = Product::where('business_id', request()->session()->get('user.business_id'))
                ->join('variations as v', 'products.id', '=', 'v.product_id')
                ->select('products.name as product_name', 'products.id as p_id', 'v.id as v_id', 'v.sell_price_inc_tax as price')
                ->groupBy('products.id')->get();

            return view('delivery.return')
                ->with(compact('business_locations', 'shipping_statuses', 'products', 'customers', 'delivery', 'agent'));
        }
    }

    public function printDeliveryReceipt($id)
    {
        if (!auth()->user()->can('delivery.view')) {
            abort(403, 'Unauthorized action.');
        }

        // print the html content from the show method
        $delivery = Delivery::find($id);

        $customer = Contact::find($delivery->contact_id);

        $html_content = view('delivery.delivery_receipt')
            ->with(compact('delivery', 'customer'))
            ->render();


        return [
            'success' => true,
            'receipt' => [
                'html_content' => $html_content]
        ];
    }
}
