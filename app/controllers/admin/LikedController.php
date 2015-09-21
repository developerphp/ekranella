<?php
/**
 * Created by PhpStorm.
 * User: erenyildirim
 * Date: 10/01/15
 * Time: 19:10
 */

class LikedController extends \BaseController{
    public function getLikedDetails()
    {
        try {
            $data = [
                'liked' => $this->getItems()
            ];
            return View::make('admin.liked.likedDetail', $data);
        } catch (Exception $err) {
            dd($err->getMessage());
            return Redirect::to(action('DashController@getIndex'))->with('alert', $err->getMessage());
        }

    }

    public function getItems()
    {
        $episodes = $this->getLikedCollection(new admin\Episodes(), \Config::get('enums.episodes'));
        $news = $this->getLikedCollection(new admin\News(), \Config::get('enums.news'));
        $interviews = $this->getLikedCollection(new admin\Interviews(), \Config::get('enums.interviews'));
        $articles = $this->getLikedCollection(new admin\Article(), \Config::get('enums.articles'));
        $collection = [];
        $collection['items'] = [];
        $collection = $this->getOrderedArray($collection, $news, \Config::get('enums.news'));
        $collection = $this->getOrderedArray($collection, $interviews, \Config::get('enums.interviews'));
        $collection = $this->getOrderedArray($collection, $episodes, \Config::get('enums.episodes'));
        $collection = $this->getOrderedArray($collection, $articles, \Config::get('enums.articles'));

        usort($collection['items'], function($a, $b) {
            return $a['order'] - $b['order'];
        });

        return $collection;
    }

    private function getOrderedArray($arrTemp, $items, $enum)
    {
        foreach ($items as $item) {
            $itemDetail = $item;
            $itemDetail['item_enum'] = $enum;
            $arrTemp['items'][$itemDetail['id']] = $itemDetail;
        }

        return $arrTemp;
    }

    public function postAddItem()
    {
        $all_liked_items = admin\Liked::select(['id'])->get();
        if(count($all_liked_items)>23){
            $message = 'Beğenilenler tablosuna en fazla 24 tane içerik girebilirsiniz';
            return Response::json(['status' => 'fail', 'error' => $message]);
        }
        $input = Input::all();
        $model = $input;
        try {
            $likedItem = admin\Liked::firstOrNew($model);
            if (isset($likedItem->created_at)) {
                $likedItem->save();
                return Response::json(['status' => 'exists']);
            } else {
                $likedItem->save();
                $itemId = $model['item_id'];
                $enum = $model['enum'];
                $item = null;
                switch ($enum) {
                    case 1:
                        $item = admin\Episodes::select('published')->find($itemId);
                        break;
                    case 2:
                        $item = admin\Interviews::select('published')->find($itemId);
                        break;
                    case 3:
                        $item = admin\News::select('published')->find($itemId);
                        break;
                    case 4:
                        $item = admin\Article::select('published')->find($itemId);
                        break;
                    default;
                }
                return Response::json(['status' => 'success', 'published' => $item['published'], 'likedId' => $likedItem->id]);
            }
        } catch (Exception $err) {
            return Response::json(['status' => 'fail', 'error' => $err->getMessage()]);
        }
    }

    public function postOrderLiked()
    {
        $input = Input::all();

        try {

            foreach ($input['orderArray'] as $key => $val) {
                $item = admin\Liked::find($val);
                $item->order = $key;
                $item->save();
            }

            return 'success';
        } catch (Exception $err) {

            return $err->getMessage();
        }
    }

    public function postDeleteItem()
    {
        try {
            admin\Liked::where(function ($query) {
                $input = Input::all();
                $query->where('item_id', $input['item_id'])->where('enum', $input['enum']);
            })->delete();
            return Response::json(['status' => 'success']);
        } catch (Exception $err) {
            return Response::json(['status' => 'fail', 'error' => $err->getMessage()]);
        }
    }

    public function createIdList($items){
        $ids = [];
        foreach($items as $key => $item){
            $ids['ids'][$key] = $item->item_id;
            $ids['liked'][$key] = $item->id;
            $ids['order'][$key] = $item->order;
        }
        return $ids;
    }

    public function getLikedCollection($model, $enum)
    {
        $liked_items = admin\Liked::select(['id', 'item_id', 'order'])->where('enum', $enum)->get();
        $item_ids = $this->createIdList($liked_items);

        if (count($item_ids) > 0) {
            $items = $model->whereIn('id', $item_ids['ids'])->get()->toArray();
            foreach($items as $key => $item){
                $items[$key]['liked_id'] = $item_ids['liked'][$key];
                $items[$key]['order'] = $item_ids['order'][$key];
            }
            return $items;
        } else {
            return array();
        }
    }
}