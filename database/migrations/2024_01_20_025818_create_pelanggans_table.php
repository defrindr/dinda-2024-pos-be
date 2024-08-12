<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePelanggansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pelanggans', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('nik', 90); // 4 * 16
            $table->string('name', 250);
            $table->string('phone', 120); // 4 * 13
            $table->text('address');
            $table->text('dob');
            $table->enum('gender', ['L', 'P']);
            $table->string('salt');
            $table->enum('status', ['active', 'nonactive']);
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
        Schema::dropIfExists('pelanggans');
    }
}
