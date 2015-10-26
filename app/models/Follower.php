<?php
class Follower extends Eloquent
{
    protected $table = 'followers';
    protected $fillable = array(
        'user_id', 
        'follower_user_id',
        'update_at',
        'created_at'
    );
    
    public static function getFollowersByUser($user_id, $limit=null)
    {
         $follower = DB::table('followers');
         $follower->select(DB::raw('*, users.id as user_id, users.slug as user_slug, users.created_at'));
         $follower->join('users', 'users.id', '=', 'followers.follower_user_id')->where('followers.user_id', '=', $user_id);
         if ($limit==null) {
             return $follower->get();
         }
         return $follower->paginate($limit);
    }
    
    public static function getFollowingUsers($user_id, $limit=null)
    {
         $following = DB::table('followers');
         $following->select(DB::raw('*, users.id as user_id, users.slug as user_slug, users.created_at'));
         $following->join('users', 'users.id', '=', 'followers.user_id')->where('followers.follower_user_id', '=', $user_id);
         if ($limit==null) {
             return $following->get();
         }
         return $following->paginate($limit);
    }    
}
