<?php

class SliderController extends \BaseController
{
    public function getIndex()
    {

        $data = [
            'title' => 'Manşet',
            'sliderList' => admin\Slider::get(),
            'ratingSlider' => ConfigController::getRatingSlider()
        ];

        return View::make('admin.slider.index', $data);

    }


    public function getOrder()
    {

        $data = [
            'title' => 'Manşet',
            'slides' => admin\Slider::orderBy('order')->get(),
        ];

        return View::make('admin.slider.orderSlider', $data);

    }

    public function postOrderItem(){
        $input = Input::all();

        try{

            foreach($input['orderArray'] as $key => $val){
                $item = admin\Slider::find($val);
                $item->order = $key;
                $item->save();
            }

            return 'success';
        }catch (Exception $err){

            return $err->getMessage();
        }
    }

    public function postUpdateRatingSlider(){

        $rating_infos['text'] = Input::get('text');
        $rating_infos['title'] = Input::get('title');

        $file = Input::file('img');
        if($file) {
            $destinationPath = 'uploads/editor';
            $filename = str_random(12) . str_random(12) . '.' . $file->getClientOriginalExtension();

            $upload_success = $file->move($destinationPath, $filename);

            if ($upload_success) {
                $rating_infos['img'] = asset('uploads/editor/' . $filename);
            } else {
                $rating_infos['img'] = '';
            }
        }else{
            $rating_infos['img'] = Input::get('imghide');
        }

        return Redirect::to(action('SliderController@getIndex'))->with('alert',  ConfigController::updateRatingSlider($rating_infos));
    }

    public function getAddSlider($edit = false, $messages = false, $title = null, $inputs = false)
    {
        $item = new admin\Slider();
        $relation = [];
        if ($edit && $edit != 0) {
            $item = admin\Slider::find($edit);
            $relation = admin\Slider::item($item->item_id,$item->enum);
        }

        if ($inputs) {
            $item = new stdClass();
            $item = json_decode(json_encode($inputs), FALSE);
        }

        $data = [
            'title' => ' Yeni Manşet',
            'item' => $item,
            'relation' => $relation,
            'as' => 'Manşet',
            'error' => $messages
        ];

        return View::make('admin.slider.addSlider', $data);

    }

    public function postAddSlider($edit = false, $messages = false, $title = null)
    {
        $user = \Auth::user();
        $input = Input::all();

        // Hata mesajlarını ata
        $messages = [
            'required' => ':attribute alanı boş bırakılamaz.',
            'integer' => ':attribute alanı sadece sayıdan oluşmalıdır.',
            'unique' => 'Bu :attribute alanına sahip bir içerik zaten kayıt edilmiş. Lütfen :attribute alanının doğruluğunu kontrol ediniz',
            'max' => ':attribute alanı 31\'den büyük olamaz'
        ];

        $cases = [
            'title' => 'required',
            'item_id' => 'required',
            'enum' => 'required'
        ];

        // Validasyon kurallarını ata
        $validator = Validator::make($input, $cases, $messages);

        // Form elemanlarını türkçeleştir
        $fields = [
            'title' => 'Başlık',
            'item_id' => 'Manşet İçeriği',
            'enum' => 'Manşet İçeriği'
        ];

        // Türkçeleştirilmiş form elemanlarını ata
        $validator->setAttributeNames($fields);

        if ($validator->fails()) //Validasyon kontrolü
        {
            $message = implode('<br />', $validator->messages()->all());

            return $this->getAddSlider(false,$message,$title,$input);

        }else {

            $model = $input;

            if ($model['slideThumb'] == null || $model['slideThumb'] == "")
                $model['slideThumb'] = false;

            if ($model['mainImg'] == null || $model['mainImg'] == "")
                $model['mainImg'] = false;

            unset($model['img']);

            if($model['slideThumb'] === false && $model['mainImg'] === false) {
                return $this->getAddSlider(false, 'Bir resim yüklemelisiniz.', $title, $input);
            }

            try {

                if ($edit && $edit != 0) {
                    $sliderUpdated = admin\Slider::find($edit);
                    admin\LogController::log($user->id, $sliderUpdated->title . ' isimli manşeti güncelledi');
                    $sliderUpdated->update($model);
                    if ($sliderUpdated->img != null || $sliderUpdated->img != "")
                        GalleryController::uploadSliderImage($model['slideThumb'], $model['mainImg'], $sliderUpdated->img);
                    else {
                        $sliderUpdated->img = GalleryController::uploadSliderImage($model['slideThumb'], $model['mainImg']);
                        $sliderUpdated->save();
                    }
                    $alert = "Slider Başarıyla Güncellendi";
                } else {
                    $model['img'] = GalleryController::uploadSliderImage($model['slideThumb'], $model['mainImg']);
                    admin\Slider::create($model);
                    admin\LogController::log($user->id, $model['title'] . ' isimli bir manşet ekledi');
                    $alert = "Slider Başarıyla Eklendi";
                }

                return Redirect::to(action('SliderController@getIndex'))->with('alert', $alert);
            } catch (Exception $err) {
                //TODO:: beklenmeyen bir hata oluştu ekranı

                return Redirect::to(action('SliderController@getIndex'))->with('alert', $err->getMessage());

            }
        }

    }

    public function getDeleteSlider($id){
        $user = \Auth::user();
        try{
            $slider = admin\Slider::find($id);
            $slider->delete();
            admin\LogController::log($user->id, $slider->title . ' isimli manşeti sildi');
            $message = "Manşet Başarıyla Silindi";
        }catch (Exception $err){
            $message = $err->getMessage();

        }
        return Redirect::to(action('SliderController@getIndex'))->with('alert', $message);

    }


}
