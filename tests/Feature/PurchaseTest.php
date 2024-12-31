<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PurchaseTest extends TestCase
{
    /** @test */
    public function user_can_get_products_of_business()
    {
        $user = User::find(1);
        $response = $this->actingAs($user)->get(route('purchases.getProducts', [
            'check_enable_stock' => false,
            'term' => 'leche'
        ]));

        $response->assertStatus(200);

        $response->assertJsonStructure([
            [
                'id',
                'text',
                'product_id',
                'variation_id',
            ]
        ]);
    }
}
