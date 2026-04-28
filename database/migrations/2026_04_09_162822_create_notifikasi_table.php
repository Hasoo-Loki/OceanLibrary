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
    Schema::create('notifikasi', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('user_id');      // yang request
        $table->unsignedBigInteger('book_id');
        $table->unsignedBigInteger('peminjaman_id');
        $table->text('pesan');
        $table->boolean('dibaca')->default(false);
        $table->timestamps();
    });
}

public function down()
{
    Schema::dropIfExists('notifikasi');
}
};