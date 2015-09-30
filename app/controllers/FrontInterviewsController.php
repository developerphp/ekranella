<?php

/**
 * Created by PhpStorm.
 * User: erenyildirim
 * Date: 03/01/15
 * Time: 20:16
 */
class FrontInterviewsController extends \FrontController
{
    public function getIndex($serial_permalink = 'tum')
    {
        if ($serial_permalink != "tum") {
            $serial = admin\Serials::where('permalink', $serial_permalink)->first();
            if (count($serial) < 1) {
                return Redirect::to('index');
            }
        }

        //->paginate(10)

        if(!isset($serial))
            return View::make('front.interviews.index', ['interviews' => admin\Interviews::where('published', 1)->orderBy('created_at', 'desc')->get(),'social' => ConfigController::getSocial()]);
        else
            return View::make('front.interviews.index', ['interviews' => admin\Interviews::where('published', 1)->where('serial_id', $serial->id)->orderBy('created_at', 'desc')->get(),'social' => ConfigController::getSocial()]);
    }

    public function getInterview($permalink, $page = 1)
    {
        $interviewObject = new admin\Interviews();
        $interview = $interviewObject->where('permalink', $permalink)->where('published', 1)->with(['user', 'tags', 'questions'])->first();
        if ($interview->published != 1) {
            return Redirect::to(action('HomeController@getIndex'));
        }
        admin\ViewsController::upInterviewViews($interview);

        $others = $this->getOthers([$interview->id]);

        $others_id = [];
        array_push($others_id, $interview->id);
        foreach ($others as $key => $other) {
            array_push($others_id, $other->id);
        }
        $sidebarOtherInterviews = $this->getOthers($others_id);

        $time = strtotime($interview->created_at);
        $created_at = date('d/m/Y H:i', $time);
        $content = '';
        $content .= $interview->text;
        $content .= '<ul class="info"><li>'.$created_at.'<br>';
        if($interview->is_author)
            $content .= '<a href="'.action('front.authors.detail', ['id' => $interview->user->id]).'"
                    style="text-decoration: none"><strong class="pink">'.$interview->user->name.'</strong></a>';
        else {
            $content .= '<strong class="pink">'.$interview->guest_author.'</strong>';
        }

        $content .='</li></ul>';

        foreach($interview->questions as $question)
        {
            $content .='<div class="interview"><div class="devil-icon"><strong>';
            $content .= $question->questionText. '</strong>';
            $content .= $question->answerText;
            $content .= '</div></div>';
        }

        $pages = explode('<!-- pagebreak -->', $content);
        $pageCount = count ($pages);

        if($page !== 'all') {
            $content = $pages[$page - 1];
            if($page > 1) {
                $c = '<div><div><p>';
                $content = $c.$content;
            }
        }



        if ($interview) {
            $data = [
                'interview' => $interview,
                'content' => $content,
                'contentTotalPage' => $pageCount,
                'permalink' => $permalink,
                'page' => $page,
                'others' => $others,
                'headers' => ['title'=> $interview->title, 'description' => \BaseController::shorten($interview->summary, 200)],
                'largeImage' => $interview->img,
                'sidebarOthers' => $sidebarOtherInterviews,
                'social' => ConfigController::getSocial()
            ];
            return View::make('front.interviews.interviewDetail', $data);
        } else {
            return Redirect::to('index');
        }
    }

    private function getOthers($ignore)
    {
        //Random news
        $others = admin\Interviews::limit(6)->where('published', 1)->orderBy(DB::raw('RAND()'))->whereNotIn('interviews.id', $ignore)->with('user')->get();
        foreach($others as $key => $other){
            $others[$key] = $this->addAction($other);
        }
        return $others;
    }

    private function addAction($item){
        $time = strtotime($item['created_at']);
        $date = iconv('latin5','utf-8',strftime("%d %B %Y", $time));

        $item['date'] = $date;
        $item['action'] = 'front.interviews.interviewDetail';
        $item['alias'] = \Config::get('alias.' . \Config::get('enums.interviews'));

        return $item;
    }

}