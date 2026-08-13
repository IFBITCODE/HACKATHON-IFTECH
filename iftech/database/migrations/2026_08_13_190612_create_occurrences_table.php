<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('occurrences', function (Blueprint $table) {
            $table->id();

            $table->string('title');

            $table->text('description');

            $table->string('location');

            $table->string('category');

            $table->enum(
                'severity',
                [
                    'baixa',
                    'media',
                    'alta',
                    'critica'
                ]
            )->default('baixa');

            $table->enum(
                'status',
                [
                    'aberta',
                    'em_atendimento',
                    'resolvida',
                    'cancelada'
                ]
            )->default('aberta');

            $table->timestamp('occurred_at');

            $table->timestamp('resolved_at')->nullable();

            $table->text('resolution_notes')->nullable();

            $table->timestamps();

            $table->index('location');
            $table->index('category');
            $table->index('severity');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('occurrences');
    }
};