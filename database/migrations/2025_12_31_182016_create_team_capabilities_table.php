<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('team_capabilities', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('team_id');
            $table->string('capability_type'); 
            $table->unsignedTinyInteger('skill_level')->default(1); // 1–5

            $table->timestamps();

            $table->foreign('team_id')
                ->references('id')
                ->on('relief_teams')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('team_capabilities');
    }
};
