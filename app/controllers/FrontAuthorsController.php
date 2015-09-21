<?php

/**
 * Created by PhpStorm.
 * User: erenyildirim
 * Date: 03/01/15
 * Time: 21:32
 */
class FrontAuthorsController extends \FrontController
{
    public function getAuthors()
    {
        $authors = \User::orderBy('name','ASC')->get();
        $data = [
            'as' => 'YAZARLAR',
            'authors' => $authors
        ];
        return View::make('front.authors.index', $data);
    }

    public function getAuthor($id)
    {
        $author = \User::where('id', $id)->first();
        $posts = $this->getTabbedItems($author);
        admin\ViewsController::upAuthorViews($author);
        $data = [
            'as' => 'YAZAR',
            'author' => $author,
            'posts' => $posts,
            'headers' => ['title'=> $author->name , 'description' => \BaseController::shorten($author->bio, 200)]
        ];
        return View::make('front.authors.detail', $data);
    }

    public function getTabbedItems($author)
    {
        $episodes = admin\Episodes::where('created_by', $author->id)->where('is_author', 1)->where('published', 1)->orderBy('created_at', 'desc')->get();
        $news = admin\News::where('created_by', $author->id)->where('is_author', 1)->where('published', 1)->orderBy('created_at', 'desc')->get();
        $interviews = admin\Interviews::where('created_by', $author->id)->where('is_author', 1)->where('published', 1)->orderBy('created_at', 'desc')->get();
        $articles = admin\Article::where('created_by', $author->id)->where('is_author', 1)->where('published', 1)->orderBy('created_at', 'desc')->get();

        $news = $this->addToArray($news, \Config::get('enums.news'));
        $episodes = $this->addToArray($episodes, \Config::get('enums.episodes'));
        $interviews = $this->addToArray($interviews, \Config::get('enums.interviews'));
        $articles = $this->addToArray($articles, \Config::get('enums.articles'));

        $items = [
            \Config::get('enums.news') => $news,
            \Config::get('enums.episodes') => $episodes,
            \Config::get('enums.interviews') => $interviews,
            \Config::get('enums.articles') => $articles
        ];

        return $items;
    }

    public function addToArray($items, $enum)
    {
        foreach ($items as $key => $item) {
            $itemDetail = $item->toArray();
            $action = '';
            switch ($enum) {
                case \Config::get('enums.news'):
                    $action = 'front.news.newsDetail';
                    break;
                case \Config::get('enums.articles'):
                    $action = 'front.article.articleDetail';
                    break;
                case \Config::get('enums.interviews'):
                    $action = 'front.interviews.interviewDetail';
                    break;
            }
            if ($enum == \Config::get('enums.episodes')) {
                $enums = \Config::get('enums.episode');
                $aliases = \Config::get('alias.episode');
                $itemDetail['alias'] = $aliases[$itemDetail['enum']];
                switch ($itemDetail['enum']) {
                    case $enums['summary']:
                        $action = 'front.serial.episodeDetail';
                        break;
                    case $enums['specials']:
                        $action = 'front.serial.specialDetail';
                        break;
                    case $enums['trailer']:
                        $action = 'front.serial.trailerDetail';
                        break;
                    case $enums['sgallery']:
                        $action = 'front.serial.sgalleryDetail';
                        break;
                    default;
                }
            }
            $time = strtotime($itemDetail['created_at']);
            $date = iconv('latin5','utf-8',strftime("%d %B %Y", $time));
            $itemDetail['date'] = $date;
            $itemDetail['action'] = $action;
            $items[$key] = $itemDetail;
        }

        return $items;
    }

    //Deprecated
    public function getItems($author)
    {
        $episodes = admin\Episodes::where('created_by', $author->id)->where('published', 1)->orderBy('created_at', 'desc')->get();
        $news = admin\News::where('created_by', $author->id)->where('published', 1)->orderBy('created_at', 'desc')->get();
        $interviews = admin\Interviews::where('created_by', $author->id)->where('published', 1)->orderBy('created_at', 'desc')->get();
        $articles = admin\Article::where('created_by', $author->id)->where('published', 1)->orderBy('created_at', 'desc')->get();

        $collection = [];
        $collection = $this->getOrderedArray($collection, $news, \Config::get('enums.news'));
        $collection = $this->getOrderedArray($collection, $episodes, \Config::get('enums.episodes'));
        $collection = $this->getOrderedArray($collection, $interviews, \Config::get('enums.interviews'));
        $collection = $this->getOrderedArray($collection, $articles, \Config::get('enums.articles'));
        usort($collection, function ($a, $b) {
            return $b['created_at'] - $a['created_at'];
        });
        $collection = array_slice($collection, 0, 10);

        return $collection;
    }

    //Deprecated
    public function getOrderedArray($arrTemp, $items, $enum)
    {
        foreach ($items as $item) {
            $itemDetail = $item->toArray();
            $itemDetail['item_enum'] = $enum;
            array_push($arrTemp, $itemDetail);
        }

        return $arrTemp;
    }

}