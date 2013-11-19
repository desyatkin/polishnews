<?php
/*
|-------------------------------------------------------------------------------
| Контроллер управлющий выводом контента на сайт
|-------------------------------------------------------------------------------
| NB: Хочется следуя принципу DRY разметать эти три здоровых функции, 
| минимум на 8 маленьких.
| Но для таких манипуляций сейчас пожалуй позновато.
| 
| Необходимый функционал воспроизведен полностью (по крайней мере я так думаю)
|
| Не сделано: 
|   404-я ошибка (там сейчас заглушка)
|   Отсутствуют комментарии к некотороым функциям
|-------------------------------------------------------------------------------
|
| Фуннкции:
|   основные (отвечают за вывод контента):
|   getShowIndex()                                  - формирует главную страницу
|   getShowCategory($category, $subcategory)        - формирует страницу категории
|   getShowArticle($category, $subcategory, $alias) - формирует страницу статьи
|
|   вспомогательные (повторяющиеся операции, вывод ошибок, etc...):
|   getVideoNews($limit)                            - последние статьи в категории "Видео Новости"
|
|   blockArticles($limit, $order)                   - несколько случайных или последних статей 
|                                                     (безотносительно категории)
|
|   articleFromCategory($categoryId, $subcategoryId, $limit) - несколько последних
|                                                   статей из конкретной категории
|   error404() - ошибка 404 NotFound
|
| ВАЖНО!!! Все переменные передающиеся во view являются обязательными! 
| Удалите что-то и удачного вам дебагинга...
| Прежде чем удалять их от сюда необходимо убрать их в соответствующем шаблоне 
| в папке /app/views/
| 
| site.index:
|   randomArticles          - 4 случайных статьи для блока в шапке
|   videoNews               - 2 случайных статьи для блока "Видео Новости"
|   previewBlocks           - Массив с блоками статей для анонсов по категориям
|
| site.category
|   url                     - ссылка на категориию 
|                             (идет как префикс к ссылкам на статьи)
|   randomArticles          - 4 случайных статьи для блока в шапке
|   saidebarPreviewBlock    - 1 случайная статья для блока в сайдбаре
|   saidebarVideoNews       - 2 последних статьи для блока "ВидеоНовости" в сайдбаре
|   lastNews                - 4 последних статьи (для блока в сайдбаре)
|   categoryName            - имя категории
|   articles                - массив статей
|   pagination              - данные для пагинации
|
| site.article
|   randomArticles          - 4 случайных статьи для блока в шапке
|   categoryName            - имя категории
|   url                     - ссылка на категориию 
|   saidebarPreviewBlock    - 1 случайная статья для блока в сайдбаре
|   saidebarVideoNews       - 2 последних статьи для блока "ВидеоНовости" в сайдбаре
|   lastNews                - 4 последних статьи (для блока в сайдбаре)
|   article                 - массив со статьей
|   newsInCtaegory          - 3 последних новости в категории
|-------------------------------------------------------------------------------
*/
class SiteController extends \BaseController {
    //------------------------------------------------------------------------------
    // Основные методы
    //------------------------------------------------------------------------------
    /*
    |-------------------------------------------------------------------------------
    | Показывает главную страницу сайта
    |-------------------------------------------------------------------------------
    | возвращает:
    |   $view - сформированная страница
    |
    | дополнительные функции:
    |   blockArticles($limit)  - возвращает массив случайных статей 
    |                            для блока анонсов в шапке
    |   getVideoNews($limit)   - возвращает массив последних статей
    |                            в категории "Видео Новости"
    | переменные:
    |   $articles         - массив случайных статей
    |   $previewVideoNews - массив статей для блока анонсов категории "ВидеоНовости"
    |   $otherCategories  - массив подкатегорий (все категории кроме корневых)
    |   $previewBlocks    - массив данных для блоков анонсов по категориям
    |-------------------------------------------------------------------------------
    */
    public function getShowIndex() {

        // Получаем 4 случайных статьи для блока в шапке
        $articles = $this->blockArticles(4);

        // Получаем 2 последних статьи для блока анонсов категории "ВидеоНовости"
        $previewVideoNews = $this->getVideoNews(2);

        // Получаем массив всех оставшихся категорий
        // (все категории кроме news и video)
        $otherCategories = Categories::select('id', 'alias')
                                     ->where('parent_id', '!=', 0)
                                     ->get()
                                     ->toArray();
        
        $i = 0;
        $previewBlocks = array();

        // перебираем массив категории 
        // и для каждого элемента берем по 3 последних статьи.
        // Формируем новый массив previewBlocks содержащий псевдонимы категорий(alias)
        // и выбранные из них статьи
        foreach ($otherCategories as $category) {
            $previewArticles = Articles::where('subcategory_id', '=', $category['id'])
                                       ->limit(3)
                                       ->orderBy('id', 'DESC')
                                       ->get()
                                       ->toArray();

            $previewBlocks[$i]['category_alias'] = $category['alias'];
            $previewBlocks[$i]['articles']       = $previewArticles;
            $i++;
        }

        // отправляем все переменные во view
        $view = View::make('site.index')
                    ->with('randomArticles', $articles)
                    ->with('videoNews', $previewVideoNews)
                    ->with('previewBlocks', $previewBlocks);

        // Возвращаем сформированную страницу
        return $view;
    }

