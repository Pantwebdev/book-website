<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAdditionalFieldsToMasterOrdersTable extends Migration
{
    public function up()
    {
        Schema::table('master_orders', function (Blueprint $table) {
            // Check if columns already exist before adding
            if (!Schema::hasColumn('master_orders', 'order_source')) {
                $table->string('order_source')->default('website')->after('payment_method');
            }

            if (!Schema::hasColumn('master_orders', 'shipping_method')) {
                $table->string('shipping_method')->default('standard')->after('order_source');
            }

            if (!Schema::hasColumn('master_orders', 'country')) {
                $table->string('country')->default('India')->after('pincode');
            }

            if (!Schema::hasColumn('master_orders', 'shipping_country')) {
                $table->string('shipping_country')->default('India')->after('shipping_pincode');
            }

            if (!Schema::hasColumn('master_orders', 'shipping_cost')) {
                $table->decimal('shipping_cost', 10, 2)->default(0)->after('shipping_method');
            }

            if (!Schema::hasColumn('master_orders', 'tax_rate')) {
                $table->decimal('tax_rate', 5, 2)->default(0)->after('shipping_cost');
            }

            if (!Schema::hasColumn('master_orders', 'currency')) {
                $table->string('currency')->default('INR')->after('tax_rate');
            }

            if (!Schema::hasColumn('master_orders', 'language')) {
                $table->string('language')->default('en')->after('currency');
            }

            if (!Schema::hasColumn('master_orders', 'device_type')) {
                $table->string('device_type')->nullable()->after('language');
            }

            if (!Schema::hasColumn('master_orders', 'browser')) {
                $table->string('browser')->nullable()->after('device_type');
            }

            if (!Schema::hasColumn('master_orders', 'ip_address')) {
                $table->ipAddress('ip_address')->nullable()->after('browser');
            }

            if (!Schema::hasColumn('master_orders', 'utm_source')) {
                $table->string('utm_source')->nullable()->after('ip_address');
            }

            if (!Schema::hasColumn('master_orders', 'utm_medium')) {
                $table->string('utm_medium')->nullable()->after('utm_source');
            }

            if (!Schema::hasColumn('master_orders', 'utm_campaign')) {
                $table->string('utm_campaign')->nullable()->after('utm_medium');
            }

            if (!Schema::hasColumn('master_orders', 'referral_url')) {
                $table->string('referral_url')->nullable()->after('utm_campaign');
            }

            if (!Schema::hasColumn('master_orders', 'is_guest')) {
                $table->boolean('is_guest')->default(false)->after('referral_url');
            }

            if (!Schema::hasColumn('master_orders', 'is_test')) {
                $table->boolean('is_test')->default(false)->after('is_guest');
            }

            if (!Schema::hasColumn('master_orders', 'is_recurring')) {
                $table->boolean('is_recurring')->default(false)->after('is_test');
            }

            if (!Schema::hasColumn('master_orders', 'reminder_sent_at')) {
                $table->dateTime('reminder_sent_at')->nullable()->after('is_recurring');
            }

            if (!Schema::hasColumn('master_orders', 'followup_sent_at')) {
                $table->dateTime('followup_sent_at')->nullable()->after('reminder_sent_at');
            }

            if (!Schema::hasColumn('master_orders', 'tags')) {
                $table->text('tags')->nullable()->after('followup_sent_at');
            }

            // Add indexes for better performance
            $table->index('order_source');
            $table->index('shipping_method');
            $table->index('country');
            $table->index('currency');
            $table->index('is_guest');
            $table->index('created_at');
            $table->index(['payment_method', 'created_at']);
            $table->index(['order_status', 'payment_status']);
        });
    }

    public function down()
    {
        Schema::table('master_orders', function (Blueprint $table) {
            // Drop indexes first
            $table->dropIndex(['order_source']);
            $table->dropIndex(['shipping_method']);
            $table->dropIndex(['country']);
            $table->dropIndex(['currency']);
            $table->dropIndex(['is_guest']);
            $table->dropIndex(['created_at']);
            $table->dropIndex(['payment_method', 'created_at']);
            $table->dropIndex(['order_status', 'payment_status']);

            // Drop columns if they exist
            $columns = [
                'order_source', 'shipping_method', 'country', 'shipping_country',
                'shipping_cost', 'tax_rate', 'currency', 'language', 'device_type',
                'browser', 'ip_address', 'utm_source', 'utm_medium', 'utm_campaign',
                'referral_url', 'is_guest', 'is_test', 'is_recurring', 'reminder_sent_at',
                'followup_sent_at', 'tags',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('master_orders', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
}
