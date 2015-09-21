<?php
/**
 * Created by PhpStorm.
 * User: erenyildirim
 * Date: 01/01/15
 * Time: 21:40
 */

use GuzzleHttp\Client;
use Masterminds\HTML5;
use QueryPath\QueryPath;


class RatingController extends \BaseController
{

    public function getIndex()
    {
        $date = date('d/m/Y', time() - 60 * 60 * 24);
        $data = [
            'title' => 'Ratingler',
            'ratings' => Rating::where('date', $date)->get(),
            'date' => $date
        ];
        return View::make('admin.rating.index', $data);
    }

    public function getPullRatings()
    {
        $date = date('d/m/Y', time() - 60 * 60 * 24);
        $dateM = date('Y-m-d', time() - 60 * 60 * 24);

        $dateForm = explode('/', $date);

        $client = new Client();
        $response = $client->post('http://www.medyatava.com/rating/' . $dateM . '?' . uniqid(), [
            'body' => [
                'gun_tarih' => $dateForm[0],
                'ay_tarih' => $dateForm[1],
                'yil_tarih' => $dateForm[2]
            ]
        ]);


        $body = $response->getBody();

        $html5 = new HTML5();
        $dom = $html5->loadHTML($body->getContents());
        $tables = qp($dom)->find('.rating-page-tab-content');

        //$tables = qp($dom)->find('.rating_tablo');

        $type = 0;

        foreach ($tables as $table) {

            $type++;

            if ($type > 2)
                continue;

            $trs = $table->find('tbody')->find('tr');
            $trsTemp = [];
            $rowCount = 0;
            foreach ($trs as $key => $tr) {

                if (++$rowCount > 20)
                    continue;

                $tdsTemp = [];
                $tds = $tr->find('td')->toArray();

                $tdsTemp['title'] = strip_tags(qp($tds[1])->text());
                $tdsTemp['order'] = strip_tags(qp($tds[0])->text());
                $tdsTemp['channel'] = strip_tags(qp($tds[2])->text());
                $tdsTemp['start'] = date("H:i", strtotime(strip_tags(qp($tds[3])->text())));
                $tdsTemp['end'] = date("H:i", strtotime(strip_tags(qp($tds[4])->text())));
                $tdsTemp['rating'] = strip_tags(qp($tds[5])->text());
                $tdsTemp['share'] = strip_tags(qp($tds[6])->text());
                $tdsTemp['type'] = $type;
                $tdsTemp['date'] = $date;
                $trsTemp[] = $tdsTemp;

            }
            try {
                foreach ($trsTemp as $row) {
                    $rating = new Rating();
                    $control = Rating::where('title', $row['title'])
                        ->where('channel', $row['channel'])
                        ->where('start', $row['start'])
                        ->where('end', $row['end'])
                        ->where('type', $row['type'])
                        ->where('date', $tdsTemp['date'])
                        ->first();
                    if ($control == null) {
                        $rating->create($row);
                    } else
                        $control->update($row);
                }
            } catch (Exception $er) {
                dd($er);
            }
        }


        //somera
        $client = new Client();
        $response = $client->get('http://rating.somera.com.tr/ekranella?date=' . date('Ymd', time() - 60 * 60 * 24));

        $body = json_decode($response->getBody()->getContents());

        $someraI = 0;
        foreach ($body as $somera) {
            $someraTemp = [];
            $someraTemp['title'] = $somera->program;
            $someraTemp['order'] = ++$someraI;
            $someraTemp['channel'] = $somera->channel;
            $someraTemp['start'] = null;
            $someraTemp['end'] = null;
            $someraTemp['rating'] = $somera->rating;
            $someraTemp['share'] = $somera->share;
            $someraTemp['type'] = 3;
            $someraTemp['date'] = $date;
            $rating = new Rating();

            $control = Rating::where('title', $someraTemp['title'])
                ->where('channel', $someraTemp['channel'])
                ->where('start', $someraTemp['start'])
                ->where('end', $someraTemp['end'])
                ->where('date', $date)
                ->where('type', $someraTemp['type'])
                ->first();
            if ($control == null) {
                $rating->create($someraTemp);
            } else
                $control->update($someraTemp);
        }

        return Response::json(['status' => 'ok']);

    }

}