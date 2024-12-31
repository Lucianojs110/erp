<?php

namespace Tests\Feature;

use App\Business;
use App\Product;
use App\ProductVariation;
use App\Unit;
use App\User;
use App\Variation;
use App\VariationTemplate;
use Tests\TestCase;

class ProductTest extends TestCase
{
    /** @test */
    public function user_can_get_variation_templates_view()
    {
        $user = User::find(1);

        $business = Business::find(1);
        $currency = $business->currency;
        $currency_data = [
            'id' => $currency->id,
            'code' => $currency->code,
            'symbol' => $currency->symbol,
            'thousand_separator' => $currency->thousand_separator,
            'decimal_separator' => $currency->decimal_separator
        ];

        session(['user.business_id' => 1, 'user.id' => $user->id]);
        session()->put('currency', $currency_data);

        $response = $this->actingAs($user)->get(route('variation-templates.index'));
        $response->assertOk();

        $response->assertViewIs('variation.index');
    }

    /** @test */
    public function user_can_store_variation_templates()
    {
        $user = User::find(1);

        $response = $this->actingAs($user)->post(route('variation-templates.store', []));
        $response->assertStatus(302);

        $productData = [
            "name" => "Variation",
            "variation_values" => [
                "Primero", "Segundo"
            ],
        ];
        
        $response = $this->actingAs($user)->post(route('variation-templates.store', $productData));
        $response->assertOk();

        $this->assertDatabaseHas('variation_templates', [
            "name" => "Variation",
        ]);

        $this->assertDatabaseHas('variation_value_templates', [
            "name" => "Primero",
        ]);

        $this->assertDatabaseHas('variation_value_templates', [
            "name" => "Segundo",
        ]);
    }

    /** @test */
    public function user_can_update_variation_templates()
    {
        $user = User::find(1);
        $variation_template = VariationTemplate::latest()->first();
        $values = $variation_template->values;

        $variationData = [
            "name" => "VariationUpdate",
            'edit_variation_values' => [
                $values[0]->id => "PrimeroUpdate",
                $values[1]->id => "SegundoUpdate",
            ]
        ];
        
        $response = $this->actingAs($user)->put(route('variation-templates.update', $variation_template->id), $variationData);
        $response->assertOk();

        $this->assertDatabaseHas('variation_templates', [
            "name" => "VariationUpdate",
        ]);

        $this->assertDatabaseMissing('variation_value_templates', [
            "name" => "Primero",
        ]);

        $this->assertDatabaseMissing('variation_value_templates', [
            "name" => "Segundo",
        ]);

        $this->assertDatabaseHas('variation_value_templates', [
            "name" => "PrimeroUpdate",
        ]);

        $this->assertDatabaseHas('variation_value_templates', [
            "name" => "SegundoUpdate",
        ]);
    }

    /** @test */
    public function user_can_destroy_variation_templates()
    {
        $user = User::find(1);
        $variation_template = VariationTemplate::latest()->first();
        
        $response = $this->actingAs($user)->delete(route('variation-templates.destroy', $variation_template->id));
        $response->assertOk();

        $this->assertDatabaseMissing('variation_templates', [
            "name" => "VariationUpdate",
        ]);

        $this->assertDatabaseMissing('variation_value_templates', [
            "name" => "PrimeroUpdate",
        ]);

        $this->assertDatabaseMissing('variation_value_templates', [
            "name" => "SegundoUpdate",
        ]);
    }
}