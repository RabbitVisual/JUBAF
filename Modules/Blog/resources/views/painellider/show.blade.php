@extends('painellider::layouts.lideres')

@section('title', $post->title)

@section('breadcrumbs')
    <x-icon name="chevron-right" class="w-3 h-3 shrink-0 opacity-50" />
    <a href="{{ route('lideres.blog.index') }}" class="text-slate-500 hover:text-emerald-600">Blog</a>
    <x-icon name="chevron-right" class="w-3 h-3 shrink-0 opacity-50" />
    <span class="line-clamp-1 text-emerald-600 dark:text-emerald-400">{{ Str::limit($post->title, 48) }}</span>
@endsection

@section('lideres_content')
<x-ui.lideres::page-shell class="max-w-3xl">
    @include('blog::public.partials.panel-post-read', [
        'post' => $post,
        'publicUrl' => route('blog.show', $post->slug),
    ])
</x-ui.lideres::page-shell>
@endsection
