<?php

namespace App\Jobs;

use App\Mail\OrderConfirmationMail;
use App\Models\MasterOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendOrderConfirmationEmail implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $tries = 3;
    public int $timeout = 60;

    public function __construct(protected MasterOrder $masterOrder) {}

    public function handle(): void
    {
        // Fresh load with all relations
        $order = MasterOrder::with([
            'items',
            'orderStatus',
            'paymentStatus',
            'paymentMethod',
        ])->findOrFail($this->masterOrder->id);

        // Customer को mail
        Mail::to($order->email)->send(new OrderConfirmationMail($order, false));

        // Admin को mail
        $adminEmail = config('mail.admin_email', env('ADMIN_EMAIL'));
        if ($adminEmail) {
            Mail::to($adminEmail)->send(new OrderConfirmationMail($order, true));
        }

        // email_sent flag update
        $order->update(['email_sent' => 1]);

        Log::info("Order confirmation emails sent for: {$order->order_number}");
    }

    public function failed(\Throwable $exception): void
    {
        Log::error("SendOrderConfirmationEmail job failed for order {$this->masterOrder->id}: " . $exception->getMessage());
    }
}
