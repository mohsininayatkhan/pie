<?php
class Media extends Eloquent
{
    protected $table = 'ad_media';
    protected $fillable = array(
        'ad_id',
        'type', 
        'file',
        'main'
    );
}