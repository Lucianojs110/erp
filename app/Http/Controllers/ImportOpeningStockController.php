<?php

namespace App\Http\Controllers;

use App\Business;

use App\Imports\OpeningStockImport;
use App\Transaction;
use App\Utils\ProductUtil;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ImportOpeningStockController extends Controller
{
    /**
     * All Utils instance.
     *
     */
    protected $productUtil;

    /**
     * Constructor
     *
     * @param ProductUtils $product
     * @return void
     */
    public function __construct(ProductUtil $productUtil)
    {
        $this->productUtil = $productUtil;
    }

    /**
     * Display import product screen.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!auth()->user()->can('product.opening_stock')) {
            abort(403, 'Unauthorized action.');
        }

        $zip_loaded = extension_loaded('zip') ? true : false;

        $date_formats = Business::date_formats();
        $date_format = session('business.date_format');
        $date_format = isset($date_formats[$date_format]) ? $date_formats[$date_format] : $date_format;

        //Check if zip extension it loaded or not.
        if ($zip_loaded === false) {
            $notification = [
                'success' => 0,
                'msg' => 'Please install/enable PHP Zip archive for import'
            ];

            return view('import_opening_stock.index')
                ->with(compact('notification', 'date_format'));
        } else {
            return view('import_opening_stock.index')
                ->with(compact('date_format'));
        }
    }

    /**
     * Imports the uploaded file to database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!auth()->user()->can('product.opening_stock')) {
            abort(403, 'Unauthorized action.');
        }

        try {

            //Set maximum php execution time
            ini_set('max_execution_time', 0);
            $business_id = $request->session()->get('user.business_id');

            if ($request->hasFile('products_csv')) {
                $file = $request->file('products_csv');

                $opening = new OpeningStockImport;
                
                $excel = Excel::import($opening, $file);
                $collection = $excel->toCollection($opening, $file);

                
                $data = $opening->collection($collection);

                if ($data['errors'] != []) {

                    $msg = '';

                    foreach ($data['errors'] as $error) {
                        $msg .= "Fila " . $error['fila'] . ": ";
                        $msg .= implode(" ", $error['errores']);
                        $msg .= "</br>";
                    }

                    return redirect('import-opening-stock')->with('notification', [
                        'success' => 0,
                        'msg' => nl2br($msg)
                    ]);
                }

                foreach ($data['results'] as $result) {
                    $opening_stock = [
                        'quantity' => trim($result['cantidad']),
                        'location_id' => $result['location']->id,
                        'lot_number' => trim($result['numero_de_lote']),
                    ];

                    if (!empty(trim($result['fecha_de_caducidad']))) {
                        $opening_stock['exp_date'] = $this->productUtil->uf_date($result['fecha_de_caducidad']);
                    }

                    $this->addOpeningStock(
                        $opening_stock,
                        $result['product_info'],
                        $business_id,
                        $result['unit_cost_before_tax'],
                        $result['os_transaction']
                    );
                }
            }

            return redirect('import-opening-stock')->with('status', [
                'success' => 1,
                'msg' => __('product.file_imported_successfully')
            ]);
        } catch (\Exception $e) {
            return redirect('import-opening-stock')->with('status', [
                'success' => 0,
                'msg' => $e->getMessage()
            ]);
        }
    }

    /**
     * Adds opening stock of a single product
     *
     * @param array $opening_stock
     * @param obj $product
     * @param int $business_id
     * @return void
     */
    private function addOpeningStock($opening_stock, $product, $business_id, $unit_cost_before_tax, $transaction = null)
    {
        $user_id = request()->session()->get('user.id');

        $transaction_date = request()->session()->get("financial_year.start");
        $transaction_date = \Carbon::createFromFormat('Y-m-d', $transaction_date)->toDateTimeString();

        //Get product tax
        $tax_percent = !empty($product->tax_percent) ? $product->tax_percent : 0;
        $tax_id = !empty($product->tax_id) ? $product->tax_id : null;

        $item_tax = $this->productUtil->calc_percentage($unit_cost_before_tax, $tax_percent);

        //total before transaction tax
        $total_before_trans_tax = $opening_stock['quantity'] * ($unit_cost_before_tax + $item_tax);

        //Add opening stock transaction
        if (empty($transaction)) {
            $transaction = new Transaction();
            $transaction->type = 'opening_stock';
            $transaction->opening_stock_product_id = $product->id;
            $transaction->business_id = $business_id;
            $transaction->transaction_date = $transaction_date;
            $transaction->location_id = $opening_stock['location_id'];
            $transaction->payment_status = 'paid';
            $transaction->created_by = $user_id;
            $transaction->total_before_tax = 0;
            $transaction->final_total = 0;
        }
        $transaction->total_before_tax += $total_before_trans_tax;
        $transaction->final_total += $total_before_trans_tax;
        $transaction->save();

        //Create purchase line
        $transaction->purchase_lines()->create([
            'product_id' => $product->id,
            'variation_id' => $product->variation_id,
            'quantity' => $opening_stock['quantity'],
            'pp_without_discount' => $unit_cost_before_tax,
            'item_tax' => $item_tax,
            'tax_id' => $tax_id,
            'pp_without_discount' => $unit_cost_before_tax,
            'purchase_price' => $unit_cost_before_tax,
            'purchase_price_inc_tax' => $unit_cost_before_tax + $item_tax,
            'exp_date' => !empty($opening_stock['exp_date']) ? $opening_stock['exp_date'] : null,
            'lot_number' => !empty($opening_stock['lot_number']) ? $opening_stock['lot_number'] : null,
        ]);
        //Update variation location details
        $this->productUtil->updateProductQuantity($opening_stock['location_id'], $product->id, $product->variation_id, $opening_stock['quantity']);
    }
}
