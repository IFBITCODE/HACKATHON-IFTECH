<?php

namespace Database\Seeders;

use App\Models\MetricDaily;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class MetricsDailySeeder extends Seeder
{
    public function run(): void
    {
        $startDate = Carbon::now()->subMonths(3)->startOfMonth();
        $endDate = Carbon::now()->endOfDay();

        $date = $startDate->copy();

        while ($date->lte($endDate)) {

            $accesses = rand(900, 2800);

            $uniqueVisitors = (int) ($accesses * rand(55, 75) / 100);

            $recurringVisitors = (int) (
                $uniqueVisitors * rand(15, 35) / 100
            );

            $searches = rand(200, 800);

            $navigationTime = rand(60, 240);

            $returnRate = round(
                ($recurringVisitors / max($uniqueVisitors, 1)) * 100,
                2
            );

            MetricDaily::updateOrCreate(
                [
                    'metric_date' => $date->format('Y-m-d'),
                ],
                [
                    'accesses_total' => $accesses,
                    'unique_visitors' => $uniqueVisitors,
                    'recurring_visitors' => $recurringVisitors,
                    'searches_total' => $searches,

                    'avg_navigation_seconds' => $navigationTime,

                    'return_rate' => $returnRate,

                    'top_pages' => [
                        'Página inicial' => rand(500, 1500),
                        'Destinos turísticos' => rand(400, 1200),
                        'Eventos' => rand(300, 1000),
                        'Gastronomia' => rand(250, 900),
                    ],

                    'top_attractions' => [
                        'Centro Histórico' => rand(400, 1200),
                        'Praias' => rand(300, 1000),
                        'Mercado Municipal' => rand(250, 800),
                        'Parque Estadual' => rand(150, 600),
                    ],

                    'top_routes' => [
                        'Roteiro Histórico' => rand(250, 800),
                        'Roteiro Gastronômico' => rand(200, 700),
                        'Roteiro Praias' => rand(180, 650),
                    ],

                    'top_events' => [
                        'Festival Cultural' => rand(150, 600),
                        'Feira de Artesanato' => rand(120, 500),
                        'Festival Gastronômico' => rand(100, 450),
                    ],

                    'geo_origin' => [
                        'João Pessoa' => rand(20, 40),
                        'Campina Grande' => rand(10, 25),
                        'Recife' => rand(5, 20),
                        'Natal' => rand(5, 15),
                        'São Paulo' => rand(5, 15),
                        'Outros' => rand(10, 25),
                    ],

                    'languages' => [
                        'Português' => rand(70, 90),
                        'Inglês' => rand(5, 15),
                        'Espanhol' => rand(3, 10),
                        'Outros' => rand(1, 5),
                    ],

                    'devices' => [
                        'Mobile' => rand(55, 75),
                        'Desktop' => rand(20, 40),
                        'Tablet' => rand(3, 10),
                    ],

                    'channels' => [
                        'Busca orgânica' => rand(25, 45),
                        'Redes sociais' => rand(15, 30),
                        'Acesso direto' => rand(15, 30),
                        'Links externos' => rand(5, 15),
                    ],
                ]
            );

            $date->addDay();
        }
    }
}