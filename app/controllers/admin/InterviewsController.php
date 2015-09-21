<?php


class InterviewsController extends BaseController
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
            'title' => 'Röportajlar',
            'interviews' => admin\Interviews::with(['user', 'questions'])->limit(100)->get()
        ];

        return View::make('admin.interviews.index', $data);

    }


    public function getQuestions($id)
    {
        $interview = admin\Interviews::with(['user', 'questions'])->find($id);
        $data = [
            'title' => $interview->title,
            'questions' => $interview->questions()->orderBy('order', 'asc')->get()->toArray(),
            'id' => $id
        ];

        return View::make('admin.interviews.questions', $data);
    }


    public function getAddQuestion($interview = false, $id = false, $messages = false, $inputs = false)
    {
        if ($id) {
            $question = admin\Questions::find($id);
        } else
            $question = new admin\Questions();

        if ($inputs) {
            $question = new stdClass();
            $question = json_decode(json_encode($inputs), FALSE);
        }

        if ($interview) {
            $interview_id = $interview;
        }

        $data = [
            'title' => 'Soru - Cevap',
            'item' => $question,
            'interview_id' => $interview_id,
            'id' => $id,
            'error' => $messages
        ];

        return View::make('admin.interviews.addQuestion', $data);

    }


    public function postAddQuestion($interview = false, $edit = false, $messages = false)
    {
        $input = Input::all();


        // Hata mesajlarını ata
        $messages = [
            'required' => ':attribute alanı boş bırakılamaz.'
        ];

        $cases = [
            'questionText' => 'required',
            'answerText' => 'required',
        ];

        // Validasyon kurallarını ata
        $validator = Validator::make($input, $cases, $messages);

        // Form elemanlarını türkçeleştir
        $fields = [
            'questionText' => 'Soru',
            'answerText' => 'Cevap'
        ];

        // Türkçeleştirilmiş form elemanlarını ata
        $validator->setAttributeNames($fields);

        if ($validator->fails()) //Validasyon kontrolü
        {
            $message = implode('<br />', $validator->messages()->all());

            return $this->getAddQuestion(false, $message, $input);

        } else {

            $model = $input;

            try {

                if ($edit && $edit != 0) {
                    $InterviewsUpdated = admin\Questions::find($edit);
                    $InterviewsUpdated->update($model);

                    $alert = "Soru-Cevap Başarıyla Güncellendi";
                } else {
                    admin\Questions::create($model);
                    $alert = "Soru-Cevap Başarıyla Eklendi";
                }

                return Redirect::to(action('InterviewsController@getQuestions', ['id' => $interview]))->with('alert', $alert);
            } catch (Exception $err) {
                //TODO:: beklenmeyen bir hata oluştu ekranı

                return Redirect::to(action('InterviewsController@getQuestions', ['id' => $interview]))->with('alert', $err->getMessage());

            }

        }

    }


    public function getAddInterviews($edit = false, $messages = false, $title = null, $inputs = false)
    {

        $as = 'Röportaj';
        $users = \User::get();

        $item = new admin\Interviews();
        if ($edit && $edit != 0) {
            $item = admin\Interviews::with('tags')->find($edit);
            if (!admin\RoleController::control($item)) {
                $message = 'Bu içeriği değiştirmeye yetkiniz bulunmamaktadır';
                return Redirect::to(action('InterviewsController@getIndex'))->with('alert', $message);
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
            'title' => $as . ' - Yeni Röportaj',
            'as' => $as,
            'item' => $item,
            'error' => $messages,
            'users' => $users,
            'serials' => admin\Serials::all()->toArray()
        ];

        return View::make('admin.interviews.addInterviews', $data);

    }


    public function postAddInterviews($edit = false, $messages = false, $title = null)
    {
        $user = \Auth::user();
        $input = Input::all(); //Post edilen tüm değerleri al

        // Hata mesajlarını ata
        $messages = [
            'required' => ':attribute alanı boş bırakılamaz',
            'unique' => 'Bu :attribute alanına sahip bir içerik zaten kayıt edilmiş. Lütfen :attribute alanının doğruluğunu kontrol ediniz'
        ];

        $cases = [
            'title' => 'required',
            'text' => 'required',
            'permalink' => 'unique:interviews'
        ];

        if ($edit && $edit != 0) {
            $cases['permalink'] = 'unique:interviews,permalink,' . $edit;
        }

        // Validasyon kurallarını ata
        $validator = Validator::make($input, $cases, $messages);

        // Form elemanlarını türkçeleştir
        $fields = [
            'title' => 'Başlık',
            'text' => 'Röportaj Giriş Metini',
            'permalink' => 'Permalink'
        ];

        // Türkçeleştirilmiş form elemanlarını ata
        $validator->setAttributeNames($fields);

        if ($validator->fails()) //Validasyon kontrolü
        {
            $message = implode('<br />', $validator->messages()->all());

            return $this->getAddInterviews(false, $message, $title, $input);

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
                $tags = admin\TagController::createTags($tagsTitles, \Config::get('enums.interviews'));

                if ($edit && $edit != 0) {
                    if ($model['created_by'] == 0) {
                        unset($model['created_by']);
                    }
                    $interviewsUpdated = admin\Interviews::find($edit);
                    $interviewsUpdated->update($model);
                    if ($interviewsUpdated->img != null || $interviewsUpdated->img != "")
                        GalleryController::uploadContentImages($model['thumb'], $model['square'], $model['thumbl'], $model['mainImg'], $interviewsUpdated->img);
                    else {
                        $interviewsUpdated->img = GalleryController::uploadContentImages($model['thumb'], $model['square'], $model['thumbl'], $model['mainImg']);
                        $interviewsUpdated->save();
                    }
                    $interviewsUpdated->tags()->sync($tags);
                    admin\LogController::log($user->id, $model['title'] . ' isimli bir röportajı güncelledi');
                    $alert = "Röportaj Başarıyla Güncellendi";
                } else {
                    if ($model['created_by'] == 0) {
                        $model['created_by'] = $user->id;
                    }
                    $model['img'] = GalleryController::uploadContentImages($model['thumb'], $model['square'], $model['thumbl'], $model['mainImg']);
                    $interviewsCreated = admin\Interviews::create($model);
                    $interviewsCreated->tags()->sync($tags);
                    admin\LogController::log($user->id, $model['title'] . ' isimli yeni bir röportaj ekledi');
                    $alert = "Röportaj Başarıyla Eklendi";
                }

                return Redirect::to(action('InterviewsController@getIndex'))->with('alert', $alert);
            } catch (Exception $err) {
                //TODO:: beklenmeyen bir hata oluştu ekranı

                return Redirect::to(action('InterviewsController@getIndex'))->with('alert', $err->getMessage());

            }

        }

    }

    public function postOrderQuestions()
    {
        $input = Input::all();

        try {

            foreach ($input['orderArray'] as $key => $val) {
                $item = admin\Questions::find($val);
                $item->order = $key;
                $item->save();
            }

            return 'success';
        } catch (Exception $err) {

            return $err->getMessage();
        }
    }

    public function getDeleteInterview($id)
    {
        $user = \Auth::user();
        try {
            $interview = admin\Interviews::find($id);
            if (admin\RoleController::control($interview)) {
                $interview->delete();
                admin\LogController::log($user->id, $interview['title'] . ' isimli bir röportajı sildi');
                $message = "Röportaj Başarıyla Silindi";
            } else {
                $message = "Bu içeriği silmeye yetkiniz bulunmamaktadır";
            }
        } catch (Exception $err) {
            $message = $err->getMessage();

        }
        return Redirect::to(action('InterviewsController@getIndex'))->with('alert', $message);
    }

    public function getPublishToggle($id)
    {
        $user = \Auth::user();
        try {
            $interview = admin\Interviews::find($id);
            if (admin\RoleController::control($interview)) {
                if ($interview['published']) {
                    $model['published'] = 0;
                    admin\LogController::log($user->id, $interview['title'] . ' isimli bir röportajı yayından kaldırdı');
                    $message = "Röportaj Başarıyla Yayından Kaldırıldı";
                } else {
                    $model['published'] = 1;
                    admin\LogController::log($user->id, $interview['title'] . ' isimli bir röportajı yayınladı');
                    $message = "Röportaj Başarıyla Yayınlandı";
                }
                admin\Interviews::find($id)->update($model);
            } else {
                $message = "Bu içeriğin yayın durumunu değiştirmeye yetkiniz bulunmamaktadır";
            }

        } catch (Exception $err) {
            $message = $err->getMessage();

        }
        return Redirect::to(action('InterviewsController@getIndex'))->with('alert', $message);
    }

    public function getDeleteQuestion($interviews, $id)
    {
        try {
            admin\Questions::find($id)->delete();
            $message = "Soru-Cevap Başarıyla Silindi";
        } catch (Exception $err) {
            $message = $err->getMessage();

        }
        return Redirect::to(action('InterviewsController@getQuestions', ['id' => $interviews]))->with('alert', $message);
    }


}
