<?php

class FeaturedController extends \BaseController
{

    public function getIndex()
    {
        $data = [
            'title' => 'Gündemler',
            'featuredList' => admin\Featured::with(['user', 'tags'])->get()
        ];

        return View::make('admin.featured.index', $data);

    }

    public function getAddFeatured($edit = false, $messages = false, $title = null, $inputs = false)
    {
        //Yetki Kontrolü
        $item = new admin\Featured();
        if ($edit && $edit != 0) {
            $item = admin\Featured::with(['tags'])->find($edit);
            /*
            if(!admin\RoleController::control($item)){
                $message = 'Bu içeriği değiştirmeye yetkiniz bulunmamaktadır';
                return Redirect::to(action('FeaturedController@getIndex'))->with('alert', $message);
            }*/
        }

        if ($inputs) {
            $item = new stdClass();
            $item = json_decode(json_encode($inputs), FALSE);
        }

        $data = [
            'title' => ' - Yeni Gündem',
            'item' => $item,
            'as' => 'Gündem',
            'error' => $messages
        ];

        return View::make('admin.featured.addFeatured', $data);

    }

    public function postAddFeatured($edit = false, $messages = false, $title = null)
    {
        $user = Auth::user();
        $input = Input::all(); //Post edilen tüm değerleri al


        // Hata mesajlarını ata
        $messages = [
            'required' => ':attribute alanı boş bırakılamaz',
            'unique' => 'Bu :attribute alanına sahip bir içerik zaten kayıt edilmiş. Lütfen :attribute alanının doğruluğunu kontrol ediniz'

        ];

        $cases = [
            'title' => 'required',
            'permalink' => 'unique:featured'
        ];

        if ($edit && $edit != 0) {
            $cases['permalink'] = 'unique:featured,permalink,' . $edit;
        }


        // Validasyon kurallarını ata
        $validator = Validator::make($input, $cases, $messages);

        // Form elemanlarını türkçeleştir
        $fields = [
            'title' => 'Başlık',
            'permalink' => 'Permalink'
        ];

        // Türkçeleştirilmiş form elemanlarını ata
        $validator->setAttributeNames($fields);

        if ($validator->fails()) //Validasyon kontrolü
        {
            $message = implode('<br />', $validator->messages()->all());

            return $this->getAddNews(false, $message, $title, $input);

        } else {

            $model = $input;
            //only one featured item can be published
            if ($model['published'] == 1) {
                $this->unpublishAll();
            }

            //add tags
            $tagsTitles = explode(',', $model['tags']);

            //Veritabanında tag varsa çek yoksa yarat.
            $tags = admin\TagController::createTags($tagsTitles, \Config::get('enums.featured'));


            try {
                if(Input::file('cover')) {
                    $file = Input::file('cover');
                    $destinationPath = 'uploads/gundem';
                    $filename = str_random(12) . '.' . $file->getClientOriginalExtension();

                    $upload_success = $file->move($destinationPath, $filename);

                    if ($upload_success) {
                        $model['cover'] = $destinationPath . '/' . $filename;
                    } else {
                        $model['cover'] = null;
                    }
                }else{
                    unset($model['cover']);
                }

                if(Input::file('img')) {
                    $file = Input::file('img');
                    $destinationPath = 'uploads/gundem';
                    $filename = str_random(12) . '.' . $file->getClientOriginalExtension();

                    $upload_success = $file->move($destinationPath, $filename);

                    if ($upload_success) {
                        $model['img'] = $destinationPath . '/' . $filename;
                    } else {
                        $model['img'] = null;
                    }
                }else{
                    unset($model['img']);
                }

                if ($edit && $edit != 0) {
                    $featuredUpdated = admin\Featured::find($edit);
                    $featuredUpdated->update($model);
                    $featuredUpdated->tags()->sync($tags);
                    admin\LogController::log($user->id, $model['title'] . ' isimli gündemin bilgilerini güncelledi');
                    $alert = "Gündem Başarıyla Güncellendi";
                } else {
                    $model['created_by'] = $user->id;
                    $featuredCreated = admin\Featured::create($model);
                    $featuredCreated->tags()->sync($tags);
                    admin\LogController::log($user->id, $model['title'] . ' isimli yeni bir gündem ekledi');
                    $alert = "Gündem Başarıyla Eklendi";
                }

                return Redirect::to(action('FeaturedController@getIndex'))->with('alert', $alert);
            } catch (Exception $err) {
                //TODO:: beklenmeyen bir hata oluştu ekranı

                return Redirect::to(action('FeaturedController@getIndex'))->with('alert', $err->getMessage());

            }

        }
    }

