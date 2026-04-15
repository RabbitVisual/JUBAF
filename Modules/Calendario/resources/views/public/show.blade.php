@extends('homepage::layouts.homepage')

@section('title')
{{ $event->title }} — {{ \App\Support\SiteBranding::siteName() }}
@endsection

@section('content')
@include('homepage::layouts.navbar-homepage')

<div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-teal-50/30 dark:from-slate-900 dark:via-slate-900 dark:to-slate-950 py-12 md:py-16">
    <div class="container mx-auto max-w-3xl px-4">
        <a href="{{ route('eventos.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-teal-700 transition hover:gap-2.5 dark:text-teal-400">
            <x-icon name="arrow-left" class="h-4 w-4" style="duotone" />
            Todos os eventos
        </a>

        @include('calendario::public.themes.'.$theme, ['event' => $event, 'isPreview' => $isPreview ?? false])
    </div>
</div>

@include('homepage::layouts.footer-homepage')
@endsection
