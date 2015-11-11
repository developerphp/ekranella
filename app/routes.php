<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/


Route::group(['prefix' => 'backoffice', 'before' => 'auth.basic'], function () {

    Route::controller('interviews', 'InterviewsController');
    Route::controller('series', 'SerialController');
    Route::controller('news', 'NewsController');
    Route::controller('gallery', 'GalleryController');
    Route::controller('article', 'ArticleController');
    Route::controller('featured', 'FeaturedController');
    Route::controller('settings', 'SettingsController');
    Route::controller('slider', 'SliderController');
    Route::controller('ratings', 'RatingController');
    Route::controller('ad', 'AdController');
    Route::controller('visitors', 'VisitorController');
    Route::controller('liked', 'LikedController');
    Route::controller('patch', 'PatchController');
    Route::controller('', 'DashController');
});

Route::post('ajax/dizi/all/{type?}', ['uses' => 'FrontSerialController@postAjaxAllSerialDetail', 'as' => 'front.ajax.serial.all.detail']);
Route::post('ajax/dizi/{type?}/{page?}', ['uses' => 'FrontSerialController@getAjaxSerialDetail', 'as' => 'front.ajax.serial.detail']);
Route::post('ajax/diziliste/{permalink}/{enum}/{page?}', ['uses' => 'FrontSerialController@listAjaxEpisodes', 'as' => 'front.ajax.list.episode']);
Route::post('ajax/haberliste/{permalink?}/{page?}', ['uses' => 'FrontNewsController@listAjaxNews', 'as' => 'front.ajax.list.news']);
Route::get('ajax/rating', ['uses' => 'RatingController@getPullRatings', 'as' => 'get.ratings']);
Route::post('ajax/ozelliste/{permalink?}/{page?}', ['uses' => 'FrontNewsController@getSpecialAjaxNewsList', 'as' => 'front.ajax.list.specials']);

Route::get('diziler/yerli', ['uses' => 'FrontSerialController@getLocalSerial', 'as' => 'front.serial.localIndex']);
Route::get('diziler/yabanci', ['uses' => 'FrontSerialController@getForeignSerial', 'as' => 'front.serial.foreignIndex']);
Route::get('diziler/program', ['uses' => 'FrontSerialController@getProgram', 'as' => 'front.serial.programIndex']);

Route::get('ozelyazilar/{permalink?}/{page?}', ['uses' => 'FrontNewsController@getSpecialNewsList', 'as' => 'front.news.specialNews']);
Route::get('fotohaberler/{permalink?}', ['uses' => 'FrontNewsController@getPhotoNewsList', 'as' => 'front.news.photoNews']);
Route::get('dosyalar', ['uses' => 'FrontFeaturedController@getArchive', 'as' => 'front.featured.archive']);
Route::get('yazarlar', ['uses' => 'FrontAuthorsController@getAuthors', 'as' => 'front.authors.index']);
Route::get('roportajlar/{permalink?}', ['uses' => 'FrontInterviewsController@getIndex', 'as' => 'front.interviews.index']);
Route::get('begenilenler', ['uses' => 'FrontLikedController@listLiked', 'as' => 'front.list.likedIndex']);