    /*
    |-------------------------------------------------------------------------------
    | Показывает категорию
    |-------------------------------------------------------------------------------
    | принимает:
    |   $category   - псевдоним (alias) категории
    | возвращает:
    |   $view       - сформированная страница
    |
    | дополнительные функции:
    |   blockArticles($limit) - возвращает массив случайных статей 
    |                           для блока анонсов в шапке
    |   getVideoNews($limit)  - возвращает массив последних статей
    |                           в категории "Видео Новости"
    |   error404()            - выдает ошибку 404 not found
    |
    | переменные:
    |   $categoryArray         - массив содержащий id категории и её имя
    |   $articlesAndPagination - массив содержащий блок статей из категории 
    |                            и данные необходимые для организации пагинации
    |   $articles              - часть массива articlesAndPagination содержащая
    |                            только блок статей
    |   $pagination            - часть массива articlesAndPagination содержащая
    |                            только данные для пагинации
    |   $topBlockArticles      - случайные статьи для блока в шапке
    |   $saidebarPreviewBlock  - случайная статья для блока в сайдбаре
    |   $previewVideoNews      - последние статьи из категории "Видео Новости"
    |-------------------------------------------------------------------------------
    */
    public function getShowCategory($category, $subcategory = false) {

        // ишем в базе корневую категорию по её псевдониму (alias)
        $categoryArray = Categories::select('id', 'category_name')
                                   ->where('alias', '=', $category)
                                   ->where('parent_id', '=', 0)
                                   ->get()
                                   ->toArray();


        // если категории нет -> 404
        if (empty($categoryArray)) 
            $this->error404();
        
        $categoryName = $categoryArray[0]['category_name'];

        // если указана подкатегория (subcategory != false)
        if ($subcategory) {
            // пытаемся получить категорию с псевдонином $subcategory 
            // id родителя равным id корневой категории
            $subcategoryArray = Categories::select('id', 'category_name')
                                          ->where('alias', '=', $subcategory)
                                          ->where('parent_id', '=', $categoryArray[0]['id'])
                                          ->get()
                                          ->toArray();
            
            // если такой категории не существует, считаем что в $subcategory 
            // псевдоним статьи из корневой категории и вызываем метод getShowArticle
            if (empty($subcategoryArray)) {
                return $this->getShowArticle($category, false, $subcategory);
            }
            
            // если категория существует переменной subcategoryId присваивается значение id подкатегории
            // и переменная categoryName меняется на имя подкатегории
            $subcategoryId = $subcategoryArray[0]['id'];
            $categoryName  = $subcategoryArray[0]['category_name'];
        } else {
            // если подкатегория не указана subcategoryId присваивается 0
            $subcategoryId = 0;
        }
        
        // ищем статьи из данной категории (по id категории с условием что id подкатегории равно 0)
        // (метод paginate добавит к полученному массиву элементы позволяющие легко построить пагинацию)
        $articlesAndPagination = Articles::where('category_id', '=', $categoryArray[0]['id'])
                                         ->where('subcategory_id', '=', $subcategoryId)
                                         ->orderBy('id', 'DESC')
                                         ->paginate(15)
                                         ->toArray();

        // если статей нет -> 404
        if (empty($articlesAndPagination['data'])) 
            $this->error404();

        // забираем из массива элемент со статьями (он последний)
        // оставшиеся элементы(данные для пагинации) для удобства восприятия 
        // отправляем в массив $pagination
        $articles               = array_pop($articlesAndPagination);
        $pagination             = $articlesAndPagination;

        // берем 4 случайных статьи для блока в шапке
        $topBlockArticles       = $this->blockArticles(4);
        // берем 1 случайную статью для блока в сайдбаре
        $saidebarPreviewBlock   = $this->blockArticles(1);
        // берем 2 последних статьи для блока анонсов категории "ВидеоНовости" в сайдбаре
        $previewVideoNews       = $this->getVideoNews(2);
        // берем 4 последних статьи(безотносительно категории) для блока в сайдбаре
        $lastNews               = $this->blockArticles(4, 'id DESC');

        // собираем link который будет префиксом для всех ссылок на статьи в данной категории
        $url = '/' . $category . '/';
        if ($subcategory) $url .= $subcategory . '/';

        // отправляем все переменные во view
        $view = View::make('site.category')
                    ->with('url'                    , $url)
                    ->with('randomArticles'         , $topBlockArticles)
                    ->with('saidebarPreviewBlock'   , $saidebarPreviewBlock)
                    ->with('saidebarVideoNews'      , $previewVideoNews)
                    ->with('lastNews'               , $lastNews)
                    ->with('categoryName'           , $categoryName)
                    ->with('articles'               , $articles)
                    ->with('pagination'             , $pagination);

        // возвращаем сформированную страницу
        return $view;
    }


