<?php

class FrontSerialController extends FrontController
{
    public function getIndex()
    {
        return View::make('front.list');
    }

    public function getLocalSerial()
    {
        $data = [
            'as' => 'YERLİ DİZİ',
            'type' => 2,
            'social' => ConfigController::getSocial(),
            // 'popularSerials' => \admin\Serials::where('type', 2)->where('is_popular', 1)->orderBy('title', 'ASC')->limit(8)->get(),
            'allSerials' => \admin\Serials::where('type', 2)->orderBy('views', 'DESC')->get(),
        ];
        return View::make('front.serial.index', $data);
    }

    public function getForeignSerial()
    {
        $data = [
            'as' => 'YABANCI DİZİ',
            'type' => 1,
            'social' => ConfigController::getSocial(),
            'popularSerials' => \admin\Serials::where('type', 1)->where('is_popular', 1)->orderBy('views', 'DESC')->limit(8)->get(),
            'allSerials' => \admin\Serials::where('type', 1)->orderBy('views', 'DESC')->get(),
        ];
        return View::make('front.serial.index', $data);
    }

    public function getProgram()
    {
        $data = [
            'as' => 'PROGRAM',
            'type' => 3,
            'social' => ConfigController::getSocial(),
            'popularSerials' => \admin\Serials::where('type', 3)->where('is_popular', 1)->orderBy('views', 'DESC')->limit(8)->get(),
            'allSerials' => \admin\Serials::where('type', 3)->orderBy('views', 'DESC')->get(),
        ];
        return View::make('front.serial.index', $data);
    }

    public function getAjaxSerialDetail($type, $page)
    {
        $data = [
            'serials' => \admin\Serials::where('type', $type)->orderBy('title', 'ASC')->skip($page * 9)->take(9)->get(),
        ];

        if (count($data['serials']->toArray()) > 0)
            return View::make('front.serial.ajax', $data);
        else
            return 'false';
    }

    public function postAjaxAllSerialDetail($type)
    {
        $data = [
            'serials' => \admin\Serials::where('type', $type)->orderBy('title', 'ASC')->get(),
        ];

        if (count($data['serials']->toArray()) > 0)
            return View::make('front.serial.ajax', $data);
        else
            return 'false';
    }


    public function getSerialDetail($permalink)
    {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        $serial = \admin\Serials::where('permalink', $permalink)->with(['channel', 'season'])->first();
        $episodes = \admin\Serials::episodes($serial->id, true, 0, 2);

        admin\ViewsController::upSerialViews($serial);

        $tempArr = [];
        foreach ($episodes as $episode) {
            $tempArr[$episode['season']][] = $episode;
        }
        $episodes = $tempArr;
        $sidebarOthers = $this->getSidebarOthers();

        if ($serial) {
            $data = [
                'serial' => $serial,
                'episodes' => $episodes,                
                'specials' => admin\Serials::specials($serial->id, 3, true),
                'trailers' => admin\Serials::trailers($serial->id, 2, true),
                'sgalleries' => admin\Serials::sgalleries($serial->id, 3, true),
                'specialNews' => admin\News::where('serial_id', $serial->id)->where('type', 2)->take(3)->orderBy('created_at', 'DESC')->get()->toArray(),
                'news' => admin\News::where('serial_id', $serial->id)->where('type', 1)->take(2)->orderBy('created_at', 'DESC')->get()->toArray(),
                'interviews' => admin\Interviews::where('serial_id', $serial->id)->take(3)->orderBy('created_at', 'DESC')->get()->toArray(),
                'photoNews' => admin\News::where('serial_id', $serial->id)->where('type', 3)->take(3)->orderBy('created_at', 'DESC')->get()->toArray(),
                'sidebarOthers' => $sidebarOthers,
                'social' => ConfigController::getSocial(),
                'headers' => ['title' => $serial->title, 'description' => \BaseController::shorten($serial->info, 200)]
            ];
            return View::make('front.serial.detail', $data);
        } else {
            return Redirect::to('index');
        }
    }

