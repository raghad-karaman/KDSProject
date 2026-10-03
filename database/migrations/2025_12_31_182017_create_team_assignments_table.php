<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('team_assignments', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('team_id');
            $table->unsignedBigInteger('bolge_id');

            // 🔴 KDS için SAYISAL öncelik
            $table->unsignedTinyInteger('priority');
            // 1=Kritik, 2=Yüksek, 3=Orta, 4=Düşük

            // 🔴 Görev durumu (standart)
            $table->enum('status', [
                'SUGGESTED',
                'ASSIGNED',
                'ENROUTE',
                'ARRIVED',
                'COMPLETED',
                'CANCELED'
            ])->default('SUGGESTED');

            $table->timestamp('assigned_at')->nullable();
            $table->timestamp('completed_at')->nullable();

            // 🔴 Görev sonrası performans (KDS öğrenme için)
            $table->decimal('performance_score', 5, 2)->nullable();

            $table->timestamps();

            $table->foreign('team_id')
                ->references('id')
                ->on('relief_teams')
                ->cascadeOnDelete();

            $table->foreign('bolge_id')
                ->references('id')
                ->on('regions')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('team_assignments');
    }
};
