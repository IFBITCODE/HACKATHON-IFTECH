<?php

namespace Database\Seeders;

use App\Models\Occurrence;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class OccurrenceSeeder extends Seeder
{
    public function run(): void
    {
        $occurrences = [

            [
                'title' => 'Iluminação pública danificada',
                'description' =>
                    'Postes de iluminação apresentando falhas no período noturno.',
                'location' => 'Centro Histórico',
                'category' => 'Infraestrutura',
                'severity' => 'media',
                'status' => 'em_atendimento',
                'occurred_at' => Carbon::now()->subDays(2),
            ],

            [
                'title' => 'Reclamação sobre limpeza',
                'description' =>
                    'Visitantes relataram acúmulo de resíduos em área turística.',
                'location' => 'Orla',
                'category' => 'Limpeza',
                'severity' => 'baixa',
                'status' => 'resolvida',
                'occurred_at' => Carbon::now()->subDays(5),
                'resolved_at' => Carbon::now()->subDays(4),
                'resolution_notes' =>
                    'Equipe de limpeza enviada ao local.',
            ],

            [
                'title' => 'Interdição de acesso',
                'description' =>
                    'Acesso temporariamente bloqueado devido a manutenção.',
                'location' => 'Parque Estadual',
                'category' => 'Acesso',
                'severity' => 'alta',
                'status' => 'aberta',
                'occurred_at' => Carbon::now()->subDay(),
            ],

            [
                'title' => 'Evento cancelado',
                'description' =>
                    'Evento turístico cancelado devido às condições climáticas.',
                'location' => 'Praça Central',
                'category' => 'Evento',
                'severity' => 'alta',
                'status' => 'resolvida',
                'occurred_at' => Carbon::now()->subDays(7),
                'resolved_at' => Carbon::now()->subDays(6),
                'resolution_notes' =>
                    'Novo comunicado publicado aos visitantes.',
            ],
        ];

        foreach ($occurrences as $occurrence) {
            Occurrence::create($occurrence);
        }
    }
}