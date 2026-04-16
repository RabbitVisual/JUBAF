<?php

namespace App\View\Composers;

use App\Support\SiteBranding;
use Illuminate\Support\Facades\Route;
use Illuminate\View\View;

class ErpShellComposer
{
    public function compose(View $view): void
    {
        $name = Route::currentRouteName() ?? '';

        $shell = 'diretoria';
        if (str_starts_with($name, 'admin.')) {
            $shell = 'admin';
        } elseif (str_starts_with($name, 'pastor.')) {
            $shell = 'pastor';
        } elseif (str_starts_with($name, 'lideres.')) {
            $shell = 'lideres';
        } elseif (str_starts_with($name, 'jovens.')) {
            $shell = 'jovens';
        }

        $includeBlogAdminJs = $shell === 'admin'
            || str_starts_with($name, 'diretoria.blog')
            || str_starts_with($name, 'admin.blog');

        $manifestUrl = match ($shell) {
            'jovens' => asset('manifest-jovens.json'),
            'lideres' => asset('manifest.json'),
            default => asset('manifest.json'),
        };

        $view->with([
            'erpShell' => $shell,
            'erpManifestUrl' => $manifestUrl,
            'erpTitleSuffix' => match ($shell) {
                'admin' => SiteBranding::siteName(),
                'pastor' => SiteBranding::siteName(),
                'lideres' => 'JUBAF',
                'jovens' => 'JUBAF',
                default => 'Painel da Diretoria',
            },
            'erpPageTitleDefault' => match ($shell) {
                'admin' => 'Painel Admin',
                'pastor' => 'Painel Pastor',
                'lideres' => 'Painel de Líderes',
                'jovens' => 'Painel de Jovens',
                default => config('app.name', 'JUBAF'),
            },
            'erpIncludePwaMeta' => in_array($shell, ['lideres', 'jovens'], true),
            'erpIncludeBlogAdminJs' => $includeBlogAdminJs,
        ]);
    }
}
