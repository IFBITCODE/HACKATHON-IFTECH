<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('metrics_daily', function (Blueprint $table) {
            $table->id();

            $table->date('metric_date')->unique();

            $table->unsignedBigInteger('accesses_total')->default(0);
            $table->unsignedBigInteger('unique_visitors')->default(0);
            $table->unsignedBigInteger('recurring_visitors')->default(0);
            $table->unsignedBigInteger('searches_total')->default(0);

            $table->unsignedInteger('avg_navigation_seconds')->default(0);

            $table->decimal('return_rate', 5, 2)->default(0);

            $table->json('top_pages')->nullable();
            $table->json('top_attractions')->nullable();
            $table->json('top_routes')->nullable();
            $table->json('top_events')->nullable();

            $table->json('geo_origin')->nullable();
            $table->json('languages')->nullable();
            $table->json('devices')->nullable();
            $table->json('channels')->nullable();

            $table->timestamps();

            $table->index('metric_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('metrics_daily');
    }
};