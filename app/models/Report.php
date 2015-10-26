<?php
class Report extends Eloquent
{
    protected $table = 'ad_reports';

    protected $fillable = array('ad_id', 
        'user_id', 
        'message', 
        'report_type'
    );
}