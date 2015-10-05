<?php

/**
 * Created by PhpStorm.
 * User: erenyildirim
 * Date: 03/01/15
 * Time: 21:32
 */
class FrontFeaturedController extends \FrontController
{
    public function getArchive()
    {
        $archives = admin\Featured::where('published', 0)->orderBy('created_at', 'desc')->get();
        $data = [
            'as' => 'DOSYALAR',
            'archives' => $archives,
            'social' => ConfigController::getSocial()
        ];
        return View::make('front.featured.archive', $data);
    }

    public function getFeatured($permalink)
    {
        try {
            $featured = admin\Featured::where('permalink', $permalink)->with('tags')->first();
            $data = [
                'featured' => $featured,
                'featuredTags' => $this->getItems($featured),
                'social' => ConfigController::getSocial()
            ];
            if($featured->published == 1){
                $featured['headers'] = ['title'=> $featured->title , 'description' => \BaseController::shorten($featured->title, 200)];
            } else {
                $featured['headers'] = ['title'=> $featured->title , 'description' => \BaseController::shorten($featured->title, 200)];
            }
            return View::make('front.featured.detail', $data);
        } catch (Exception $err) {
            return Redirect::to(action('HomeController@getIndex'));
        }
    }

    public function getItems($featured)
    {
        $tags = $featured->tags()->select(DB::raw('`tags`.`id` as tagid'))->lists('tagid');

        $episodes = $featured->episodes()->whereIn('featured_item.tag_id', $tags)->with(['featured_tags', 'user'])->where('published', 1)->get();
        $news = $featured->news()->whereIn('featured_item.tag_id', $tags)->with(['featured_tags', 'user'])->where('published', 1)->get();
        $interviews = $featured->interviews()->whereIn('featured_item.tag_id', $tags)->with(['featured_tags', 'user'])->where('published', 1)->get();
        $articles = $featured->articles()->whereIn('featured_item.tag_id', $tags)->with(['featured_tags', 'user'])->where('published', 1)->get();
        $summary = $featured->summary()->select(DB::raw('`episodes`.created_at, summary, img, created_by, permalink, title ,  episodes.id ,  `episodes`.`enum` as enumNumber,  "' . \Config::get('enums.episodes') . '" as enum'))->whereIn('featured_item.tag_id', $tags)->with(['featured_tags', 'user'])->where('published', 1)->get();
        $specials = $featured->specials()->select(DB::raw('`episodes`.created_at, summary, img, created_by, permalink, title ,  episodes.id ,  `episodes`.`enum` as enumNumber,  "' . \Config::get('enums.episodes') . '" as enum'))->whereIn('featured_item.tag_id', $tags)->with(['featured_tags', 'user'])->where('published', 1)->get();
        $trailer = $featured->trailer()->select(DB::raw('`episodes`.created_at, summary, img, created_by, permalink, title ,  episodes.id ,  `episodes`.`enum` as enumNumber,  "' . \Config::get('enums.episodes') . '" as enum'))->whereIn('featured_item.tag_id', $tags)->with(['featured_tags', 'user'])->where('published', 1)->get();
        $sgallery = $featured->sgallery()->select(DB::raw('`episodes`.created_at, summary, img, created_by, permalink, title ,  episodes.id ,  `episodes`.`enum` as enumNumber,  "' . \Config::get('enums.episodes') . '" as enum'))->whereIn('featured_item.tag_id', $tags)->with(['featured_tags', 'user'])->where('published', 1)->get();

        $collection = [];
        $collection = $this->getOrderedArray($collection, $news, \Config::get('enums.news'));
        $collection = $this->getOrderedArray($collection, $interviews, \Config::get('enums.interviews'));
        $collection = $this->getOrderedArray($collection, $specials, \Config::get('enums.episodes'));
        $collection = $this->getOrderedArray($collection, $summary, \Config::get('enums.episodes'));
        $collection = $this->getOrderedArray($collection, $trailer, \Config::get('enums.episodes'));
        $collection = $this->getOrderedArray($collection, $sgallery, \Config::get('enums.episodes'));
        $collection = $this->getOrderedArray($collection, $articles, \Config::get('enums.articles'));
        //dd($summary->toArray());

        //$collection =  array_merge($this->getOrderedArray($news), $this->getOrderedArray($interviews), $this->getOrderedArray($episodes), $this->getOrderedArray($articles));

        return $collection;
    }

    private function getOrderedArray($arrTemp, $items, $enum)
    {
        foreach ($items as $item) {
            $itemDetail = $item->toArray();
            $itemDetail['username'] = $item->user()->first()->name;
            $itemDetail['item_enum'] = $enum;
            if (count($item->featured_tags) > 0) {
                $tagDetails = $item->featured_tags()->get()->toArray();
                foreach ($tagDetails as $tagDetail) {
                    $arrTemp[$tagDetail['id']]['title'] = $tagDetail['title'];
                    $arrTemp[$tagDetail['id']]['id'] = $tagDetail['id'];
                    $arrTemp[$tagDetail['id']]['items'][$itemDetail['id']] = $itemDetail;
                }
            }
        }

        return $arrTemp;
    }

}