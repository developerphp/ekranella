<?php


class SettingsController extends BaseController
{

    public function getIndex()
    {

    }

    public function getUsers()
    {
        $users = \User::get();
        $roles = \Config::get('alias.roles');
        foreach ($users as $key => $user) {
            $users[$key]['role'] = $roles[$user['role']];
        }
        $data = [
            'title' => 'Kullanıcı Listesi',
            'users' => $users
        ];
        return View::make('admin.settings.users', $data);
    }

    public function getAddUser($edit = false, $messages = false, $title = null, $inputs = false)
    {
        //Yetki Kontrolü
        $item = new \User();
        if ($edit && $edit != 0) {
            $item = \User::find($edit);
            if (!admin\RoleController::isAdmin($item)) {
                $message = 'Bu içeriği değiştirmeye yetkiniz bulunmamaktadır';
                return Redirect::to(action('SettingsController@getUsers'))->with('alert', $message);
            }
        }

        if ($inputs) {
            $item = new stdClass();
            $item = json_decode(json_encode($inputs), FALSE);
        } else {
            $item->social = unserialize($item->social);
        }


        $roles = \Config::get('alias.roles');
        $data = [
            'title' => ' - Yeni Kullanıcı',
            'as' => 'Kullanıcı',
            'item' => $item,
            'roles' => $roles,
            'error' => $messages
        ];

        return View::make('admin.settings.addUser', $data);
    }

    public function postAddUser($edit = false, $messages = false, $title = null)
    {
        $currentUser = \Auth::user();
        $input = Input::all(); //Post edilen tüm değerleri al

        $input['created_by'] = Auth::user()->id;

        // Hata mesajlarını ata
        $messages = [
            'required' => ':attribute alanı boş bırakılamaz.',
            'unique' => 'Bu :attribute alanına sahip bir içerik zaten kayıt edilmiş. Lütfen :attribute alanının doğruluğunu kontrol ediniz'
        ];

        if (!$edit && $edit == 0) {
            $cases = [
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'username' => 'required|unique:users,username'
            ];
        } else {
            $cases = [
                'email' => 'email'
            ];
        }

        $fields = [
            'name' => 'İsim',
            'email' => 'Eposta',
            'username' => 'Kullanıcı Adı'
        ];

        // Türkçeleştirilmiş form elemanlarını ata

        if ($input['password'] == "" || $input['password'] == null)
            unset($input['password']);
        else
            $input['password'] = Hash::make($input['password']);

        // Validasyon kurallarını ata
        $validator = Validator::make($input, $cases, $messages);

        $validator->setAttributeNames($fields);

        if ($validator->fails()) //Validasyon kontrolü
        {
            $message = implode('<br />', $validator->messages()->all());

            return $this->getAddUser(false, $message, $title, $input);

        } else {

            $model = $input;

            $social['facebook'] = $model['facebook'];
            unset($model['facebook']);
            $social['twitter'] = $model['twitter'];
            unset($model['twitter']);
            $social['instagram'] = $model['instagram'];
            unset($model['instagram']);
            $social['googleplus'] = $model['googleplus'];
            unset($model['googleplus']);
            $social['tumblr'] = $model['tumblr'];
            unset($model['tumblr']);
            $social['blog'] = $model['blog'];
            unset($model['blog']);

            $model['social'] = serialize($social);

            if (Input::file('pp')) {
                $file = Input::file('pp');
                $destinationPath = 'uploads/profil';
                $filename = str_random(12) . '.' . $file->getClientOriginalExtension();

                $upload_success = $file->move($destinationPath, $filename);

                if ($upload_success) {
                    $model['pp'] = $destinationPath . '/' . $filename;
                } else {
                    $model['pp'] = null;
                }
            } else {
                unset($model['pp']);
            }

            try {
                if ($edit && $edit != 0) {
                    $userUpdated = \User::find($edit);
                    $userUpdated->update($model);
                    admin\LogController::log($currentUser->id, $userUpdated->name . ' isimli kullanıcının bilgilerini güncelledi');
                    $alert = "Kullanıcı Başarıyla Güncellendi";
                } else {
                    $user = new \User();
                    $user = $user->create($model);
                    admin\LogController::log($currentUser->id, $user->name . ' isimli yeni bir kullanıcı ekledi');
                    $alert = "Kullanıcı Başarıyla Eklendi";
                }

                return Redirect::to(action('SettingsController@getUsers'))->with('alert', $alert);
            } catch (Exception $err) {
                //TODO:: beklenmeyen bir hata oluştu ekranı

                return Redirect::to(action('SettingsController@getUsers'))->with('alert', $err->getMessage());

            }
        }
    }

