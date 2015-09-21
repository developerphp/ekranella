<?php


class ArticleController extends BaseController
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
            'title' => 'Köse Yazıları',
            'articles' => admin\Article::with(['user'])->get()
        ];
        return View::make('admin.article.index', $data);

    }


    public function getAddArticle($edit = false, $messages = false, $title = null, $inputs = false)
    {
        $users = \User::get();
        $item = new admin\Article();
        if ($edit && $edit != 0) {
            $item = admin\Article::find($edit);
            if (!admin\RoleController::control($item)) {
                $message = 'Bu içeriği değiştirmeye yetkiniz bulunmamaktadır';
                return Redirect::to(action('ArticleController@getIndex'))->with('alert', $message);
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
            'title' => 'Yeni Köşe Yazısı',
            'item' => $item,
            'as' => 'Köşe Yazısı',
            'error' => $messages,
            'users' => $users
        ];

        return View::make('admin.article.addArticle', $data);

    }

    public function postAddArticle($edit = false, $messages = false, $title = null)
    {
        $user = Auth::user();

        $input = Input::all(); //Post edilen tüm değerleri al

        // Hata mesajlarını ata
        $messages = [
            'required' => ':attribute alanı boş bırakılamaz.'
        ];

        $cases = [
            'title' => 'required',
            'text' => 'required',
            'permalink' => 'unique:articles'
        ];

        if ($edit && $edit != 0) {
            $cases['permalink'] = 'unique:articles,permalink,' . $edit;
        }

        // Validasyon kurallarını ata
        $validator = Validator::make($input, $cases, $messages);

        // Form elemanlarını türkçeleştir
        $fields = [
            'title' => 'Başlık',
            'text' => 'Metin'
        ];

        // Türkçeleştirilmiş form elemanlarını ata
        $validator->setAttributeNames($fields);

        if ($validator->fails()) //Validasyon kontrolü
        {
            $message = implode('<br />', $validator->messages()->all());

            return $this->getAddArticle(false, $message, $title, $input);

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

            //add tags
            $tagsTitles = explode(',', $model['tags']);


            //Veritabanında tag varsa çek yoksa yarat.
            $tags = admin\TagController::createTags($tagsTitles, \Config::get('enums.articles'));


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
                    $articleUpdated = admin\Article::find($edit);
                    $articleUpdated->update($model);
                    $articleUpdated->tags()->sync($tags);
                    if ($articleUpdated->img != null || $articleUpdated->img != "")
                        GalleryController::uploadContentImages($model['thumb'], $model['square'], $model['thumbl'], $model['mainImg'], $articleUpdated->img);
                    else {
                        $articleUpdated->img = GalleryController::uploadContentImages($model['thumb'], $model['square'], $model['thumbl'], $model['mainImg']);
                        $articleUpdated->save();
                    }
                    admin\LogController::log($user->id, $model['title'] . ' isimli köşe yazısını güncelledi');
                    $alert = "Köşe Yazısı Başarıyla Güncellendi";
                } else {
                    if ($model['created_by'] == 0) {
                        $model['created_by'] = $user->id;
                    }
                    $model['img'] = GalleryController::uploadContentImages($model['thumb'], $model['square'], $model['thumbl'], $model['mainImg']);
                    $articleCreated = admin\Article::create($model);
                    $articleCreated->tags()->sync($tags);
                    admin\LogController::log($user->id, $model['title'] . ' isimli yeni bir köşe yazısı ekledi');
                    $alert = "Köşe Yazısı Başarıyla Eklendi";
                }


                return Redirect::to(action('ArticleController@getIndex'))->with('alert', $alert);
            } catch (Exception $err) {
                //TODO:: beklenmeyen bir hata oluştu ekranı

                return Redirect::to(action('ArticleController@getIndex'))->with('alert', $err->getMessage());

            }

        }

    }


    public function getDeleteArticle($id)
    {
        $user = \Auth::user();

        try {
            $article = admin\Article::find($id);
            if (admin\RoleController::control($article)) {
                $article->delete();
                admin\LogController::log($user->id, $article['title'] . ' isimli köşe yazısını sildi');
                $message = "Köşe Yazısı Başarıyla Silindi";
            } else {
                $message = "Bu içeriği silme yetkiniz bulunmamaktadır";
            }
        } catch (Exception $err) {
            $message = $err->getMessage();

        }
        return Redirect::to(action('ArticleController@getIndex'))->with('alert', $message);

    }

    public function getPublishToggle($id)
    {
        $user = \Auth::user();
        try {
            $article = admin\Article::find($id);
            if (admin\RoleController::control($article)) {
                if ($article['published']) {
                    $model['published'] = 0;
                    admin\LogController::log($user->id, $article['title'] . ' isimli köşe yazısını yayından kaldırdı');
                    $message = "İçerik Başarıyla Yayından Kaldırıldı";
                } else {
                    $model['published'] = 1;
                    admin\LogController::log($user->id, $article['title'] . ' isimli köşe yazısını yayınladı');
                    $message = "İçerik Başarıyla Yayınlandı";
                }
                admin\Article::find($id)->update($model);
            } else {
                $message = "Bu içeriğin yayın durumunu değiştirmeye yetkiniz bulunmamaktadır";
            }
        } catch (Exception $err) {
            $message = $err->getMessage();

        }
        return Redirect::to(action('ArticleController@getIndex'))->with('alert', $message);

    }

    public function getSelectTodays($id)
    {
        $user = \Auth::user();
        try {
            $article = admin\Article::find($id);
            if (admin\RoleController::isAdmin()) {
                if ($article['todays']) {
                    $model['todays'] = 0;
                    $message = "Günün köşe yazısı kaldırıldı";
                    admin\LogController::log($user->id, 'günün köşe yazısını kaldırdı');
                } else {
                    $model['todays'] = 1;
                    $message = "Günün köşe yazısı belirlendi";
                    $this->unselectTodaysAll();
                    if ($article['published'] == 0) {
                        $model['published'] = 1;
                        $message .= ". Ayrıca köşe yazısı yayınlandı";
                    }
                    admin\LogController::log($user->id, 'günün köşe yazısını "' . $article['title'] . '" olarak değiştirdi');
                }
                $article->update($model);
            } else {
                $message = "Günün köşe yazısını seçme yetkiniz bulunmamaktadır";
            }
        } catch (Exception $err) {
            $message = $err->getMessage();
        }
        return Redirect::to(action('ArticleController@getIndex'))->with('alert', $message);
    }

    private function unselectTodaysAll()
    {
        $model['todays'] = 0;
        admin\Article::where('todays', 1)->update($model);
    }


}
