<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('region_priority_scores', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('region_id');

            $table->decimal('kds_score', 6, 2);
            $table->string('priority'); // kritik / yüksek / orta / düşük

            $table->json('required_capabilities'); 
            // örn: ["Saglik", "Arama Kurtarma", "Lojistik"]

            $table->timestamp('calculated_at');

            $table->timestamps();

            $table->foreign('region_id')
                ->references('id')
                ->on('regions')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('region_priority_scores');
    }
};
