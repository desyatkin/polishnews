<?
/*
|-------------------------------------------------------------------------------
| Вывод курса валют (блок в хедере справа от лого)
|-------------------------------------------------------------------------------
*/

$date = date("d/m/Y");

$codeUsd = 'R01235'; 
$codeEur = 'R01239';

$html  = '<span>$ '      . getCurrencyValue($codeUsd, $date) . '</span><br />';
$html .= '<span>&euro; ' . getCurrencyValue($codeEur, $date) . '</span>';


file_put_contents(__DIR__.'/currency_parse.php', $html);

/*
|-------------------------------------------------------------------------------
| Функция парсинга XML выдачи с сайта ЦБРФ
|-------------------------------------------------------------------------------
| принимает:
| $code         - код валюты
| $date         - дата 
|
| возвращает:
| $currValue    - курс валюты по отношению к рублю
|-------------------------------------------------------------------------------
*/
function getCurrencyValue($code, $date) {
    $xml = file_get_contents('http://www.cbr.ru/scripts/XML_daily.asp?date_req='.$date.'&ID=R01060');

    preg_match("#<Valute ID=\"" . $code . "\".*?>(.*?)</Valute>#is", $xml, $currency);
    preg_match("#<Value>(.*?)</Value>#is", $currency[1], $currValue);
    
    return $currValue[1];
}            