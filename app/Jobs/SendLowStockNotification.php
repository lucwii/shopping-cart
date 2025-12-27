<?php

namespace App\Jobs;

use App\Mail\LowStockNotification;
use App\Models\Product;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendLowStockNotification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Product $product
    )
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Šalje email administratoru
        Mail::to('admin@example.com')
            ->send(new LowStockNotification($this->product));
    }
}
