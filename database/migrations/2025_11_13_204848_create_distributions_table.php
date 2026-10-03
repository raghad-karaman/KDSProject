<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('distribution', function (Blueprint $table) {
            $table->id(); // BIGINT [pk, increment]

            $table->unsignedBigInteger('resource_id');      // Kaynak
            $table->unsignedBigInteger('bolge_id');         // Bölge
            $table->integer('miktar');                      // Miktar

            // ✅ DAĞITIM TÜRÜ
            $table->enum('dagitim_turu', [
                'acil',
                'rutin',
                'ilk_mudahale',
                'planli',
                'iade'
            ]);

            $table->date('tarih');                          // Tarih
            $table->unsignedBigInteger('sorumlu_ekip_id');  // Ekip
            $table->timestamps();

            $table->foreign('resource_id')
                ->references('id')->on('resources')
                ->onDelete('cascade');

            $table->foreign('bolge_id')
                ->references('id')->on('regions')
                ->onDelete('cascade');

            $table->foreign('sorumlu_ekip_id')
                ->references('id')->on('relief_teams')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('distribution');
    }
};