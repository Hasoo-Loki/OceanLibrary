<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::create('dendas', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        $table->foreignId('peminjaman_id')->nullable()->constrained('peminjaman')->onDelete('set null');
        $table->enum('jenis', ['terlambat', 'rusak', 'hilang']);
        $table->integer('jumlah'); // nominal denda dalam rupiah
        $table->text('keterangan')->nullable();
        $table->enum('status', ['belum_lunas', 'lunas'])->default('belum_lunas');
        $table->timestamps();
    });
}

public function down()
{
    Schema::dropIfExists('dendas');
}
};
