<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemConfig;
use App\Support\SiteBranding;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BrandingController extends Controller
{
    public function upload(Request $request)
    {
        $validated = $request->validate([
            'logo_default' => ['nullable', 'file', 'max:4096', 'mimes:png,jpeg,jpg,webp,svg'],
            'logo_light' => ['nullable', 'file', 'max:4096', 'mimes:png,jpeg,jpg,webp,svg'],
            'logo_dark' => ['nullable', 'file', 'max:4096', 'mimes:png,jpeg,jpg,webp,svg'],
            'branding_site_tagline' => ['nullable', 'string', 'max:500'],
        ]);

        if ($request->filled('branding_site_tagline')) {
            SystemConfig::set(
                SiteBranding::KEY_SITE_TAGLINE,
                $request->input('branding_site_tagline'),
                'string',
                'branding',
                'Slogan / descrição curta (meta e textos auxiliares)'
            );
        }

        $disk = 'public';

        $hadFile = false;

        foreach (['logo_default' => SiteBranding::KEY_LOGO_DEFAULT, 'logo_light' => SiteBranding::KEY_LOGO_LIGHT, 'logo_dark' => SiteBranding::KEY_LOGO_DARK] as $input => $configKey) {
            if (empty($validated[$input]) || ! $request->hasFile($input)) {
                continue;
            }

            $hadFile = true;

            $file = $request->file($input);
            $ext = $file->getClientOriginalExtension() ?: $file->guessExtension() ?: 'png';
            $basename = match ($input) {
                'logo_default' => 'logo-default',
                'logo_light' => 'logo-light',
                default => 'logo-dark',
            };

            $previous = SystemConfig::get($configKey, '');
            $this->deleteStoredBrandingFile($previous);

            $path = $file->storeAs('branding', $basename.'.'.$ext, $disk);

            SystemConfig::set(
                $configKey,
                $path,
                'string',
                'branding',
                'Caminho do logo (storage público)'
            );
        }

        if (! $hadFile && ! $request->filled('branding_site_tagline')) {
            return redirect()
                ->route('admin.config.index', [], 302)
                ->withFragment('branding')
                ->with('error', 'Envie pelo menos um arquivo ou preencha o slogan.');
        }

        $message = $hadFile ? 'Identidade visual atualizada com sucesso.' : 'Slogan atualizado com sucesso.';

        return redirect()
            ->route('admin.config.index', [], 302)
            ->withFragment('branding')
            ->with('success', $message);
    }

    public function restore()
    {
        foreach (SiteBranding::defaultLogoPaths() as $key => $publicPath) {
            $previous = SystemConfig::get($key, '');
            $this->deleteStoredBrandingFile($previous);

            SystemConfig::set(
                $key,
                $publicPath,
                'string',
                'branding',
                match ($key) {
                    SiteBranding::KEY_LOGO_DEFAULT => 'Logo padrão (colorido)',
                    SiteBranding::KEY_LOGO_LIGHT => 'Logo para fundo escuro (claro/branco)',
                    SiteBranding::KEY_LOGO_DARK => 'Logo para fundo claro (escuro)',
                    default => 'Logo',
                }
            );
        }

        return redirect()
            ->route('admin.config.index', [], 302)
            ->withFragment('branding')
            ->with('success', 'Logos restaurados para os arquivos oficiais em public/images/logo/.');
    }

    private function deleteStoredBrandingFile(?string $storedPath): void
    {
        if ($storedPath === null || $storedPath === '') {
            return;
        }

        if (str_starts_with($storedPath, 'branding/') && Storage::disk('public')->exists($storedPath)) {
            Storage::disk('public')->delete($storedPath);
        }
    }
}
