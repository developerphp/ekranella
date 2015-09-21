<?php


class DashController extends BaseController
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
            'logs' => \admin\LogController::getLastForDash(),
            'todays_logs' => \admin\Logs::where('created_at', '>=', new DateTime('today'))->where('created_at', '<', new DateTime('tomorrow'))->remember(4)->count(),
            'all_logs' => \admin\Logs::remember(4)->count(),
            'personal_todays_logs' => \admin\Logs::where('subject', Auth::user()->id)->where('created_at', '>=', new DateTime('today'))->where('created_at', '<', new DateTime('tomorrow'))->remember(4)->count(),
            'personal_all_logs' => \admin\Logs::where('subject', Auth::user()->id)->remember(4)->count(),
            'personal_sum' =>  \admin\Article::where('created_by', Auth::user()->id)->remember(4)->sum('views') + \admin\Episodes::where('created_by', Auth::user()->id)->remember(4)->sum('views') +  \admin\Interviews::where('created_by', Auth::user()->id)->remember(4)->sum('views') +  \admin\News::where('created_by', Auth::user()->id)->remember(4)->sum('views'),
            'profile' => User::where('id', Auth::user()->id)->remember(4)->sum('views')
        ];
        return View::make('admin.dash.dashboard', $data);

    }

    function getDash()
    {
        return $this->getIndex();
    }

    function getMessages()
    {
        return View::make('admin.includes.chat',['messages' => admin\Messages::where('created_at', '>', strtotime("-1 day"))->orderBy('created_at','ASC')->get()]);
    }


    function postMessage()
    {
        $message = new admin\Messages();
        $message->text = Input::get('message');
        $message->text = preg_replace_callback('#(?:https?://\S+)|(?:www.\S+)|(?:\S+\.\S+)#', function ($arr)
        {
            if(strpos($arr[0], 'http://') !== 0)
            {
                $arr[0] = 'http://' . $arr[0];
            }
            $url = parse_url($arr[0]);

            //links
            return sprintf('<a href="%1$s" target="_blank">%1$s</a>', $arr[0]);
        }, $message->text);
        $message->created_by = Auth::user()->id;
        $message->save();

        admin\Messages::where('created_at', '<' ,strtotime("-2 day"))->delete();
        return View::make('admin.includes.chat',['messages' => admin\Messages::where('created_at', '>', strtotime("-1 day"))->orderBy('created_at','ASC')->get()]);
    }

    public function getLogsLatest()
    {
        $latest = admin\LogController::getLatest();
        setlocale(LC_TIME, 'Turkish');
        $tempArray = [];
        foreach ($latest as $key => $log) {
            $user = $log->subject()->first();
            $tempArray[$key]['pp'] = $user->pp;
            $tempArray[$key]['message'] = '<span class="name">' . $user->name . '</span>' . $log->message;
            $tempArray[$key]['created_at'] = admin\LogController::timeConvert($log->created_at);
        }
        return Response::json($tempArray);

    }

    public function getLogs()
    {
        $logs = admin\LogController::getPaginatedLogs();

        $data = [
            'title' => 'Kullanıcı hareketleri',
            'logs' => $logs
        ];
        return View::make('admin.logs.index', $data);
    }

    public static function getNameOfUser($id)
    {
        return \User::find($id)->name;
    }

}
