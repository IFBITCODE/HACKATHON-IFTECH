<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('codigos_troca', function (Blueprint $table) {
            $table->unsignedInteger('moedas')->default(1)->after('codigo');
        });
    }

    public function down(): void
    {
        Schema::table('codigos_troca', function (Blueprint $table) {
            $table->dropColumn('moedas');
        });
    }
};
