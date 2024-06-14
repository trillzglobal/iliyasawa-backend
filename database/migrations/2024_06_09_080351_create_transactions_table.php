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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['procurement', 'storing', 'sales', 'usage', 'distribute', 'finished_prod'])->default('procurement');
            $table->json('transaction_detail_ids');
            $table->integer('sold_to')->nullable();
            $table->integer('accepted_by')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('paid_amount');
            $table->integer('remaining_amount');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
