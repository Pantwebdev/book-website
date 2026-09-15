<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MasterOrder;
use Illuminate\Support\Facades\DB;

class OrderStatusMigrationSeeder extends Seeder
{
    public function run()
    {
        // Migrate existing orders to new status system
        $orders = MasterOrder::all();

        foreach ($orders as $order) {
            if ($order->payment_method == 'cod') {
                // Migrate COD orders
                $order->cod_order_status = $this->mapLegacyToCodOrderStatus($order->order_status);
                $order->cod_payment_status = $this->mapLegacyToCodPaymentStatus($order->payment_status);
            } else {
                // Migrate Online orders
                $order->online_order_status = $this->mapLegacyToOnlineOrderStatus($order->order_status);
                $order->online_payment_status = $this->mapLegacyToOnlinePaymentStatus($order->payment_status);
            }

            // Add initial status to history
            $history = [
                'type' => $order->payment_method == 'cod' ? 'cod' : 'online',
                'order_status_old' => 'order_placed',
                'order_status_new' => $order->payment_method == 'cod' ? $order->cod_order_status : $order->online_order_status,
                'payment_status_old' => 'pending',
                'payment_status_new' => $order->payment_method == 'cod' ? $order->cod_payment_status : $order->online_payment_status,
                'timestamp' => $order->created_at->toDateTimeString(),
                'user_id' => null,
            ];

            $order->status_history = [$history];
            $order->save();
        }

        $this->command->info('Order status migration completed!');
    }

    private function mapLegacyToCodOrderStatus($legacyStatus)
    {
        $mapping = [
            'pending' => 'order_placed',
            'confirmed' => 'confirmed',
            'processing' => 'processing',
            'shipped' => 'shipped',
            'delivered' => 'delivered',
            'cancelled' => 'cancelled',
            'returned' => 'cancelled',
            'refunded' => 'cancelled',
        ];

        return $mapping[$legacyStatus] ?? 'order_placed';
    }

    private function mapLegacyToCodPaymentStatus($legacyStatus)
    {
        $mapping = [
            'pending' => 'pending',
            'paid' => 'collected',
            'failed' => 'failed',
            'refunded' => 'refunded',
        ];

        return $mapping[$legacyStatus] ?? 'pending';
    }

    private function mapLegacyToOnlineOrderStatus($legacyStatus)
    {
        $mapping = [
            'pending' => 'order_placed',
            'confirmed' => 'confirmed',
            'processing' => 'processing',
            'shipped' => 'shipped',
            'delivered' => 'delivered',
            'cancelled' => 'cancelled',
            'returned' => 'cancelled',
            'refunded' => 'cancelled',
        ];

        return $mapping[$legacyStatus] ?? 'order_placed';
    }

    private function mapLegacyToOnlinePaymentStatus($legacyStatus)
    {
        $mapping = [
            'pending' => 'pending',
            'paid' => 'successful',
            'failed' => 'failed',
            'refunded' => 'refunded',
        ];

        return $mapping[$legacyStatus] ?? 'pending';
    }
}
