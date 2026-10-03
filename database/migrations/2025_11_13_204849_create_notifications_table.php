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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id(); // BIGINT [pk, increment]
            $table->string('baslik', 255);
            $table->text('icerik');
            $table->unsignedBigInteger('hedef_kullanici_id'); // BIGINT [ref: > U.id]
            $table->enum('oncelik', ['Düşük', 'Orta', 'Yüksek']);
            $table->enum('durum', ['Okunmadı', 'Okundu']);
            $table->timestamp('olusturma_tarihi');
            $table->timestamps(); // created_at, updated_at TIMESTAMP

            $table->foreign('hedef_kullanici_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};