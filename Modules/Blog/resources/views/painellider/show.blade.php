@extends('painellider::components.layouts.app')

@section('title', $post->title)

@section('breadcrumbs')
    <x-icon name="chevron-right" class="w-3 h-3 shrink-0 opacity-50" />
    <a href="{{ route('lideres.blog.index') }}" class="text-slate-500 hover:text-emerald-600">Blog</a>
    <x-icon name="chevron-right" class="w-3 h-3 shrink-0 opacity-50" />
    <span class="text-emerald-600 dark:text-emerald-400 line-clamp-1">{{ Str::limit($post->title, 48) }}</span>
@endsection

@section('content')
    @include('blog::public.partials.panel-post-read', [
        'post' => $post,
        'publicUrl' => route('blog.show', $post->slug),
    ])
@endsection
