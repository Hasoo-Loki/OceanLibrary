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
        Schema::table('notifikasi', function (Blueprint $table) {
            // Ubah kolom menjadi nullable dengan default null
            $table->unsignedBigInteger('book_id')->nullable()->change();
            $table->unsignedBigInteger('peminjaman_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('notifikasi', function (Blueprint $table) {
            $table->unsignedBigInteger('book_id')->nullable(false)->change();
            $table->unsignedBigInteger('peminjaman_id')->nullable(false)->change();
        });
    }
};
