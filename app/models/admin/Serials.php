<?php

namespace admin;


class Serials extends \Eloquent
{

    protected $table = 'serials';
    public $timestamps = true;


    protected $dates = ['deleted_at'];
    protected $fillable = ['id', 'type', 'title', 'cover', 'img', 'permalink', 'channel_id', 'airing', 'extra', 'info', 'start_year', 'end_year', 'created_by', 'cast', 'writer', 'director', 'producer', 'views', 'twitter', 'is_popular', 'is_masked'];
    protected $visible = ['id', 'type', 'title', 'cover', 'img', 'permalink', 'channel_id', 'airing', 'extra', 'info', 'start_year', 'end_year', 'created_by', 'cast', 'writer', 'director', 'producer', 'views', 'twitter', 'is_popular', 'is_masked'];

    public function season()
    {
        return $this->hasMany('admin\Seasons', 'serial_id');
    }

    public function user(){
        return true;
    }

    public function channel()
    {
        return $this->belongsTo('admin\Channel');
    }

    public static function episodes($serial, $onlyPublished = false, $offset = 0, $limit = 0)
    {

        $episodes = [];

        $seasons = Serials::find($serial)->season;

        foreach ($seasons as $season) {
            $epsQuery = $season->episode();
            $epsQuery = $epsQuery->orderBy('created_at', 'desc');
            if ($onlyPublished)
                $epsQuery = $epsQuery->where('published', 1);
            if($limit)
                $epsQuery = $epsQuery->take($limit)->skip($offset);
            $eps = $epsQuery->get();
            foreach ($eps as $sp) {
                $sa = $sp->toArray();
                $sa['username'] = $sp->user->name;
                $sa['season'] = $season->number;
                $episodes[] = $sa;
            }

        }

        return $episodes;
    }

    public static function specials($serial, $count = false, $onlyPublished = false, $offset = 0, $limit = 0)
    {

        $specials = [];

        $seasons = Serials::find($serial)->season;

        foreach ($seasons as $season) {
            $query = $season->special();

            if ($count)
                $query = $query->limit($count);

            $spsQuery = $query->orderBy('created_at', 'desc');
            if ($onlyPublished)
                $spsQuery = $spsQuery->where('published', 1);
            if($limit)
                $spsQuery = $spsQuery->take($limit)->skip($offset);
            $sps = $spsQuery->get();
            foreach ($sps as $sp) {
                $sa = $sp->toArray();
                $sa['username'] = $sp->user->name;
                $sa['season'] = $season->number;
                $specials[] = $sa;
            }

        }

        return $specials;
    }

    public static function trailers($serial, $count = false, $onlyPublished = false, $offset = 0, $limit = 0)
    {

        $specials = [];

        $seasons = Serials::find($serial)->season;

        foreach ($seasons as $season) {
            $query = $season->trailer();

            if ($count)
                $query = $query->limit($count);

            $spsQuery = $query->orderBy('created_at', 'desc');
            if ($onlyPublished)
                $spsQuery = $spsQuery->where('published', 1);
            if($limit)
                $spsQuery = $spsQuery->take($limit)->skip($offset);
            $sps = $spsQuery->get();
            foreach ($sps as $sp) {
                $sa = $sp->toArray();
                $sa['username'] = $sp->user->name;
                $sa['season'] = $season->number;
                $specials[] = $sa;
            }

        }

        return $specials;
    }

    public static function sgalleries($serial, $count = false, $onlyPublished = false, $offset = 0, $limit = 0)
    {

        $sgalleries = [];

        $seasons = Serials::find($serial)->season;

        foreach ($seasons as $season) {
            $query = $season->sgallery();

            if ($count)
                $query = $query->limit($count);

            $spsQuery = $query->orderBy('created_at', 'desc');
            if ($onlyPublished)
                $spsQuery = $spsQuery->where('published', 1);
            if($limit)
                $spsQuery = $spsQuery->take($limit)->skip($offset);
            $sps = $spsQuery->get();
            foreach ($sps as $sp) {
                $sa = $sp->toArray();
                $sa['username'] = $sp->user->name;
                $sa['season'] = $season->number;
                $sgalleries[] = $sa;
            }
        }

        return $sgalleries;
    }

}