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
        Schema::create('victims', function (Blueprint $table) {
            $table->id(); // BIGINT [pk, increment]
            $table->string('ad_soyad', 255);
            $table->integer('yas');
            $table->enum('cinsiyet', ['Erkek', 'Kadın', 'Diğer']);
            $table->string('telefon', 50);
            $table->string('adres', 255);
            $table->unsignedBigInteger('bolge_id'); // BIGINT [ref: > R.id]
            $table->timestamp('kayit_tarihi');
            $table->timestamps(); // created_at, updated_at TIMESTAMP

            $table->foreign('bolge_id')->references('id')->on('regions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('victims');
    }
};