    public function getEpisode($permalink, $galleryPage = 1,  $page = 1)
    {
        $episodeObject = new admin\Episodes();
        $episode = $episodeObject->where('permalink', $permalink)->with(['user', 'tags', 'season', 'gallery'])->first();
        if ($episode->published != 1) {
            return Redirect::to(action('HomeController@getIndex'));
        }
        $alias = $this->getAlias($episode);
        $season = $episode->season;
        $serial = admin\Serials::find($season->serial_id);

        $galleryTotal = count($episode->gallery);
        if ($galleryTotal && $galleryPage == 'all') {
            $gallery = $episode->gallery()->orderBy('order', 'asc')->get()->toArray();
        } else if ($galleryTotal) {
            $gallery = $episode->gallery()->orderBy('order', 'asc')->get()->toArray();
            $gallery = [$gallery[$galleryPage - 1]];
        } else {
            $gallery = null;
        }

        admin\ViewsController::upSerialViews($serial);
        admin\ViewsController::upEpisodeViews($episode);

        $enumEpisode = \Config::get('enums.episode');
        $others = $this->getOthers($episode->id, $episode->title);
        $others_id = [];
        array_push($others_id, $episode->id);
        foreach ($others as $other) {
            array_push($others_id, $other->id);
        }
        $sidebarOthers = $this->getSidebarOthers($others_id, $season);

        $content = $episode->content;
        $pages = explode('<!-- pagebreak -->', $content);
        $pageCount = count ($pages);

        if($page !== 'all') {
            $content = $pages[$page - 1];
        }

        if ($episode) {
            $data = [
                'alias' => $alias,
                'enums' => $enumEpisode,
                'serial' => $serial,
                'season' => $season,
                'content' => $content,
                'contentTotalPage' => $pageCount,
                'permalink' => $permalink,
                'page' => $page,
                'episode' => $episode,
                'gallery' => $gallery,
                'galleryPage' => $galleryPage,
                'galleryTotal' => $galleryTotal,
                'social' => ConfigController::getSocial(),
                'others' => $others,
                'sidebarOthers' => $sidebarOthers,
                'largeImage' => $episode->img,
                'headers' => ['title' => $episode->title . ',' . $season->number . '.Sezon ' . $episode->number . '.Bölüm ' . $this->getAlias($episode) . '-', 'description' => \BaseController::shorten($episode->summary, 200)]
            ];
            return View::make('front.serial.episodeDetail', $data);
        } else {
            return Redirect::to('index');
        }

    }

    public function listEpisodes($serial_permalink, $enum, $page = 1)
    {
        $enums = \Config::get('enums.episode');
        $limit = 5;
        $offset = ($page - 1) * $limit;
        if ($serial_permalink == 'tum') {
            $episodes = admin\Episodes::where('published', 1)->orderBy('created_at', 'desc')->where('enum', $enum)->skip($offset)->take($limit)->with(['season'])->get();
            foreach ($episodes as $episode) {
                $season = admin\Seasons::where('id', $episode->season->id)->with('serial')->first();
                $episode['season'] = $season->number;
                $serie = $season->serial;
                $episode['serial'] = $serie;
            }
            $episodes->toArray();
        } else {
            $serial = \admin\Serials::where('permalink', $serial_permalink)->with(['channel', 'season'])->first();
            if (count($serial) < 1) {
                return Redirect::to('index');
            }
            $episodes = [];
            switch ($enum) {
                case $enums['summary']:
                    $episodes = \admin\Serials::episodes($serial->id, false, true, $offset, $limit);
                    break;
                case $enums['specials']:
                    $episodes = \admin\Serials::specials($serial->id, false, true, $offset, $limit);
                    break;
                case $enums['trailer']:
                    $episodes = \admin\Serials::trailers($serial->id, false, true, $offset, $limit);
                    break;
                case $enums['sgallery']:
                    $episodes = \admin\Serials::sgalleries($serial->id, false, true, $offset, $limit);
                    break;
                default;
            }
        }

        foreach ($episodes as $key => $episode) {
            $episodes[$key] = $this->addAction($episode);
        }
        $aliases = \Config::get('alias.episode');
        $data = [
            'enum' => $enum,
            'permalink' => $serial_permalink,
            'as' => $aliases[$enum],
            'list' => $episodes,
            'social' => ConfigController::getSocial(),
        ];
        if (isset($serial))
            $data['serial'] = $serial;
        return View::make('front.list.serialIndex', $data);
    }