    public function getDeleteFeatured($id)
    {
        $user = \Auth::user();
        try {
            $featured = admin\Featured::find($id);
            admin\FeaturedItem::where('featured_id', $id)->delete();
            $featured->delete();
            admin\LogController::log($user->id, $featured['title'] . ' isimli gündemi sildi');
            $message = "Gündem Başarıyla Silindi";

        } catch (Exception $err) {
            $message = 'Gündem silinemiyor. Önce içeriklerin bağlantısını kesmeniz gerekiyor.';
        }
        return Redirect::to(action('FeaturedController@getIndex'))->with('alert', $message);

    }

    public function getPublishToggle($id)
    {
        $user = \Auth::user();
        try {
            $featured = admin\Featured::find($id);
            //if(admin\RoleController::control($featured)) {
            if ($featured['published']) {
                $model['published'] = 0;
                admin\LogController::log($user->id, $featured['title'] . ' isimli gündemi yayından kaldırdı');
                $message = "İçerik Başarıyla Yayından Kaldırıldı";
            } else {
                $model['published'] = 1;
                $this->unpublishAll();
                admin\LogController::log($user->id, $featured['title'] . ' isimli gündemi yayınladı');
                $message = "İçerik Başarıyla Yayınlandı";
            }
            admin\Featured::find($id)->update($model);
            //} else {
            //$message = "Bu içeriğin yayın durumunu değiştirmeye yetkiniz bulunmamaktadır";
            //}
        } catch (Exception $err) {
            $message = $err->getMessage();

        }
        return Redirect::to(action('FeaturedController@getIndex'))->with('alert', $message);

    }

    private function unpublishAll()
    {
        $model['published'] = 0;
        admin\Featured::where('published', 1)->update($model);
    }

    public function getFeaturedDetails($id)
    {
        try {
            $featured = admin\Featured::where('id', $id)->with('tags')->first();
            $data = [
                'featured' => $featured,
                'featuredTags' => $this->getItems($featured)
            ];
            return View::make('admin.featured.addFeaturedDetail', $data);
        } catch (Exception $err) {
            $message = 'Bu gündeme hiç alt başlık eklenmemiş. Önce alt başlıkları ekleyin';
            return Redirect::to(action('FeaturedController@getIndex'))->with('alert', $message);
        }

    }

    public function getItems($featured)
    {
        $tags = $featured->tags()->select(DB::raw('`tags`.`id` as tagid'))->lists('tagid');

        $news = $featured->news()->whereIn('featured_item.tag_id', $tags)->with('featured_tags')->get();
        $interviews = $featured->interviews()->whereIn('featured_item.tag_id', $tags)->with('featured_tags')->get();
        $episodes = $featured->episodes()->whereIn('featured_item.tag_id', $tags)->with('featured_tags')->get();
        $summary = $featured->summary()->select(DB::raw('img, title ,  episodes.id ,  `episodes`.`enum` as enumNumber,  "' . \Config::get('enums.episodes') . '" as enum'))->whereIn('featured_item.tag_id', $tags)->with('featured_tags')->get();
        $specials = $featured->specials()->select(DB::raw('img, title ,   episodes.id ,  `episodes`.`enum` as enumNumber,  "' . \Config::get('enums.episodes') . '" as enum'))->whereIn('featured_item.tag_id', $tags)->with('featured_tags')->get();
        $trailer = $featured->trailer()->select(DB::raw('img, title ,   episodes.id ,  `episodes`.`enum` as enumNumber,  "' . \Config::get('enums.episodes') . '" as enum'))->whereIn('featured_item.tag_id', $tags)->with('featured_tags')->get();
        $sgallery = $featured->sgallery()->select(DB::raw('img, title ,   episodes.id ,  `episodes`.`enum` as enumNumber,  "' . \Config::get('enums.episodes') . '" as enum'))->whereIn('featured_item.tag_id', $tags)->with('featured_tags')->get();
        $articles = $featured->articles()->whereIn('featured_item.tag_id', $tags)->with('featured_tags')->get();

        $collection = [];
        $collection = $this->getOrderedArray($collection, $news, \Config::get('enums.news'));
        $collection = $this->getOrderedArray($collection, $interviews, \Config::get('enums.interviews'));
        $collection = $this->getOrderedArray($collection, $specials, \Config::get('enums.episodes'));
        $collection = $this->getOrderedArray($collection, $summary, \Config::get('enums.episodes'));
        $collection = $this->getOrderedArray($collection, $trailer, \Config::get('enums.episodes'));
        $collection = $this->getOrderedArray($collection, $sgallery, \Config::get('enums.episodes'));
        $collection = $this->getOrderedArray($collection, $articles, \Config::get('enums.articles'));

        //$collection =  array_merge($this->getOrderedArray($news), $this->getOrderedArray($interviews), $this->getOrderedArray($episodes), $this->getOrderedArray($articles));
        /*usort($arrTemp[$tagDetail['id']]['items'], function($a, $b) {
                        return $a['created_at'] - $b['created_at'];
                    });*/

        //echo '<pre>';
        //dd($collection);
        return $collection;
    }

