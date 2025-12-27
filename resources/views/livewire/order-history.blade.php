<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-900 dark:text-white">Order History</h1>
        <p class="mt-2 text-gray-600 dark:text-gray-400">View all your past orders and their details</p>
    </div>

    @if ($orders->count() > 0)
        <div class="space-y-6">
            @foreach ($orders as $order)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                    <!-- Order Header -->
                    <div class="bg-gray-50 dark:bg-gray-700 px-6 py-4 border-b border-gray-200 dark:border-gray-600">
                        <div class="flex flex-wrap items-center justify-between gap-4">
                            <div class="flex items-center gap-6">
                                <div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Order Number</p>
                                    <p class="text-lg font-semibold text-gray-900 dark:text-white">#{{ $order->id }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Date</p>
                                    <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $order->created_at->format('M d, Y') }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Total</p>
                                    <p class="text-lg font-semibold text-blue-600 dark:text-blue-400">${{ number_format($order->total_amount, 2) }}</p>
                                </div>
                            </div>
                            <div>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                    @if($order->status === 'completed')
                                        bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                    @elseif($order->status === 'pending')
                                        bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                    @else
                                        bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200
                                    @endif">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="px-6 py-4">
                        <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">Items Ordered</h3>
                        <div class="space-y-3">
                            @foreach ($order->items as $item)
                                <div class="flex items-center gap-4 py-3 border-b border-gray-100 dark:border-gray-700 last:border-0">
                                    <!-- Product Image Placeholder -->
                                    <div class="flex-shrink-0 w-16 h-16 bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-800 rounded-lg flex items-center justify-center">
                                        <svg class="w-8 h-8 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>

                                    <!-- Product Info -->
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $item->product->name }}
                                        </p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            Quantity: {{ $item->quantity }} × ${{ number_format($item->price, 2) }}
                                        </p>
                                    </div>

                                    <!-- Item Total -->
                                    <div class="text-right">
                                        <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                            ${{ number_format($item->price * $item->quantity, 2) }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Order Footer -->
                    <div class="bg-gray-50 dark:bg-gray-700 px-6 py-3 flex justify-between items-center">
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            {{ $order->items->sum('quantity') }} item(s) • Ordered {{ $order->created_at->diffForHumans() }}
                        </p>
                        <div class="text-right">
                            <p class="text-xs text-gray-500 dark:text-gray-400">Order Total</p>
                            <p class="text-lg font-bold text-gray-900 dark:text-white">${{ number_format($order->total_amount, 2) }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $orders->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-12 text-center">
            <svg class="mx-auto h-24 w-24 text-gray-400 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">No orders yet</h3>
            <p class="text-gray-600 dark:text-gray-400 mb-6">You haven't placed any orders yet. Start shopping!</p>
            <a
                href="{{ route('shop') }}"
                wire:navigate
                class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition-colors duration-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
                Browse Products
            </a>
        </div>
    @endif
</div>
