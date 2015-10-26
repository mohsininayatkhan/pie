<?php
class City extends Eloquent
{
    protected $table = 'facebook_friends';
    protected $fillable = array(
        'id', 
        'user_id',
        'friend_user_id',
        'created_at', 
        'updated_at'
    );
    
    public static function saveUserFriends($user_id, $friends)
    {
        foreach ($friends as $friend) {
            
        }
    }
}