Route::get('dizi/{permalink}', ['uses' => 'FrontSerialController@getSerialDetail', 'as' => 'front.serial.detail']);
Route::get('diziliste/{permalink}/{enum}/{page?}', ['uses' => 'FrontSerialController@listEpisodes', 'as' => 'front.serial.enumIndex']);
Route::get('yazar/{id}', ['uses' => 'FrontAuthorsController@getAuthor', 'as' => 'front.authors.detail']);
Route::get('bolum/{permalink}/{galleryPage?}/{page?}', ['uses' => 'FrontSerialController@getEpisode', 'as' => 'front.serial.episodeDetail']);
Route::get('bolumozel/{permalink}/{galleryPage?}', ['uses' => 'FrontSerialController@getEpisode', 'as' => 'front.serial.specialDetail']);
Route::get('fragman/{permalink}/{galleryPage?}', ['uses' => 'FrontSerialController@getEpisode', 'as' => 'front.serial.trailerDetail']);
Route::get('galeri/{permalink}/{galleryPage?}', ['uses' => 'FrontSerialController@getEpisode', 'as' => 'front.serial.sgalleryDetail']);
Route::get('kose/{permalink}/{page?}', ['uses' => 'FrontArticleController@getArticle', 'as' => 'front.article.articleDetail']);
Route::get('roportaj/{permalink}/{page?}', ['uses' => 'FrontInterviewsController@getInterview', 'as' => 'front.interviews.interviewDetail']);
Route::get('guncel/{permalink}', ['uses' => 'FrontFeaturedController@getFeatured', 'as' => 'front.featured.featuredDetail']);
Route::get('rating/{type?}/{skip?}', ['uses' => 'FrontRatingController@getRating', 'as' => 'front.rating.index']);
Route::get('haber/{permalink}/{galleryPage?}/{page?}', ['uses' => 'FrontNewsController@getNews', 'as' => 'front.news.newsDetail']);
Route::get('ozel/{permalink}/{galleryPage?}', ['uses' => 'FrontNewsController@getNews', 'as' => 'front.news.specialNewsDetail']);
Route::get('haberliste/{permalink?}/{page?}', ['uses' => 'FrontNewsController@listNews', 'as' => 'front.list.newsIndex']);
Route::get('haberler', ['uses' => 'FrontNewsController@allNews', 'as' => 'front.list.newsAllIndex']);
Route::get('ara', ['uses' => 'SearchController@getSearch', 'as' => 'front.search.index']);

Route::post('ajax/facebook', ['uses' => 'VisitorController@postAjaxFacebookUser', 'as' => 'front.ajax.facebook.user']);
Route::get('adclick/{id?}', ['uses' => 'FrontAdController@upAdClick', 'as' => 'front.ad.click']);

Route::get('iletisim', ['uses' => 'HomeController@getContact', 'as' => 'front.home.contact']);

Route::get('unsubscribe/{email}', ['uses' => 'NewsletterController@getUnsubscribe', 'as' => 'front.newsletter.unsubscribe']);
Route::get('us-success/{email}', ['uses' => 'NewsletterController@unsubscribeSuccess', 'as' => 'front.newsletter.usSuccess']);


Route::get('sitemap', function(){

    $sitemap = App::make("sitemap");

    $sitemap->setCache('ekranella.sitemap', 300);


    if (!$sitemap->isCached())
    {
        $sitemap->add(URL::to('/'), '2014-09-22T20:10:00+02:00', '1.0', 'daily');



        foreach (\admin\Serials::orderBy('title', 'ASC')->get()as $serial) {

            $sitemap->add( action('front.serial.detail',['permalink' => $serial->permalink]), $serial->updated_at, '1', 'weekly');

        }


        foreach (\admin\Episodes::where('enum', Config::get('enums.episode')['summary'])->where('published', 1)->orderBy('created_at','DESC')->get() as $episode) {

            $sitemap->add(action('FrontSerialController@getEpisode', ['permalink' =>$episode->permalink ]), $episode->updated_at, '0.9', 'weekly');

        }

        foreach (admin\Episodes::where('enum', Config::get('enums.episode')['sgallery'])->where('published', 1)->orderBy('created_at','DESC')->get() as $gallery) {

            $sitemap->add(action('front.serial.sgalleryDetail', ['permalink' => $gallery->permalink ]), $gallery->updated_at, '0.9', 'weekly');

        }


        foreach (admin\Episodes::where('enum', Config::get('enums.episode')['trailer'])->where('published', 1)->orderBy('created_by','DESC')->get() as $trailer) {

            $sitemap->add(action('front.serial.trailerDetail', ['permalink' => $trailer->permalink ]), $trailer->updated_at, '0.9', 'weekly');

        }


        foreach (admin\Episodes::where('enum', Config::get('enums.episode')['specials'])->where('published', 1)->orderBy('created_by','DESC')->get() as $special) {

            $sitemap->add(action('front.serial.specialDetail', ['permalink' => $special->permalink ]), $special->updated_at, '0.9', 'daily');

        }

        foreach (admin\News::where('published', 1)->where('type', 1)->where('published', 1)->orderBy('created_at','DESC')->get() as $new) {

            $sitemap->add(action('front.news.newsDetail', ['permalink' => $new->permalink]), $new->updated_at, '0.9', 'weekly');
        }


    }

    return $sitemap->render('xml');

});

Route::controller('/', 'HomeController');

