@extends('site.main')

@section('content')

@include('site.top_articles')
@include('site.menu_header')
@include('banners.banner_top')

<div class="left">
    @include('helpers.sape')
    <div id="lastnews" class = "greyBG">
        <h1>{{ $categoryName }}</h1>
        @foreach ($articles as $article)
        <div class="news_item">
            <img src="/{{ $article['preview'] }}" width="150" height="115" alt="{{ $article['article_name'] }}">
            <div>
                <span class="vid"><a href="{{ $url }}{{ $article['alias'] }}">{{ $article['article_name'] }}</a></span>
                <p class="date">{{ $article['created_at'] }}</p>
                <noindex>{{ mb_substr($article['content'], 0, 180) }}...</noindex>
            </div>

            <div class="clear"></div>
        </div>
        @endforeach
        <div class="clear"></div>
        @include('site.pagination_links')
    </div>
</div>

<div class="right">
    @include('site.sidebar')
</div>
<div class="clear"></div>
@endsection