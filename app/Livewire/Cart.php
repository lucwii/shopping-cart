<?php

namespace App\Livewire;

use App\Models\Cart as CartModel;
use App\Models\CartItem;
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
