<?php

use App\Models\Brand;
use App\Models\Unit;
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
        Schema::table('products', function (Blueprint $table) {
            // Add brand and unit foreign keys if missing
            if (!Schema::hasColumn('products', 'brand_id')) {
                $table->foreignIdFor(Brand::class)->nullable()->constrained()->nullOnDelete();
            }
            if (!Schema::hasColumn('products', 'unit_id')) {
                $table->foreignIdFor(Unit::class)->nullable()->constrained()->nullOnDelete();
            }

            // Add pricing-related columns if missing
            if (!Schema::hasColumn('products', 'price')) {
                $table->double('price', 10, 2)->default(0);
            }
            if (!Schema::hasColumn('products', 'discount')) {
                $table->double('discount', 10, 2)->default(0);
            }
            if (!Schema::hasColumn('products', 'discount_type')) {
                $table->string('discount_type')->default('fixed');
            }
            if (!Schema::hasColumn('products', 'purchase_price')) {
                $table->double('purchase_price', 10, 2)->default(0);
            }
            if (!Schema::hasColumn('products', 'expire_date')) {
                $table->date('expire_date')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'expire_date')) {
                $table->dropColumn('expire_date');
            }
            if (Schema::hasColumn('products', 'purchase_price')) {
                $table->dropColumn('purchase_price');
            }
            if (Schema::hasColumn('products', 'discount_type')) {
                $table->dropColumn('discount_type');
            }
            if (Schema::hasColumn('products', 'discount')) {
                $table->dropColumn('discount');
            }
            if (Schema::hasColumn('products', 'price')) {
                $table->dropColumn('price');
            }
            if (Schema::hasColumn('products', 'unit_id')) {
                $table->dropConstrainedForeignId('unit_id');
            }
            if (Schema::hasColumn('products', 'brand_id')) {
                $table->dropConstrainedForeignId('brand_id');
            }
        });
    }
};