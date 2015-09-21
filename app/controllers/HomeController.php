<?php

class HomeController extends FrontController
{
    public function getIndex()
    {

        $localEpisodes = [];
        foreach (\admin\Episodes::where('enum', Config::get('enums.episode')['summary'])->where('published', 1)->where('type', 2)->with('season')->orderBy('created_at','DESC')->limit(27)->remember(1)->get() as $local) {
            $localTemp = $local->toArray();
            $localTemp['season'] = $local->season->number;
            $localTemp['serial'] = admin\Seasons::where('id', $local->season->id)->with(['serial'])->first()->serial->title;
            $localEpisodes[] = $localTemp;
        }

        $foreignEpisodes = [];
        foreach (\admin\Episodes::where('enum', Config::get('enums.episode')['summary'])->where('published', 1)->where('type', 1)->with('season')->orderBy('created_at','DESC')->limit(27)->remember(1)->get() as $foreign) {
            $foreignTemp = $foreign->toArray();
            $foreignTemp['season'] = $foreign->season->number;
            $foreignTemp['serial'] = admin\Seasons::where('id', $foreign->season->id)->with(['serial'])->first()->serial->title;
            $foreignEpisodes[] = $foreignTemp;
        }


        $programEpisodes = [];
        foreach (\admin\Episodes::where('enum', Config::get('enums.episode')['summary'])->where('published', 1)->where('type', 3)->with('season')->orderBy('created_at','DESC')->limit(27)->remember(1)->get() as $program) {
            $programTemp = $program->toArray();
            $programTemp['season'] = $program->season->number;
            $programTemp['serial'] = admin\Seasons::where('id', $program->season->id)->with(['serial'])->first()->serial->title;
            $programEpisodes[] = $programTemp;
        }

        $galleryTemp = [];
        foreach (admin\Episodes::where('enum', Config::get('enums.episode')['sgallery'])->where('published', 1)->orderBy('created_at','DESC')->limit(8)->get() as $gallery) {
            $gTemp = $gallery->toArray();
            $gTemp['season'] = $gallery->season->number;
            $gTemp['user'] = $gallery->user->name;
            $galleryTemp[] = $gTemp;
        }


        $articleOfDay = admin\Article::where('published', 1)->where('todays', 1)->with('user')->first();
        if($articleOfDay) {
            $time = strtotime($articleOfDay->created_at);
            setlocale(LC_ALL, 'tr_TR.UTF-8', 'tr_TR', 'tr', 'turkish');
            setlocale(LC_CTYPE, 'C');
            $articleOfDay->date = iconv('latin5','utf-8',strftime("%d %B %Y %A", $time));
            $articleOfDay->user->social = unserialize($articleOfDay->user->social);
        } else {
            $articleOfDay = null;
        }

        $social = ConfigController::getSocial();

        $date = date('d/m/Y', time() - 60 * 60 * 24);
        $RatingTime = time() - 60 * 60 * 24;
        $ratingDate = iconv('latin5','utf-8',strftime("%d %B %Y %A", $RatingTime));

        $rating['total'] = Rating::where('type', 1)->where('date', $date)->orderBy('order')->limit(5)->get();
        $rating['ab'] = Rating::where('type', 2)->where('date', $date)->orderBy('order')->limit(5)->get();
        $rating['somera'] = Rating::where('type', 3)->where('date', $date)->orderBy('order')->limit(5)->get();
        $checkRating = false;
        if(count($rating['ab'])<1){
            $checkRating = true;
            $date = date('d/m/Y', time() - 2 * 60 * 60 * 24);
            $RatingTime = time() - 2 * 60 * 60 * 24;
            $ratingDate = iconv('latin5','utf-8',strftime("%d %B %Y %A", $RatingTime));
            $rating['total'] = Rating::where('type', 1)->where('date', $date)->orderBy('order')->limit(5)->get();
            $rating['ab'] = Rating::where('type', 2)->where('date', $date)->orderBy('order')->limit(5)->get();
            $rating['somera'] = Rating::where('type', 3)->where('date', $date)->orderBy('order')->limit(5)->get();
        } else if(count($rating['somera'])<1){//We're double checking because somera is from another source and might not be ready yet
            $checkRating = true;
            $date = date('d/m/Y', time() - 2 * 60 * 60 * 24);
            $RatingTime = time() - 2 * 60 * 60 * 24;
            $ratingDate = iconv('latin5','utf-8',strftime("%d %B %Y %A", $RatingTime));
            $rating['somera'] = Rating::where('type', 3)->where('date', $date)->orderBy('order')->limit(5)->get();
        }

        $featured = admin\Featured::where('published', 1)->with('tags')->first();
        if(isset($featured) && count($featured->tags()->get()->toArray())>0){

            if (!Cache::has('featuredItems'.$featured->permalink)) {
                $featuredItems = Cache::remember('featuredItems'.$featured->permalink, 1, function() use ($featured) {
                                    $featuredController = new FrontFeaturedController();
                                    return  $featuredController->getItems($featured);
                                });
            } else {
                $featuredItems =  Cache::get('featuredItems'.$featured->permalink);
            }
        }
        else
        $featuredItems = [];


        if (!Cache::has('likedItems')) {
            $liked = Cache::remember('likedItems', 4, function() {
                return  $this->getLikedItems();
            });
        } else {
            $liked =  Cache::get('likedItems');
        }


        $news = admin\News::where('published', 1)->where('type', 1)->orderBy('created_at','DESC')->limit(18)->get()->toArray();
        $home_config = admin\Config::select(['home_description','home_tags'])->first();
        $data = [
            'sliders' => admin\Slider::allSliders(),
            'locals' => $localEpisodes,
            'foreigns' => $foreignEpisodes,
            'programs' => $programEpisodes,
            'articleOfDay' => $articleOfDay,
            'rating' => $rating,
            'cRating' => $checkRating,
            'ratingDate' => $ratingDate,
            'galleries' => $galleryTemp,
            'trailers' => admin\Episodes::where('enum', Config::get('enums.episode')['trailer'])->where('published', 1)->orderBy('created_by','DESC')->limit(3)->get()->toArray(),
            'social' => $social,
            'featured' => $featured,
            'featuredItems' => $featuredItems,
            'news' => $news,
            'liked' => $liked,
            'headers' => ['title'=> '', 'description' => $home_config->home_description, 'tags' => $home_config->home_tags]
            //title is pulled directly from database so please leave empty.
        ];
        return View::make('front.index', $data);
    }

