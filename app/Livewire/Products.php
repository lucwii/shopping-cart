<?php

namespace App\Livewire;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class Products extends Component
{
    use WithPagination;

    public function addToCart($productId)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $product = Product::find($productId);

        if (!$product || $product->stock_quantity < 1) {
            session()->flash('error', 'Product is out of stock!');
            return;
        }

        $cart = Cart::firstOrCreate(['user_id' => auth()->id()]);

        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $productId)
            ->first();

        if ($cartItem) {
            if ($product->stock_quantity >= $cartItem->quantity + 1) {
                $cartItem->increment('quantity');
                session()->flash('success', 'Quantity updated!');
            } else {
                session()->flash('error', 'Not enough stock!');
            }
        } else {
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $productId,
                'quantity' => 1,
            ]);
            session()->flash('success', 'Added to cart!');
        }
    }

    public function render()
    {
        $products = Product::where('stock_quantity', '>', 0)
            ->orderBy('name')
            ->paginate(12);

        return view('livewire.products', [
            'products' => $products
        ])->layout('components.layouts.app', ['title' => 'Shop']);
    }
}
