<?php

use GuzzleHttp\Client;
use Masterminds\HTML5;
use QueryPath\QueryPath;

class PatchController extends BaseController
{
    public function getIndex(){
        ini_set('max_execution_time', 0);

        $links = \Links::where('lock',0)->get();
        $data = [
            'links' => $links
        ];
        return View::make('admin.patch.index', $data);
    }

    public function getDivideMultiplePages(){
        ini_set('max_execution_time', 0);

        $links = \Links::where('link', 'LIKE', '%/88/%')->get();

        foreach($links as $link){
            $i = 0;
            while(true) {
                $tempLink = str_replace('/88/', '/' . ++$i . '/', $link->link);
                $tempLinkObject = \Links::where('link', $tempLink)->first();
                if($tempLinkObject != null){
                    $tempLinkObject->delete();
                }else{
                    break;
                }
            }
        }

    }

    public function getSerialForGallery(){
        ini_set('max_execution_time', 0);

        $links = Links::where('type','foto-galeri')->get();
        foreach($links as $link){
            $permalink = str_replace('http://test.ekranella.com/','',$link->link);
            $permalink = explode('/',$permalink);

            $actualSerial = \admin\Serials::where('permalink', 'LIKE', '%'.$permalink[2].'%')->first();
            if($actualSerial == null){
                $link->delete();
                continue;
            }else{
                $link->serial_id = $actualSerial->id;
                $link->save();
            }

        }
    }

    public function getSerialIdAndSeasonId(){
        ini_set('max_execution_time', 0);

        $links = Links::where('serial', '!=', '')->get();
        foreach($links as $link){
            $actualSerial = \admin\Serials::where('title', 'LIKE', '%'.$link->serial.'%')->first();
            if($actualSerial == null){
                $link->delete();
              continue;
            }else{
                $link->serial_id = $actualSerial->id;
                $actualSeason = \admin\Seasons::where('serial_id', $link->serial_id )->where('number',$link->season)->first();
                if($actualSeason == null){
                    $link->delete();
                    continue;
                }else{
                    $link->season_id = $actualSeason->id;
                    $link->save();
                }
            }

        }
    }

    public function getUniqTable(){
        ini_set('max_execution_time', 0);

        $links = Links::all();
        foreach($links as $link){
            $count = Links::where('link',$link->link)->count();

            if($count>1){
                $link->delete();
            }
        }
    }

    public function getDivideType(){
        ini_set('max_execution_time', 0);

        $objects = [
            'ozetler'  => 'none',
            'dizi' => 'none',
            'program' => 'none',
            'ekranella-ozel' => 'none',
            'roportajlar' => 'none',
            'reyting' => 'none',
            'ozel' => 'none',
            'yazarlar' => 'none',
            'yazar' => 'none',
            'blog' => 'none',
                                                                            'ozel-haber' => 'haber',
                                            'ozet' => 'dizi',
                                            'foto-galeri' => 'dizi',
            'galeriler'  => 'none',
            'roportaj' => 'roportaj',
                                                                            'haber'  => 'haber',
            'haberler'  => 'none',
            'portre'  => 'none',
            'portreler'  => 'none',
                                                                            'foto-haber'  => 'foto-haber',
            'foto-haberler' => 'none',
            'foto-galeriler' => 'none',
            'Content.aspx' =>  'none',
            'sosyal-medya-reyting' =>  'none',
            'etiket' =>  'none',
            'ekranella-portre' =>  'none',
                                            'bolum-ozeti' =>  'dizi',
            'anket-listesi' => 'none'
        ];

        $links = Links::all();
        foreach($links as $link){
            if(isset($objects[$link->type]))
                $link->object = $objects[$link->type];
            else{
                $link->delete();
                continue;
            }


            if ($link->type == "ozet"){
                $this->getDetailOzet($link);
            }
            else if($link->type == "ozel-haber"){
                $link->object = 'haber';
                $link->save();
            }else if($link->type == "foto-galeri"){
                $this->getDetailFotoGaleri($link);
            }else if($link->type == "roportaj"){
                $link->object = 'roportaj';
                $link->save();
            }else if($link->type == "foto-haber"){
                $link->object = 'haber';
                $link->save();
            }
            else if($link->type == "bolum-ozeti"){
                $this->getDetailBolumOzet($link);

            }else if($link->type == "none"){
                $link->delete();
            }
        }
    }

    public function getDetailBolumOzet($link){
        $client = new Client();
        $response = $client->get($link->link);
        $body = $response->getBody();

        $html5 = new HTML5();
        $dom = $html5->loadHTML($body->getContents());

        $serial = qp($dom)->find('.recap-band-header')->find('a')->text();
        $episodeInfo = qp($dom)->find('#MainContent_dvContent')->find('span')->first()->text();


        $episodeInfo = explode(',', $episodeInfo);

            if($episodeInfo == null || $episodeInfo == false || $episodeInfo == "" || count($episodeInfo) < 2){
                $link->delete();

            }else{
                $season =  preg_replace("/[^0-9]/","",$episodeInfo[0]);
                $episode = preg_replace("/[^0-9]/","",$episodeInfo[1]);

                $link->season = $season;
                $link->episode = $episode;
                $link->object = 'dizi';
                $link->serial = $serial;
                $link->save();
            }
    }

