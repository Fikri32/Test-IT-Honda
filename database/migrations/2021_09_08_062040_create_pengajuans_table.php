<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePengajuansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pengajuans', function (Blueprint $table) {
            $table->string('number',30)->primary();
            $table->date('tgl_awal');
            $table->date('tgl_akhir');
            $table->timestamp('tgl_input', $precision = 0);
            $table->datetime('tgl_acc');
            $table->unsignedBigInteger('user_input');
            $table->unsignedBigInteger('user_acc');
            $table->enum('status',[0,1]);
            $table->integer('jumlah_cuti');
            $table->string('keterangan');
            $table->string('keterangan_acc');
            $table->foreign('user_input')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('user_acc')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pengajuans');
    }
}
