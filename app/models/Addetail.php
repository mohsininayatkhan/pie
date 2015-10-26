<?php
class Addetail extends Eloquent 
{
    protected $table = 'ad_details';
    protected $fillable = array(
        'ad_id',
        'attribute_id', 
        'cat_id', 
        'attribute_val'
    );
    
    public static function getAttribVal($ad_id, $attribute_id)
    {
        return self::where('ad_id', '=', $ad_id)->where('attribute_id', '=', $attribute_id)->get();
    }
}