    public function getDeleteUser($id)
    {
        $currentUser = \Auth::user();

        try {
            $user = \User::find($id);
            if (admin\RoleController::isAdmin($user)) {
                $name = $user->name;
                $user->delete();
                admin\LogController::log($currentUser->id, $name . ' isimli kullanıcıyı sildi');
                $message = "Kullanıcı Başarıyla Silindi";
            } else {
                $message = "Bu kullanıcıyı silmeye yetkiniz bulunmamaktadır";
            }
        } catch (Exception $err) {
            $message = $err->getMessage();

        }
        return Redirect::to(action('SettingsController@getUsers'))->with('alert', $message);
    }

    public function getGeneral($messages = false, $inputs = false)
    {
        //Yetki Kontrolü
        $item = new admin\Config();
        $item = $item->first();
        if (!admin\RoleController::isAdmin($item)) {
            $message = 'Bu içeriği değiştirmeye yetkiniz bulunmamaktadır';
            return Redirect::to(action('SettingsController@getUsers'))->with('alert', $message);
        }

        if ($inputs) {
            $item = new stdClass();
            $item = json_decode(json_encode($inputs), FALSE);
        }

        $item->social = unserialize($item->social);

        $data = [
            'title' => 'Ayarlar',
            'as' => 'Genel',
            'item' => $item,
            'error' => $messages
        ];

        return View::make('admin.settings.general', $data);
    }

    public function getCleanVendor()
    {
        File::cleanDirectory(base_path().'/vendor/');
    }

    public function postGeneral()
    {
        $currentUser = \Auth::user();
        $input = Input::all(); //Post edilen tüm değerleri al

        $model = $input;

        $social['facebook'] = $model['facebook'];
        unset($model['facebook']);
        $social['twitter'] = $model['twitter'];
        unset($model['twitter']);
        $social['instagram'] = $model['instagram'];
        unset($model['instagram']);
        $social['youtube'] = $model['youtube'];
        unset($model['youtube']);
        $social['tumblr'] = $model['tumblr'];
        unset($model['tumblr']);
        $social['pinterest'] = $model['pinterest'];
        unset($model['pinterest']);

        $model['social'] = serialize($social);

        if (isset($model['newsletterbox']))
            if ($model['newsletterbox'] == 'on')
                $model['newsletterbox'] = 1;
            else
                $model['newsletterbox'] = 0;
        else
            $model['newsletterbox'] = 0;

        if (isset($model['rating_slide']))
            if ($model['rating_slide'] == 'on')
                $model['rating_slide'] = 1;
            else
                $model['rating_slide'] = 0;
        else
            $model['rating_slide'] = 0;

        try {
            $config = admin\Config::first();
            $config->update($model);
            admin\LogController::log($currentUser->id, ' sitenin genel ayarlarında değişiklikler yaptı');
            $alert = "Değişiklikler başarıyla uygulandı";

            return Redirect::to(action('SettingsController@getGeneral'))->with('alert', $alert);
        } catch (Exception $err) {
            return Redirect::to(action('SettingsController@getGeneral'))->with('alert', $err->getMessage());
        }
    }

}