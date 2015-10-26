<?php
class Town extends Eloquent
{
    protected $table = 'towns';
    protected $fillable = array('city_id','town_name');
}
