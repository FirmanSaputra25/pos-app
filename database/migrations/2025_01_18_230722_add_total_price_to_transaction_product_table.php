<?php

// database/migrations/xxxx_xx_xx_xxxxxx_add_total_price_to_transaction_product_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTotalPriceToTransactionProductTable extends Migration
{
    public function up()
    {
        Schema::table('transaction_product', function (Blueprint $table) {
            $table->decimal('total_price', 10, 2)->nullable(); // Menambahkan kolom total_price
        });
    }

    public function down()
    {
        Schema::table('transaction_product', function (Blueprint $table) {
            $table->dropColumn('total_price');
        });
    }
}