{{-- Блок анонсов статей(случайных) в шапке --}}
<div class="mainblock">
    <div class="featured">

        <ul class="ui-tabs-nav">

            <li class="ui-tabs-nav-item">
                <img src="/{{ $randomArticles[0]['preview']}}" width="79" height="61" alt="">
                <div class="ui-txt">
                    <a href="{{ $randomArticles[0]['url']}}"><span>{{ $randomArticles[0]['article_name'] }}</span></a>
                    {{ $randomArticles[0]['content'] }} <br>
                    <span class="date">({{ $randomArticles[0]['created_at'] }})</span>
                </div>
                <div class="clear"></div>
            </li>

            <li class="ui-tabs-nav-item">
                <img src="/{{ $randomArticles[1]['preview']}}" width="79" height="61" alt="">
                <div class="ui-txt">
                    <a href="{{ $randomArticles[1]['url']}}"><span>{{ $randomArticles[1]['article_name'] }}</span></a>
                    {{ $randomArticles[1]['content'] }} <br>
                    <span class="date">({{ $randomArticles[1]['created_at'] }})</span>
                </div>
                <div class="clear"></div>
            </li>

            <li class="last">
                <img src="/{{ $randomArticles[2]['preview']}}" width="79" height="61" alt="">
                <div class="ui-txt">
                    <a href="{{ $randomArticles[2]['url']}}"><span>{{ $randomArticles[2]['article_name'] }}</span></a>
                    {{ $randomArticles[2]['content'] }} <br>
                    <span class="date">({{ $randomArticles[2]['created_at'] }})</span>
                </div>
                <div class="clear"></div>
            </li>

        </ul>

        <div id="fragment-1" class="ui-tabs-panel" style="float:left">
            <img src="/{{ $randomArticles[3]['preview']}}" width="273" height="215" alt="">
            <div class="best"></div>
            <div class="info">
                <p><a href="{{ $randomArticles[3]['url']}}">{{ $randomArticles[3]['article_name'] }}</a></p>
                <span class="date">({{ $randomArticles[3]['created_at'] }})</span>

                <hr>
                <p class="descr">{{ $randomArticles[3]['content'] }}</p>
                <a class="readmore" href="{{ $randomArticles[2]['url']}}">Читать полностью &gt;&gt;&gt;</a>
            </div>
        </div>
    </div>
</div>
<div class="clear"></div>