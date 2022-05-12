<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransaksiDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaksi_details', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('transaksi_id');
            $table->unsignedInteger('barang_id')->default(0);
            $table->unsignedInteger('cad_id')->default(0);
            $table->integer('qty');
            $table->string('Serial_number')->nullable();
            $table->string('note')->nullable();
            $table->string('rtype');
            $table->integer('approve')->default(0);
            $table->integer('ho')->default(0);
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
        Schema::dropIfExists('transaksi_details');
    }
}
