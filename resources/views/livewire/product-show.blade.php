<div class="max-w-7xl mx-auto">
    <!-- Breadcrumb -->
    <nav class="mb-8 text-sm">
        <ol class="flex items-center space-x-2">
            <li>
                <a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">Dashboard</a>
            </li>
            <li class="text-gray-400">/</li>
            <li>
                <a href="{{ route('shop') }}" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">Shop</a>
            </li>
            <li class="text-gray-400">/</li>
            <li class="text-gray-900 dark:text-white font-medium">{{ $product->name }}</li>
        </ol>
    </nav>

    <!-- Flash Messages -->
    @if (session('success'))
        <div class="mb-6 bg-green-50 border-l-4 border-green-400 text-green-800 px-6 py-4 rounded-r shadow-sm">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="mb-6 bg-red-50 border-l-4 border-red-400 text-red-800 px-6 py-4 rounded-r shadow-sm">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                {{ session('error') }}
            </div>
        </div>
    @endif

    <!-- Product Details -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
        <div class="grid md:grid-cols-2 gap-8 p-8">
            <!-- Product Image -->
            <div class="aspect-square bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-800 rounded-xl flex items-center justify-center">
                <svg class="w-32 h-32 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>

            <!-- Product Info -->
            <div class="flex flex-col">
                <div class="flex-1">
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">
                        {{ $product->name }}
                    </h1>

                    <!-- Price -->
                    <div class="mb-6">
                        <span class="text-4xl font-bold text-blue-600 dark:text-blue-400">
                            ${{ number_format($product->price, 2) }}
                        </span>
                    </div>

                    <!-- Stock Status -->
                    <div class="mb-6">
                        <div class="flex items-center gap-3">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Availability:</span>
                            @if ($product->stock_quantity > 5)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    In Stock ({{ $product->stock_quantity }} available)
                                </span>
                            @elseif ($product->stock_quantity > 0)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    Low Stock (Only {{ $product->stock_quantity }} left!)
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                    Out of Stock
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Quantity Selector -->
                    @if ($product->stock_quantity > 0)
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Quantity
                            </label>
                            <div class="flex items-center gap-4">
                                <div class="flex items-center border border-gray-300 dark:border-gray-600 rounded-lg">
                                    <button
                                        wire:click="decrementQuantity"
                                        type="button"
                                        class="px-4 py-3 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-l-lg transition-colors"
                                        @if($quantity <= 1) disabled @endif>
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                                        </svg>
                                    </button>
                                    <input
                                        type="text"
                                        wire:model="quantity"
                                        readonly
                                        class="w-20 text-center py-3 text-gray-900 dark:text-white bg-transparent border-0 focus:outline-none">
                                    <button
                                        wire:click="incrementQuantity"
                                        type="button"
                                        class="px-4 py-3 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-r-lg transition-colors"
                                        @if($quantity >= $product->stock_quantity) disabled @endif>
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                        </svg>
                                    </button>
                                </div>
                                <span class="text-sm text-gray-500 dark:text-gray-400">
                                    Max: {{ $product->stock_quantity }}
                                </span>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Add to Cart Button -->
                @if ($product->stock_quantity > 0)
                    <button
                        wire:click="addToCart"
                        class="w-full bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-semibold py-4 px-6 rounded-lg transition-colors duration-200 flex items-center justify-center gap-2 shadow-md hover:shadow-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        Add to Cart
                    </button>
                @else
                    <button
                        disabled
                        class="w-full bg-gray-400 text-white font-semibold py-4 px-6 rounded-lg cursor-not-allowed">
                        Out of Stock
                    </button>
                @endif

                <!-- Back to Shop -->
                <div class="mt-6">
                    <a href="{{ route('shop') }}" class="text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 text-sm font-medium inline-flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Back to Shop
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
