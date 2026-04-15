<?php

namespace Modules\Homepage\App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\HomepageNewsletterCampaignMail;
use App\Models\HomepageNewsletterSubscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Modules\Homepage\App\Support\HomepageNewsletterTemplates;

class HomepageNewsletterAdminController extends Controller
{
    public function index()
    {
        $subscribers = HomepageNewsletterSubscriber::query()
            ->orderByDesc('created_at')
            ->paginate(30);

        $newsletterStats = [
            'total' => HomepageNewsletterSubscriber::query()->count(),
            'active' => HomepageNewsletterSubscriber::query()->activeList()->count(),
        ];

        return view('homepage::admin.homepage.newsletter.index', compact('subscribers', 'newsletterStats'));
    }

    public function create()
    {
        return view('homepage::admin.homepage.newsletter.compose', [
            'newsletterTemplates' => HomepageNewsletterTemplates::definitions(),
            'newsletterTokenLabels' => HomepageNewsletterTemplates::tokenLabels(),
        ]);
    }

    public function send(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'content' => 'required|string|max:100000',
        ]);

        $html = HomepageNewsletterTemplates::applyTokens($validated['content']);

        $recipients = HomepageNewsletterSubscriber::query()->activeList()->get();
        if ($recipients->isEmpty()) {
            return redirect()
                ->to(homepage_panel_route('newsletter.create'))
                ->withErrors(['content' => 'Não há assinantes ativos para enviar.']);
        }

        $sent = 0;
        $failed = 0;
        foreach ($recipients as $subscriber) {
            try {
                Mail::to($subscriber->email)->send(
                    new HomepageNewsletterCampaignMail($validated['subject'], $html)
                );
                $sent++;
            } catch (\Throwable $e) {
                $failed++;
                Log::warning('homepage.newsletter.send_failed', [
                    'email' => $subscriber->email,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        $msg = "Envio concluído: {$sent} e-mail(s) enviados.";
        if ($failed > 0) {
            $msg .= " Falhas: {$failed} (verifique o log e as configurações de e-mail).";
        }

        return redirect()
            ->to(homepage_panel_route('newsletter.index'))
            ->with('success', $msg);
    }

    public function destroy(int $id)
    {
        $subscriber = HomepageNewsletterSubscriber::query()->findOrFail($id);
        $subscriber->delete();

        return redirect()
            ->to(homepage_panel_route('newsletter.index'))
            ->with('success', 'Assinante removido.');
    }
}
