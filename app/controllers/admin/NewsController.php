<?php

use GuzzleHttp\Client;
use QueryPath\QueryPath;
use Masterminds\HTML5;

class NewsController extends BaseController
{

    /*
    |--------------------------------------------------------------------------
    | Default Home Controller
    |--------------------------------------------------------------------------
    |
    | You may wish to use controllers instead of, or in addition to, Closure
    | based routes. That's great! Here is an example controller method to
    | get you started. To route to this controller, just add the route:
    |
    |	Route::get('/', 'HomeController@showWelcome');
    |
    */

    public function getIndex()
    {
        $data = [
            'title' => 'Haberler',
            'news' => admin\News::with(['user', 'tags'])->where('type', 1)->get(),
            'type' => 1,
            'actionNews' => 'NewsController@getNewsGallery'
        ];

        return View::make('admin.news.index', $data);

    }


    public function getNewsGallery($id, $type)
    {
        $news = admin\News::with(['user', 'gallery'])->find($id);
        $data = [
            'title' => $news->title,
            'gallery' => $news->gallery()->orderBy('order', 'asc')->get()->toArray(),
            'type' => 1,
            'id' => $id
        ];

        return View::make('admin.news.gallery', $data);

    }


    public function getSpecialNews()
    {
        $data = [
            'title' => 'Özel Haberler',
            'news' => admin\News::with(['user', 'tags'])->where('type', 2)->get(),
            'type' => 2,
            'actionNews' => 'NewsController@getSpecialGallery'
        ];

        return View::make('admin.news.index', $data);

    }


    public function getSpecialGallery($id, $type)
    {
        $news = admin\News::with(['user', 'gallery'])->find($id);
        $data = [
            'title' => $news->title,
            'gallery' => $news->gallery()->orderBy('order', 'asc')->get()->toArray(),
            'type' => 2,
            'id' => $id
        ];

        return View::make('admin.news.gallery', $data);

    }


    public function getPortrait()
    {
        $data = [
            'title' => 'Foto Haberler',
            'news' => admin\News::with(['user', 'tags'])->where('type', 3)->get(),
            'type' => 3,
            'actionNews' => 'NewsController@getPortraitGallery'
        ];

        return View::make('admin.news.index', $data);

    }

    public function getPortraitGallery($id, $type)
    {
        $news = admin\News::with(['user', 'gallery'])->find($id);
        $data = [
            'title' => $news->title,
            'gallery' => $news->gallery()->orderBy('order', 'asc')->get()->toArray(),
            'type' => 3,
            'id' => $id
        ];

        return View::make('admin.news.gallery', $data);

    }

    public function getAddNews($type, $edit = false, $messages = false, $title = null, $inputs = false)
    {
        $users = \User::get();
        if ($type == 1)
            $as = 'Haber';
        elseif ($type == 2)
            $as = 'Özel Haber';
        else
            $as = 'Foto Haber';

        //Yetki Kontrolü
        $item = new admin\News();
        if ($edit && $edit != 0) {
            $item = admin\News::find($edit);
            if (!admin\RoleController::control($item)) {
                $as = $this->getAs($type);
                $message = 'Bu içeriği değiştirmeye yetkiniz bulunmamaktadır';
                return Redirect::to(action('NewsController@' . $as, ['type' => $type]))->with('alert', $message);
            }
        }

        if ($inputs) {
            if(isset($inputs['tags'])){
                $inputs['tags'] = '';
            }
            $item = new stdClass();
            $item = json_decode(json_encode($inputs), FALSE);
        }

        $data = [
            'title' => $as . ' - Yeni Haber',
            'as' => $as,
            'item' => $item,
            'type' => $type,
            'error' => $messages,
            'users' => $users,
            'serials' => admin\Serials::all()->toArray()
        ];

        return View::make('admin.news.addNews', $data);

    }

