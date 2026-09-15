<?php

namespace App\Jobs;

use App\Models\PrintOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendRemainingPaymentJob implements ShouldQueue
{
    use Dispatchable;
    use Queueable;

    public $order;

    public function __construct(PrintOrder $order)
    {
        $this->order = $order;
    }

    public function handle()
    {
        $order = $this->order;

        $message = "
        Pay Remaining Amount

        Order No: {$order->order_number}
        Remaining: ₹{$order->remaining_amount}

        Pay Now:
        " . url('/pay-remaining/' . $order->order_number);

        // customer
        Mail::raw($message, function ($mail) use ($order) {
            $mail->to($order->email)
                 ->subject('Remaining Payment');
        });

        // admin
        Mail::raw($message, function ($mail) {
            $mail->to(env('ADMIN_EMAIL'))
                 ->subject('Remaining Payment Sent');
        });
    }
}
