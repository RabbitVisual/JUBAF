<?php

namespace Modules\Secretaria\App\Http\Controllers\Concerns;

use Illuminate\Contracts\View\View;

trait RendersSecretariaPanelViews
{
    /**
     * Subpasta em secretaria:: (paineldiretoria | admin).
     */
    protected function secretariaViewsDirectory(): string
    {
        return 'paineldiretoria';
    }

    /**
     * Prefixo de rotas nomeadas para links do dashboard (admin.secretaria | diretoria.secretaria).
     */
    protected function secretariaBaseRoute(): string
    {
        return str_starts_with($this->routePrefix(), 'admin.')
            ? 'admin.secretaria'
            : 'diretoria.secretaria';
    }

    protected function secretariaView(string $dotPath, array $data = []): View
    {
        return view('secretaria::'.$this->secretariaViewsDirectory().'.'.$dotPath, array_merge($data, [
            'layout' => $this->panelLayout(),
            'routePrefix' => $this->routePrefix(),
        ]));
    }
}
