<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pengaduans', function (Blueprint $table) {
            $table->id();
            $table->string("judul_laporan");
            $table->text("deskripsi");
            $table->enum("kategori",["Pencurian","Tindakan Kriminal","Bencana Alam","Kerusakan Fasilitas Umum"]);
            $table->enum("status",["Diproses","Selesai","Tolak"])->default("Diproses");
            $table->string("gambar")->nullable();
            $table->text("catatan")->nullable();
            $table->date('tgl_pengaduan');
            $table->unsignedBigInteger("id_user");
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengaduans');
    }
};
