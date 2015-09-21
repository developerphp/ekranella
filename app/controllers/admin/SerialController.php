<?php

use GuzzleHttp\Client;
use QueryPath\QueryPath;
use Masterminds\HTML5;

class SerialController extends BaseController
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

    public function getForeignSeries()
    {
        $data = [
            'title' => 'Yabancı Diziler',
            'serials' => admin\Serials::with('channel')->where('type', 1)->get(),
            'type' => 1,
            'actionEpisodes' => 'SerialController@getForeignEpisodes'
        ];
        return View::make('admin.serial.index', $data);

    }


    public function getForeignEpisodes($id, $type)
    {
        $serial = admin\Serials::with('channel')->find($id);
        $data = [
            'title' => $serial->title,
            'episodes' => admin\Serials::episodes($id),
            'specials' => admin\Serials::specials($id),
            'trailers' => admin\Serials::trailers($id),
            'sgalleries' => admin\Serials::sgalleries($id),
            'serial_id' => $serial->id,
            'channel' => $serial->channel->title,
            'type' => 1,
            'actionGallery' => 'SerialController@getEpisodeGallery'
        ];

        return View::make('admin.serial.episodes', $data);

    }


    public function getLocalSeries()
    {
        $data = [
            'title' => 'Yerli Diziler',
            'serials' => admin\Serials::with('channel')->where('type', 2)->get(),
            'type' => 2,
            'actionEpisodes' => 'SerialController@getLocalEpisodes'
        ];
        return View::make('admin.serial.index', $data);
    }


    public function getLocalEpisodes($id, $type)
    {
        $serial = admin\Serials::with('channel')->find($id);
        $data = [
            'title' => $serial->title,
            'episodes' => admin\Serials::episodes($id),
            'specials' => admin\Serials::specials($id),
            'trailers' => admin\Serials::trailers($id),
            'sgalleries' => admin\Serials::sgalleries($id),
            'serial_id' => $serial->id,
            'channel' => $serial->channel->title,
            'type' => 2,
            'actionGallery' => 'SerialController@getEpisodeGallery'
        ];

        return View::make('admin.serial.episodes', $data);

    }

    public function getPrograms()
    {
        $data = [
            'title' => 'Programlar',
            'serials' => admin\Serials::with('channel')->where('type', 3)->get(),
            'type' => 3,
            'actionEpisodes' => 'SerialController@getProgramEpisodes'

        ];
        return View::make('admin.serial.index', $data);

    }

    public function getProgramEpisodes($id, $type)
    {
        $serial = admin\Serials::with('channel')->find($id);
        $data = [
            'title' => $serial->title,
            'episodes' => admin\Serials::episodes($id),
            'specials' => admin\Serials::specials($id),
            'trailers' => admin\Serials::trailers($id),
            'sgalleries' => admin\Serials::sgalleries($id),
            'serial_id' => $serial->id,
            'channel' => $serial->channel->title,
            'type' => 3,
            'actionGallery' => 'SerialController@getEpisodeGallery'
        ];

        return View::make('admin.serial.episodes', $data);

    }

    public function getAddEpisode($type, $serial_id, $edit = false, $messages = false, $title = null, $inputs = false)
    {
        $as = $this->getAs($type);
        $func = $this->getAsFunction($type);
        $users = \User::get();

        $item = new admin\Episodes();
        if ($edit && $edit != 0) {
            $item = admin\Episodes::with('tags')->find($edit);
            if (!admin\RoleController::control($item)) {
                $message = 'Bu içeriği değiştirmeye yetkiniz bulunmamaktadır';
                return Redirect::to(action('SerialController@' . $func, ['id' => $serial_id, 'type' => $type]))->with('alert', $message);
            }
        }

        if ($inputs) {
            if(isset($inputs['tags'])){
                $inputs['tags'] = '';
            }
            $item = new stdClass();
            $inputs['img'] = '';
            $inputs['airing_date'] = '';
            $item = json_decode(json_encode($inputs), FALSE);
        }

        $data = [
            'title' => $title . ' - Yeni Bölüm',
            'as' => $as,
            'item' => $item,
            'type' => $type,
            'seasons' => admin\Serials::with('season')->find($serial_id)->season->toArray(),
            'error' => $messages,
            'users' => $users
        ];

        return View::make('admin.serial.addEpisode', $data);

    }

    public function postAddEpisode($type, $serial_id, $edit = false, $messages = false, $title = null)
    {
        $url = \Input::get('url');
        if($url != null){
            $link = \Links::where('link', $url)->first();
            $link->lock = 1;
            $link->save();
        }
        $user = \Auth::user();
        $input = Input::all(); //Post edilen tüm değerleri al
        $enum = \Config::get('enums.episode');

        // Hata mesajlarını ata
        $messages = [
            'required' => ':attribute alanı boş bırakılamaz.',
            'integer' => ':attribute alanı sadece sayıdan oluşmalıdır.',
            'unique' => 'Bu :attribute alanına sahip bir içerik zaten kayıt edilmiş. Lütfen :attribute alanının doğruluğunu kontrol ediniz'
        ];

        $cases = [
            'title' => 'required',
            'season_id' => 'required|integer',
            'permalink' => 'unique:episodes',
        ];
        if (isset($input['season_id'])) {
            $cases['number'] = 'required|integer';
        }
        if ($edit && $edit != 0) {
            unset($cases['number']);
            $cases['permalink'] = 'unique:episodes,permalink,' . $edit;
        }

        // Validasyon kurallarını ata
        $validator = Validator::make($input, $cases, $messages);

        // Form elemanlarını türkçeleştir
        $fields = [
            'title' => 'Başlık',
            'season_id' => 'Sezon',
            'number' => 'Bölüm Numarası',
            'summary' => 'Özet'
        ];

        // Türkçeleştirilmiş form elemanlarını ata
        $validator->setAttributeNames($fields);

        if ($validator->fails()) //Validasyon kontrolü
        {
            $message = implode('<br />', $validator->messages()->all());

            return $this->getAddEpisode($type, $serial_id, false, $message, $title, $input);

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

            $model['enum'] = $enum['summary'];
            $model['type'] = $type;
            $as = $this->getAsFunction($type);

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

                $model['airing_date'] = $model['day_number'] . ' ' . $model['month'] . ' ' . $model['day'];
                unset($model['day_number']);
                unset($model['month']);
                unset($model['day']);


                //add tags
                $tagsTitles = explode(',', $model['tags']);

                //Veritabanında tag varsa çek yoksa yarat.
                $tags = admin\TagController::createTags($tagsTitles, \Config::get('enums.episodes'));

                if ($edit && $edit != 0) {
                    if ($model['created_by'] == 0) {
                        unset($model['created_by']);
                    }
                    $episodeUpdated = admin\Episodes::find($edit);
                    $episodeUpdated->update($model);
                    if ($episodeUpdated->img != null || $episodeUpdated->img != "")
                        GalleryController::uploadContentImages($model['thumb'], $model['square'], $model['thumbl'], $model['mainImg'], $episodeUpdated->img);
                    else {
                        $episodeUpdated->img = GalleryController::uploadContentImages($model['thumb'], $model['square'], $model['thumbl'], $model['mainImg']);
                        $episodeUpdated->save();
                    }
                    admin\LogController::log($user->id, $episodeUpdated->title . ' isimli bölüm özetini güncelledi');
                    $alert = "Bölüm Başarıyla Güncellendi";
                    $episodeUpdated->tags()->sync($tags);
                } else {
                    if ($model['created_by'] == 0) {
                        $model['created_by'] = $user->id;
                    }
                    $model['img'] = GalleryController::uploadContentImages($model['thumb'], $model['square'], $model['thumbl'], $model['mainImg']);
                    $episodeCreated = admin\Episodes::create($model);
                    admin\LogController::log($user->id, $model['title'] . ' isimli yeni bir bölüm özeti ekledi');
                    $alert = "Bölüm Başarıyla Eklendi";
                    $episodeCreated->tags()->sync($tags);
                }

                return Redirect::to(action('SerialController@' . $as, ['id' => $serial_id, 'type' => $type]))->with('alert', $alert);
            } catch (Exception $err) {
                return Redirect::to(action('SerialController@' . $as, ['id' => $serial_id, 'type' => $type]))->with('alert', $err->getMessage());

            }

        }

    }


    public function getAddTrailer($type, $serial_id, $edit = false, $messages = false, $title = null, $inputs = false)
    {
        $as = $this->getAs($type);
        $func = $this->getAsFunction($type);
        $users = \User::get();

        $item = new admin\Episodes();
        if ($edit && $edit != 0) {
            $item = admin\Episodes::with('tags')->find($edit);
            if (!admin\RoleController::control($item)) {
                $message = 'Bu içeriği değiştirmeye yetkiniz bulunmamaktadır';
                return Redirect::to(action('SerialController@' . $func, ['id' => $serial_id, 'type' => $type]))->with('alert', $message);
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
            'title' => $title . ' - Yeni Fragman',
            'as' => $as,
            'item' => $item,
            'type' => $type,
            'seasons' => admin\Serials::with('season')->find($serial_id)->season->toArray(),
            'error' => $messages,
            'users' => $users
        ];

        return View::make('admin.serial.addTrailer', $data);

    }

    public function postAddTrailer($type, $serial_id, $edit = false, $messages = false, $title = null)
    {
        $user = \Auth::user();
        $input = Input::all(); //Post edilen tüm değerleri al
        $enum = \Config::get('enums.episode');

        // Hata mesajlarını ata
        $messages = [
            'required' => ':attribute alanı boş bırakılamaz.',
            'integer' => ':attribute alanı sadece sayıdan oluşmalıdır.',
            'unique' => 'Bu :attribute alanına sahip bir içerik zaten kayıt edilmiş. Lütfen :attribute alanının doğruluğunu kontrol ediniz'
        ];

        $cases = [
            'title' => 'required',
            'season_id' => 'required|integer',
            'permalink' => 'unique:episodes',
        ];
        if (isset($input['season_id'])) {
            $cases['number'] = 'required|integer';
        }
        if ($edit && $edit != 0) {
            unset($cases['number']);
            $cases['permalink'] = 'unique:episodes,permalink,' . $edit;
        }

        // Validasyon kurallarını ata
        $validator = Validator::make($input, $cases, $messages);

        // Form elemanlarını türkçeleştir
        $fields = [
            'title' => 'Başlık',
            'season_id' => 'Sezon',
            'number' => 'Bölüm Numarası',
            'summary' => 'Fragman'
        ];

        // Türkçeleştirilmiş form elemanlarını ata
        $validator->setAttributeNames($fields);

        if ($validator->fails()) //Validasyon kontrolü
        {
            $message = implode('<br />', $validator->messages()->all());
            $input['img'] = false;

            return $this->getAddTrailer($type, $serial_id, false, $message, $title, $input);

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

            $model['type'] = $type;
            $model['enum'] = $enum['trailer'];

            $as = $this->getAsFunction($type);

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

                //add tags
                $tagsTitles = explode(',', $model['tags']);

                //Veritabanında tag varsa çek yoksa yarat.
                $tags = array();
                foreach ($tagsTitles as $tagTitle) {
                    if ($tagTitle != '') {
                        $tempObj = admin\Tags::firstOrCreate(array('title' => $tagTitle));
                        $tags[$tempObj->id] = ['enum' => \Config::get('enums.episodes')];
                    }
                }

                if ($edit && $edit != 0) {
                    if ($model['created_by'] == 0) {
                        unset($model['created_by']);
                    }
                    $episodeUpdated = admin\Episodes::find($edit);
                    $episodeUpdated->update($model);
                    if ($episodeUpdated->img != null || $episodeUpdated->img != "")
                        GalleryController::uploadContentImages($model['thumb'], $model['square'], $model['thumbl'], $model['mainImg'], $episodeUpdated->img);
                    else {
                        $episodeUpdated->img = GalleryController::uploadContentImages($model['thumb'], $model['square'], $model['thumbl'], $model['mainImg']);
                        $episodeUpdated->save();
                    }

                    admin\LogController::log($user->id, $episodeUpdated->title . 'isimli fragmanı güncelledi');
                    $alert = "Fragman Başarıyla Güncellendi";
                    $episodeUpdated->tags()->sync($tags);
                } else {
                    if ($model['created_by'] == 0) {
                        $model['created_by'] = $user->id;
                    }
                    $model['img'] = GalleryController::uploadContentImages($model['thumb'], $model['square'], $model['thumbl'], $model['mainImg']);
                    $episodeCreated = admin\Episodes::create($model);
                    admin\LogController::log($user->id, $model['title'] . ' isimli yeni bir fragman ekledi');
                    $alert = "Fragman Başarıyla Eklendi";
                    $episodeCreated->tags()->sync($tags);
                }

                return Redirect::to(action('SerialController@' . $as, ['id' => $serial_id, 'type' => $type]))->with('alert', $alert);
            } catch (Exception $err) {
                //TODO:: beklenmeyen bir hata oluştu ekranı

                return Redirect::to(action('SerialController@' . $as, ['id' => $serial_id, 'type' => $type]))->with('alert', $err->getMessage());

            }

        }

    }


    public function getAddSpecial($type, $serial_id, $edit = false, $messages = false, $title = null, $inputs = false)
    {
        $as = $this->getAs($type);
        $func = $this->getAsFunction($type);
        $users = \User::get();

        $item = new admin\Episodes();
        if ($edit && $edit != 0) {
            $item = admin\Episodes::with('tags')->find($edit);
            if (!admin\RoleController::control($item)) {
                $message = 'Bu içeriği değiştirmeye yetkiniz bulunmamaktadır';
                return Redirect::to(action('SerialController@' . $func, ['id' => $serial_id, 'type' => $type]))->with('alert', $message);
            }
        }

        if ($inputs) {
            if(isset($inputs['tags'])){
                $inputs['tags'] = '';
            }
            $item = new stdClass();
            $item = json_decode(json_encode($inputs), FALSE);
            $item->tags = false;
        }

        $data = [
            'title' => $title . ' - Yeni Özel Yazı',
            'as' => $as,
            'item' => $item,
            'type' => $type,
            'seasons' => admin\Serials::with('season')->find($serial_id)->season->toArray(),
            'error' => $messages,
            'users' => $users
        ];

        return View::make('admin.serial.addSpecial', $data);

    }

    public function postAddSpecial($type, $serial_id, $edit = false, $messages = false, $title = null)
    {
        $url = \Input::get('url');
        if($url != null){
            $link = \Links::where('link', $url)->first();
            $link->lock = 1;
            $link->save();
        }

        $user = \Auth::user();
        $input = Input::all(); //Post edilen tüm değerleri al
        $enum = \Config::get('enums.episode');

        // Hata mesajlarını ata
        $messages = [
            'required' => ':attribute alanı boş bırakılamaz.',
            'integer' => ':attribute alanı sadece sayıdan oluşmalıdır.',
            'unique' => 'Bu :attribute alanına sahip bir içerik zaten kayıt edilmiş. Lütfen :attribute alanının doğruluğunu kontrol ediniz',
            'max' => ':attribute alanı 31\'den büyük olamaz'
        ];

        $cases = [
            'title' => 'required',
            'season_id' => 'required|integer',
            'permalink' => 'unique:episodes',
            'day_number' => 'max:31',
        ];
        if (isset($input['season_id'])) {
            $cases['number'] = 'integer';
        }
        if ($edit && $edit != 0) {
            unset($cases['number']);
            $cases['permalink'] = 'unique:episodes,permalink,' . $edit;
        }

        // Validasyon kurallarını ata
        $validator = Validator::make($input, $cases, $messages);

        // Form elemanlarını türkçeleştir
        $fields = [
            'title' => 'Başlık',
            'season_id' => 'Sezon',
            'day_number' => 'Gün',
            'number' => 'Bölüm Numarası',
            'summary' => 'Özet'
        ];

        // Türkçeleştirilmiş form elemanlarını ata
        $validator->setAttributeNames($fields);

        if ($validator->fails()) //Validasyon kontrolü
        {
            $message = implode('<br />', $validator->messages()->all());

            return $this->getAddSpecial($type, $serial_id, false, $message, $title, $input);

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

            $model['type'] = $type;
            $model['enum'] = $enum['specials'];

            $as = $this->getAsFunction($type);

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

                if($model['day_number'] != 0 && $model['month'] != 0 && $model['day'] != 0) {
                    $model['airing_date'] = $model['day_number'] . ' ' . $model['month'] . ' ' . $model['day'];
                } else {
                    $model['airing_date'] = "";
                }
                unset($model['day_number']);
                unset($model['month']);
                unset($model['day']);

                //add tags
                $tagsTitles = explode(',', $model['tags']);

                //Veritabanında tag varsa çek yoksa yarat.
                $tags = array();
                foreach ($tagsTitles as $tagTitle) {
                    if ($tagTitle != '') {
                        $tempObj = admin\Tags::firstOrCreate(array('title' => $tagTitle));
                        $tags[$tempObj->id] = ['enum' => \Config::get('enums.episodes')];
                    }
                }

                if ($edit && $edit != 0) {
                    if ($model['created_by'] == 0) {
                        unset($model['created_by']);
                    }
                    $episodeUpdated = admin\Episodes::find($edit);
                    $episodeUpdated->update($model);
                    if ($episodeUpdated->img != null || $episodeUpdated->img != "")
                        GalleryController::uploadContentImages($model['thumb'], $model['square'], $model['thumbl'], $model['mainImg'], $episodeUpdated->img);
                    else {
                        $episodeUpdated->img = GalleryController::uploadContentImages($model['thumb'], $model['square'], $model['thumbl'], $model['mainImg']);
                        $episodeUpdated->save();
                    }
                    admin\LogController::log($user->id, $episodeUpdated->title . ' isimli özel yazıyı güncelledi');
                    $alert = "Özel Yazı Başarıyla Güncellendi";
                    $episodeUpdated->tags()->sync($tags);
                } else {
                    if ($model['created_by'] == 0) {
                        $model['created_by'] = $user->id;
                    }
                    $model['img'] = GalleryController::uploadContentImages($model['thumb'], $model['square'], $model['thumbl'], $model['mainImg']);
                    $episodeCreated = admin\Episodes::create($model);
                    admin\LogController::log($user->id, $model['title'] . ' isimli yeni bir özel yazı ekledi');
                    $alert = "Özel Yazı Başarıyla Eklendi";
                    $episodeCreated->tags()->sync($tags);
                }

                return Redirect::to(action('SerialController@' . $as, ['id' => $serial_id, 'type' => $type]))->with('alert', $alert);
            } catch (Exception $err) {
                //TODO:: beklenmeyen bir hata oluştu ekranı

                return Redirect::to(action('SerialController@' . $as, ['id' => $serial_id, 'type' => $type]))->with('alert', $err->getMessage());

            }

        }

    }

    public function getEpisodeGallery($id, $type)
    {
        $episode = admin\Episodes::with(['gallery'])->find($id);
        $data = [
            'type' => $type,
            'title' => $episode->title,
            'gallery' => $episode->gallery()->orderBy('order', 'asc')->get()->toArray(),
            'id' => $id
        ];

        return View::make('admin.serial.gallery', $data);
    }

    public function getSGalleryGallery($id, $type)
    {
        $sgallery = admin\Episodes::with(['gallery'])->find($id);
        $data = [
            'type' => $type,
            'title' => $sgallery->title,
            'gallery' => $sgallery->gallery()->orderBy('order', 'asc')->get()->toArray(),
            'id' => $id
        ];

        return View::make('admin.serial.gallery', $data);
    }

    public function getAddSGallery($type, $serial_id, $edit = false, $messages = false, $title = null, $inputs = false)
    {
        $as = $this->getAs($type);
        $func = $this->getAsFunction($type);
        $users = \User::get();

        $item = new admin\Episodes();
        if ($edit && $edit != 0) {
            $item = admin\Episodes::with('tags')->find($edit);
            if (!admin\RoleController::control($item)) {
                $message = 'Bu içeriği değiştirmeye yetkiniz bulunmamaktadır';
                return Redirect::to(action('SerialController@' . $func, ['id' => $serial_id, 'type' => $type]))->with('alert', $message);
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
            'title' => $title . ' - Yeni Galeri',
            'as' => $as,
            'item' => $item,
            'type' => $type,
            'seasons' => admin\Serials::with('season')->find($serial_id)->season->toArray(),
            'error' => $messages,
            'users' => $users
        ];

        return View::make('admin.serial.addGallery', $data);

    }

    public function postAddSGallery($type, $serial_id, $edit = false, $messages = false, $title = null)
    {
        $url = \Input::get('url');
        if($url != null){
            $link = \Links::where('link', $url)->first();
            $link->lock = 1;
            $link->save();
        }

        $user = \Auth::user();
        $input = Input::all(); //Post edilen tüm değerleri al
        $enum = \Config::get('enums.episode');

        // Hata mesajlarını ata
        $messages = [
            'required' => ':attribute alanı boş bırakılamaz.',
            'integer' => ':attribute alanı sadece sayıdan oluşmalıdır.',
            'unique' => 'Bu :attribute alanına sahip bir içerik zaten kayıt edilmiş. Lütfen :attribute alanının doğruluğunu kontrol ediniz'
        ];

        $cases = [
            'title' => 'required',
            'season_id' => 'required|integer',
            'permalink' => 'unique:episodes',
        ];
        if (isset($input['season_id'])) {
            $cases['number'] = 'required|integer';
        }
        if ($edit && $edit != 0) {
            unset($cases['number']);
            $cases['permalink'] = 'unique:episodes,permalink,' . $edit;
        }

        // Validasyon kurallarını ata
        $validator = Validator::make($input, $cases, $messages);

        // Form elemanlarını türkçeleştir
        $fields = [
            'title' => 'Başlık',
            'season_id' => 'Sezon',
            'number' => 'Bölüm Numarası',
            'summary' => 'Galeri Açıklama'
        ];

        // Türkçeleştirilmiş form elemanlarını ata
        $validator->setAttributeNames($fields);

        if ($validator->fails()) //Validasyon kontrolü
        {
            $message = implode('<br />', $validator->messages()->all());
            $input['img'] = false;

            return $this->getAddSGallery($type, $serial_id, false, $message, $title, $input);

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

            $model['type'] = $type;
            $model['enum'] = $enum['sgallery'];

            $as = $this->getAsFunction($type);

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

                //add tags
                $tagsTitles = explode(',', $model['tags']);

                //Veritabanında tag varsa çek yoksa yarat.
                $tags = array();
                foreach ($tagsTitles as $tagTitle) {
                    if ($tagTitle != '') {
                        $tempObj = admin\Tags::firstOrCreate(array('title' => $tagTitle));
                        $tags[$tempObj->id] = ['enum' => \Config::get('enums.episodes')];
                    }
                }

                if ($edit && $edit != 0){
                    if ($model['created_by'] == 0) {
                        unset($model['created_by']);
                    }
                    $sgalleryUpdated = admin\Episodes::find($edit);
                    $sgalleryUpdated->update($model);
                    if ($sgalleryUpdated->img != null || $sgalleryUpdated->img != "")
                        GalleryController::uploadContentImages($model['thumb'], $model['square'], $model['thumbl'], $model['mainImg'], $sgalleryUpdated->img);
                    else {
                        $sgalleryUpdated->img = GalleryController::uploadContentImages($model['thumb'], $model['square'], $model['thumbl'], $model['mainImg']);
                        $sgalleryUpdated->save();
                    }

                    admin\LogController::log($user->id, $sgalleryUpdated->title . ' isimli galeriyi güncelledi');
                    $alert = "Galeri Başarıyla Güncellendi";
                    $sgalleryUpdated->tags()->sync($tags);
                } else {
                    if ($model['created_by'] == 0) {
                        $model['created_by'] = $user->id;
                    }
                    $model['img'] = GalleryController::uploadContentImages($model['thumb'], $model['square'], $model['thumbl'], $model['mainImg']);
                    $sgalleryCreated = admin\Episodes::create($model);
                    admin\LogController::log($user->id, $model['title'] . ' isimli yeni bir galeri ekledi');
                    $alert = "Galeri Başarıyla Eklendi";
                    $sgalleryCreated->tags()->sync($tags);
                }

                return Redirect::to(action('SerialController@' . $as, ['id' => $serial_id, 'type' => $type]))->with('alert', $alert);
            } catch (Exception $err) {
                //TODO:: beklenmeyen bir hata oluştu ekranı

                return Redirect::to(action('SerialController@' . $as, ['id' => $serial_id, 'type' => $type]))->with('alert', $err->getMessage());

            }

        }

    }


    public function getAddSeries($type, $edit = false, $messages = false, $inputs = false)
    {
        $as = $this->getAs($type);


        $item = new admin\Serials();
        if ($edit && $edit != 0) {
            $item = admin\Serials::find($edit);
        }

        if ($inputs) {
            $item = new stdClass();
            $item = json_decode(json_encode($inputs), FALSE);
            $item->tags = false;
        }

        $data = [
            'title' => $as . ' - Ekle',
            'as' => $as,
            'item' => $item,
            'type' => $type,
            'error' => $messages,
            'channels' => admin\Channel::all()->toArray()
        ];

        return View::make('admin.serial.addSerial', $data);

    }

    public function postAddSeries($type, $edit = false, $messages = false, $inputs = false)
    {
        $user = \Auth::user();
        $input = Input::all(); //Post edilen tüm değerleri al
        $input['type'] = $type;

        $input['is_popular'] = isset($_POST['is_popular']) ? $_POST['is_popular'] : 0;

        if (isset($_POST['is_masked']) && $_POST['is_masked'] = 'on')
            $input['is_masked'] = 1;
        else
            $input['is_masked'] = 0;

        $tempDayArray = [];
        if (isset($input['day']))
            for ($i = 0; count($input['day']) > $i; ++$i) {
                $tempDayArray[] = $input['day'][$i] . ', ' . $input['time'][$i];
            }

        $input['airing'] = serialize($tempDayArray);
        // Hata mesajlarını ata
        $messages = [
            'required' => ':attribute alanı boş bırakılamaz.',
            'integer' => ':attribute alanı sadece sayıdan oluşmalıdır.',
            'unique' => 'Bu :attribute alanına sahip bir içerik zaten kayıt edilmiş. Lütfen :attribute alanının doğruluğunu kontrol ediniz'
        ];

        $cases = [
            'title' => 'required',
            'permalink' => 'unique:serials',
            'channel_id' => 'required|integer'
        ];

        if ($edit && $edit != 0) {
            $cases['permalink'] = 'unique:serials,permalink,' . $edit;
        }


        // Validasyon kurallarını ata
        $validator = Validator::make($input, $cases, $messages);

        // Form elemanlarını türkçeleştir
        $fields = [
            'title' => 'Başlık',
            'permalink' => 'Permalink',
            'channel_id' => 'Kanal'
        ];

        // Türkçeleştirilmiş form elemanlarını ata
        $validator->setAttributeNames($fields);

        if ($validator->fails()) //Validasyon kontrolü
        {
            $message = implode('<br />', $validator->messages()->all());

            return $this->getAddSeries($type, false, $message, $input);

        } else {

            $model = $input;

            if ($type == 1)
                $as = 'getForeignSeries';
            elseif ($type == 2)
                $as = 'getLocalSeries';
            else
                $as = 'getPrograms';

            if (Input::file('cover')) {
                $file = Input::file('cover');
                $destinationPath = 'uploads/dizi';
                $filename = str_random(12) . str_random(12) . '.' . $file->getClientOriginalExtension();

                $upload_success = $file->move($destinationPath, $filename);

                if ($upload_success) {
                    $model['cover'] = $destinationPath . '/' . $filename;
                } else {
                    $model['cover'] = null;
                }
            } else {
                unset($model['cover']);
            }

            if (Input::file('img')) {
                $file = Input::file('img');
                $destinationPath = 'uploads/dizi';
                $filename = str_random(12) . str_random(12) . '.' . $file->getClientOriginalExtension();

                $upload_success = $file->move($destinationPath, $filename);

                if ($upload_success) {
                    $model['img'] = $destinationPath . '/' . $filename;
                } else {
                    $model['img'] = null;
                }
            } else {
                unset($model['img']);
            }
            try {


                if ($edit && $edit != 0) {
                    $serieUpdated = admin\Serials::find($edit);
                    $serieUpdated->update($model);
                    admin\LogController::log($user->id, $serieUpdated->title . ' dizisinin bilgilerini güncelledi');
                    $alert = "Dizi/Program Başarıyla Güncellendi";
                } else {
                    $model['created_by'] = $user->id;
                    admin\Serials::create($model);
                    admin\LogController::log($user->id, $model['title'] . ' isimli yeni bir dizi ekledi');
                    $alert = "Dizi/Program Başarıyla Eklendi";
                }


                return Redirect::to(action('SerialController@' . $as))->with('alert', $alert);
            } catch (Exception $err) {
                //TODO:: beklenmeyen bir hata oluştu ekranı

                return Redirect::to(action('SerialController@' . $as))->with('alert', $err->getMessage());

            }

        }

    }


    public function getAddSeason($type, $serial_id, $edit = false, $messages = false, $inputs = false)
    {
        $as = $this->getAs($type);
        $serial = \admin\Serials::where('id', $serial_id)->with(['season'])->first();

        $item = new admin\Seasons();
        if ($edit && $edit != 0) {
            $item = admin\Seasons::find($edit);
        }

        if ($inputs) {
            $item = new stdClass();
            $item = json_decode(json_encode($inputs), FALSE);
        }

        $data = [
            'title' => $as . ' - Sezonu Ekle',
            'as' => $as,
            'item' => $item,
            'type' => $type,
            'error' => $messages,
            'seasons' => $serial->season
        ];

        return View::make('admin.serial.addSeason', $data);

    }

    public function postAddSeason($type, $serial_id, $edit = false, $messages = false, $inputs = false)
    {
        $input = Input::all(); //Post edilen tüm değerleri al
        $user = \Auth::user();
        $input['serial_id'] = $serial_id;

        // Hata mesajlarını ata
        $messages = [
            'required' => ':attribute alanı boş bırakılamaz.',
            'integer' => ':attribute alanı sadece sayılardan oluşmalıdır.',
            'unique' => 'Bu :attribute alanına sahip bir içerik zaten kayıt edilmiş. Lütfen :attribute alanının doğruluğunu kontrol ediniz'
        ];

        $cases = [
            'number' => 'required|integer|unique:seasons,number,NULL,id,number,' . $input['number'] . ',serial_id,' . $serial_id
        ];


        // Validasyon kurallarını ata
        $validator = Validator::make($input, $cases, $messages);

        // Form elemanlarını türkçeleştir
        $fields = [
            'number' => 'Sezon Numarası',
        ];

        // Türkçeleştirilmiş form elemanlarını ata
        $validator->setAttributeNames($fields);

        if ($validator->fails()) //Validasyon kontrolü
        {
            $message = implode('<br />', $validator->messages()->all());

            return $this->getAddSeason($type, $serial_id, 0, $message, $input);

        } else {

            $model = $input;

            $as = $this->getAsFunction($type);


            try {


                if ($edit && $edit != 0) {
                    admin\Seasons::find($edit)->update($model);
                    $alert = "Sezon Başarıyla Güncellendi";
                } else {
                    $model['created_by'] = $user->id;
                    admin\Seasons::create($model);
                    $alert = "Sezon Başarıyla Eklendi";
                }


                return Redirect::to(action('SerialController@getAddSeason', [ 'type' => $type, 'serial_id' => $serial_id]))->with('alert', $alert);
            } catch (Exception $err) {
                //TODO:: beklenmeyen bir hata oluştu ekranı

                return Redirect::to(action('SerialController@' . $as, ['serial_id' => $serial_id, 'type' => $type]))->with('alert', $err->getMessage());

            }

        }

    }

    public function postDeleteSeason(){
        try {
            admin\Seasons::where(function ($query) {
                $input = Input::all();
                $query->where('id', $input['item_id']);
            })->delete();
            return Response::json(['status' => 'success']);
        } catch (Exception $err) {
            return Response::json(['status' => 'fail', 'error' => $err->getMessage()]);
        }
    }

    public function getDeleteTrailer($id, $serial_id, $type)
    {
        $user = \Auth::user();
        $as = $this->getAsFunction($type);

        try {
            $trailer = admin\Episodes::find($id);
            if (admin\RoleController::control($trailer)) {
                $trailer->delete();
                admin\LogController::log($user->id, $trailer->title . ' isimli fragmanı sildi');
                $message = "Fragman Başarıyla Silindi";
            } else {
                $message = "Bu içeriği silmeye yetkiniz bulunmamaktadır";
            }
        } catch (Exception $err) {
            $message = $err->getMessage();

        }
        return Redirect::to(action('SerialController@' . $as, ['serial_id' => $serial_id, 'type' => $type]))->with('alert', $message);

    }

    public function getDeleteSGallery($id, $serial_id, $type)
    {
        $user = \Auth::user();
        $as = $this->getAsFunction($type);

        try {
            $sgallery = admin\Episodes::find($id);
            if (admin\RoleController::control($sgallery)) {
                $sgallery->delete();
                admin\LogController::log($user->id, $sgallery->title . ' isimli galeriyi sildi');
                $message = "Galeri Başarıyla Silindi";
            } else {
                $message = "Bu içeriği silmeye yetkiniz bulunmamaktadır";
            }
        } catch (Exception $err) {
            $message = $err->getMessage();

        }
        return Redirect::to(action('SerialController@' . $as, ['serial_id' => $serial_id, 'type' => $type]))->with('alert', $message);

    }

    public function getDeleteEpisode($id, $serial_id, $type)
    {
        $user = \Auth::user();
        $as = $this->getAsFunction($type);

        try {
            $episode = admin\Episodes::find($id);
            if (admin\RoleController::control($episode)) {
                $episode->delete();
                admin\LogController::log($user->id, $episode->title . ' isimli bölüm özetini sildi');
                $message = "Bölüm Özeti Başarıyla Silindi";
            } else {
                $message = "Bu içeriği silmeye yetkiniz bulunmamaktadır";
            }
        } catch (Exception $err) {
            $message = $err->getMessage();

        }
        return Redirect::to(action('SerialController@' . $as, ['serial_id' => $serial_id, 'type' => $type]))->with('alert', $message);

    }

    public function getDeleteSpecial($id, $serial_id, $type)
    {
        $user = \Auth::user();
        $as = $this->getAsFunction($type);

        try {
            $special = admin\Episodes::find($id);
            if (admin\RoleController::control($special)) {
                $special->delete();
                admin\LogController::log($user->id, $special->title . ' isimli özel yazıyı sildi');
                $message = "Özel Yazı Başarıyla Silindi";
            } else {
                $message = "Bu içeriği silmeye yetkiniz bulunmamaktadır";
            }

        } catch (Exception $err) {
            $message = $err->getMessage();

        }
        return Redirect::to(action('SerialController@' . $as, ['serial_id' => $serial_id, 'type' => $type]))->with('alert', $message);

    }


    public function getDeleteSeries($serial_id, $type)
    {
        $user = \Auth::user();
        if ($type == 1)
            $as = 'getForeignSeries';
        elseif ($type == 2)
            $as = 'getLocalSeries';
        else
            $as = 'getPrograms';

        try {
            $serie = admin\Serials::find($serial_id);
            $serie->delete();
            admin\LogController::log($user->id, $serie->title . ' dizisini sildi');
            $message = "İçerik Başarıyla Silindi";
        } catch (Exception $err) {
            $message = $err->getMessage();

        }
        return Redirect::to(action('SerialController@' . $as, ['serial_id' => $serial_id, 'type' => $type]))->with('alert', $message);

    }

    public function getPublishToggle($id, $serial_id, $type)
    {
        $user = \Auth::user();
        $as = $this->getAsFunction($type);

        try {
            $episode = admin\Episodes::find($id);
            if (admin\RoleController::control($episode)) {
                if ($episode['published']) {
                    $model['published'] = 0;
                    admin\LogController::log($user->id, $episode->title . ' adlı bölüm öğesini yayından kaldırdı');
                    $message = "İçerik Başarıyla Yayından Kaldırıldı";
                } else {
                    $model['published'] = 1;
                    admin\LogController::log($user->id, $episode->title . ' adlı bölüm öğesini yayınladı');
                    $message = "İçerik Başarıyla Yayınlandı";
                }
                admin\Episodes::find($id)->update($model);
            } else {
                $message = 'Bu içeriğin yayın durumunu değiştirmeye yetkiniz bulunmamaktadır';
            }
        } catch (Exception $err) {
            $message = $err->getMessage();

        }
        return Redirect::to(action('SerialController@' . $as, ['serial_id' => $serial_id, 'type' => $type]))->with('alert', $message);
    }

    public function postUpload()
    {
        $file = Input::file('file');
        $destinationPath = 'uploads/bolum';
        $filename = str_random(12) . str_random(12) . '.' . $file->getClientOriginalExtension();

        $upload_success = Input::file('file')->move($destinationPath, $filename);

        if ($upload_success) {
            return Response::json(['status' => 'success', 'filename' => 'uploads/bolum/' . $filename], 200);
        } else {
            return Response::json(['status' => 'error'], 400);
        }
    }

    private function getAs($type)
    {
        if ($type == 1)
            return 'Yabancı Dizi';
        elseif ($type == 2)
            return 'Yerli Dizi';
        elseif ($type == 3)
            return 'Program';
    }

    private function getAsFunction($type)
    {
        if ($type == 1)
            return 'getForeignEpisodes';
        elseif ($type == 2)
            return 'getLocalEpisodes';
        else
            return 'getProgramEpisodes';

    }

    public function getAddChannel($edit = false, $messages = false, $inputs = false)
    {
        $as = 'Kanal';

        $item = new admin\Channel();
        if ($edit && $edit != 0) {
            $item = admin\Channel::find($edit);
        }

        if ($inputs) {
            $item = new stdClass();
            $item = json_decode(json_encode($inputs), FALSE);
        }

        $data = [
            'title' => $as . ' - Ekle',
            'as' => $as,
            'item' => $item,
            'error' => $messages
        ];

        return View::make('admin.serial.addChannel', $data);
    }

    public function postAddChannel($edit = false, $messages = false, $inputs = false)
    {
        $user = \Auth::user();
        $input = Input::all(); //Post edilen tüm değerleri al

        // Hata mesajlarını ata
        $messages = [
            'required' => ':attribute alanı boş bırakılamaz.',
            'unique' => 'Bu :attribute alanına sahip bir içerik zaten kayıt edilmiş. Lütfen :attribute alanının doğruluğunu kontrol ediniz'
        ];

        $cases = [
            'title' => 'required|unique:channels,title'
        ];


        // Validasyon kurallarını ata
        $validator = Validator::make($input, $cases, $messages);

        // Form elemanlarını türkçeleştir
        $fields = [
            'title' => 'Kanal Adı'
        ];

        // Türkçeleştirilmiş form elemanlarını ata
        $validator->setAttributeNames($fields);

        if ($validator->fails()) //Validasyon kontrolü
        {
            $message = implode('<br />', $validator->messages()->all());

            return $this->getAddChannel(false, $message, $input);

        } else {

            $model = $input;

            try {


                if ($edit && $edit != 0) {
                    $channel = admin\Channel::find($edit);
                    $channel->update($model);
                    admin\LogController::log($user->id, $channel->title . ' isimli kanalı güncelledi');
                    $alert = "Kanal Başarıyla Güncellendi";
                } else {
                    admin\Channel::create($model);
                    admin\LogController::log($user->id, $model['title'] . ' isimli yeni bir kanal ekledi');
                    $alert = "Kanal Başarıyla Eklendi";
                }


                return Redirect::action('SerialController@getChannels')->with('alert', $alert);
            } catch (Exception $err) {
                //TODO:: beklenmeyen bir hata oluştu ekranı

                return Redirect::action('SerialController@getChannels')->with('alert', $err->getMessage());

            }

        }

    }


    public function getChannels()
    {
        $data = [
            'title' => 'Kanallar',
            'channels' => admin\Channel::get(),
        ];
        return View::make('admin.serial.channels', $data);

    }


    public function getDeleteChannel($id)
    {
        $user = \Auth::user();
        try {
            $channel = admin\Channel::find($id);
            $channel->delete();
            admin\LogController::log($user->id, $channel->title . ' isimli kanalı sildi');
            $message = "Kanal Başarıyla Silindi";
        } catch (Exception $err) {
            $code = $err->getCode();
            if ($code == "23000") {
                $message = "Kanala bağlı dizi veya programlar mevcut. Bu kanalı silemezsiniz.";
            } else {
                $message = $err->getMessage();
            }
        }
        return Redirect::to(action('SerialController@getChannels'))->with('alert', $message);

    }

    public function postTempBaseImage(){
        $type = pathinfo(Input::get('image'), PATHINFO_EXTENSION);
        $data = file_get_contents(Input::get('image'));
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        return Response::json(['src' => $base64]);
    }

    public function getEpisodeInfos(){
        $client = new Client();
        $response = $client->get(Input::get('url'));
        $body = $response->getBody();

        $html5 = new HTML5();
        $dom = $html5->loadHTML($body->getContents());

        $data['title'] = trim(qp($dom)->find('.recap-title')->find('h2')->text());

        $data['text'] =  '<p>'.trim(qp($dom)->find('.alignleft.wp-caption')->first()->html()).'</p>'.trim(qp($dom)->find('.storycontent')->html());
        $data['short'] =  trim(qp($dom)->find('.storycontent')->text());

        $images = qp($dom)->find('.storycontent')->find('img');

        foreach($images as $image){
            $filename =  str_random(12).str_random(12);
            $url = $image->attr('src');
            copy($url, 'uploads/editor/'.$filename.'.jpeg');
            $data['text'] = str_replace($url,asset('uploads/editor/'.$filename.'.jpeg'),$data['text']);
        }

        $data['short'] = trim(implode(' ', array_slice(explode(' ', strip_tags($data['short'])), 0, 30)));

        $data['image'] = qp($dom)->find('.alignleft.wp-caption')->find('img')->first()->attr('src');

        $data['date'] = trim(qp($dom)->find('.recap-title')->find('p')->text());
        $data['date'] = str_replace(qp($dom)->find('.recap-title')->find('p')->find('a')->text(), '' , $data['date'] );
        $data['date'] = explode(',' , $data['date']);

        $patchController =  new PatchController();
        $data['date'] = $patchController->getDateFromString(trim(str_replace('&nbsp;&nbsp;&nbsp;', '' , $data['date'][0] )));


        $tagsDom = qp($dom)->find('.dvTags')->find('a');
        $tagsTemp = [];
        foreach ($tagsDom as $key => $tag) {
            $tagsTemp[] = trim(str_replace(':','',$tag->text()));
        }
        $data['tags'] = implode(',',$tagsTemp);
        return Response::json($data);
    }


    public function getGalleryInfos(){
        $client = new Client();
        $response = $client->get(Input::get('url'));
        $body = $response->getBody();

        $html5 = new HTML5();
        $dom = $html5->loadHTML($body->getContents());

        $data['title'] = str_replace(' - Foto Galeri','',trim(qp($dom)->find('#packageHeader')->text()) );


        $images = qp($dom)->find('#photoGallerySlides')->find('img');

        $data['imgCont'] = [];
        foreach($images as $image){
            $filename =  str_random(12).str_random(12);
            $url = $image->attr('src');
            copy($url, 'uploads/editor/'.$filename.'.jpeg');
            $data['imgCont'][] = '<img src="'.asset('uploads/editor/'.$filename.'.jpeg').'" />';
        }


        $data['text'] = implode('<!-- pagebreak -->',$data['imgCont']);

        $data['image'] = qp($dom)->find('#photoGallerySlides')->find('img')->first()->attr('src');

        return Response::json($data);
    }




}
