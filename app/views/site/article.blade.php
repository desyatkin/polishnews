@extends('site.main')

@section('content')

@include('site.top_articles')
@include('site.menu_header')
@include('banners.banner_top')

<div class="left">
    @include('helpers.sape')
    <div class="newsB">
        <h1>{{ $article[0]['article_name'] }}</h1>
        <hr>
        <span class="sp">{{ $article[0]['created_at'] }}</span> 
        <span class="sp">Рубрика: <a href="{{ $url }}">{{ $categoryName }}</a></span>

        {{-- Видео --}}
        @if(!empty($article[0]['video']))
        <div style="width:425px; margin:15px auto 0 auto;">
            <object width="425" height="350">
                <param name="movie" value="http://www.youtube.com/v/{{ $article[0]['video'] }}" />
                </param>
                <embed src="http://www.youtube.com/v/{{ $article[0]['video'] }}" type="application/x-shockwave-flash" width="425" height="350"></embed>
            </object>
        </div>
        @endif


        <p>
        @if(!empty($article[0]['preview']))
            <img width="240" height="180" src="/{{ $article[0]['preview'] }}" alt="">
        @endif
        </p>
        
        {{ $article[0]['content'] }}

        <script type="text/javascript" src="//yandex.st/share/share.js" charset="utf-8"></script>
        <div class="yashare-auto-init" data-yashareL10n="ru" data-yashareType="none" data-yashareQuickServices="yaru,vkontakte,facebook,twitter"></div>
    </div>

    <h2>Новости по теме:</h2>
    <div style="margin-top:5px;" id="lastnews">
    @foreach($newsInCtaegory as $news)
        <div class="news_item">
            <div>
                <p style="margin-top:-8px;">
                <spna class="date">{{ $news['created_at'] }} 
                    <span class="vid">
                        <a href="{{ $url }}{{ $news['alias'] }}/">{{ $news['article_name'] }}</a>
                    </span>
                </spna>
                </p>
            </div>
            <div class="clear"></div>
        </div>
    @endforeach
    </div>
</div>

<div class="right">
    @include('site.sidebar')
</div>
<div class="clear"></div>
@endsection