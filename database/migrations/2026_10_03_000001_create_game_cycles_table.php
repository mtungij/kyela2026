<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('game_cycles', function (Blueprint $table) {
            $table->id();
            $table->enum('pay_type', ['mchango_mdogo', 'mchango_mkubwa']);
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('status', ['active', 'closed'])->default('active');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['pay_type', 'status']);
        });

        Schema::table('collections', function (Blueprint $table) {
            $table->foreignId('game_cycle_id')
                ->nullable()
                ->after('member_id')
                ->constrained('game_cycles')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('collections', function (Blueprint $table) {
            $table->dropConstrainedForeignId('game_cycle_id');
        });

        Schema::dropIfExists('game_cycles');
    }
};