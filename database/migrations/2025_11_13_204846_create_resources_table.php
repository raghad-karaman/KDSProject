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
        Schema::create('resources', function (Blueprint $table) {
            $table->id(); // BIGINT [pk, increment]
            $table->string('malzeme_adi', 255);
            $table->integer('miktar');
            $table->string('birim', 50);
            $table->unsignedBigInteger('depo_id')->nullable(); // (depo tablosu eklenmemiş, geçici olarak nullable)
            $table->timestamp('son_guncelleme');
            $table->timestamps(); // created_at, updated_at TIMESTAMP
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resources');
    }
};
