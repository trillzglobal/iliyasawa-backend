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
        Schema::create('main_stores', function (Blueprint $table) {
            $table->id();
            $table->string('product_name');
            $table->text('product_description');
            $table->decimal('measurement_unit');
            $table->integer('quantity');
            $table->enum('store_type', ['procurement', 'finished']);
            $table->unsignedBigInteger('last_active_role')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('main_stores');
    }
};
