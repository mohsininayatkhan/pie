<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface
{
	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password', 'remember_token');
    
    protected $fillable = array('fname', 'lname', 'email', 'photo','slug', 'password', 'phone', 'country', 'website', 'state', 'city',  'town', 'status', 'activation_hash', 'receive_newsletter', 'email_messages_notification', 'new_listing_alert', 'role');
    
    public static function getSlug($name, $id='')
    {
        $slug = Str::slug($name);
        $user = DB::table('users');
        
        if ($id == '') {
            $slugCount = count(self::whereRaw("slug REGEXP '^{$slug}(-[0-9]*)?$'")->get());    
        } else {
            $slugCount = count(self::whereRaw("slug REGEXP '^{$slug}(-[0-9]*)?$'")->where('id', '!=', $id)->get());
        }
        return ($slugCount > 0) ? "{$slug}-{$slugCount}" : $slug;
    }
    
    public static function getLocation($user)
    {
         $ads = DB::table('users');
         return $ads->leftJoin('states', 'users.state', '=', 'states.id')->leftJoin('cities', 'users.city', '=', 'cities.id')->leftJoin('towns', 'users.town', '=', 'towns.id')->where('users.id', '=', $user)->get();
    }
    
    public static function getFacebookUser($token)
    {
        $graph_url = "https://graph.facebook.com/me?access_token=".$token;
        if (!function_exists('curl_init')) {
            return array('status' => 'ERROR', 'message' => 'CURL is not installed!');
        }
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $graph_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $output = curl_exec($ch);

        if (curl_errno($ch)) {
            return array('status' => 'ERROR', 'message' => curl_error($ch));
        }
        curl_close($ch);
        return array('status' => 'SUCCESS', 'data' => json_decode($output));
    }
    
    public static function getFacbookFriends($token, $id)
    {
        $graph_url = "https://graph.facebook.com/$id/friends?access_token=".$token;
        if (!function_exists('curl_init')) {
            return array('status' => 'ERROR', 'message' => 'CURL is not installed!');
        }
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $graph_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $output = curl_exec($ch);

        if (curl_errno($ch)) {
            return array('status' => 'ERROR', 'message' => curl_error($ch));
        }
        curl_close($ch);
        return array('status' => 'SUCCESS', 'data' => json_decode($output));
    }
    
    public static function search($param = array(), $page = null, $limit = null, $with_count = false, $order_by=array())
    {
        $users = DB::table('users');
        
        $users->select(DB::raw('*, COUNT(follower_user_id) as num_of_followers, users.id as user_id, users.created_at as joined_date'));
        
        $users->leftJoin('cities', 'cities.id', '=', 'users.city')
              ->leftJoin('states', 'states.id', '=', 'users.state')
              ->leftJoin('towns', 'towns.id', '=', 'users.town')
              ->leftJoin('followers', 'followers.user_id', '=', 'users.id');
            
        if (isset($param['keyword']) && !empty($param['keyword'])) {
            $users->orWhere(function($query) use ($param) {
                $query->orwhere('fname', 'LIKE', '%' . $param['keyword'] . '%')
                    ->orwhere('lname', 'LIKE', '%' . $param['keyword'] . '%')
                    ->orwhere('email', 'LIKE', '%' . $param['keyword'] . '%');
            });
        }
        
        $users->groupBy('users.id');
        
        if (count($order_by)) {
            foreach ($order_by as $key => $val) {
                $users->orderBy($key, $val);
            }
        } else {
            $users->orderBy('users.fname', 'asc');
        }       
        
        if ($with_count) {
            return count($users->get());
        }
        
        if ($page != null) {
            $res = $users->paginate($limit);
        } else {
            $res = $users->get();    
        }
        return $res;
    }

    public static function getSuggestedUsers($user_id, $limit=null)
    {
        $user = User::find($user_id);
        $followers = Follower::getFollowingUsers($user_id);
		$following_users = Follower::getFollowersByUser($user_id);
		
		$following_user_ids = array();
        $following_user_ids[] = 0;
		if (count($following_users)) {
            foreach ($following_users as $following_user) {
                $following_user_ids[] = $following_user->id;
            }    
        }
		
        $follower_ids = array();
        $follower_ids[] = 0;
        if (count($followers)) {
            foreach ($followers as $follower) {
                $follower_ids[] = $follower->user_id;
            }    
        }
        
        $exclude_ids = $follower_ids;
        $exclude_ids[] = $user_id;        
        
        $users = DB::table('followers');
        $users->select(DB::raw('users.*, users.id as user_id, users.created_at as joined_date, COUNT(followers.user_id) AS mutual_count'));
        $users->rightJoin('users', 'users.id', '=', 'followers.user_id')
            ->whereIn('followers.follower_user_id', $follower_ids)
            ->whereNotIn('users.id', $exclude_ids)
            ->groupBy('followers.user_id')
            ->orderBy('mutual_count');
            
        if ($user->state!='') {
            $users->orWhere(function($users) use ($user, $exclude_ids) {
                $users->Where('users.state', '=', $user->state)->whereNotIn('users.id', $exclude_ids);
            });
        }
		
		
		if (count($following_user_ids)) {
			$users->Where(function($users) use ($user, $exclude_ids, $following_user_ids) {
                $users->WhereIn('users.id', $following_user_ids)->whereNotIn('users.id', $exclude_ids);
            });
		}
        
        if ($user->city!='') {
            $users->orWhere(function($users) use ($user, $exclude_ids) {
                $users->Where('users.city', '=', $user->city)->whereNotIn('users.id', $exclude_ids);
            });
        }
        
        if ($user->town!='') {
            $users->orWhere(function($users) use ($user, $exclude_ids) {
                $users->Where('users.town', '=', $user->town)->whereNotIn('users.id', $exclude_ids);
            });
        }
        
        $res = $users->get();
        $queries = DB::getQueryLog();
        $last_query = end($queries);
		//print_r($last_query);        
       
        if ($limit==null) {
           return $users->get();
        }
        return $users->paginate($limit);
    }

	public static function isAdmin()
	{
		if (Auth::user()->role == 'admin') {
			return true;
		} 
		return false;
	}
}