    /*
    |-------------------------------------------------------------------------------
    | Показывает статью
    |-------------------------------------------------------------------------------
    | принимает:
    |   $category           - псевдоним категории
    |   $subcategory        - псевдоним подкатегории
    |   $alias              - псевдоним статьи
    | возвращает:
    |   $view               - сформированная страница
    | дополнительные функции: 
    |   blockArticles($limit) - возвращает массив случайных статей 
    |                           для блока анонсов в шапке
    |   getVideoNews($limit)  - возвращает массив последних статей
    |                           в категории "Видео Новости"
    |   error404()            - выдает ошибку 404 not found
    |   articleFromCategory   - берет несколько произвольных статей
    |                           из заданной категории
    |-------------------------------------------------------------------------------
    */
    public function getShowArticle($category, $subcategory, $alias) {  
        // ишем в базе корневую категорию по её псевдониму (alias)
        $categoryArray = Categories::select('id', 'category_name')
                                   ->where('alias', '=', $category)
                                   ->where('parent_id', '=', 0)
                                   ->get()
                                   ->toArray();
        // если категории нет -> 404
        if (empty($categoryArray)) 
            $this->error404();

        $categoryId   = $categoryArray[0]['id'];
        $categoryName = $categoryArray[0]['category_name'];

        // если указана подкатегория (subcategory != false)
        if ($subcategory) {
            // пытаемся получить категорию с псевдонином $subcategory 
            // id родителя равным id корневой категории
            $subcategoryArray = Categories::select('id', 'category_name')
                                          ->where('alias', '=', $subcategory)
                                          ->where('parent_id', '=', $categoryId)
                                          ->get()
                                          ->toArray();
            
            // если такой категории не существует, считаем что пользователь сошел с ума
            // и выдаем 404
            if (empty($subcategoryArray)) 
                $this->error404;

            // если категория существует переменной subcategoryId присваивается значение id подкатегории
            // и переменная categoryName меняется на имя подкатегории
            $subcategoryId = $subcategoryArray[0]['id'];
            $categoryName  = $subcategoryArray[0]['category_name'];
        } else {
            // если подкатегория не указана subcategoryId присваивается 0
            $subcategoryId = 0;
        }

        // ищем статью из данной категории/подкатегории
        $article = Articles::where('alias', '=', $alias)
                           ->where('category_id', '=', $categoryId)
                           ->where('subcategory_id', '=', $subcategoryId)
                           ->orderBy('id', 'DESC')
                           ->get()
                           ->toArray();

        
        // если статьи нет -> 404
        if (empty($article)) 
            $this->error404();

        // берем 4 случайных статьи для блока в шапке
        $topBlockArticles       = $this->blockArticles(4);
        // берем 1 случайную статью для блока в сайдбаре
        $saidebarPreviewBlock   = $this->blockArticles(1);
        // берем 2 последних статьи для блока анонсов категории "ВидеоНовости" в сайдбаре
        $previewVideoNews       = $this->getVideoNews(2);
        // берем 4 последних статьи(безотносительно категории) для блока в сайдбаре
        $lastNews               = $this->blockArticles(4, 'id DESC');
        // берем 3 случайных статьи из текущей катигории
        $newsInCtaegory         = $this->articleFromCategory($categoryId, $subcategoryId, 3);

        // собираем ссылку на категорию в которой находится статья
        $url = '/' . $category . '/';
        if ($subcategory) $url .= $subcategory . '/';

        // отправляем все переменные во view
        $view = View::make('site.article')
                    ->with('randomArticles'         , $topBlockArticles)
                    ->with('categoryName'           , $categoryName)
                    ->with('url'                    , $url)
                    ->with('saidebarPreviewBlock'   , $saidebarPreviewBlock)
                    ->with('saidebarVideoNews'      , $previewVideoNews)
                    ->with('lastNews'               , $lastNews)
                    ->with('article'                , $article)
                    ->with('newsInCtaegory'         , $newsInCtaegory);

        // возвращаем сформированную страницу
        return $view;
    }


