<?php

/**
 * Created by PhpStorm.
 * User: erenyildirim
 * Date: 03/01/15
 * Time: 16:36
 */
class FrontNewsController extends \FrontController
{
    public function getNews($permalink, $galleryPage = 1, $page = 1)
    {

        $newsObject = new admin\News();
        $news = $newsObject->where('permalink', $permalink)->with(['user', 'tags', 'gallery'])->first();
        if ($news->published != 1) {
            return Redirect::to(action('HomeController@getIndex'));
        }
        $as = '';
        switch ($news->type) {
            case 1:
                $as = 'Haber';
                break;
            case 2:
                $as = 'Özel';
                break;
            case 3:
                $as = 'Foto Haber';
                break;
            default;
        }

        $galleryTotal = count($news->gallery);
        if ($galleryTotal && $galleryPage == 'all') {
            $gallery = $news->gallery()->orderBy('order', 'asc')->get()->toArray();
        } else if ($galleryTotal) {
            $gallery = $news->gallery()->orderBy('order', 'asc')->get()->toArray();
            $gallery = [$gallery[$galleryPage - 1]];
        } else {
            $gallery = null;
        }
        admin\ViewsController::upNewsViews($news);

        $others = $this->getOthers([$news->id]);

        $others_id = [];
        array_push($others_id, $news->id);
        foreach ($others as $key => $other) {
            array_push($others_id, $other->id);
        }

        $sidebarOtherNews = $this->getOthers($others_id);

        $content = $news->text;
        $pages = explode('<!-- pagebreak -->', $content);
        $pageCount = count ($pages);

        if($page !== 'all') {
            $content = $pages[$page - 1];
        }

        if ($news) {
            $data = [
                'as' => $as,
                'news' => $news,
                'content' => $content,
                'contentTotalPage' => $pageCount,
                'permalink' => $permalink,
                'page' => $page,
                'gallery' => $gallery,
                'galleryPage' => $galleryPage,
                'galleryTotal' => $galleryTotal,
                'others' => $others,
                'sidebarOthers' => $sidebarOtherNews,
                'largeImage' => $news->img,
                'headers' => ['title' => $news->title , 'description' => \BaseController::shorten($news->summary, 200)],
                'social' => ConfigController::getSocial(),
            ];
            if ($as=="Foto Haber") {
                return View::make('front.news.photonewsDetail', $data);
            }
            else {                
                return View::make('front.news.newsDetail', $data);
            }            
        } else {
            return Redirect::to('index');
        }
    }

    public function listNews($serial_permalink = "tum", $page = 1)
    {
        if ($serial_permalink != "tum") {
            $serial = admin\Serials::where('permalink', $serial_permalink)->first();
            if (count($serial) < 1) {
                return Redirect::to('index');
            }
        }
        $enum = \Config::get('enums.news');
        $limit = 5;
        $offset = ($page - 1) * $limit;
        $newsQuery = admin\News::where('published', 1)->where('type', 1);
        if(isset($serial)){
            $newsQuery = $newsQuery->where('serial_id', $serial->id);
        }
        $news = $newsQuery->orderBy('created_at', 'desc')->skip($offset)->take($limit)->get()->toArray();
        foreach ($news as $key => $newsSingle) {
            $news[$key] = $this->addAction($newsSingle);
        }
        $alias = \Config::get('alias.' . $enum);
        $data = [
            'as' => $alias,
            'permalink' => $serial_permalink,
            'list' => $news,
        ];
        if (isset($serial))
            $data['serial'] = $serial;
        return View::make('front.list.newsIndex', $data);
    }

    public function listAjaxNews($serial_permalink = "tum", $page = 1)
    {
        if ($serial_permalink != "tum") {
            $serial = admin\Serials::where('permalink', $serial_permalink)->first();
            if (count($serial) < 1) {
                return Redirect::to('index');
            }
        }
        $enum = \Config::get('enums.news');
        $limit = 5;
        $offset = ($page) * $limit;
        $newsQuery = admin\News::where('published', 1)->where('type', 1);
        if(isset($serial)){
            $newsQuery = $newsQuery->where('serial_id', $serial->id);
        }
        $news = $newsQuery->orderBy('created_at', 'desc')->skip($offset)->take($limit)->get()->toArray();
        foreach ($news as $key => $newsSingle) {
            $news[$key] = $this->addAction($newsSingle);
        }
        $alias = \Config::get('alias.' . $enum);
        if (!(count($news) > 0)) {
            return 'false';
        }
        $data = [
            'as' => $alias,
            'list' => $news,
        ];
        return View::make('front.list.ajax', $data);
    }

