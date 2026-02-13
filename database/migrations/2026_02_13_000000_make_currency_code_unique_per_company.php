<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('currencies')) {
            return;
        }

        Schema::table('currencies', function (Blueprint $table) {
            try {
                $table->dropUnique('currencies_code_unique');
            } catch (\Throwable $e) {
            }
        });

        Schema::table('currencies', function (Blueprint $table) {
            try {
                $table->unique(['company_id', 'code'], 'currencies_company_id_code_unique');
            } catch (\Throwable $e) {
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('currencies')) {
            return;
        }

        Schema::table('currencies', function (Blueprint $table) {
            try {
                $table->dropUnique('currencies_company_id_code_unique');
            } catch (\Throwable $e) {
            }
        });

        Schema::table('currencies', function (Blueprint $table) {
            try {
                $table->unique(['code'], 'currencies_code_unique');
            } catch (\Throwable $e) {
            }
        });
    }
};
