<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRelationshipsToTransaksiDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transaksi_details', function (Blueprint $table) {
            $table->foreign('transaksi_id')
                ->references('id')->on('transaksis')
                ->onDelete('cascade');
            //$table->foreign('barang_id')
            //    ->references('id')->on('barangs')
            //    ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transaksi_details', function (Blueprint $table) {
            $table->dropForeign('transaksi_details_transaksi_id_foreign');
            //$table->dropForeign('transaksi_details_barang_id_foreign');
        });
    }
}
