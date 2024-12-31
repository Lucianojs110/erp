<?php

namespace Tests\Feature;

use App\Product;
use App\ProductVariation;
use App\Unit;
use App\User;
use App\Variation;
use Tests\TestCase;

class ProductTest extends TestCase
{
    /** @test */
    public function user_can_get_products()
    {
        $user = User::find(1);
        $response = $this->actingAs($user)->get(route('products.index'));
        $response->assertStatus(200);

        $response->assertViewIs('product.index');
        $response->assertViewHas('rack_enabled');
        $response->assertViewHas('categories');
        $response->assertViewHas('brands');
        $response->assertViewHas('units');
        $response->assertViewHas('taxes');
        $response->assertViewHas('business_locations');
        
        $this->assertEquals($response->original->getData()['units'], Unit::forDropdown($user->business_id));
        
        $this->assertEquals($response->original->getData()['rack_enabled'], false);
        $this->assertNotEquals($response->original->getData()['rack_enabled'], true);
    }

    /** @test */
    public function user_can_store_products()
    {
        $user = User::find(1);
        session(['user.business_id' => 1, 'user.id' => $user->id]);


        $response = $this->actingAs($user)->post(route('products.store', []));
        $response->assertStatus(302);

        $productData = [
            "name" => "Exequiel Rodriguez",
            "brand_id" => "109",
            "unit_id" => "3",
            "category_id" => "2",
            "sub_category_id" => null,
            "sku" => "38512",
            "barcode_type" => "C128",
            "enable_stock" => "1",
            "alert_quantity" => "20",
            "product_description" => null,
            "expiry_period" => "12",
            "expiry_period_type" => "months",
            "enable_sr_no" => null,
            "weight" => null,
            "product_custom_field1" => null,
            "product_custom_field2" => null,
            "product_custom_field3" => null,
            "product_custom_field4" => null,
            "tax" => "1",
            "tax_type" => "inclusive",
            "type" => "single",
            "single_dpp" => "2",
            "single_dpp_inc_tax" => "2.42",
            "profit_percent" => "25.00",
            "single_dsp" => "2.50",
            "single_dsp_inc_tax" => "3.03",
            "cantidadMayorista" => null,
            "precioMayorista" => null,
            "submit_type" => "submit",
        ];
        
        $response = $this->actingAs($user)->post(route('products.store', $productData));
        $response->assertStatus(302);

        $this->assertDatabaseHas('products', [
            "name" => "Exequiel Rodriguez",
            "brand_id" => 109,
            "unit_id" => 3,
            "category_id" => 2,
            "sub_category_id" => null,
            "sku" => "38512",
            "barcode_type" => "C128",
            "enable_stock" => 1,
            "alert_quantity" => 20,
            "product_description" => null,
            "enable_sr_no" => 0,
            "weight" => null,
            "product_custom_field1" => null,
            "product_custom_field2" => null,
            "product_custom_field3" => null,
            "product_custom_field4" => null,
        ]);
    }

    /** @test */
    public function user_can_update_products()
    {
        $user = User::find(1);
        session(['user.business_id' => 1, 'user.id' => $user->id]);
        $product = Product::latest()->first();

        $productData = [
            "name" => "Marcelo Pagano",
            "brand_id" => "109",
            "unit_id" => "3",
            "category_id" => "2",
            "sub_category_id" => null,
            "sku" => "38512",
            "barcode_type" => "C128",
            "enable_stock" => "1",
            "alert_quantity" => "20",
            "product_description" => null,
            "expiry_period" => "12",
            "expiry_period_type" => "months",
            "enable_sr_no" => null,
            "weight" => null,
            "product_custom_field1" => null,
            "product_custom_field2" => null,
            "product_custom_field3" => null,
            "product_custom_field4" => null,
            "tax" => "1",
            "tax_type" => "inclusive",
            "type" => "single",
            "single_dpp" => "2",
            "single_dpp_inc_tax" => "2.42",
            "profit_percent" => "25.00",
            "single_dsp" => "2.50",
            "single_dsp_inc_tax" => "3.03",
            "cantidadMayorista" => null,
            "precioMayorista" => null,
            "submit_type" => "submit",
        ];
        
        $this->assertDatabaseMissing('products' ,[
            'name' => 'Marcelo Pagano'
        ]);

        $response = $this->actingAs($user)->put(route('products.update', $product->id), $productData);
        $response->assertStatus(302);

    }

    /** @test */
    public function user_can_get_products_list()
    {
        $user = User::find(1);
        session(['user.business_id' => 1, 'user.id' => $user->id]);

        $response = $this->actingAs($user)->get(route('products.list'));
        $response->assertOk();

        $response->assertJsonFragment([
            "name" => "Marcelo Pagano",
            "type" => 'single',
            "enable_stock" => 1,
            "variation" => "DUMMY",
            "qty_available" => null,
            "selling_price" => "3.03000",
            "sub_sku" => "38512",
            "unit" => "kg.",
            "precioMayorista" => "0.00000",
            "cantidadMayorista" => "0.00",
        ]);
    }

    /** @test */
    public function user_can_mass_desactive_products()
    {
        $user = User::find(1);
        session(['user.business_id' => 1, 'user.id' => $user->id]);
        $product = Product::latest()->first();

        $this->assertDatabaseHas('products', [
            'is_inactive' => 0,
        ]);

        $response = $this->actingAs($user)->post(route('products.massDeactivate', [
            'selected_products' => $product->id,
        ]));
        $response->assertStatus(302);

        $this->assertDatabaseHas('products', [
            'is_inactive' => 1,
        ]);
    }

