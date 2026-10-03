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
        Schema::create('reports', function (Blueprint $table) {
            $table->id(); // BIGINT [pk, increment]
            $table->enum('rapor_turu', ['Günlük', 'Haftalık', 'Aylık']);
            $table->unsignedBigInteger('bolge_id'); // BIGINT [ref: > R.id]
            $table->unsignedBigInteger('olusturan_id'); // BIGINT [ref: > U.id]
            $table->string('dosya_yolu', 255);
            $table->timestamp('olusturma_tarihi');
            $table->timestamps(); // created_at, updated_at TIMESTAMP

            $table->foreign('bolge_id')->references('id')->on('regions')->onDelete('cascade');
            $table->foreign('olusturan_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};