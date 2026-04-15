<?php

namespace Modules\Homepage\App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomepageContactMessage;
use Illuminate\Http\Request;

class HomepageContactAdminController extends Controller
{
    public function index()
    {
        $messages = HomepageContactMessage::query()
            ->orderByDesc('created_at')
            ->paginate(20);

        $contactStats = [
            'total' => HomepageContactMessage::query()->count(),
            'unread' => HomepageContactMessage::query()->whereNull('read_at')->count(),
        ];

        return view('homepage::admin.homepage.contacts.index', compact('messages', 'contactStats'));
    }

    public function show(int $id)
    {
        $message = HomepageContactMessage::query()->findOrFail($id);
        if ($message->read_at === null) {
            $message->update(['read_at' => now()]);
        }

        return view('homepage::admin.homepage.contacts.show', compact('message'));
    }

    public function destroy(int $id)
    {
        $message = HomepageContactMessage::query()->findOrFail($id);
        $message->delete();

        return redirect()
            ->to(homepage_panel_route('contacts.index'))
            ->with('success', 'Mensagem removida.');
    }
}