    /** @test */
    public function user_can_mass_active_products()
    {
        $user = User::find(1);
        session(['user.business_id' => 1, 'user.id' => $user->id]);
        $product = Product::latest()->first();

        $this->assertDatabaseHas('products', [
            'is_inactive' => 1,
        ]);

        $response = $this->actingAs($user)->get(route('products.activate', $product->id));
        $response->assertOk();

        $this->assertDatabaseHas('products', [
            'is_inactive' => 0,
        ]);
    }

    /** @test */
    public function user_can_mass_destroy_products()
    {
        $user = User::find(1);
        session(['user.business_id' => 1, 'user.id' => $user->id]);
        $product = Product::create([
            "name" => "Exequiel Rodriguez",
            "business_id" => 1,
            "created_by" => $user->id,
            "brand_id" => 109,
            "unit_id" => 3,
            "category_id" => 2,
            "sub_category_id" => null,
            "sku" => "38512",
            "barcode_type" => "C128",
            "enable_stock" => 1,
            "alert_quantity" => 20,
            "product_description" => null,
            "enable_sr_no" => 0,
            "weight" => null,
            "product_custom_field1" => null,
            "product_custom_field2" => null,
            "product_custom_field3" => null,
            "product_custom_field4" => null,
        ]);

        
        $this->actingAs($user)->post(route('products.massDestroy', [
            'selected_rows' => $product->id,
        ]))->assertStatus(302);
        
        $this->assertSoftDeleted($product);
    }
    
    /** @test */
    public function user_can_delete_products()
    {
        $user = User::find(1);
        session(['user.business_id' => 1, 'user.id' => $user->id]);
        $product = Product::latest()->first();

        $this->actingAs($user)
            ->delete(route('products.destroy', $product->id))
            ->assertOk();

        $this->assertSoftDeleted($product);
    }

    /** @test */
    public function user_can_massive_update_percent_products()
    {
        $user = User::find(1);
        session(['user.business_id' => 1, 'user.id' => $user->id]);
        $product = Product::create([
            "name" => "Exequiel Rodriguez",
            "business_id" => 1,
            "created_by" => $user->id,
            "brand_id" => 109,
            "unit_id" => 3,
            "category_id" => 2,
            "sub_category_id" => null,
            "sku" => "38512",
            "barcode_type" => "C128",
            "enable_stock" => 1,
            "alert_quantity" => 20,
            "product_description" => null,
            "enable_sr_no" => 0,
            "weight" => null,
            "product_custom_field1" => null,
            "product_custom_field2" => null,
            "product_custom_field3" => null,
            "product_custom_field4" => null,
        ]);

        $productVariation = ProductVariation::create([
            "variation_template_id" => null,
            "name" => "DUMMY",
            "product_id" => $product->id,
            "is_dummy" => 1,
            "created_at" => "2023-10-09 07:11:29",
            "updated_at" => "2023-10-09 07:11:29",
        ]);

        Variation::create([
            "name" => "DUMMY",
            "product_id" => $product->id,
            "sub_sku" => "38512",
            "product_variation_id" => $productVariation->id,
            "variation_value_id" => null,
            "default_purchase_price" => "2.00",
            "dpp_inc_tax" => "2.42",
            "profit_percent" => "25.00",
            "default_sell_price" => "2.50000",
            "sell_price_inc_tax" => "3.03000",
            "cantidadMayorista" => "0.00",
            "precioMayorista" => "0.00000",
            "created_at" => "2023-10-09 06:59:08",
            "updated_at" => "2023-10-09 06:59:08",
            "deleted_at" => null
        ]);

        $this->actingAs($user)->post(route('products.massiveUpdatePercent'), [
            "increment-percent" => "50",
            "brand_id" => "109",
            "category_id" => "2",
            "sub_category_id" => null
        ])->assertStatus(302);
        
        $this->assertDatabaseHas('variations', [
            "default_sell_price" => "3.75000",
            "sell_price_inc_tax" => "4.54500",
        ]);
    }

    /** @test */
    public function user_can_update_selected_products()
    {
        $user = User::find(1);
        session(['user.business_id' => 1, 'user.id' => $user->id]);
        $product = Product::latest()->first();
        $result = $product->variations->first()->sell_price_inc_tax + 20;

        $data = [
            'typeOfUpdate' => 'fixed',
            'increment-percent' => 80,
            'fixed-value' => 20,
            'products-ids' => "[$product->id]"
        ];

        $this->actingAs($user)
            ->post(route('products.selectionUpdate', $data))
            ->assertStatus(302);

        $newProduct = Product::latest()->first();

        $this->assertEquals($newProduct->variations->first()->sell_price_inc_tax, $result);

        $product = Product::latest()->first();
        $result = $product->variations->first()->sell_price_inc_tax * 1.8;

        $data2 = [
            'typeOfUpdate' => 'percentage',
            'increment-percent' => 80,
            'fixed-value' => 20,
            'products-ids' => "[$product->id]"
        ];

        $this->actingAs($user)
            ->post(route('products.selectionUpdate', $data2))
            ->assertStatus(302);
        
        $newProduct = Product::latest()->first();
        $this->assertEquals(round($newProduct->variations->first()->sell_price_inc_tax), round($result));
    }
}