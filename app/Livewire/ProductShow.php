<?php

namespace App\Livewire;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Livewire\Component;

class ProductShow extends Component
{
    public Product $product;
    public int $quantity = 1;

    public function mount($id)
    {
        $this->product = Product::findOrFail($id);
    }

    public function incrementQuantity()
    {
        if ($this->quantity < $this->product->stock_quantity) {
            $this->quantity++;
        }
    }

    public function decrementQuantity()
    {
        if ($this->quantity > 1) {
            $this->quantity--;
        }
    }

    public function addToCart()
    {
        if ($this->quantity > $this->product->stock_quantity) {
            session()->flash('error', 'Not enough stock available!');
            return;
        }

        $cart = Cart::firstOrCreate(['user_id' => auth()->id()]);

        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $this->product->id)
            ->first();

        if ($cartItem) {
            $newQuantity = $cartItem->quantity + $this->quantity;

            if ($newQuantity > $this->product->stock_quantity) {
                session()->flash('error', 'Not enough stock available!');
                return;
            }

            $cartItem->update(['quantity' => $newQuantity]);
            session()->flash('success', "Updated quantity to {$newQuantity}!");
        } else {
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $this->product->id,
                'quantity' => $this->quantity,
            ]);
            session()->flash('success', "Added {$this->quantity} item(s) to cart!");
        }

        $this->quantity = 1;
    }

    public function render()
    {
        return view('livewire.product-show')
            ->layout('components.layouts.app', ['title' => $this->product->name]);
    }
}
