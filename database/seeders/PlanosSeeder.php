<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Plano;

class PlanosSeeder extends Seeder
{
    public function run(): void
    {
        $planos = [
            [
                'nome' => 'Pequenas Empresas',
                'valor' => 199.00,
                'desconto' => 0,
                'descricao' => [
                    'Para quem está começando.',
                    'Até 20 funcionários',
                    'Leitura OCR (300 notas)',
                    'Integração WhatsApp',
                    'Auditoria Antifraude'
                ]
            ],
            [
                'nome' => 'Crescimento',
                'valor' => 399.00,
                'desconto' => 0,
                'descricao' => [
                    'Controle total e automação.',
                    'Até 50 funcionários',
                    'Leitura OCR (1000 notas)',
                    'Integração WhatsApp',
                    'Auditoria Antifraude'
                ]
            ],
            [
                'nome' => 'Corporativo',
                'valor' => 599.00,
                'desconto' => 0,
                'descricao' => [
                    'Para grandes operações.',
                    'Funcionários ilimitados',
                    'OCR Ilimitado',
                    'Integração WhatsApp',
                    'Auditoria Antifraude',
                    'API Dedicada'
                ]
            ]
        ];

        foreach ($planos as $plano) {
            Plano::create($plano);
        }
    }
}