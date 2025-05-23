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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name'); 
            $table->text('description'); 
            $table->decimal('price', 10, 2); 
            $table->integer('stock_quantity'); 
            $table->foreignId('store_id')->constrained('stores')->onDelete('cascade'); 
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade'); 
            $table->string('image_url');
            $table->enum('request', ['yes', 'no'])->default('no');
            $table->timestamps(); 
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