    public function postUpdateTag()
    {
        try {
            $input = Input::all();
            $model['title'] = $input['title'];
            admin\Tags::find($input['id'])->update($model);
            return Response::json(['status' => 'success']);
        } catch (Exception $err) {
            return Response::json(['status' => 'fail']);
        }
    }

    private function getOrderedArray($arrTemp, $items, $enum)
    {
        foreach ($items as $item) {
            $itemDetail = $item->toArray();
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

    public function getSearchItem()
    {

        $items = [];
        $news = admin\News::where('title', 'like', '%' . Input::get('q') . '%')->select(DB::raw('`title` as text ,  id , img ,   "' . \Config::get('enums.news') . '" as enumNumber'))->get()->toArray();
        if (count($news) > 0)
            $items[] = ['text' => 'Haberler', 'children' => $news];

        $interviews = admin\Interviews::where('title', 'like', '%' . Input::get('q') . '%')->select(DB::raw('`title` as text , img ,  id, "' . \Config::get('enums.interviews') . '" as enumNumber'))->get()->toArray();
        if (count($interviews) > 0)
            $items[] = ['text' => 'Röportajlar', 'children' => $interviews];

        $article = admin\Article::where('title', 'like', '%' . Input::get('q') . '%')->select(DB::raw('`title` as text , img ,   id, "' . \Config::get('enums.articles') . '" as enumNumber'))->get()->toArray();
        if (count($article) > 0)
            $items[] = ['text' => 'Köşe Yazıları', 'children' => $article];

        $episodes = admin\Episodes::where('title', 'like', '%' . Input::get('q') . '%')->select(DB::raw('`title` as text , enum , img ,  id, "episode" as enumNumber'))->get()->toArray();
        if (count($episodes) > 0)
            $items[] = ['text' => 'Diziler', 'children' => $episodes];


        return Response::json($items);

    }

    public function postAddItem()
    {
        $input = Input::all();
        $model = $input;
        try {
            $featuredItem = admin\FeaturedItem::firstOrNew($model);
            if (isset($featuredItem->created_at)) {
                $featuredItem->save();
                return Response::json(['status' => 'exists']);
            } else {
                $featuredItem->save();
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
                return Response::json(['status' => 'success', 'published' => $item['published']]);
            }
        } catch (Exception $err) {
            return Response::json(['status' => 'fail', 'error' => $err->getMessage()]);
        }
    }

    public function postDeleteItem()
    {
        try {
            admin\FeaturedItem::where(function ($query) {
                $input = Input::all();
                $query->where('featured_id', $input['featured_id'])->where('tag_id', $input['tag_id'])->where('item_id', $input['item_id'])->where('enum', $input['enum']);
            })->delete();
            return Response::json(['status' => 'success']);
        } catch (Exception $err) {
            return Response::json(['status' => 'fail', 'error' => $err->getMessage()]);
        }
    }

}
