<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_checkout_fails_when_stock_is_insufficient_and_does_not_deduct_stock()
    {
        $user = \App\Models\User::factory()->create();
        // Create a product with only 5 items in stock
        $product = \App\Models\Product::factory()->create(['stock' => 5]);

        // Create a cart for the user
        $cart = \App\Models\Cart::create(['user_id' => $user->id]);

        // Add 10 items to the cart (exceeding stock)
        $cart->items()->create([
            'product_id' => $product->id,
            'qty' => 10,
            'price_at_time' => $product->price
        ]);

        // Attempt checkout
        $response = $this->actingAs($user, 'sanctum')->postJson('/api/cart/checkout');

        // Assert 422 Unprocessable Entity
        $response->assertStatus(422);

        // Assert stock remains 5 (Not deducted)
        $this->assertEquals(5, $product->fresh()->stock);

        // Assert cart still has 1 item (Not cleared)
        $this->assertEquals(1, $cart->items()->count());
    }
}
