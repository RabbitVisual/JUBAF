<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('igrejasProvisionYouth', function (?User $user) {
            return $user && $user->can('igrejas.jovens.provision') && (bool) $user->church_id;
        });

        Gate::define('igrejasManageChurchYouth', function (?User $actor, User $target) {
            if (! $actor || ! $actor->can('igrejas.jovens.provision') || ! $actor->church_id) {
                return false;
            }
            if (! $target->hasRole('jovens')) {
                return false;
            }

            return (int) $target->church_id === (int) $actor->church_id;
        });

        Gate::policy(\Modules\Avisos\App\Models\Aviso::class, \App\Policies\AvisoPolicy::class);
        Gate::policy(\Modules\Blog\App\Models\BlogPost::class, \App\Policies\BlogPostPolicy::class);
        Gate::policy(\Modules\Blog\App\Models\BlogCategory::class, \App\Policies\BlogCategoryPolicy::class);
        Gate::policy(\Modules\Blog\App\Models\BlogTag::class, \App\Policies\BlogTagPolicy::class);
        Gate::policy(\Modules\Blog\App\Models\BlogComment::class, \App\Policies\BlogCommentPolicy::class);
        Gate::policy(\Modules\Gateway\App\Models\GatewayProviderAccount::class, \Modules\Gateway\App\Policies\GatewayProviderAccountPolicy::class);
        Gate::policy(\Modules\Gateway\App\Models\GatewayPayment::class, \Modules\Gateway\App\Policies\GatewayPaymentPolicy::class);

        // Configurar paginação para usar Tailwind CSS
        \Illuminate\Pagination\Paginator::defaultView('pagination::tailwind');
        \Illuminate\Pagination\Paginator::defaultSimpleView('pagination::simple-tailwind');

        // Anti-brute-force para rotas de login web e API.
        // Usado por middleware `throttle:login`.
        RateLimiter::for('login', function (Request $request) {
            $email = (string) $request->input('email');

            return $email !== ''
                ? Limit::perMinute(5)->by($email)
                : Limit::perMinute(5)->by($request->ip());
        });

        // API JSON pública da Bíblia (api/v1/bible/*) — leitura anónima, limite por IP.
        RateLimiter::for('bible-public-api', function (Request $request) {
            return Limit::perMinute(120)->by($request->ip());
        });

    }
}
