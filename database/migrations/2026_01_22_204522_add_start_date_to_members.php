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
            $table->date('start_date')->nullable()->after('amount');
            $table->enum('pay_type', ['mchango_mdogo', 'mchango_mkubwa'])->default('mchango_mdogo')->after('start_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->dropColumn('start_date');
            $table->dropColumn('pay_type');
        });
    }
};
