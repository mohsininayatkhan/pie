<?php
class Favouriteads extends Eloquent 
{
    protected $table = 'favourite_ads';
    protected $fillable = array(
        'id',
        'ad_id', 
        'user_id'
    );
    
    public static function getSavedAds($param = array(), $page = null, $limit = null)
    {
        $res = array();

        $ads = DB::table('ads');
        
        $ads->select(DB::raw('*, ads.id as id, ads.slug as slug, users.slug as user_slug, ads.created_at as posted_date, users.created_at as membership_date, states.id as state_id, cities.id as city_id, towns.id as town_id'));    
        
        $ads->join('users', 'users.id', '=', 'ads.user_id')
            ->join('favourite_ads', 'favourite_ads.ad_id', '=', 'ads.id')
            ->join('cities', 'cities.id', '=', 'ads.city_id')
            ->join('states', 'states.id', '=', 'ads.state_id')
            ->join('categories', 'categories.id', '=', 'ads.cat_id')
            ->leftJoin('towns', 'towns.id', '=', 'ads.town_id')
            ->leftJoin('ad_media', function($join) {
                $join->on('ad_media.ad_id', '=', 'ads.id')
                    ->where('ad_media.main', '=', '1');
            }
        );
        
        $ads->where('favourite_ads.user_id', '=', $param['user_id']);
        $res = $ads->get();
        return $res;
    }
}
