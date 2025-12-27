<?php

namespace App\Livewire\Dashboard;

use App\Models\Product;
use Livewire\Component;

class StatsWidget extends Component
{
    public function render()
    {
        $totalProducts = Product::count();
        $lowStockCount = Product::where('stock_quantity', '<=', 5)->count();
        $outOfStockCount = Product::where('stock_quantity', 0)->count();
        $totalValue = Product::sum(\DB::raw('price * stock_quantity'));

        return view('livewire.dashboard.stats-widget', [
            'totalProducts' => $totalProducts,
            'lowStockCount' => $lowStockCount,
            'outOfStockCount' => $outOfStockCount,
            'totalValue' => $totalValue,
        ]);
    }
}
