<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::table('cart_products', function (Blueprint $table) {
        $table->foreignId('design_request_id')->nullable()->constrained('design_requests')->onDelete('set null');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cart_products', function (Blueprint $table) {
            //
        });
    }
};
