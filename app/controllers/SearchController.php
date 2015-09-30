<?php

/**
 * Created by PhpStorm.
 * User: erenyildirim
 * Date: 05/01/15
 * Time: 22:23
 */
class SearchController extends \FrontController
{
    public function getSearch()
    {
        $keyword = Input::get('q');
        $items = $this->getSearchItems($keyword);
        $data = [
            'as' => 'Arama',
            'items' => $items,
            'keyword' => $keyword,
            'headers' => ['title'=> $keyword , 'description' => $keyword . ' araması için gösterilen sonuçlar'],
            'social' => ConfigController::getSocial(),
        ];
        return View::make('front.search.index', $data);
    }

    public function getSearchItems($keyword)
    {
        $items = [];
        $news = admin\News::whereHas('tags', function ($query) use ($keyword) {
            $query->where('title', 'LIKE', "%$keyword%");

        })->orWhere('title', 'like', '%' . $keyword . '%')->where('published', 1)->with('user')->get();

        if (count($news) > 0) {
            $news = $this->moderateArray($news, \Config::get('enums.news'));
            $items[] = ['text' => 'Haberler', 'children' => $news];
        }

        $interviews = admin\Interviews::whereHas('tags', function ($query) use ($keyword) {
            $query->where('title', 'LIKE', "%$keyword%");

        })->orWhere('title', 'like', '%' . $keyword . '%')->where('published', 1)->with('user')->get();
        if (count($interviews) > 0) {
            $interviews = $this->moderateArray($interviews, \Config::get('enums.interviews'));
            $items[] = ['text' => 'Röportajlar', 'children' => $interviews];
        }

        $article = admin\Article::whereHas('tags', function ($query) use ($keyword) {
            $query->where('title', 'LIKE', "%$keyword%");

        })->orWhere('title', 'like', '%' . $keyword . '%')->where('published', 1)->with('user')->get();
        if (count($article) > 0) {
            $article = $this->moderateArray($article, \Config::get('enums.articles'));
            $items[] = ['text' => 'Köşe Yazıları', 'children' => $article];
        }

        $episodes = admin\Episodes::whereHas('tags', function ($query) use ($keyword) {
            $query->where('title', 'LIKE', "%$keyword%");

        })->orWhere('title', 'like', '%' . $keyword . '%')->where('published', 1)->with('user')->get();
        if (count($episodes) > 0) {
            $episodes = $this->moderateArray($episodes, \Config::get('enums.episodes'));
            $items[] = ['text' => 'Dizi İçerikleri', 'children' => $episodes];
        }


        $serials = admin\Serials::where('title', 'like', '%' . $keyword . '%')->get();
        if (count($serials) > 0) {
            $serials = $this->moderateArray($serials, \Config::get('enums.serials'),true);
            $items[] = ['text' => 'Diziler', 'children' => $serials];
        }

        return $items;
    }

    public function moderateArray($collection, $enum,$is_serial = false)
    {
        $newArray = [];
        foreach ($collection as $item) {
            if($is_serial)
            $username = '';
            else
            $username = $item->user()->first()->name;

            $item->toArray();
            $item['item_enum'] = $enum;
            $item['username'] = $username;
            array_push($newArray, $item);
        }
        return $newArray;
    }


}