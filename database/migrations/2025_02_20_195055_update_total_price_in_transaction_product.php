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
        Schema::table('transaction_product', function (Blueprint $table) {
            $table->decimal('total_price', 15, 2)->change();
        });
    }

    public function down()
    {
        Schema::table('transaction_product', function (Blueprint $table) {
            $table->decimal('total_price', 10, 2)->change(); // Sesuaikan dengan ukuran sebelumnya
        });
    }
};