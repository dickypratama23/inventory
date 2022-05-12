<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cads', function (Blueprint $table) {
            $table->increments('id');
            $table->string('kode')->unique();
            $table->string('mac')->unique();
            $table->string('name');
            $table->unsignedInteger('kategori_id');//
            $table->boolean('recid')->default(0);
            $table->unsignedInteger('department_id')->default(0);
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
        Schema::dropIfExists('cads');
    }
}
