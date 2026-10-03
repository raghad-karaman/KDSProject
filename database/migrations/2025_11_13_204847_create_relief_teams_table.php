<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('relief_teams', function (Blueprint $table) {
            $table->id();

            // 🔹 Görünen ekip adı
            $table->string('ekip_adi', 255);

            // 🔹 KDS için ekip türü (KRİTİK)
            $table->enum('ekip_turu', [
                'Saglik',
                'Lojistik',
                'AramaKurtarma',
                'Psikososyal'
            ])->default('Lojistik');

            $table->string('lider_ad', 255);

            // 🔹 Ekip şu an bir bölgede olmayabilir
            $table->unsignedBigInteger('bolge_id')->nullable();

            $table->string('iletisim', 50);

            // 🔹 Ekip durumu
            $table->enum('durum', [
                'Hazır',
                'Görevde',
                'Dinleniyor'
            ])->default('Hazır');

            // 🔹 Ekip konumu (ileride ETA için)
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            $table->timestamps();

            $table->foreign('bolge_id')
                ->references('id')
                ->on('regions')
                ->nullOnDelete(); // Bölge silinirse ekip BOŞTA kalır
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('relief_teams');
    }
};