    public function getDetailOzet($link){
        $client = new Client();
        $response = $client->get($link->link);
        $body = $response->getBody();

        $html5 = new HTML5();
        $dom = $html5->loadHTML($body->getContents());

        $serial = qp($dom)->find('.recap-band-header')->find('a')->text();
        $episodeInfo = qp($dom)->find('.recap-band-header')->find('p')->text();
        $episodeInfo = explode(',', $episodeInfo);

            if($episodeInfo == null || $episodeInfo == false || $episodeInfo == "" || count($episodeInfo) < 2){
                $link->delete();

            }else{
                $season =  preg_replace("/[^0-9]/","",$episodeInfo[0]);
                $episode = preg_replace("/[^0-9]/","",$episodeInfo[1]);

                $link->season = $season;
                $link->episode = $episode;
                $link->object = 'dizi';
                $link->serial = $serial;
                $link->save();
            }
    }

    public function getDetailFotoGaleri($link){
        $client = new Client();
        $response = $client->get($link->link);
        $body = $response->getBody();

        $html5 = new HTML5();
        $dom = $html5->loadHTML($body->getContents());

        $serial = qp($dom)->find('.eyebrow')->text();
        $data = qp($dom)->find('title')->text();

        $data = explode(',',$data);

        $episode =  preg_replace("/[^0-9]/","",$data[0]);

        $link->object = 'dizi';
        $link->serial = str_replace(' - Foto Galeri','',$serial);
        $link->episode = $episode;

        $link->save();
    }

    public function getTypeContent()
    {
        $id = Input::get('id');
        $data = $this->getData($id);

        if ($data['link']->type == "ozet")
            return $this->getSerial($data['dom'], $data['link'], $id);

    }

    public function getSerial($dom, $link, $id){

        $data = [];
        $data['serial'] = qp($dom)->find('.recap-band-header')->find('a')->text();
        $data['serials'] = \admin\Serials::where('title', 'like', '%'.$data['serial'].'%')->get();
        $data['link'] = $link->link;
        $data['id'] = $link->id;
        return View::make('admin/patch/select-serial',$data);

    }

    public function postSerial(){

        $data = $this->getData(Input::get('id'));

        $summaryDomData = $this->getSerialSummary($data['dom']);


        $SerialController = new SerialController();
        $serial = \admin\Serials::find(Input::get('serial'));

        return $SerialController->getAddEpisode($serial->type, $serial->id, 0, 0, $serial->title,$summaryDomData);

    }

    public function getSerialSummary($dom)
    {
        $data = [];

        $data['serial'] = qp($dom)->find('.recap-band-header')->find('a')->text();
        $episodeInfo = qp($dom)->find('.recap-band-header')->find('p')->text();
        $episodeInfo = explode(',', $episodeInfo);

        preg_match('!\d+!', $episodeInfo[0], $season);
        $season = $season[0];

        preg_match('!\d+!', $episodeInfo[1], $episode);
        $episode = $episode[0];

        $data['season'] =  $season;
        $data['number'] = $episode;

        $titleDom = qp($dom)->find('.recap-title');
        $data['title'] =  $titleDom->find('h2')->text();
        $data['guest_author'] = $titleDom->find('p')->find('a')->text();

        $dateString = str_replace($data['guest_author'], '', $titleDom->find('p')->text());
        $data['created_at'] = $this->getDateFromString($dateString);

        $data['img'] = $this->downloadImage(qp($dom)->find('.alignleft.wp-caption')->find('img')->attr('src'));
        $data['content'] =  qp($dom)->find('.alignleft.wp-caption')->html() .  qp($dom)->find('.storycontent')->html();
        $data['summary'] =  substr(qp($dom)->find('.storycontent')->html(), 0, 300).'...';
        $data['tags'] = [];
        $data['permalink'] = '';
        $data['is_author'] = '0';
        $data['created_by'] = Auth::user()->id;
        $data['published'] = '1';


        return $data;
    }

    public function getDateFromString($dateString)
    {
        $month = $this->searchMonth($dateString);
        $date = explode(',', $dateString);
        $date = explode(' ', $date[0]);

        preg_match('!\d+!', $date[0], $dayInt);
        preg_match('!\d+!', $date[2], $yearInt);

        return trim(intval($yearInt[0])) . '-' . trim(intval($month)) . '-' . trim(intval($dayInt[0])).' 00:00:00';
    }

    private function searchMonth($string)
    {
        $months = [
            "Ocak" => 1, "Şubat" => 2, "Mart" => 3, "Nisan" => 4, "Mayıs" => 5, "Haziran" => 6,
            "Temmuz" => 7, "Ağustos" => 8, "Eylül" => 9, "Ekim" => 10, "Kasım" => 11, "Aralık" => 12
        ];

        foreach ($months as $month => $value) {
            $contentMonthPos = strpos($string, $month);

            if ($contentMonthPos === false) {
                continue;
            } else {
                $contentMonth = $value;
            }
        }

        return $contentMonth;

    }

    private function downloadImage($url){
        $id = uniqid().uniqid();
        $imageString = file_get_contents($url);
        $save = file_put_contents('uploads/'.$id.'_main.jpg',$imageString);
        return $id;
    }

    private function getData($id){
        $link = Links::find($id);
        $client = new Client();
        $response = $client->get($link->link);
        $body = $response->getBody();

        $html5 = new HTML5();
        $dom = $html5->loadHTML($body->getContents());
        return ['link' => $link, 'dom' => $dom];
    }
}