    public function postAddNews($type, $edit = false, $messages = false, $title = null)
    {
        $url = \Input::get('url');
        if($url != null){
            $link = \Links::where('link', $url)->first();
            $link->lock = 1;
            $link->save();
        }

        $user = \Auth::user();
        $input = Input::all(); //Post edilen tüm değerleri al

        $input['type'] = $type;

        // Hata mesajlarını ata
        $messages = [
            'required' => ':attribute alanı boş bırakılamaz',
            'unique' => 'Bu :attribute alanına sahip bir içerik zaten kayıt edilmiş. Lütfen :attribute alanının doğruluğunu kontrol ediniz'
        ];

        $cases = [
            'title' => 'required',
            'permalink' => 'unique:news'
        ];

        if ($edit && $edit != 0) {
            $cases['permalink'] = 'unique:news,permalink,' . $edit;
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

            return $this->getAddNews($type, false, $message, $title, $input);

        } else {

            $model = $input;
            if(!\admin\RoleController::isAdmin()){
                $model['created_by'] = 0;
            }

            if(isset($model['created_at'])){
                if($model['created_at'] == ""){
                    unset($model['created_at']);
                } else {
                    $time = strtotime($model['created_at']);
                    $model['created_at'] = date('Y-m-d H:i:s', $time);
                }
            }

            if(isset($model['is_author'])){
                if($model['is_author'] == 'on')
                    $model['is_author'] = 1;
            } else {
                $model['is_author'] = 0;
            }

            if($model['serial_id'] == 'null')
            $model['serial_id'] = 0;

            $as = $this->getAs($type);

            //add tags
            $tagsTitles = explode(',', $model['tags']);


            //Veritabanında tag varsa çek yoksa yarat.
            $tags = admin\TagController::createTags($tagsTitles, \Config::get('enums.news'));

            try {
                if ($model['thumb'] == null || $model['thumb'] == "")
                    $model['thumb'] = false;

                if ($model['square'] == null || $model['square'] == "")
                    $model['square'] = false;

                if ($model['thumbl'] == null || $model['thumbl'] == "")
                    $model['thumbl'] = false;

                if ($model['mainImg'] == null || $model['mainImg'] == "")
                    $model['mainImg'] = false;

                unset($model['img']);
                if ($edit && $edit != 0) {
                    if ($model['created_by'] == 0) {
                        unset($model['created_by']);
                    }
                    $newsUpdated = admin\News::find($edit);
                    $newsUpdated->update($model);
                    $newsUpdated->tags()->sync($tags);
                    if ($newsUpdated->img != null || $newsUpdated->img != "")
                        GalleryController::uploadContentImages($model['thumb'], $model['square'], $model['thumbl'], $model['mainImg'], $newsUpdated->img);
                    else {
                        $newsUpdated->img = GalleryController::uploadContentImages($model['thumb'], $model['square'], $model['thumbl'], $model['mainImg']);
                        $newsUpdated->save();
                    }
                    admin\LogController::log($user->id, $newsUpdated['title'] . ' isimli bir haberi güncelledi');
                    $alert = "Haber Başarıyla Güncellendi";
                } else {
                    if ($model['created_by'] == 0) {
                        $model['created_by'] = $user->id;
                    }
                    $model['img'] = GalleryController::uploadContentImages($model['thumb'], $model['square'], $model['thumbl'], $model['mainImg']);
                    $newsCreated = admin\News::create($model);
                    $newsCreated->tags()->sync($tags);
                    admin\LogController::log($user->id, $model['title'] . ' isimli bir haber ekledi');
                    $alert = "Haber Başarıyla Eklendi";
                }


                return Redirect::to(action('NewsController@' . $as, ['type' => $type]))->with('alert', $alert);
            } catch (Exception $err) {
                //TODO:: beklenmeyen bir hata oluştu ekranı

                return Redirect::to(action('NewsController@' . $as, ['type' => $type]))->with('alert', $err->getMessage());

            }

        }

    }