    public function getSpecialNewsList($serial_permalink = "tum", $page = 1)
    {
        if ($serial_permalink != "tum") {
            $serial = admin\Serials::where('permalink', $serial_permalink)->first();
            if (count($serial) < 1) {
                return Redirect::to('index');
            }
        }
        $limit = 10;
        $offset = ($page - 1) * $limit;
        $enums = \Config::get('enums.episode');
        $itemList = [];
        $newsObject = new admin\News();
        $newsQuery = $newsObject->where('published', 1)->where('type', 2);
        if (isset($serial))
            $newsQuery = $newsQuery->where('serial_id', $serial->id);
        $newsList = $newsQuery->orderBy('created_at', 'desc')->skip($offset)->take($limit)->with(['user'])->get();
        if (isset($serial)) {
            $specialsList = \admin\Serials::specials($serial->id, false, true, $offset, $limit);
        } else {
            $specialsObject = new admin\Episodes();
            $specialsList = $specialsObject->where('published', 1)->where('enum', $enums['specials'])->orderBy('created_at', 'desc')->skip($offset)->take($limit)->with(['user'])->get();
        }
        foreach ($newsList as $news) {
            $username = $news->user()->first()->name;
            $news->toArray();
            $news['action'] = 'front.news.specialNewsDetail';
            $news['username'] = $username;
            $news['alias'] = 'Özel';
            array_push($itemList, $news);
        }
        foreach ($specialsList as $special) {
            $username = $special->user()->first()->name;
            $special->toArray();
            $special['action'] = 'front.serial.specialDetail';
            $special['username'] = $username;
            $aliases = \Config::get('alias.episode');
            $enums = \Config::get('enums.episode');
            $special['alias'] = $aliases[$enums['specials']];
            array_push($itemList, $special);
        }

        usort($itemList, function ($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });
        $data = [
            'as' => 'Özel',
            'permalink' => $serial_permalink,
            'itemList' => $itemList,
            'social' => ConfigController::getSocial(),
        ];

        return View::make('front.news.specialIndex', $data);
    }

    public function getSpecialAjaxNewsList($serial_permalink = "tum", $page = 1)
    {
        if ($serial_permalink != "tum") {
            $serial = admin\Serials::where('permalink', $serial_permalink)->first();
            if (count($serial) < 1) {
                return 'false';
            }
        }
        $limit = 10;
        $offset = ($page) * $limit;
        $enums = \Config::get('enums.episode');
        $itemList = [];
        $newsObject = new admin\News();
        $newsQuery = $newsObject->where('published', 1)->where('type', 2);
        if (isset($serial))
            $newsQuery = $newsQuery->where('serial_id', $serial->id);
        $newsList = $newsQuery->orderBy('created_at', 'desc')->skip($offset)->take($limit)->with(['user'])->get();
        if (isset($serial)) {
            $specialsList = \admin\Serials::specials($serial->id, false, true, $offset, $limit);
        } else {
            $specialsObject = new admin\Episodes();
            $specialsList = $specialsObject->where('published', 1)->where('enum', $enums['specials'])->orderBy('created_at', 'desc')->skip($offset)->take($limit)->with(['user'])->get();
        }
        foreach ($newsList as $news) {
            $username = $news->user()->first()->name;
            $news->toArray();
            $news['action'] = 'front.news.specialNewsDetail';
            $news['username'] = $username;
            $news['alias'] = 'Özel';
            array_push($itemList, $news);
        }
        foreach ($specialsList as $special) {
            $username = $special->user()->first()->name;
            $special->toArray();
            $special['action'] = 'front.serial.specialDetail';
            $special['username'] = $username;
            $aliases = \Config::get('alias.episode');
            $enums = \Config::get('enums.episode');
            $special['alias'] = $aliases[$enums['specials']];
            array_push($itemList, $special);
        }
        usort($itemList, function ($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });
        if (!(count($itemList) > 0))
            return 'false';
        $data = [
            'as' => 'Özel',
            'itemList' => $itemList
        ];
        return View::make('front.news.ajax', $data);
    }

    public function getPhotoNewsList($serial_permalink = 'tum')
    {
        if ($serial_permalink != "tum") {
            $serial = admin\Serials::where('permalink', $serial_permalink)->first();
            if (count($serial) < 1) {
                return 'false';
            }
        }
        $newsObject = new admin\News();
        $newsQuery = $newsObject->where('published', 1)->where('type', 3);
        if (isset($serial))
            $newsQuery = $newsQuery->where('serial_id', $serial->id);
        // $newsList = $newsQuery->orderBy('created_at','DESC')->paginate(5);
        $newsList = $newsQuery->orderBy('created_at','DESC')->get();
        $data = [
            'as' => 'FOTO HABER',
            'newsList' => $newsList,
            'social' => ConfigController::getSocial(),
            'color_class' => 'fotohaber'
        ];
        if (isset($serial))
            $data['serial'] = $serial;
        return View::make('front.news.index', $data);
    }

    private function getOthers($ignore)
    {
        //Random news
        $others = admin\News::limit(6)->where('published', 1)->orderBy(DB::raw('RAND()'))->whereNotIn('news.id', $ignore)->with('user')->get();
        foreach ($others as $key => $other) {
            $others[$key] = $this->addAction($other);
        }
        return $others;
    }

    private function addAction($item)
    {
        $time = strtotime($item['created_at']);
        $date = date("d.m.Y", $time);

        $item['date'] = $date;
        $item['action'] = 'front.news.newsDetail';
        if ($item['type'] == 2)
            $item['alias'] = 'Özel';
        else
            $item['alias'] = \Config::get('alias.' . \Config::get('enums.news'));

        return $item;
    }
}