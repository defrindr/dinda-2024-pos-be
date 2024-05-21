<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->references('id')->on('categories');
            $table->string('code');
            $table->string('name');
            $table->integer('stock');
            $table->string('unit');
            $table->integer('per_pack');
            $table->integer('per_item')->default(1);
            $table->string('unit_item');
            $table->decimal('price_buy');
            $table->decimal('price_sell');
            $table->decimal('price_sell_item');
            $table->text('description');
            $table->date('date');
            $table->string('photo');
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
        Schema::dropIfExists('products');
    }
}
