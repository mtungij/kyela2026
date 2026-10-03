<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('collections', function (Blueprint $table) {
            $table->decimal('installment_amount', 15, 2)->nullable()->after('game_cycle_id');
        });

        \Illuminate\Support\Facades\DB::table('collections')
            ->whereNull('installment_amount')
            ->orderBy('id')
            ->chunkById(100, function ($collections) {
                foreach ($collections as $collection) {
                    $amount = \Illuminate\Support\Facades\DB::table('members')
                        ->where('id', $collection->member_id)
                        ->value('amount');

                    \Illuminate\Support\Facades\DB::table('collections')
                        ->where('id', $collection->id)
                        ->update(['installment_amount' => $amount]);
                }
            });
    }

    public function down(): void
    {
        Schema::table('collections', function (Blueprint $table) {
            $table->dropColumn('installment_amount');
        });
    }
};