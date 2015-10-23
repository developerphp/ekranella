<?php
/**
 * Created by PhpStorm.
 * User: erenyildirim
 * Date: 15/01/15
 * Time: 23:29
 */

class FrontLikedController extends FrontController{
    public function listLiked(){
        $liked = $this->getLikedItems();
        $data = [
            'as' => 'Beğenilenler',
            'list' => $liked,
            'social' => ConfigController::getSocial(),
        ];
        return View::make('front.list.likedIndex', $data);
    }


    public function getLikedItems()
    {
        $controller = new FrontAuthorsController();
        $episodes = admin\Episodes::where('published', 1)->where('created_at', '>=', Carbon\Carbon::now()->subMonth())->orderBy('views', 'desc')->limit(20)->get();
        $news = admin\News::where('published', 1)->where('created_at', '>=', Carbon\Carbon::now()->subMonth())->orderBy('views', 'desc')->limit(20)->get();
        $interviews = admin\Interviews::where('published', 1)->where('created_at', '>=', Carbon\Carbon::now()->subMonth())->orderBy('views', 'desc')->limit(20)->get();
        $articles = admin\Article::where('published', 1)->where('created_at', '>=', Carbon\Carbon::now()->subMonth())->orderBy('views', 'desc')->limit(20)->get();

        $collection = [];
        $collection = $controller->getOrderedArray($collection, $news, \Config::get('enums.news'));
        $collection = $controller->getOrderedArray($collection, $episodes, \Config::get('enums.episodes'));
        $collection = $controller->getOrderedArray($collection, $interviews, \Config::get('enums.interviews'));
        $collection = $controller->getOrderedArray($collection, $articles, \Config::get('enums.articles'));

        usort($collection, function($a, $b) {
            return $b['views'] - $a['views'];
        });
        $collection = array_slice($collection, 0, 50);

        return $collection;

    }

}