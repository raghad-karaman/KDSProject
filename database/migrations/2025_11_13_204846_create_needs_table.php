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
        Schema::create('needs', function (Blueprint $table) {
            $table->id(); // BIGINT [pk, increment]
            $table->unsignedBigInteger('victim_id'); // BIGINT [ref: > V.id]
            $table->unsignedBigInteger('bolge_id'); // BIGINT [ref: > R.id]
            $table->integer('su_litre');
            $table->integer('gida_paketi');
            $table->integer('cadir');
            $table->integer('ilac_adet');
            $table->enum('oncelik', ['Düşük', 'Orta', 'Yüksek']);
            $table->timestamp('kayit_tarihi');
            $table->timestamps(); // created_at, updated_at TIMESTAMP

            $table->foreign('victim_id')->references('id')->on('victims')->onDelete('cascade');
            $table->foreign('bolge_id')->references('id')->on('regions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('needs');
    }
};