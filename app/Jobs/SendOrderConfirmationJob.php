<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\MasterOrder;
use App\Mail\OrderConfirmationMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendOrderConfirmationEmail implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    protected $order;

    /**
     * Create a new job instance.
     */
    public function __construct(MasterOrder $order)
    {
        $this->order = $order;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            // Load necessary relationships (if not already loaded)
            $orderWithRelations = $this->order->load([
                'items',
                'orderStatus',
                'paymentStatus',
                'paymentMethod',
            ]);

            // Send to customer
            Mail::to($orderWithRelations->email)
                ->send(new OrderConfirmationMail($orderWithRelations));

            // Send to admin
            $adminEmail = env('ADMIN_EMAIL', 'info@ajhuie.com');
            if ($adminEmail) {
                Mail::to($adminEmail)
                    ->send(new OrderConfirmationMail($orderWithRelations, true));
            }

            // Optional: mark email_sent flag
            $orderWithRelations->update(['email_sent' => 1]);

            Log::info("Order confirmation email queued successfully for order: " . $this->order->order_number);
        } catch (\Exception $e) {
            Log::error('Email sending job failed: ' . $e->getMessage());
            // You can retry the job or handle failure
            $this->fail($e);
        }
    }
}