    public function listAjaxEpisodes($serial_permalink, $enum, $page = 1)
    {
        $enums = \Config::get('enums.episode');
        $limit = 5;
        $offset = ($page) * $limit;
        if ($serial_permalink == 'tum') {
            $episodes = admin\Episodes::where('published', 1)->orderBy('created_at', 'desc')->where('enum', $enum)->skip($offset)->take($limit)->with(['season'])->get();
            foreach ($episodes as $episode) {
                $season = admin\Seasons::where('id', $episode->season->id)->with('serial')->first();
                $episode['season'] = $season->number;
                $serie = $season->serial;
                $episode['serial'] = $serie;
            }
            $episodes->toArray();
        } else {
            $serial = \admin\Serials::where('permalink', $serial_permalink)->with(['channel', 'season'])->first();
            if (count($serial) < 1) {
                return null;
            }
            $episodes = [];
            switch ($enum) {
                case $enums['summary']:
                    $episodes = \admin\Serials::episodes($serial->id, true, $offset, $limit);
                    break;
                case $enums['specials']:
                    $episodes = \admin\Serials::specials($serial->id, false, true, $offset, $limit);
                    break;
                case $enums['trailer']:
                    $episodes = \admin\Serials::trailers($serial->id, false, true, $offset, $limit);
                    break;
                case $enums['sgallery']:
                    $episodes = \admin\Serials::sgalleries($serial->id, false, true, $offset, $limit);
                    break;
                default;
            }
        }

        foreach ($episodes as $key => $episode) {
            $episodes[$key] = $this->addAction($episode);
        }
        $aliases = \Config::get('alias.episode');
        if (!(count($episodes) > 0)) {
            return 'false';
        }
        $data = [
            'as' => $aliases[$enum],
            'list' => $episodes,
        ];
        if (isset($serial))
            $data['serial'] = $serial;
        return View::make('front.list.ajax', $data);
    }

    private function getOthers($episode_id, $title)
    {
        $others_id = [];
        $others = [];
        //Other posts
        $first2 = admin\Episodes::limit(2)->where('episodes.id', '!=', $episode_id)->where('published', 1)->orderBy('views', 'desc')->with('user', 'season')->get();
        foreach ($first2 as $post) {
            array_push($others_id, $post['id']);
            $post = $this->addAction($post);
            array_push($others, $post);
        }
        $middle2 = admin\Episodes::limit(2)->where('episodes.id', '!=', $episode_id)->where('published', 1)->whereNotIn('episodes.id', $others_id)->where('title', 'like', '%' . $title . '%')->with('user', 'season')->get();
        foreach ($middle2 as $post) {
            array_push($others_id, $post['id']);
            $post = $this->addAction($post);
            array_push($others, $post);
        }
        $twoweeksago = time() - (2 * 7 * 24 * 60 * 60);
        $date = strtotime('Y-m-d H:i:s', $twoweeksago);
        $last2 = admin\Episodes::limit(2)->where('episodes.id', '!=', $episode_id)->where('published', 1)->whereNotIn('episodes.id', $others_id)->where('created_at', '>', $date)->orderBy('views', 'asc')->with('user', 'season')->get();
        foreach ($last2 as $post) {
            $post = $this->addAction($post);
            array_push($others, $post);
        }

        return $others;
    }

    private function getSidebarOthers($ignore = [0], $season = '')
    {
        $collection = [];
        if($season != '') {
            $oldPosts = admin\Episodes::limit(3)->where('published', 1)->where('season_id', $season->id)->orderBy(DB::raw('RAND()'))->whereNotIn('episodes.id', $ignore)->with('user', 'season')->get();
            foreach ($oldPosts as $key => $oldPost) {
                array_push($ignore, $oldPost->id);
                array_push($collection, $this->addAction($oldPost));
            }
        } else {
            $oldPosts = [];
        }
        //Random episodes
        $others = admin\Episodes::limit(6 - count($oldPosts))->where('published', 1)->orderBy(DB::raw('RAND()'))->whereNotIn('episodes.id', $ignore)->with('user', 'season')->get();
        foreach ($others as $key => $other) {
            $others[$key] = $this->addAction($other);
            array_push($collection, $others[$key]);
        }
        return $collection;
    }

    private function addAction($item)
    {
        $enums = \Config::get('enums.episode');
        $action = null;
        switch ($item['enum']) {
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
        $time = strtotime($item['created_at']);
        $date = iconv('latin5','utf-8',strftime("%d %B %Y", $time));

        $item['date'] = $date;
        $item['action'] = $action;
        $item['alias'] = $this->getAlias($item);

        return $item;
    }

    private function getAlias($item)
    {
        $aliases = \Config::get('alias.episode');
        $alias = $aliases[$item['enum']];
        return $alias;
    }
}
