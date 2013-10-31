{{-- Шапка (самый верхний блок) --}}
<div class="header">
    {{-- Лого --}}
    <div class="logo">
        <img src="/images/logo.png">
    </div>

    {{-- Сегодняшняя дата, Курс доллара евро, Погода --}}
    <div class="weather">
        <span>
            @include('helpers.todate')
        </span><br />
        <div class="weather_left">
            @include('helpers.currency')
        </div>
        <div class="weather_right">
            <span>Сейчас в Варшаве</span><br />
            <span class="gradus">
                @include('helpers.weather')
            </span>
        </div>
    </div>

    {{-- Информеры --}}
    <div class="infoblock">
        <ul>
            <li id="yandex"><a href="http://www.yandex.ru/?add=72023&from=promocode">Я.Виджет</a></li>
            <li id="informer"><a href="/pages/informer/">Информер</a></li>
            <li id="twit"><a href="http://twitter.com/#!/PolishNews_ru">Twitter</a></li>
        </ul>
        <ul>
            <li id="rss"><a href="/rss.php">RSS</a></li>
            <li id="info"><a href="/pages/informacija-o-strane/">Информация о стране</a></li>
            <li id="turistu"><a href="/pages/turistu/">Туристу</a></li>
        </ul>
    </div>

    {{-- Поиск --}}
    <div class="search">
        <form id="search" action="/service/poisk/" method="get">
            <input type="text" name="search" class="searchInput" size="25" placeholder="Поиск">
            <input type="submit" name="addComments" value="" class="searchButton" />
        </form>
    </div>
</div>
