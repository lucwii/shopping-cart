<div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Top Stocked Products</h3>
        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
            Top {{ $products->count() }}
        </span>
    </div>

    @if($products->isEmpty())
        <p class="text-sm text-gray-500 dark:text-gray-400">No products available.</p>
    @else
        <div class="space-y-3">
            @foreach($products as $product)
                <div class="flex items-center justify-between py-2 border-b border-gray-100 dark:border-gray-700 last:border-0">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                            {{ $product->name }}
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            ${{ number_format($product->price, 2) }}
                        </p>
                    </div>
                    <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        {{ $product->stock_quantity }} units
                    </span>
                </div>
            @endforeach
        </div>
    @endif
</div>
