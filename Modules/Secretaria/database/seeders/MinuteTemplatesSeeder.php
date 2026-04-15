<?php

namespace Modules\Secretaria\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Secretaria\App\Models\MinuteTemplate;

class MinuteTemplatesSeeder extends Seeder
{
    public function run(): void
    {
        $templates = [
            [
                'slug' => 'assembleia-ordinaria',
                'title' => 'Assembleia ordinária',
                'body' => '<h2>1. Abertura</h2>'
                    .'<p><em>Indique hora de início, nomes de quem preside e quem lavra a ata, e se há quórum.</em></p>'
                    .'<h2>2. Leitura da ata anterior</h2>'
                    .'<p><em>Resumo: a ata foi lida / aprovada / retificada? (apague o que não servir)</em></p>'
                    .'<h2>3. Ordem do dia</h2>'
                    .'<ol><li><em>Primeiro ponto (edite o texto)</em></li><li><em>Segundo ponto</em></li><li><em>Assuntos gerais</em></li></ol>'
                    .'<h2>4. Deliberações e votações</h2>'
                    .'<p><em>Descreva o que foi decidido. Se houve votação, indique resultado.</em></p>'
                    .'<h2>5. Encerramento</h2>'
                    .'<p><em>Hora de fim e assinaturas (quando aplicável).</em></p>',
            ],
            [
                'slug' => 'assembleia-extraordinaria',
                'title' => 'Assembleia extraordinária',
                'body' => '<h2>1. Abertura</h2>'
                    .'<p><em>Motivo da convocação extraordinária (uma frase).</em></p>'
                    .'<h2>2. Ordem do dia</h2>'
                    .'<ol><li><em>Único ou principais pontos a tratar</em></li></ol>'
                    .'<h2>3. Debate e deliberação</h2>'
                    .'<p><em>Resumo do que foi discutido e decidido.</em></p>'
                    .'<h2>4. Encerramento</h2>'
                    .'<p><em>Hora e próximos passos.</em></p>',
            ],
            [
                'slug' => 'reuniao-conselho',
                'title' => 'Reunião de conselho / diretoria',
                'body' => '<h2>Participantes</h2>'
                    .'<p><em>Liste quem esteve presente (ou “todos os membros convocados”, se for o caso).</em></p>'
                    .'<h2>Assuntos tratados</h2>'
                    .'<ul><li><em>Assunto 1 — resumo breve</em></li><li><em>Assunto 2</em></li></ul>'
                    .'<h2>Decisões</h2>'
                    .'<p><em>O que ficou acordado, responsáveis e prazos, se existirem.</em></p>'
                    .'<h2>Próxima reunião ou follow-up</h2>'
                    .'<p><em>Data sugerida ou “a combinar”.</em></p>',
            ],
            [
                'slug' => 'ata-resumida',
                'title' => 'Ata resumida',
                'body' => '<h2>Resumo</h2>'
                    .'<p><em>Em poucas frases: do que se tratou a reunião.</em></p>'
                    .'<h2>Decisões principais</h2>'
                    .'<ul><li><em>Decisão 1</em></li><li><em>Decisão 2</em></li></ul>'
                    .'<h2>Observações</h2>'
                    .'<p><em>Opcional.</em></p>',
            ],
        ];

        foreach ($templates as $row) {
            MinuteTemplate::query()->updateOrCreate(
                ['slug' => $row['slug']],
                ['title' => $row['title'], 'body' => $row['body'], 'is_active' => true]
            );
        }
    }
}
