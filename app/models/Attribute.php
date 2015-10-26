<?php
class Attribute extends Eloquent
{
    protected $table = 'cat_attributes';
    public $timestamps = false;
    protected $fillable = array(
        'category_id', 
        'name',
        'type',
        'required', 
        'multiple',
        'value', 
        'order'
    );
}
