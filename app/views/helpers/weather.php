<?
/*
|-------------------------------------------------------------------------------
| Вывод  погоды в Варшаве на текущий момент времени
| (блок в хедере справа от лого)
|-------------------------------------------------------------------------------
*/
file_put_contents(__DIR__.'/weather_parse.php', weather());

/*
|-------------------------------------------------------------------------------
| Функция парсит XML выдачу gismeteo.ru и формирует блок прогноза погоды
|-------------------------------------------------------------------------------
| Vars:
| $imgs(array)      - массив ссылок на иконки погодных явлений 
| $imgId[1]         - id элемента из массива imgs 
|                    (определяет иконку которая будет отображаться)
| $xml              - xml выдача гисметео
| $weatherArray[1]  - содержимое первого блока "FORECAST" из выдачи
| $temperature[1]   - температура max
| $temperature[2]   - температура min
|
| Выводит:
| $weatherBlock     - сформированый блок "Погода" для отображения на сайте
|
|-------------------------------------------------------------------------------
*/
function weather(){

    $imgs[0]    = "/images/diz_elements/weather/sunly.png";
    $imgs[1]    = "/images/diz_elements/weather/little_cloudy.png";
    $imgs[2]    = "/images/diz_elements/weather/cloudy.png";
    $imgs[3]    = "/images/diz_elements/weather/overcast.png";
    $imgs[4]    = "/images/diz_elements/weather/rain.png";
    $imgs[5]    = "/images/diz_elements/weather/big_rain.png";
    $imgs[6]    = "/images/diz_elements/weather/snow.png";
    $imgs[7]    = "/images/diz_elements/weather/snow.png";
    $imgs[8]    = "/images/diz_elements/weather/thunderstorm.png";
    $imgs[9]    = "/images/diz_elements/weather/none.png";
    $imgs[10]   = "/images/diz_elements/weather/none.png";

    $imgId = '';

    $xml = file_get_contents('http://informer.gismeteo.ru/xml/12375_1.xml');
             

    // Забираем первый блок "FORECAST" из XML выдачи
    // он соттветствует прогнозу максимально приближеному к настоящему времени
    // (проще говоря прогноз на сейчас =) 
    preg_match("#<FORECAST .*?>(.*?)</FORECAST>#is", $xml, $weatherArray);

    // Забираем значение свойства "cloudiness" (оно одно на весь блок)
    // Определяет какая картинка бутет отображаться в блоке (солнышко, тучка, etc...)
    preg_match("#cloudiness=\"(.*?)\"#is", $weatherArray[1], $imgId);

    // Забираем свойства "max" и "min" тега "TEMPERATURE" 
    preg_match("#<TEMPERATURE max=\"(.*?)\" min=\"(.*?)\"/>#is", $weatherArray[1], $temperature);

    if($temperature[1] > 0) $temperature[1] = "+" .  $temperature[1];
    if($temperature[2] > 0) $temperature[2] = "+" .  $temperature[2];

    // Формируем блок
    $weatherBlock = '<div class="weather_bl">' 
                  . '   <img id="weather1" alt="" src="' . $imgs[$imgId[1]] . '">'
                  . '</div>' 
                  . $temperature[2] . '...' . $temperature[1] . ' °C';

    // Вывод
    return $weatherBlock;
}