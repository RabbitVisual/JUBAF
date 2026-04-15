<?php

namespace Modules\Blog\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Modules\Blog\App\Models\BlogCategory;
use Modules\Blog\App\Models\BlogPost;
use Modules\Blog\App\Models\BlogTag;

class BlogIntegrationController extends Controller
{
    /**
     * Gera post automático com resumo mensal institucional JUBAF (métricas dos módulos ativos).
     */
    public function generateMonthlyReport(Request $request)
    {
        $this->authorize('create', BlogPost::class);

        $month = (int) $request->input('month', date('m'));
        $year = (int) $request->input('year', date('Y'));
        $monthName = $this->getMonthName($month);

        $existingPost = BlogPost::where('auto_generated_from', 'monthly_report')
            ->whereYear('published_at', $year)
            ->whereMonth('published_at', $month)
            ->first();

        if ($existingPost) {
            return response()->json([
                'success' => false,
                'message' => 'Já existe um relatório mensal para este período.',
                'post_id' => $existingPost->id,
            ]);
        }

        try {
            $moduleData = $this->collectJubafModuleData($month, $year);
            $content = $this->generateMonthlyContentHtml($moduleData, $monthName, $year);

            $category = BlogCategory::firstOrCreate(
                ['slug' => 'relatorios-mensais'],
                [
                    'name' => 'Relatórios mensais',
                    'description' => 'Resumos automáticos com dados do ecossistema JUBAF.',
                    'color' => '#059669',
                    'icon' => 'chart-bar',
                    'is_active' => true,
                ]
            );

            $authorId = auth()->id() ?: 1;

            $post = BlogPost::create([
                'title' => "Resumo institucional JUBAF — {$monthName} {$year}",
                'slug' => Str::slug("resumo-institucional-jubaf-{$monthName}-{$year}"),
                'excerpt' => "Panorama do mês de {$monthName} de {$year} com indicadores dos módulos ativos no sistema JUBAF.",
                'content' => $content,
                'category_id' => $category->id,
                'author_id' => $authorId,
                'status' => 'published',
                'published_at' => now(),
                'is_featured' => false,
                'allow_comments' => true,
                'meta_title' => "Resumo JUBAF {$monthName} {$year}",
                'meta_description' => "Indicadores e atividades registradas em {$monthName} de {$year} na Juventude Batista Feirense (JUBAF).",
                'meta_keywords' => ['JUBAF', 'resumo mensal', $monthName, (string) $year],
                'module_data' => $moduleData,
                'auto_generated_from' => 'monthly_report',
            ]);

            $this->syncPostTags($post, ['JUBAF', 'resumo mensal', $monthName, (string) $year]);

            return response()->json([
                'success' => true,
                'message' => 'Relatório mensal gerado com sucesso!',
                'post_id' => $post->id,
                'post_url' => route('blog.show', $post->slug),
            ]);
        } catch (\Throwable $e) {
            report($e);

            return response()->json([
                'success' => false,
                'message' => 'Erro ao gerar relatório: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * @return array<string, int|string|float>
     */
    private function collectJubafModuleData(int $month, int $year): array
    {
        $data = [];

        try {
            if (module_enabled('Avisos') && class_exists(\Modules\Avisos\App\Models\Aviso::class)) {
                $q = \Modules\Avisos\App\Models\Aviso::query();
                $data['avisos_no_mes'] = (clone $q)->whereYear('created_at', $year)->whereMonth('created_at', $month)->count();
                $data['avisos_ativos'] = (clone $q)->where('ativo', true)->count();
            }

            if (module_enabled('Igrejas') && class_exists(\Modules\Igrejas\App\Models\Church::class)) {
                $data['igrejas_cadastradas'] = \Modules\Igrejas\App\Models\Church::query()->count();
            }

            if (module_enabled('Calendario') && class_exists(\Modules\Calendario\App\Models\CalendarEvent::class)) {
                $data['eventos_no_mes'] = \Modules\Calendario\App\Models\CalendarEvent::query()
                    ->whereYear('starts_at', $year)
                    ->whereMonth('starts_at', $month)
                    ->count();
            }

            if (module_enabled('Blog')) {
                $data['posts_publicados_no_mes'] = BlogPost::query()
                    ->where('status', 'published')
                    ->whereYear('published_at', $year)
                    ->whereMonth('published_at', $month)
                    ->count();
            }

            if (Schema::hasTable('users')) {
                $data['utilizadores_registados'] = DB::table('users')->count();
            }
        } catch (\Throwable $e) {
            report($e);
        }

        return $data;
    }

    /**
     * @param  array<string, int|string|float>  $moduleData
     */
    private function generateMonthlyContentHtml(array $moduleData, string $monthName, int $year): string
    {
        $site = \App\Support\SiteBranding::siteName();
        $content = '<h1 style="text-align:center;margin-bottom:2rem;">Resumo institucional — '.$monthName.' '.$year.'</h1>';
        $content .= '<p style="text-align:justify;margin-bottom:1.5rem;font-size:1.05em;">Este artigo foi gerado automaticamente a partir dos dados disponíveis no sistema <strong>'.$site.'</strong>, com o objetivo de dar transparência às atividades e indicadores do período.</p>';

        if ($moduleData !== []) {
            $content .= '<h2 style="margin-top:2rem;color:#059669;">Indicadores do período</h2><ul style="margin-bottom:1.5rem;">';
            foreach ($moduleData as $key => $value) {
                $label = Str::title(str_replace('_', ' ', $key));
                $content .= '<li style="margin-bottom:0.5rem;"><strong>'.e($label).':</strong> '.e((string) $value).'</li>';
            }
            $content .= '</ul>';
        } else {
            $content .= '<p style="text-align:justify;">Não foram encontrados dados agregados para este mês nos módulos ativos. Os indicadores aparecerão automaticamente quando os respetivos módulos estiverem em uso.</p>';
        }

        $content .= '<p style="text-align:justify;margin-top:1.5rem;">Para mais informações sobre a vida da juventude, eventos e comunicação oficial, acompanhe o blog e os avisos no painel JUBAF.</p>';
        $content .= '<hr style="border:none;border-top:2px solid #e5e7eb;margin:2rem 0;">';
        $content .= '<p style="text-align:center;font-style:italic;color:#6b7280;"><em>Publicação automática gerada em '.now()->format('d/m/Y \à\s H:i').'</em></p>';

        return $content;
    }

    /**
     * @param  array<int, string>  $tagNames
     */
    private function syncPostTags(BlogPost $post, array $tagNames): void
    {
        $tagIds = [];
        foreach ($tagNames as $tagName) {
            $tagName = trim($tagName);
            if ($tagName === '') {
                continue;
            }
            $tag = BlogTag::firstOrCreate(
                ['name' => $tagName],
                ['slug' => Str::slug($tagName)]
            );
            $tagIds[] = $tag->id;
        }
        $post->tags()->sync($tagIds);
    }

    private function getMonthName(int $month): string
    {
        $months = [
            1 => 'Janeiro', 2 => 'Fevereiro', 3 => 'Março', 4 => 'Abril',
            5 => 'Maio', 6 => 'Junho', 7 => 'Julho', 8 => 'Agosto',
            9 => 'Setembro', 10 => 'Outubro', 11 => 'Novembro', 12 => 'Dezembro',
        ];

        return $months[$month] ?? 'Mês';
    }
}
