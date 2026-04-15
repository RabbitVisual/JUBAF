<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\SystemConfigService;
use App\Models\SystemConfig;
use Illuminate\Http\Request;

class SystemConfigController extends Controller
{
    protected $configService;

    public function __construct(SystemConfigService $configService)
    {
        $this->configService = $configService;
    }

    public function index()
    {
        $configs = $this->configService->getConfigsGrouped();
        $groups = ['general', 'branding', 'email', 'security', 'backup', 'modules', 'recaptcha', 'integrations', 'platform_modules', 'bible_homepage', 'homepage'];

        // Garantir que as configurações de marca existam
        $this->configService->ensureBrandingConfigs();

        // Garantir que as configurações reCAPTCHA existam
        $this->configService->ensureRecaptchaConfigs();

        // Garantir que as configurações Google Maps existam
        $this->configService->ensureGoogleMapsConfigs();

        // Garantir que as configurações SMTP existam
        $this->configService->ensureMailConfigs();

        // Garantir que as configurações Pusher/Broadcast existam
        $this->configService->ensurePusherConfigs();

        $this->configService->ensureModulePlatformConfigs();

        $this->configService->ensureHomepageBibleAndFooterConfigs();

        // Recarregar configurações após garantir que existem
        $configs = $this->configService->getConfigsGrouped();

        return view('admin::config.index', compact('configs', 'groups'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'configs' => 'required|array',
        ]);

        try {
            $this->configService->batchUpdateConfigsWithEnvSync($validated['configs']);

            return redirect()->route('admin.config.index')
                ->with('success', 'Configurações atualizadas com sucesso');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erro ao atualizar configurações: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function initialize()
    {
        try {
            $this->configService->initializeDefaultConfigs();
            return redirect()->route('admin.config.index')
                ->with('success', 'Configurações padrão inicializadas com sucesso');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erro ao inicializar configurações: ' . $e->getMessage());
        }
    }
}
