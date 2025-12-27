<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Simulate the checkout process
     */
    private function simulateCheckout(User $user, Cart $cart): Order
    {
        DB::beginTransaction();

        try {
            // Calculate total
            $totalAmount = $cart->items->sum(function ($item) {
                return (float) $item->product->price * $item->quantity;
            });

            // Create order
            $order = Order::create([
                'user_id' => $user->id,
                'total_amount' => $totalAmount,
                'status' => 'completed',
            ]);

            // Create order items
            foreach ($cart->items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                ]);

                // Decrease stock
                $product = Product::find($item->product_id);
                $product->decrement('stock_quantity', $item->quantity);
            }

            // Clear cart
            $cart->items()->delete();

            DB::commit();

            return $order;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function test_order_can_be_created_with_cart_items(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create([
            'price' => 99.99,
            'stock_quantity' => 10,
        ]);

        $cart = Cart::create(['user_id' => $user->id]);
        CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => 2,
        ]);

        $order = $this->simulateCheckout($user, $cart->fresh('items.product'));

        // Check order was created
        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'total_amount' => 199.98, // 99.99 * 2
            'status' => 'completed',
        ]);

        // Check order items were created
        $this->assertDatabaseHas('order_items', [
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'price' => 99.99,
        ]);

        // Check cart was cleared
        $this->assertEquals(0, $cart->items()->count());
    }

    public function test_checkout_decreases_product_stock(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create([
            'stock_quantity' => 10,
        ]);

        $cart = Cart::create(['user_id' => $user->id]);
        CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => 3,
        ]);

        $this->simulateCheckout($user, $cart->fresh('items.product'));

        $product->refresh();
        $this->assertEquals(7, $product->stock_quantity);
    }

    public function test_order_preserves_price_at_time_of_purchase(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create([
            'price' => 50.00,
            'stock_quantity' => 10,
        ]);

        $cart = Cart::create(['user_id' => $user->id]);
        CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $this->simulateCheckout($user, $cart->fresh('items.product'));

        // Change product price
        $product->update(['price' => 100.00]);

        // Order item should still have old price
        $orderItem = OrderItem::where('product_id', $product->id)->first();
        $this->assertEquals(50.00, $orderItem->price);
    }

    public function test_checkout_creates_multiple_order_items(): void
    {
        $user = User::factory()->create();
        $product1 = Product::factory()->create(['stock_quantity' => 10, 'price' => 30.00]);
        $product2 = Product::factory()->create(['stock_quantity' => 10, 'price' => 50.00]);

        $cart = Cart::create(['user_id' => $user->id]);
        CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $product1->id,
            'quantity' => 2,
        ]);
        CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $product2->id,
            'quantity' => 1,
        ]);

        $order = $this->simulateCheckout($user, $cart->fresh('items.product'));

        // Check total amount (2*30 + 1*50 = 110)
        $this->assertEquals(110.00, $order->total_amount);

        // Check both order items exist
        $this->assertEquals(2, $order->items()->count());
    }

    public function test_order_has_relationships_to_user_and_items(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['stock_quantity' => 10]);
        $cart = Cart::create(['user_id' => $user->id]);

        CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $order = $this->simulateCheckout($user, $cart->fresh('items.product'));

        $this->assertInstanceOf(User::class, $order->user);
        $this->assertEquals(1, $order->items()->count());
        $this->assertInstanceOf(Product::class, $order->items->first()->product);
    }
}
