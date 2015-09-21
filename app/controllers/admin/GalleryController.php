<?php


class GalleryController extends BaseController
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

    public function getEdit($id)
    {
        $data = [
            'item' => admin\Galleries::find($id)
        ];

        return View::make('admin.gallery.edit', $data);

    }


    public function postEdit($id)
    {
        $input = Input::all();

        $model = [
            'text' => $input['text']
        ];

        try {
            if ($id && $id != 0) {
                admin\Galleries::find($id)->update($model);

                $alert = "Galeri Başarıyla Güncellendi";
            }

            return Redirect::to($input['ref'])->with('alert', $alert);
        } catch (Exception $err) {
            //TODO:: beklenmeyen bir hata oluştu ekranı

            return Redirect::to($input['ref'])->with('alert', $err->getMessage());

        }

    }

    public function postAddItem()
    {
        $user = \Auth::user();
        $input = Input::all();

        $model = [
            'img' => $input['img']
        ];

        try {
            $modelObject = null;
            switch($input['enum']){
                case 1: $modelObject = new admin\Episodes();break;
                case 3: $modelObject = new admin\News();break;
                default;
            }
            $itemCreated = admin\Galleries::create($model);
            $item = $modelObject::find($input['item']);
            $item->gallery()->attach($itemCreated->id, ['enum'=>$input['enum']]);

            return Response::json(['status' => 'success', 'item_id' => $itemCreated->id], 200);

        } catch (Exception $err) {
            //TODO:: beklenmeyen bir hata oluştu ekranı

            return Response::json(['status' => 'error'], 400);

        }

    }

    public function postUpload(){
        $file = Input::file('upload');
        $destinationPath = 'uploads/editor';
        $filename = str_random(12).str_random(12).'.'.$file->getClientOriginalExtension();

        $upload_success = Input::file('upload')->move($destinationPath, $filename);

        if( $upload_success ) {
            return Response::make(asset('uploads/editor/'.$filename));
        } else {
            return Response::json(['status'=> 'error'], 400);
        }
    }

    public function postOrderItem(){
        $input = Input::all();

        try{

            foreach($input['orderArray'] as $key => $val){
                $item = admin\Galleries::find($val);
                $item->order = $key;
                $item->save();
            }

            return 'success';
        }catch (Exception $err){

            return $err->getMessage();
        }
    }


    public function getDelete($id,$news,$enum=3){

        try{

            if($enum == 1)
                $news = admin\Episodes::find($news);
            else
                $news = admin\News::find($news);

            $news->gallery()->detach($id);

            $item = admin\Galleries::find($id);


            $filename = public_path().'/'.$item->img;
            if (File::exists($filename)) {
                File::delete($filename);
            }

            $item->delete();

            return Redirect::back()->with('alert','Görsel başarıyla silindi');
        }catch (Exception $err){

            return Redirect::back()->with('alert',$err->getMessage());

        }
    }

    public static function uploadContentImages($thumb = false, $square = false, $thumbl = false, $main = false, $edit = false){


        if($edit)
            $name = $edit;
        else
            $name = uniqid();

        define('UPLOAD_DIR', 'uploads/');

        //Thumbnail upload
        if($thumb){
            $thumb = str_replace('data:image/png;base64,', '', $thumb);
            $thumb = str_replace('data:image/jpeg;base64,', '', $thumb);
            $thumb = str_replace(' ', '+', $thumb);
            $data = base64_decode($thumb);
            $file = UPLOAD_DIR . $name;
            file_put_contents($file.'_thumb.jpg', $data);
        }

        //Square upload
        if($square) {
            $square = str_replace('data:image/png;base64,', '', $square);
            $square = str_replace('data:image/jpeg;base64,', '', $square);
            $square = str_replace(' ', '+', $square);
            $data = base64_decode($square);
            $file = UPLOAD_DIR . $name;
            file_put_contents($file . '_square.jpg', $data);
        }



        //Thumbnail Large upload
        if($thumbl) {
            $thumbl = str_replace('data:image/png;base64,', '', $thumbl);
            $thumbl = str_replace('data:image/jpeg;base64,', '', $thumbl);
            $thumb = str_replace(' ', '+', $thumbl);
            $data = base64_decode($thumbl);
            $file = UPLOAD_DIR . $name;
            file_put_contents($file.'_thumbl.jpg', $data);
        }



        //Main upload
        if($main) {
            $main = str_replace('data:image/png;base64,', '', $main);
            $main = str_replace('data:image/jpeg;base64,', '', $main);
            $main = str_replace(' ', '+', $main);
            $data = base64_decode($main);
            $file = UPLOAD_DIR . $name;
            file_put_contents($file.'_main.jpg', $data);
        }


        return $name;

    }


    public static function uploadSliderImage($slideThumb = false, $mainImg=false, $edit = false){

        if($edit)
            $name = $edit;
        else
            $name = uniqid();

        define('UPLOAD_DIR', 'uploads/');

        //slideThumb upload
        if($slideThumb){
            $slideThumb = str_replace('data:image/png;base64,', '', $slideThumb);
            $slideThumb = str_replace('data:image/jpeg;base64,', '', $slideThumb);
            $slideThumb = str_replace(' ', '+', $slideThumb);
            $data = base64_decode($slideThumb);
            $file = UPLOAD_DIR . $name;
            file_put_contents($file.'_slideThumb.jpg', $data);
        }

        //Square upload
        if($mainImg) {
            $mainImg = str_replace('data:image/png;base64,', '', $mainImg);
            $mainImg = str_replace('data:image/jpeg;base64,', '', $mainImg);
            $mainImg = str_replace(' ', '+', $mainImg);
            $data = base64_decode($mainImg);
            $file = UPLOAD_DIR . $name;
            file_put_contents($file . '_main.jpg', $data);
        }

        return $name;

    }
}
