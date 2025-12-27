<?php

namespace App\Console\Commands;

use App\Mail\DailySalesReport;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendDailySalesReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'report:daily-sales';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send daily sales report to admin';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Uzmi sve porudžbine od danas
        $orders = Order::with(['user', 'items'])
            ->whereDate('created_at', Carbon::today())
            ->get();

        // Izračunaj ukupan prihod
        $totalRevenue = $orders->sum('total_amount');

        // Izračunaj ukupan broj prodatih proizvoda
        $totalItemsSold = $orders->sum(function ($order) {
            return $order->items->sum('quantity');
        });

        // Pošalji email admin-u
        Mail::to('admin@example.com')
            ->send(new DailySalesReport($orders, $totalRevenue, $totalItemsSold));

        $this->info('Daily sales report sent successfully!');
    }
}
