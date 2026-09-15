<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\PrintOrder;

class SendOrderMailJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public $order;

    public function __construct(PrintOrder $order)
    {
        $this->order = $order;
    }

    public function handle()
    {
        $order = $this->order;

        $message = "
        Order Confirmed!

        Order No: {$order->order_number}
        Total: ₹{$order->total_amount}
        Paid: ₹{$order->paid_amount}
        Remaining: ₹{$order->remaining_amount}

        Track Order:
        " . route('print.order.confirmation', $order->order_number);

        // ✅ CUSTOMER MAIL
        Mail::raw($message, function ($mail) use ($order) {
            $mail->to($order->email)
                 ->subject('Order Confirmation');
        });

        // ✅ ADMIN MAIL
        Mail::raw($message, function ($mail) {
            $mail->to(config('mail.admin_email')) // ✅ yaha change
                 ->subject('New Print Order');
        });
    }
}
