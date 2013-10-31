
<div class="mainNews_wrapper">
    <div class="mainNews">
        <div class="bl">
        <img src="/{{ $saidebarPreviewBlock[0]['preview'] }}" width="274" height="215" alt="">
        <div></div>
        <a class="readmore" class="rm" href="{{ $saidebarPreviewBlock[0]['url'] }}">Читать полностью &gt;&gt;&gt;</a>
        </div>
        
        <div class="info">
        <p><a href="{{ $saidebarPreviewBlock[0]['url'] }}" title="{{ $saidebarPreviewBlock[0]['article_name'] }}">{{ $saidebarPreviewBlock[0]['article_name'] }}</a></p>

        <span class="date rm">({{ $saidebarPreviewBlock[0]['created_at'] }})</span>
        <hr>
        <p class="descr">{{ $saidebarPreviewBlock[0]['content'] }}</p>
        </div>
    </div>
</div>
<div class="clear"></div>
<br>

@include('banners.banner_right')

<div id="last_video">
    <div class="block_h">
        <div class="video_h"></div>
    </div>
    <div class="clear"></div>
    @foreach ($saidebarVideoNews as $videoNews)
    <div class="news_item">
        <img src="/{{ $videoNews['preview'] }}" alt="" height="90" width="120">
            
        <div class="videoText">
            <p style="margin:2px 0;" class="date">{{ $videoNews['created_at'] }}</p>
            <div class="vid"><a href="/video/{{ $videoNews['alias'] }}">{{ $videoNews['article_name'] }}</a></div>

            {{ mb_substr($videoNews['content'], 0, 50) }}...
        </div>
        <div class="clear"></div>
    </div>
    @endforeach
</div>

<div class="clear"></div>
<br>

@include('helpers.sotmarket')

<div id="last_video">
    <div class="block_h">
        <div class="popular_h"></div>
    </div>
    <div class="clear"></div>
    @foreach ($lastNews as $lastNewsElement)
    <div class="news_item">
        <img src="/{{ $lastNewsElement['preview'] }}" alt="" height="90" width="120">
            
        <div class="videoText">
            <p style="margin:2px 0;" class="date">{{ $lastNewsElement['created_at'] }}</p>
            <div class="vid"><a href="{{ $lastNewsElement['url'] }}">{{ $lastNewsElement['article_name'] }}</a></div>
            {{ $lastNewsElement['content'] }}...
        </div>
        <div class="clear"></div>
    </div>
    @endforeach
</div>

<div class="clear"></div>
<br>