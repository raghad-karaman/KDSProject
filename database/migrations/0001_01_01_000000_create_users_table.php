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
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // BIGINT [pk, increment]
            $table->string('name', 255);
            $table->string('email', 191)->unique(); // E-posta benzersiz olmalı
            $table->string('password', 255);
            $table->enum('rol', ['Admin', 'Kullanıcı']);
            $table->timestamps(); // created_at, updated_at TIMESTAMP
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
