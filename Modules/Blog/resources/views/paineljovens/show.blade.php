@extends('paineljovens::layouts.jovens')

@section('title', $post->title)


@section('jovens_content')
    <x-ui.jovens::page-shell class="max-w-3xl">
        @include('blog::public.partials.panel-post-read', [
            'post' => $post,
            'publicUrl' => route('blog.show', $post->slug),
        ])
    </x-ui.jovens::page-shell>
@endsection
