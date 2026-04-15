<?php

namespace Modules\Igrejas\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Igrejas\App\Models\Church;

class PublicChurchController extends Controller
{
    public function index()
    {
        $churches = Church::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->paginate(36, ['id', 'name', 'slug', 'city', 'phone', 'email']);

        return view('igrejas::public.index', compact('churches'));
    }
}