    public function getDeleteNews($id, $type)
    {
        $user = \Auth::user();
        $as = $this->getAs($type);

        try {
            $news = admin\News::find($id);
            if (admin\RoleController::control($news)) {
                $news->delete();
                admin\LogController::log($user->id, $news['title'] . ' isimli haberi sildi');
                $message = "Haber Başarıyla Silindi";
            } else {
                $message = "Bu haberi silmeye yetkiniz bulunmamaktadır";
            }
        } catch (Exception $err) {
            $message = $err->getMessage();

        }
        return Redirect::to(action('NewsController@' . $as, ['type' => $type]))->with('alert', $message);

    }

    public function getPublishToggle($id, $type)
    {
        $user = \Auth::user();
        $as = $this->getAs($type);

        try {
            $news = admin\News::find($id);
            if (admin\RoleController::control($news)) {
                if ($news['published']) {
                    $model['published'] = 0;
                    admin\LogController::log($user->id, $news['title'] . ' isimli haberi yayından kaldırdı');
                    $message = "İçerik Başarıyla Yayından Kaldırıldı";
                } else {
                    $model['published'] = 1;
                    admin\LogController::log($user->id, $news['title'] . ' isimli haberi yayınladı');
                    $message = "İçerik Başarıyla Yayınlandı";
                }
                admin\News::find($id)->update($model);
            } else {
                $message = "Bu içeriğin yayın durumunu değiştirmeye yetkiniz bulunmamaktadır";
            }
        } catch (Exception $err) {
            $message = $err->getMessage();

        }
        return Redirect::to(action('NewsController@' . $as, ['type' => $type]))->with('alert', $message);

    }


    private function getAs($type)
    {
        if ($type == 1)
            $as = 'getIndex';
        elseif ($type == 2)
            $as = 'getSpecialNews';
        else
            $as = 'getPortrait';

        return $as;
    }

    public function postUpload()
    {
        $file = Input::file('file');
        $destinationPath = 'uploads/haber';
        $filename = str_random(12) . '.' . $file->getClientOriginalExtension();

        $upload_success = Input::file('file')->move($destinationPath, $filename);

        if ($upload_success) {
            return Response::json(['status' => 'success', 'filename' => 'uploads/haber/' . $filename], 200);
        } else {
            return Response::json(['status' => 'error'], 400);
        }
    }

    public function getNewsInfos()
    {
        $client = new Client();
        $response = $client->get(trim(Input::get('url')));
        $body = $response->getBody();

        $html5 = new HTML5();
        $dom = $html5->loadHTML($body->getContents());

        $data['title'] = trim(qp($dom)->find('.band-header.news-band-title')->find('h1')->text());
        $data['author'] = trim(qp($dom)->find('.band-header.news-band-title')->find('h2')->text());

        $data['text'] =  trim(qp($dom)->find('.content-main-desc')->html());
        $data['short'] =  trim(qp($dom)->find('.content-main-desc')->text());

        $images = qp($dom)->find('.content-main-desc')->find('img');

        foreach($images as $image){
            $filename =  str_random(12).str_random(12);
            $url = $image->attr('src');
            copy($url, 'uploads/editor/'.$filename.'.jpeg');
            $data['text'] = str_replace($url,asset('uploads/editor/'.$filename.'.jpeg'),$data['text']);
        }


        $data['short'] = trim(implode(' ', array_slice(explode(' ', strip_tags($data['short'])), 0, 30)));

        $data['image'] = qp($dom)->find('.content-main-image')->find('img')->first()->attr('src');



        $tagsDom = qp($dom)->find('.dvTags')->find('a');
        $tagsTemp = [];
        foreach ($tagsDom as $key => $tag) {
            $tagsTemp[] = trim(str_replace(':','',$tag->text()));
        }
        $data['tags'] = implode(',',$tagsTemp);
        return Response::json($data);
    }
}