    //------------------------------------------------------------------------------
    // Вспомогательные методы
    //------------------------------------------------------------------------------
    /*
    |-------------------------------------------------------------------------------
    | Выбрать последние статьи для блока "ВидеоНовости"
    |-------------------------------------------------------------------------------
    | принимает:
    |   $limit    - количество статей
    | возвращает:
    |   $articles - массив статей из категории "ВидеоНовости"
    |-------------------------------------------------------------------------------
    */
    public function getVideoNews($limit){
        $articles = Articles::where('category_id', '=', 18)
                            ->limit($limit)
                            ->orderBy('id', 'DESC')
                            ->get()
                            ->toArray();
        return $articles;
    }

    /*
    |-------------------------------------------------------------------------------
    | Ошибка 404 not found (не закончена... Даже не начата. Всего лишь заглушка)
    |-------------------------------------------------------------------------------
    */
    public function error404(){
        echo '<center><div style="font-size:70px; margin: 10% 0;">404<br> Not Found</div></center>';
        die();
    }

    /*
    |-------------------------------------------------------------------------------
    | Выбрать случайные или последние статьи для блока анонсов в шапке
    |-------------------------------------------------------------------------------
    | принимает:
    |   $limit    - количество статей
    |   $order    - сортировка (случайные или последние)
    | возвращает:
    |   $articles - переработанный массив статей
    |-------------------------------------------------------------------------------
    */
    public function blockArticles($limit, $order = 'RAND()'){
        // Берем несколько случайных или последних статей
        //(количество статей определяется переменной limit)
        $articles =  Articles::select('id', 'category_id', 'subcategory_id', 'alias', 'article_name', 'content', 'preview', 'created_at')
                             ->whereRaw('`id`>0 order by ' . $order)
                             ->limit($limit)
                             ->get()
                             ->toArray();

        // Изменяем-дополняем полученый массив
        foreach ($articles as &$element) {
            // Собираем ссылку на статью и добавляем её в массив
            // Добавляем к ссылке alias категории
            $url =  '/' . implode(Categories::select('alias')->find($element['category_id'])->toArray()) . '/';
            
            // Если статья находится не в корневой категории то добавляем alias подкатегории к ссылке
            $subcategory_alias = Categories::select('alias')->find($element['subcategory_id']);
            if (!is_null($subcategory_alias)) $url .= implode($subcategory_alias->toArray()) . '/';
            
            // Добавляем к ссылке alias статьи          
            $url .= $element['alias'];

            // Изменяем исходный массив (добавляем элемент с ключем "url")
            $element['url'] = $url;
            
            // Обрезаем текст статьи для анонса
            $element['content'] = mb_substr($element['content'], 0, 110) . '...';

            // Обрезаем слишком длинные заголовки 
            //(заголовки должны умещаться в одну строку иначе ломается верстка)
            if (mb_strlen($element['article_name']) > 55)
                $element['article_name'] = mb_substr($element['article_name'], 0, 55) . '...';
        }

        // возвращаем обработанный массив статей
        return $articles;
    }

