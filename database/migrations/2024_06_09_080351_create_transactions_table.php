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
            $table->string('sold_to')->nullable();
            $table->unsignedBigInteger('accepted_by')->nullable();
            $table->foreign('accepted_by')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->decimal('paid_amount')->nullable();
            $table->integer('remaining_amount')->nullable();
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
