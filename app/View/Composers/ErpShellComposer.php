<?php

namespace App\View\Composers;

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

        $view->with([
            'erpShell' => $shell,
            'erpTitleSuffix' => match ($shell) {
                'admin' => \App\Support\SiteBranding::siteName(),
                'pastor' => \App\Support\SiteBranding::siteName(),
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
