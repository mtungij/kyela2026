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
        Schema::table('members', function (Blueprint $table) {
            $table->decimal('penalty_per_day', 10, 2)->default(0)->after('number_type');
        });

        Schema::table('collections', function (Blueprint $table) {
            $table->decimal('total_penalty', 10, 2)->default(0)->after('balance');
            $table->decimal('penalty_paid', 10, 2)->default(0)->after('total_penalty');
            $table->decimal('penalty_balance', 10, 2)->default(0)->after('penalty_paid');
            $table->date('last_payment_date')->nullable()->after('penalty_balance');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->dropColumn('penalty_per_day');
        });

        Schema::table('collections', function (Blueprint $table) {
            $table->dropColumn(['total_penalty', 'penalty_paid', 'penalty_balance', 'last_payment_date']);
        });
    }
};
