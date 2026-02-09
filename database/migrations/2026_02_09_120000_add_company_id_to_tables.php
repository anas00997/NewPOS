<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $tables = [
            'branches',
            'categories',
            'brands',
            'units',
            'products',
            'orders',
            'purchases',
            'pos_carts',
            'order_products',
            'purchase_items',
            'order_transactions',
            'currencies',
        ];

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) {
                    if (!Schema::hasColumn($table->getTable(), 'company_id')) {
                        $table->foreignId('company_id')->nullable()->constrained('companies')->onDelete('cascade');
                    }
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'branches',
            'categories',
            'brands',
            'units',
            'products',
            'orders',
            'purchases',
            'pos_carts',
            'order_products',
            'purchase_items',
            'order_transactions',
            'currencies',
        ];

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) {
                    if (Schema::hasColumn($table->getTable(), 'company_id')) {
                        $table->dropForeign(['company_id']);
                        $table->dropColumn('company_id');
                    }
                });
            }
        }
    }
};
