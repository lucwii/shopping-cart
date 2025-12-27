<?php

namespace App\Livewire;

use App\Jobs\SendLowStockNotification;
use App\Models\Cart as CartModel;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Cart extends Component
{
    public function updateQuantity($cartItemId, $action)
    {
        $cartItem = CartItem::findOrFail($cartItemId);

        if ($action === 'increment') {
            if ($cartItem->quantity < $cartItem->product->stock_quantity) {
                $cartItem->increment('quantity');
                session()->flash('success', 'Quantity updated!');
            } else {
                session()->flash('error', 'Cannot exceed available stock!');
            }
        } elseif ($action === 'decrement') {
            if ($cartItem->quantity > 1) {
                $cartItem->decrement('quantity');
                session()->flash('success', 'Quantity updated!');
            }
        }
    }

    public function removeItem($cartItemId)
    {
        $cartItem = CartItem::findOrFail($cartItemId);
        $cartItem->delete();

        session()->flash('success', 'Item removed from cart!');
    }

    public function clearCart()
    {
        $cart = CartModel::where('user_id', auth()->id())->first();

        if ($cart) {
            $cart->items()->delete();
            session()->flash('success', 'Cart cleared!');
        }
    }

    public function checkout()
    {
        $cart = $this->cart;

        if (!$cart || $cart->items->count() === 0) {
            session()->flash('error', 'Your cart is empty!');
            return;
        }

        DB::beginTransaction();

        try {
            // Check stock availability for all items
            foreach ($cart->items as $item) {
                if ($item->product->stock_quantity < $item->quantity) {
                    session()->flash('error', "Insufficient stock for {$item->product->name}!");
                    DB::rollBack();
                    return;
                }
            }

            // Create the order
            $order = Order::create([
                'user_id' => auth()->id(),
                'total_amount' => $this->subtotal,
                'status' => 'completed',
            ]);

            // Create order items and update stock
            foreach ($cart->items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                ]);

                // Decrease stock quantity
                $product = Product::find($item->product_id);
                $product->decrement('stock_quantity', $item->quantity);

                // Check if stock is low and send notification
                $product->refresh(); // Učitaj updated stock vrednost
                if ($product->stock_quantity <= 5 && $product->stock_quantity > 0) {
                    SendLowStockNotification::dispatch($product);
                }
            }

            // Clear the cart
            $cart->items()->delete();

            DB::commit();

            session()->flash('success', 'Order placed successfully! Order #' . $order->id);

            return redirect()->route('shop');

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Something went wrong. Please try again.');
        }
    }

    public function getCartProperty()
    {
        return CartModel::with(['items.product'])
            ->where('user_id', auth()->id())
            ->first();
    }

    public function getSubtotalProperty()
    {
        if (!$this->cart) {
            return 0;
        }

        return $this->cart->items->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });
    }

    public function render()
    {
        return view('livewire.cart')
            ->layout('components.layouts.app', ['title' => 'Shopping Cart']);
    }
}
