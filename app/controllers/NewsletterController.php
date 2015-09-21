<?php
/**
 * Created by PhpStorm.
 * User: erenyildirim
 * Date: 10/01/15
 * Time: 17:55
 */

class NewsletterController extends \BaseController{

    public function getUnsubscribe($email){
        $visitor = admin\Visitors::where('newsletter', 1)->where('email', $email)->first();
        if(count($visitor)>0)
            return View::make('front.newsletter.unsubscribe', ['email' => $email]);
        else
            return View::make('front.newsletter.usSuccess');
    }

    public function unsubscribeSuccess($email){
        $visitor = admin\Visitors::where('email', $email)->first();
        $model = [
            'newsletter' => 0
        ];
        $visitor->update($model);
        return View::make('front.newsletter.usSuccess');
    }


}