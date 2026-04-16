<?php

namespace App\Providers;

use App\Models\User;
use App\Policies\AvisoPolicy;
use App\Policies\BlogCategoryPolicy;
use App\Policies\BlogCommentPolicy;
use App\Policies\BlogPostPolicy;
use App\Policies\BlogTagPolicy;
use App\View\Composers\ErpShellComposer;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Modules\Avisos\App\Models\Aviso;
use Modules\Blog\App\Models\BlogCategory;
use Modules\Blog\App\Models\BlogComment;
use Modules\Blog\App\Models\BlogPost;
use Modules\Blog\App\Models\BlogTag;
use Modules\Gateway\App\Models\GatewayPayment;
use Modules\Gateway\App\Models\GatewayProviderAccount;
use Modules\Gateway\App\Policies\GatewayPaymentPolicy;
use Modules\Gateway\App\Policies\GatewayProviderAccountPolicy;
use Modules\Igrejas\App\Events\IgrejaAtualizada;
use Modules\Notificacoes\App\Listeners\SendChurchCrmAlerts;

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
        View::composer('layouts.app', ErpShellComposer::class);

        if (class_exists(IgrejaAtualizada::class)
            && class_exists(SendChurchCrmAlerts::class)) {
            Event::listen(
                IgrejaAtualizada::class,
                SendChurchCrmAlerts::class
            );
        }

        Event::listen(PasswordReset::class, function (PasswordReset $event): void {
            $user = $event->user;
            if ($user instanceof User && $user->provisioned_at && $user->email_verified_at === null) {
                $user->forceFill(['email_verified_at' => now()])->save();
            }
        });

        // Painel /lideres já exige role `lider`. Igreja principal (`church_id`) é o vínculo operacional da diretoria.
        // Não depender só de Spatie `igrejas.jovens.provision` (contas antigas / roles sem sync quebram o fluxo de campo).
        Gate::define('igrejasProvisionYouth', function (?User $user) {
            return (bool) $user?->church_id && $user->hasRole('lider');
        });

        Gate::define('igrejasManageChurchYouth', function (?User $actor, User $target) {
            if (! $actor || ! (bool) $actor->church_id || ! $actor->hasRole('lider')) {
                return false;
            }
            if (! $target->hasRole('jovens')) {
                return false;
            }

            return (int) $target->church_id === (int) $actor->church_id;
        });

        Gate::policy(Aviso::class, AvisoPolicy::class);
        Gate::policy(BlogPost::class, BlogPostPolicy::class);
        Gate::policy(BlogCategory::class, BlogCategoryPolicy::class);
        Gate::policy(BlogTag::class, BlogTagPolicy::class);
        Gate::policy(BlogComment::class, BlogCommentPolicy::class);
        Gate::policy(GatewayProviderAccount::class, GatewayProviderAccountPolicy::class);
        Gate::policy(GatewayPayment::class, GatewayPaymentPolicy::class);

        // Configurar paginação para usar Tailwind CSS
        Paginator::defaultView('pagination::tailwind');
        Paginator::defaultSimpleView('pagination::simple-tailwind');

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
