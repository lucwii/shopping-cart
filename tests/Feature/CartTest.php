<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_view_cart_page(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('cart'));

        $response->assertStatus(200);
    }

    public function test_guest_cannot_view_cart_page(): void
    {
        $response = $this->get(route('cart'));

        $response->assertRedirect(route('login'));
    }

    public function test_cart_can_be_created_for_user(): void
    {
        $user = User::factory()->create();

        $cart = Cart::create(['user_id' => $user->id]);

        $this->assertDatabaseHas('carts', [
            'user_id' => $user->id,
        ]);

        $this->assertInstanceOf(Cart::class, $cart);
    }

    public function test_cart_item_can_be_added_to_cart(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['stock_quantity' => 10]);
        $cart = Cart::create(['user_id' => $user->id]);

        $cartItem = CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => 2,
        ]);

        $this->assertDatabaseHas('cart_items', [
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => 2,
        ]);
    }

    public function test_cart_has_items_relationship(): void
    {
        $user = User::factory()->create();
        $cart = Cart::create(['user_id' => $user->id]);
        $product = Product::factory()->create(['stock_quantity' => 10]);

        CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $this->assertEquals(1, $cart->items()->count());
        $this->assertInstanceOf(Product::class, $cart->items->first()->product);
    }

    public function test_cart_item_belongs_to_cart_and_product(): void
    {
        $user = User::factory()->create();
        $cart = Cart::create(['user_id' => $user->id]);
        $product = Product::factory()->create(['stock_quantity' => 10]);

        $cartItem = CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $this->assertInstanceOf(Cart::class, $cartItem->cart);
        $this->assertInstanceOf(Product::class, $cartItem->product);
    }

    public function test_cart_items_can_be_deleted(): void
    {
        $user = User::factory()->create();
        $cart = Cart::create(['user_id' => $user->id]);
        $product = Product::factory()->create(['stock_quantity' => 10]);

        $cartItem = CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $cartItem->delete();

        $this->assertDatabaseMissing('cart_items', [
            'id' => $cartItem->id,
        ]);
    }

    public function test_all_cart_items_can_be_cleared(): void
    {
        $user = User::factory()->create();
        $cart = Cart::create(['user_id' => $user->id]);

        $product1 = Product::factory()->create(['stock_quantity' => 10]);
        $product2 = Product::factory()->create(['stock_quantity' => 10]);

        CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $product1->id,
            'quantity' => 1,
        ]);

        CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $product2->id,
            'quantity' => 2,
        ]);

        $cart->items()->delete();

        $this->assertEquals(0, $cart->items()->count());
    }

    public function test_cart_subtotal_calculation(): void
    {
        $user = User::factory()->create();
        $cart = Cart::create(['user_id' => $user->id]);

        $product1 = Product::factory()->create(['price' => 50.00, 'stock_quantity' => 10]);
        $product2 = Product::factory()->create(['price' => 30.00, 'stock_quantity' => 10]);

        CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $product1->id,
            'quantity' => 2, // 2 * 50 = 100
        ]);

        CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $product2->id,
            'quantity' => 3, // 3 * 30 = 90
        ]);

        $cart = $cart->fresh(['items.product']);

        $subtotal = $cart->items->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        $this->assertEquals(190.00, $subtotal);
    }
}
