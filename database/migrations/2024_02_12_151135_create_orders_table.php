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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->foreignId('customer_id')->references('id')->on('customers')->cascadeOnDelete();
            $table->foreignId('product_id')->references('id')->on('products')->cascadeOnDelete();
            $table->integer('quantity');
            $table->decimal('price', 8, 2);
            $table->decimal('total', 8, 2);
            $table->decimal('discount', 8, 2)->default(0);
            $table->json('campaigns')->nullable();
            $table->enum('status', ['pending', 'processing', 'completed', 'declined'])->default('pending');
            $table->text('notes')->nullable();
            $table->index(['customer_id', 'product_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
