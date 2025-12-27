<?php

namespace App\Livewire\Dashboard;

use App\Models\Product;
use Livewire\Component;

class TopStockedWidget extends Component
{
    public function render()
    {
        $products = Product::where('stock_quantity', '>', 0)
            ->orderBy('stock_quantity', 'desc')
            ->limit(5)
            ->get();

        return view('livewire.dashboard.top-stocked-widget', [
            'products' => $products
        ]);
    }
}
