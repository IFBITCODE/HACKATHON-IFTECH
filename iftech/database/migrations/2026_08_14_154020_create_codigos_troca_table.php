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
        Schema::create('codigos_troca', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empreendedor_id')->constrained('empreendedores')->cascadeOnDelete();
            $table->string('codigo', 12)->unique();
            $table->enum('status', ['disponivel', 'utilizado'])->default('disponivel');
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('utilizado_em')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('codigos_troca');
    }
};