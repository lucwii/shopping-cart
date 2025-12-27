<?php

namespace App\Livewire\Dashboard;

use App\Models\Product;
use Livewire\Component;

class LowStockWidget extends Component
{
    public function render()
    {
        $products = Product::where('stock_quantity', '<=', 5)
            ->where('stock_quantity', '>', 0)
            ->orderBy('stock_quantity', 'asc')
            ->limit(5)
            ->get();

        return view('livewire.dashboard.low-stock-widget', [
            'products' => $products
        ]);
    }
}
