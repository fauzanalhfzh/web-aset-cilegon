<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePengembaliansTable extends Migration
{
    public function up()
    {
        Schema::create('pengembalians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aset_keluar_id')->constrained('aset_keluars')->onDelete('cascade');
            $table->integer('jumlah')->default(1);
            $table->date('tanggal_pengembalian');
            $table->string('keterangan')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->string('status')->default('pending'); // pending, approved
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pengembalians');
    }
}