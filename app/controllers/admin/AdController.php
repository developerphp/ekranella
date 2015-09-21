<?php

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;

class AdController extends Controller
{
    private function check($model)
    {
        $validation = Validator::make($model, [
            'name' => 'required',
            'place' => 'required'
        ]);
        return $validation->fails() ? implode('<br />', $validation->messages()->all()) : false;
    }

    public function getIndex()
    {
        return View::make('admin.ad.list', [
            'title' => 'Reklam Yönetimi',
            'ads' => Ad::all()
        ]);
    }

    public function getCreate()
    {
        return View::make('admin.ad.form', [
            'title' => 'Reklam Yönetimi - Yeni Reklam',
            'ad' => new Ad()
        ]);
    }

    public function postCreate()
    {
        $ad = Input::get('ad');
        $messages = $this->check($ad);
        if ($messages === false) {
            $ad['is_active'] = isset($ad['is_active']);

            $file = Input::file('adfile');
            if ($file !== null) {
                $fileName = implode('.', [uniqid('ad_'), $file->getClientOriginalExtension()]);
                $file->move('uploads/reklam/', $fileName);
                $file = asset('uploads/reklam/' . $fileName);

                if ($ad['type'] == 0) {
                    $ad['embed'] = <<<EOE
<a href="{$ad['link']}" target="_blank">
    <img src="{$file}" />
</a>
EOE;
                }
                else if ($ad['type'] == 1) {
                    $ad['embed'] = <<<EOE
<a href="{$ad['link']}" target="_blank">
    <object type="application/x-shockwave-flash" data="{$file}">
        <param name="movie" value="{$file}" />
        <param name="quality" value="high" />
        <embed src="{$file}" quality="high" />
    </object>
</a>
EOE;
                }
            }else{
                $ad['embed'] = $ad['code'];

            }
            unset($ad['code']);
            $ad['url'] = $ad['link'];
            unset($ad['link']);

            Ad::create($ad);
            return Redirect::action('AdController@getIndex');
        }
        return View::make('admin.ad.form', [
            'title' => 'Reklam Yönetimi - Yeni Reklam',
            'ad' => (object)$ad,
            'error' => $messages
        ]);
    }

    public function getEdit($id)
    {
        return View::make('admin.ad.form', [
            'title' => 'Reklam Yönetimi - Yeni Reklam',
            'ad' => Ad::find($id)
        ]);
    }

    public function postEdit($id)
    {
        $ad = Input::get('ad');
        $messages = $this->check($ad);
        if ($messages === false) {
            $ad['is_active'] = isset($ad['is_active']);

            $file = Input::file('adfile');
            if ($file !== null) {
                $fileName = implode('.', [uniqid('ad_'), $file->getClientOriginalExtension()]);
                $file->move('uploads/reklam/', $fileName);
                $file = asset('uploads/reklam/' . $fileName);

                if ($ad['type'] == 0) {
                    $ad['embed'] = <<<EOE
<a href="{$ad['link']}" target="_blank">
    <img src="{$file}" />
</a>
EOE;
                }
                else if ($ad['type'] == 1) {

                    $ad['embed'] = <<<EOE
<a href="{$ad['link']}" target="_blank">
    <object type="application/x-shockwave-flash" data="{$file}">
        <param name="movie" value="{$file}" />
        <param name="quality" value="high" />

        <embed src="{$file}" quality="high" />
    </object>
</a>
EOE;
                }
            }else{
                if($ad['type'] == 2) {
                    $ad['embed'] = $ad['code'];
                }
            }
            unset($ad['code']);
            $ad['url'] = $ad['link'];
            unset($ad['link']);

            Ad::find($id)->update($ad);
            return Redirect::action('AdController@getIndex');
        }
        return View::make('admin.ad.form', [
            'title' => 'Reklam Yönetimi - Yeni Reklam',
            'ad' => (object)$ad,
            'error' => $messages
        ]);
    }

    public function getDelete($id)
    {
        Ad::find($id)->delete();
        return Redirect::action('AdController@getIndex');
    }
} 