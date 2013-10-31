@extends('site.main')

@section('content')

@include('site.top_articles')
@include('site.menu_header')
@include('banners.banner_top')

<div class="videonews">
    <div class="video_news_img"></div>
    @foreach ($videoNews as $news)
        <div class="videoblock ">
            <img src="/{{ $news['preview'] }}" width="154" height="114" alt="Польский режиссер отстаивает однополую любовь в Берлине">
            <div>
                <span class="date"><nobr>{{ $news['created_at'] }}</nobr></span><br>
                <span class="vid"><a href="/video/{{ $news['alias'] }}" title="{{ $news['article_name'] }}">{{ $news['article_name'] }}</a></span>
                {{ mb_substr($news['content'], 0, 120) }}...
            </div>
            <div class="clear"></div>
        </div>
    @endforeach
<div class="clear"></div>
</div>

@include('helpers.sape')

@foreach($previewBlocks as $previewBlock)
<div class="news_block ">
    <div class="news_block_h">
    <div class="{{$previewBlock['category_alias']}}"></div>
    <span><a href="/news/{{ $previewBlock['category_alias'] }}/">Все новости раздела</a></span>
    </div>  
    @foreach ($previewBlock['articles'] as $key => $articles) 
    @if($key == 0)       
    <div class="news_pre min_height">
        <img src="/{{ $articles['preview'] }}" width="136" height="100" alt="{{ $articles['article_name'] }}">
        <span class="date"><nobr>{{ $articles['created_at'] }}</nobr></span><br>
        <span class="vid">
            <a href="/news/{{ $previewBlock['category_alias'] }}/{{ $articles['alias'] }}/" title="{{ $articles['article_name'] }}">
                {{ $articles['article_name'] }}
            </a>
        </span>
        {{ mb_substr($articles['content'], 0, 120) }}...
    </div>  
    @else
    <div class="news_pre ">             
        <span class="date"><nobr>{{ $articles['created_at'] }}</nobr></span><br>
        <span class="vid">
            <a href="/news/{{ $previewBlock['category_alias'] }}/{{ $articles['alias'] }}/" title="{{ $articles['article_name'] }}">
                {{ $articles['article_name'] }}
            </a>
        </span>
        {{ mb_substr($articles['content'], 0, 130) }}...
    </div>  
    @endif
    @endforeach 
</div>
@endforeach

<div class='clear'></div>

@endsection