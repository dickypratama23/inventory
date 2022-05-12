<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGOsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('g_os', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('barang_id')->unique();
            $table->unsignedInteger('saruan')->nullable();
            $table->integer('recid_sn');
            $table->integer('recid_biasa');
            $table->integer('recid_hybrid');
            $table->integer('qty_out');
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
        Schema::dropIfExists('g_os');
    }
}
