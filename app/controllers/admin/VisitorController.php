<?php


class VisitorController extends BaseController
{

    public function getIndex()
    {
        $data = [
            'title' => 'Kayıt Listesi',
            'visitors' => \admin\Visitors::all(),
        ];
        return View::make('admin.visitors.index', $data);
    }

    public function postAjaxFacebookUser(){

        $data = [
            'name' => Input::get('name'),
            'surname' => Input::get('surname'),
            'email' => Input::get('email'),
            'gender' => Input::get('gender'),
            'fbID' => Input::get('fbID'),
            'newsletter' => 1
        ];

        try{
            \admin\Visitors::firstOrCreate($data);
            return Response::json($data);

        }catch (Exception $err){
            return Response::json(['status'=>'already registered']);
        }

    }
}