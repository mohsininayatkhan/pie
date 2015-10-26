<?php
class City extends Eloquent
{
    protected $table = 'cities';
    
    
    public static function getMostPopular($limit=15)
    {
        $ads = DB::table('ads');
        $ads->select(DB::raw('cities.id, cities.city_name, COUNT(ads.id) AS ads_count'));
        
        $ads->leftJoin('cities', 'cities.id', '=', 'ads.city_id');
        
        $date = new DateTime;
        $exp_days = Config::get('app.ad_expiry_days');
        $date->modify("-$exp_days days");
        $formatted_date = $date->format('Y-m-d H:i:s');
        
        $ads->where('ads.status', '=', 'Active');
        $ads->where('ads.created_at', '>=', $formatted_date);
        $ads->groupBy('cities.id');
        $ads->orderBy('ads_count', 'desc');
        $ads->skip(0)->take($limit);
        return $ads->get();
    }
}
