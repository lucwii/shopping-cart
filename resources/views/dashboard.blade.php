<x-layouts.app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-6">
        <!-- Stats Cards -->
        <livewire:dashboard.stats-widget />

        <!-- Widgets Grid -->
        <div class="grid gap-6 md:grid-cols-2">
            <!-- Low Stock Widget -->
            <livewire:dashboard.low-stock-widget />

            <!-- Top Stocked Widget -->
            <livewire:dashboard.top-stocked-widget />
        </div>
    </div>
</x-layouts.app>
