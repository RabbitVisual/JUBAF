<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
    <channel>
        <title>Blog JUBAF</title>
        <description>Notícias e comunicação institucional da {{ \App\Support\SiteBranding::siteName() }} — {{ \App\Support\SiteBranding::siteTagline() }}</description>
        <link>{{ url('/') }}</link>
        <atom:link href="{{ route('blog.rss') }}" rel="self" type="application/rss+xml" />
        <language>pt-BR</language>
        <lastBuildDate>{{ now()->toRfc2822String() }}</lastBuildDate>
        <generator>JUBAF Blog</generator>

        @foreach($posts as $post)
        <item>
            <title>{{ htmlspecialchars($post->title) }}</title>
            <description><![CDATA[
                @if($post->excerpt)
                    {!! htmlspecialchars($post->excerpt) !!}
                @else
                    {!! htmlspecialchars(Str::limit(strip_tags($post->content), 300)) !!}
                @endif
            ]]></description>
            <link>{{ route('blog.show', $post->slug) }}</link>
            <guid isPermaLink="true">{{ route('blog.show', $post->slug) }}</guid>
            <pubDate>{{ $post->published_at->toRfc2822String() }}</pubDate>
            @if($post->author)
            <author>{{ htmlspecialchars($post->author->email) }} ({{ htmlspecialchars($post->author->name) }})</author>
            @endif
            <category>{{ htmlspecialchars($post->category->name) }}</category>
        </item>
        @endforeach
    </channel>
</rss>
