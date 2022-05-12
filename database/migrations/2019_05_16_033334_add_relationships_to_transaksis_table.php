<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRelationshipsToTransaksisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     public function up()
     {
         //Schema::table('transaksis', function (Blueprint $table) {
         //    $table->foreign('user_id')
         //        ->references('id')->on('users')
         //        ->onDelete('cascade');
         //    $table->foreign('department_id')
         //        ->references('id')->on('departments')
         //        ->onDelete('cascade');
         //});
     }
 
     /**
      * Reverse the migrations.
      *
      * @return void
      */
     public function down()
     {
         //Schema::table('transaksis', function (Blueprint $table) {
         //    $table->dropForeign('transaksis_user_id_foreign');
         //    $table->dropForeign('transaksis_department_id_foreign');
         //});
     }
}
