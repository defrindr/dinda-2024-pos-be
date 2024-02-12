<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kasir_id')->references('id')->on('users');
            $table->foreignId('customer_id')->nullable()->references('id')->on('pelanggans');
            $table->string('invoice');
            $table->date('date');
            $table->unsignedBigInteger('total_price');
            $table->unsignedBigInteger('total_pay');
            $table->unsignedBigInteger('total_return');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
