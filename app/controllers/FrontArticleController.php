<?php

/**
 * Created by PhpStorm.
 * User: erenyildirim
 * Date: 03/01/15
 * Time: 16:36
 */
class FrontArticleController extends \FrontController
{
    public function getArticle($permalink, $page = 1)
    {
        $articleObject = new admin\Article();
        $article = $articleObject->where('permalink', $permalink)->where('published', 1)->with(['user', 'tags'])->first();
        if ($article->published != 1) {
            return Redirect::to(action('HomeController@getIndex'));
        }
        admin\ViewsController::upArticleViews($article);
        $others = $this->getOthers([$article->id]);
        setlocale(LC_ALL, 'tr_TR.UTF-8', 'tr_TR', 'tr', 'turkish');
        setlocale(LC_CTYPE, 'C');
        foreach ($others as $key => $other) {
            $time = strtotime($other->created_at);
            $others[$key]->date = iconv('latin5','utf-8',strftime("%d %B %Y", $time));
        }

        $content = $article->text;
        $pages = explode('<!-- pagebreak -->', $content);
        $pageCount = count ($pages);

        if($page !== 'all') {
            $content = $pages[$page - 1];
        }

        if ($article) {
            $data = [
                'article' => $article,
                'content' => $content,
                'contentTotalPage' => $pageCount,
                'permalink' => $permalink,
                'page' => $page,
                'others' => $others,
                'headers' => ['title'=> $article->title, 'description' => \BaseController::shorten($article->summary, 200)],
                'largeImage' => $article->img
            ];
            return View::make('front.article.articleDetail', $data);
        } else {
            return Redirect::to('index');
        }
    }

    private function getOthers($ignore)
    {
        //Random episodes
        $others = admin\Article::limit(6)->where('published', 1)->orderBy(DB::raw('RAND()'))->whereNotIn('articles.id', $ignore)->with('user')->get();
        return $others;
    }
}