    public function getDiziler()
    {

        return Redirect::to('index');
    }

    public function getLikedItems()
    {
        /*$controller = new LikedController();
        $liked = $controller->getItems();
        return $liked['items'];*/
        $controller = new FrontAuthorsController();
        $episodes = admin\Episodes::where('published', 1)->where('created_at', '>=', Carbon\Carbon::now()->subMonth())->orderBy('views', 'desc')->limit(10)->get();
        $news = admin\News::where('published', 1)->where('created_at', '>=', Carbon\Carbon::now()->subMonth())->orderBy('views', 'desc')->limit(10)->get();
        $interviews = admin\Interviews::where('published', 1)->where('created_at', '>=', Carbon\Carbon::now()->subMonth())->orderBy('views', 'desc')->limit(10)->get();
        $articles = admin\Article::where('published', 1)->where('created_at', '>=', Carbon\Carbon::now()->subMonth())->orderBy('views', 'desc')->limit(10)->get();

        $collection = [];
        $collection = $controller->getOrderedArray($collection, $news, \Config::get('enums.news'));
        $collection = $controller->getOrderedArray($collection, $episodes, \Config::get('enums.episodes'));
        $collection = $controller->getOrderedArray($collection, $interviews, \Config::get('enums.interviews'));
        $collection = $controller->getOrderedArray($collection, $articles, \Config::get('enums.articles'));

        usort($collection, function($a, $b) {
            return $b['views'] - $a['views'];
        });
        $collection = array_slice($collection, 0, 24);

        return $collection;

    }

    public function getContact(){
        $episodes = admin\Episodes::limit(5)->where('published', 1)->orderBy('created_at', 'desc')->get();
        $news = admin\News::limit(5)->where('published', 1)->orderBy('created_at', 'desc')->get();
        $interviews = admin\Interviews::limit(5)->where('published', 1)->orderBy('created_at', 'desc')->get();
        $articles = admin\Article::limit(5)->where('published', 1)->orderBy('created_at', 'desc')->get();

        $AuthorController = new FrontAuthorsController();
        $news = $AuthorController->addToArray($news, \Config::get('enums.news'));
        $episodes = $AuthorController->addToArray($episodes, \Config::get('enums.episodes'));
        $interviews = $AuthorController->addToArray($interviews, \Config::get('enums.interviews'));
        $articles = $AuthorController->addToArray($articles, \Config::get('enums.articles'));

        $items = [
            \Config::get('enums.news') => $news,
            \Config::get('enums.episodes') => $episodes,
            \Config::get('enums.interviews') => $interviews,
            \Config::get('enums.articles') => $articles
        ];

        $data = [
            'posts' => $items
        ];

        return View::make('front.contact',$data);
    }

    public function missingMethod($parameters = array())
    {
        dd($parameters);
    }

}