    /*
    |-------------------------------------------------------------------------------
    | Выбрать последние статьи из категории
    |-------------------------------------------------------------------------------
    | принимает:
    |   $categoryId     - id категории
    |   $subcategoryId  - id подкатегории
    |   $limit          - количество статей
    | возвращает:
    |   articles        - массив со статьями
    |-------------------------------------------------------------------------------
    */
    public function articleFromCategory($categoryId, $subcategoryId, $limit) {
        $articles = Articles::whereRaw('category_id = ' . $categoryId . ' AND subcategory_id = ' . $subcategoryId . ' order by RAND()')
                           ->limit($limit)
                           ->get()
                           ->toArray();

        return $articles;
    }

    /*
    |-------------------------------------------------------------------------------
    | Generate article url from id
    |-------------------------------------------------------------------------------
    */
    public function getArticleURL ($id) {

        //get article
        $article = Articles::find($id);

        // compose url
        $url = 'http://polishnews.ru/' . $article->category->alias;
        if( isset($article->subcategory->alias) ) $url .= '/'. $article->subcategory->alias;
        $url .= '/'. $article->alias;

        return $url;

    }

    /*
    |-------------------------------------------------------------------------------
    | RSS для яндекс новостей
    |-------------------------------------------------------------------------------
    */
    public function getRSS() {

        header("Content-Type:	application/rss+xml");


        // description protocol, open xml document
        echo '<?xml version="1.0" encoding="utf-8"?>
        <rss version="2.0" xmlns="http://backend.userland.com/rss2" xmlns:yandex="http://news.yandex.ru">
        <channel>
        <title>Polish News — новости Польши каждый день</title>
        <link>http://polishnews.ru/</link>
        <description>Актуальные новости и интересные статьи.</description>
        <image>
        <url>http://polishnews.ru/images/diz_elements/logo.gif</url>
        <title>Polish News — новости Польши каждый день</title>
        <link>http://polishnews.ru/</link>
        </image>';


        // get 20 last articles
        $articles = Articles::orderBy('created_at', 'desc')->limit(20)->get();

        foreach($articles as $article) {

            echo '<item>';
            echo '<title>'. $article->article_name .'</title>';
            echo '<link>'. $this->getArticleURL($article->id) .'</link>';
            echo '<category>Разное</category>';
            echo '<enclosure url="http://polishnews.ru/userfiles/'. $article->preview .'" type="image/jpeg"/>';
            echo '<pubDate>'. date( 'r', strtotime($article->created_at) ) .'</pubDate>';
            echo '<yandex:genre>message</yandex:genre>';
            echo '<yandex:full-text>'. htmlspecialchars(strip_tags($article->description)) .'</yandex:full-text>';
            echo '</item>';
        }

        // close xml document
        echo '</channel>
              </rss>';
    }


    /*
    |-------------------------------------------------------------------------------
    | function generate sitemap
    |-------------------------------------------------------------------------------
    */
    public function getSitemap() {

        header("Content-Type:	application/xml");

        // open xml document
        echo '<?xml version="1.0" encoding="UTF-8"?>
              <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation=" http://www.sitemaps.org/schemas/sitemap/0.9">';

        $articles = Articles::all();

        foreach($articles as $article) {
            echo '<url>';

            echo '<loc>'. $this->getArticleURL($article->id) .'</loc>';
            echo '<priority>0.5</priority>';
            echo '<lastmod>'. $article->updated_at .'</lastmod>';

            echo '</url>';
        }

        //close xml document
        echo '</urlset>';
    }


    /*
    |-------------------------------------------------------------------------------
    | function make redirect to other url
    |-------------------------------------------------------------------------------
    */
    public function getRedirects() {
        return Redirect::to( Input::get('url') );
    }


}