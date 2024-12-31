<?php

namespace Tests\Feature;

use App\Product;
use App\ProductVariation;
use App\Transaction;
use App\User;
use App\Variation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LabelTest extends TestCase
{
    /** @test */
    public function user_can_show_labels_without_data()
    {
        $user = User::find(1);
        $response = $this->actingAs($user)->get(route('labels.show'));
        $response->assertStatus(200);

        $response->assertViewIs('labels.show');
        $response->assertViewHas('products');
        $response->assertViewHas('barcode_settings');
    }

    /** @test */
    public function user_can_with_product_id()
    {
        $product = Product::create([
            'name' => 'product uno',
            'business_id' => 1,
            'type' => 'single',
            'unit_id' => 2,
            'brand_id' => 48,
            'category_id' => 2,
            'sub_category_id' => null,
            'tax' => 1,
            'tax_type' => 'inclusive',
            'enable_stock' => 1,
            'alert_quantity' => 50,
            'sku' => '362514',
            'barcode_type' => 'C128',
            'expiry_period' => 12.00,
            'expiry_period_type' => 'months',
            'enable_sr_no' => 1,
            'weight' => 88,
            'product_custom_field1' => 'barco',
            'product_custom_field2' => 'marco',
            'product_custom_field3' => 'larco',
            'product_custom_field4' => 'ñarco',
            'created_by' => 1,
            'is_inactive' => 0,
            'hasMayorista' => 0,
            'created_at' => '2023-10-14 17:03:05',
            'updated_at' => '2023-10-14 17:03:05',
            'deleted_at' => null,
        ]);

        ProductVariation::create([
            'variation_template_id' => null,
            'name' => 'DUMMY',
            'product_id' => $product->id,
            'is_dummy' => 1,
            'created_at' => '2023-10-14 14:03:05',
            'updated_at' => '2023-10-14 14:03:05',
        ]);

        $user = User::find(1);
        $response = $this->actingAs($user)->get(route('labels.show', [
            'product_id' => $product->id
        ]));
        $response->assertStatus(200);

        $response->assertViewIs('labels.show');
        $response->assertViewHas('products');
        $response->assertViewHas('barcode_settings');
    }

    /** @test */
    public function user_can_with_purchase_id()
    {
        $purchase = Transaction::create([
            'business_id' => 1,
            'location_id' => 1,
            'res_table_id' => null,
            'res_waiter_id' => null,
            'res_order_status' => null,
            'type' => 'sell',
            'sub_type' => null,
            'status' => 'final',
            'is_quotation' => 0,
            'payment_status' => 'paid',
            'adjustment_type' => null,
            'contact_id' => 1,
            'customer_group_id' => null,
            'invoice_no' => '0023',
            'type_invoice' => null,
            'ref_no' => '',
            'subscription_no' => null,
            'transaction_date' => '2023-10-21 12:47:08',
            'total_before_tax' => 3125.00,
            'tax_id' => null,
            'tax_amount' => 0.00,
            'discount_type' => 'percentage',
            'discount_amount' => 10,
            'shipping_details' => null,
            'shipping_charges' => 0.00,
            'additional_notes' => null,
            'staff_note' => null,
            'final_total' => 3403.35,
            'expense_category_id' => null,
            'expense_for' => null,
            'commission_agent' => null,
            'document' => null,
            'is_direct_sale' => 1,
            'is_suspend' => 0,
            'exchange_rate' => 1.000,
            'total_amount_recovered' => null,
            'transfer_parent_id' => null,
            'return_parent_id' => null,
            'opening_stock_product_id' => null,
            'created_by' => 1,
            'order_addresses' => null,
            'is_recurring' => 0,
            'recur_interval' => null,
            'recur_interval_type' => 'days',
            'recur_repetitions' => 0,
            'recur_stopped_on' => null,
            'recur_parent_id' => null,
            'invoice_token' => null,
            'pay_term_number' => null,
            'pay_term_type' => null,
            'selling_price_group_id' => null,
            'cae' => null,
            'exp_cae' => null,
            'afip_invoice_date' => null,
            'num_invoice_afip' => null,
            'qrCode' => null,
            'iva10' => 0.00,
            'iva21' => 2812.50,
            'iva27' => 0.00,
            'created_at' => '2023-10-21 12:47:08',
            'updated_at' => '2023-10-21 13:04:31',
        ]);

        $user = User::find(1);
        $response = $this->actingAs($user)->get(route('labels.show', [
            'purchase_id' => $purchase->id,
        ]));
        $response->assertStatus(200);

        $response->assertViewIs('labels.show');
        $response->assertViewHas('products');
        $response->assertViewHas('barcode_settings');
    }

    /** @test */
    public function user_can_add_products_to_rows()
    {
        $product = Product::create([
            'name' => 'Product dos',
            'business_id' => 1,
            'type' => 'single',
            'unit_id' => 2,
            'brand_id' => 48,
            'category_id' => 2,
            'sub_category_id' => null,
            'tax' => 1,
            'tax_type' => 'inclusive',
            'enable_stock' => 1,
            'alert_quantity' => 50,
            'sku' => '362514',
            'barcode_type' => 'C128',
            'expiry_period' => 12.00,
            'expiry_period_type' => 'months',
            'enable_sr_no' => 1,
            'weight' => 88,
            'product_custom_field1' => 'barco',
            'product_custom_field2' => 'marco',
            'product_custom_field3' => 'larco',
            'product_custom_field4' => 'ñarco',
            'created_by' => 1,
            'is_inactive' => 0,
            'hasMayorista' => 0,
            'created_at' => '2023-10-14 17:03:05',
            'updated_at' => '2023-10-14 17:03:05',
            'deleted_at' => null,
        ]);

        $variation = ProductVariation::create([
            'variation_template_id' => null,
            'name' => 'DUMMY',
            'product_id' => $product->id,
            'is_dummy' => 1,
            'created_at' => '2023-10-14 14:03:05',
            'updated_at' => '2023-10-14 14:03:05',
        ]);

        $user = User::find(1);
        $response = $this->actingAs($user)->get(route('labels.addProductRow', [
            'product_id' => $product->id,
            'variation_id' => $variation->id,
            'row_count' => 0
        ]));
        $response->assertStatus(200);

        $response->assertViewIs('labels.partials.show_table_rows');
        $response->assertViewHas('products');
    }

    /** @test */
    public function user_can_view_preview_of_print()
    {
        $user = User::find(1);
        $response = $this->actingAs($user)->post(route('labels.preview', [
            'search_product' => '', 
            'products[0][product_id]' => 103,
            'products[0][variation_id]' => 67,
            'products[0][quantity]' => 1,
            'print[name]' => 1,
            'print[variations]' => 1,
            'print[price]' => 1,
            'print[price_type]' => 'inclusive',
            'print[business_name]' => 1,
            'barcode_setting' => 1,
        ]));
        $response->assertStatus(200);
        
        $response->assertJsonStructure([
            'html',
            'success',
            'msg'
        ]);
        
        $html = $response->json()['html'];
        $dom = new \DOMDocument;
        $dom->loadHTML($html);
    
        $divContent = $dom->getElementsByTagName('div')->item(0)->nodeValue;
    
        $this->assertStringContainsString('Mala', $divContent);
        $this->assertStringContainsString('Leche', $divContent);
        $this->assertStringContainsString('Precio:', $divContent);
        $this->assertStringContainsString('75.63000', $divContent);
    }
}
