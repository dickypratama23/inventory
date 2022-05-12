<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermintaansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permintaans', function (Blueprint $table) {
            $table->increments('id');
            $table->string('jenis_pp');
            $table->string('nomor_pp');
            $table->string('barang_id');
            $table->integer('qty');
            $table->dateTime('serah1');
            $table->dateTime('serah2');
            $table->dateTime('realisasi');
            $table->integer('minus');
            $table->string('note');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('permintaans');
    }
}
