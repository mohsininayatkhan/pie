<?php
class UserController extends BaseController
{
    public function getLogin()
    {
        $view = View::make('user.login.index');
        return $view;
    }

    public function postLogin()
    {
        $data = Input::all();
		if (Auth::attempt(array('email' => $data['email'], 'password' => $data['pwd'], 'role'=> 'admin', 'status' => 'Active'))) {
            return Redirect::intended('dashboard');
        } else {
            return Redirect::route('login')
                ->withErrors(array('invalid_information' => 'Your email or password is incorrect.'))
                ->withInput();
        }        
    }

    public function getSignOut()
    {
        Auth::logout();
        Session::flush();
        return Redirect::route('login');
    }
	
	public function index()
    {
    	$view = View::make('user.dashboard.index');
		return $view;
	}
	
	
	public function getList()
	{
		$view = View::make('user.list.index');
		
		$data = Input::all();       
        
        $data = Input::all();
        
        $users = User::search($data, true, Config::get('app.rec_per_page'));
        $view->total_records = $users->getTotal();
        $view->users = $users;
        return $view;
	}
        
    public function getRegister()
    {
        $view = View::make('user.register.index');
        return $view;
    }
    
    public function postRegister()
    {
        $rules = array(
            'first_name'        => 'required|max:50', 
            'last_name'         => 'required|max:50', 
            'email'             => 'required|email|unique:users', 
            'password'          => 'min:6',
            'confirm_password'  => 'required|same:password'
        );
        
        $validator = Validator::make(Input::all(), $rules);
        
        if ($validator->fails()) {
            return Redirect::route('register')->withErrors($validator)->withInput();
        }

        $activation_hash = str_random(60);
        
        $user_data = array(
            'fname'             => input::get('first_name'), 
            'lname'             => input::get('last_name'), 
            'email'             => input::get('email'), 
            'slug'              => User::getSlug(input::get('first_name').' '.input::get('last_name')),
            'password'          => Hash::make(input::get('password')), 
            'activation_hash'   => $activation_hash, 
            'status'            => 'InActive'
        );

        $user = User::create($user_data);
        
        if ($user) {
            Mail::send('emails.auth.activation', array('link' => URL::route('activate-account', $activation_hash), 'username' => $user->fname), function($message) use ($user) {
                $message->to($user->email, $user->fname)->subject('Activate your account');
            });
            return Redirect::to('new-account-greeting/'.Crypt::encrypt($user->id));
        }
        App::abort(404);
    }

    public function getActivateAccount($hash)
    {
        $user = User::where('activation_hash', '=', $hash)
            ->where('status', '=', 'InActive')
            ->get(array('id','activation_hash','status'));
        
        if ($user->count()) {
            $user = $user->first();
            
            $user->activation_hash = '';
            $user->status = 'Active';
            
            if ($user->save()) {
                return View::make('user.register.activate.success');
            }
        }
        return View::make('user.register.activate.error');
    }
    
    public function getForgotPassword()
    {
        $view = View::make('user.forgotpassword.index');
        return $view;
    }
    
    public function postForgotPassword()
    {
        $rules = array(
            'email' => 'required|email', 
        );
        
        $validator = Validator::make(Input::all(), $rules);
        
        if ($validator->fails()) {
            return Redirect::route('forgot-password')->withErrors($validator)->withInput();
        }

        $user = User::where('email', '=', Input::get('email'))->get(array('id','email'));
                
        if ($user->count()) {
            $user = $user->first();
            $password = str_random(10);             
            $user->password = Hash::make($password);
            if ($user->save()) {
                Mail::send('emails.auth.recover_password', array('password' => $password, 'username' => $user->fname), function($message) use ($user) {
                    $message->to($user->email, $user->fname)->subject('Your password');
                });
                return Redirect::route('forgot-password')->withInput()->with('global_success', 'New password is sent to your registered email id');
             }              
        } else {
            return Redirect::route('forgot-password')->withInput()->with('global_error', 'Email does not exists.');            
        }
    }

    public function getManageads()
    {
        $param = array('user_id' => Auth::id());
        
        if (Request::input('keyword_user_ads')) {
            $param['keyword'] = Request::input('keyword_user_ads');
        }
        
        if (Request::input('status_user_ads') && Request::input('status_user_ads')=='Expired') {
            $param['expired_ads'] = true;
        } else if (Request::input('status_user_ads') && Request::input('status_user_ads')=='Active') {
            $param['active_ads'] = true;
        }
        
        $view = View::make('user.ads.index');        
                
        $ads = Ad::search($param, true, Config::get('app.rec_per_page'), false);
        
        $view->total_records = $ads->getTotal(); 
        $view->ads = $ads; 
        return $view;
    }    
    
    public function getMyAccount()
    {
        $view = View::make('user.account.index');
        
        $user = Auth::user();
        $states = State::where('country_id', '=', $user->country)->get();
        
        $view->states = array('' => 'Select State');
        $view->cities = array();
        $view->towns = array();
        
        foreach ($states as $state) {
            $view->states[$state->id] = $state->state_name;     
        }
        
        if ($user->state) {
            $cities = City::where('state_id', '=', $user->state)->get();
            if (count($cities)) {
                $data = array();
                $data[] = 'Select City';
                foreach ($cities as $city) {
                    $data[$city->id] = $city->city_name;     
                }
                $view->cities = $data;
            }
            
            if ($user->city) {
                $towns = Town::where('city_id', '=', $user->city)->get();
                if (count($towns)) {
                    $data = array();
                    $data[] = 'Select Town';
                    foreach ($towns as $town) {
                        $data[$town->id] = $town->town_name;    
                    }
                    $view->towns = $data;
                }
            }
        }
        
        $view->user = $user;
        return $view;
    }
    
    public function postUpdateProfile()
    {
        $rules = array(
            'first_name'        => 'required|max:50', 
            'last_name'         => 'required|max:50', 
            'phone'             => 'digits:11',
            'website'           => 'url' 
        );
        
        $data = Input::all();
        
        $validator = Validator::make(Input::all(), $rules);
        
        if ($validator->fails()) {
            if (Request::ajax()) {    
                return Response::json(array('status' => 'ERROR', 'message' => $validator->messages()->all()));
            }
            return Redirect::route('my-account')->withInput()->withErrors($validator)->withInput();
        }
        
        $user = Auth::user();
        $user->fname = $data['first_name'];
        $user->lname = $data['last_name'];
        $user->phone = $data['phone'];
        
        if (isset($data['state']) && !empty($data['state'])) {
            $user->state = $data['state'];
        } else {
            $user->state = '';
            $user->city  = '';
            $user->town  = '';
        }
        
        if (isset($data['city']) && !empty($data['city'])) {
            $user->city = $data['city'];
        } else {
            $user->city  = '';
            $user->town  = '';
        }
        
        if (isset($data['town']) && !empty($data['town'])) {
            $user->town = $data['town'];
        }
        
        if (isset($data['website']) && !empty($data['website'])) {
            $user->website = $data['website'];
        }
        
        if ($user->save()) {
            if (Request::ajax()) {
                return Response::json(array('status' => 'SUCCESS', 'message' => 'Profile updated successfully!'));
            } 
            return Redirect::route('my-account')->withInput()->with('success', 'Profile updated successfully.'); 
        } else {
            if (Request::ajax()) {
                return Response::json(array('status' => 'ERROR', 'message' => 'Error while updating profile.'));
            }
            return Redirect::route('my-account')->withInput()->with('error', 'Error while updating profile.'); 
        }
    }

    public function postChangePassword()
    {
        $rules = array(
            'old_password'      => 'required', 
            'new_password'      => 'required|min:6',
            'confirm_password'  => 'required|same:new_password'
        );
        
        $validator = Validator::make(Input::all(), $rules);
        
        if ($validator->fails()) {
            if (Request::ajax()) {    
                return Response::json(array('status' => 'ERROR', 'messages' => $validator->messages()->all()));
            }
            return Redirect::route('my-account')->withInput()->withErrors($validator)->withInput();
        }
        
        $user = Auth::user();
        if (Hash::check(Input::get('old_password'), $user->password)) {
            
            $user->password = Hash::make(Input::get('new_password'));
            if ($user->save()) {
                if (Request::ajax()) {
                    return Response::json(array('status' => 'SUCCESS', 'message' => 'Password changed successfully!'));
                } 
                return Redirect::route('my-account')->withInput()->with('success', 'Password changed successfully!');
            } else {
                if (Request::ajax()) {
                    return Response::json(array('status' => 'ERROR', 'message' => 'Error while saving password.'));
                }
                return Redirect::route('my-account')->withInput()->with('error', 'Error while saving password.'); 
            }
        } else {
                
            if (Request::ajax()) {
                return Response::json(array('status' => 'ERROR', 'message' => 'Old password is incorrect.'));
            }
            return Redirect::route('my-account')->withInput()->with('error', 'Old password is incorrect.'); 
        }
    }

    public function postEmailNotification()
    {
        $data = Input::all();
        
        $user = Auth::user();
        
        if (isset($data['chk_newsletter'])) {
            $user->receive_newsletter = 'Yes';  
        } else {
            $user->receive_newsletter = 'No';
        }
        
        if (isset($data['chk_messages'])) {
            $user->email_messages_notification = 'Yes';  
        } else {
            $user->email_messages_notification = 'No';
        }
        
        if (isset($data['chk_listings'])) {
            $user->new_listing_alert = 'Yes';  
        } else {
            $user->new_listing_alert = 'No';
        }
        
        if ($user->save()) {
            if (Request::ajax()) {
                return Response::json(array('status' => 'SUCCESS', 'message' => 'Email settings saved successfully!'));
            } 
            return Redirect::route('my-account')->withInput()->with('success', 'Email settings saved successfully!');
        } else {
            if (Request::ajax()) {
                return Response::json(array('status' => 'ERROR', 'message' => 'Error while saving email settings.'));
            }
            return Redirect::route('my-account')->withInput()->with('error', 'Error while saving email settings.'); 
        }
    }

    public function getPublicProfile($slug)
    {
        $view = View::make('user.profile.index');
        $user = User::where('slug', '=', $slug)->get();
        
        if (!count($user)) {
            return Response::view('error', array(), 404);
        }      
        $view->user = $user[0];
        
        $user_id = $user[0]->id;        
        $data = array('user_id' => $user_id, 'active_ads' => true);       
                
        $ads = Ad::search($data, 1, Config::get('app.rec_per_page'), false);
        
        $total_ads = $ads->getTotal();
        $summary = ($total_ads<=0 ? 'Sorry! No Record': $total_ads.' ad(s)').' found from '.$user[0]->fname.' '.$user[0]->lname;        
        
        $view->summary = $summary;
        $view->ads = $ads; 
        return $view;
    }

    public function postSetPhoto()
    {
        if (!$_FILES['photo'] || !$_FILES['photo']['name']) {
            return Response::json(array('status' => 'ERROR', 'message' => 'Photo not found'));
        }       
        
        if ($_FILES["photo"]["size"] > 1000000) {
            return Response::json(array('status' => 'ERROR', 'message' => 'File size should be less then 1 MB.'));   
        }
        
        $valid_extensions = array('jpg', 'jpeg', 'png', 'gif');
        
        $file_info = pathinfo($_FILES['photo']['name']);
        
        if (!in_array(mb_strtolower($file_info['extension']), $valid_extensions)) {
            return Response::json(array('status' => 'ERROR', 'message' => 'Only jpg, jpeg, png and gif files are allowed.'));   
        }
        
        $target_file = md5(uniqid()) . '-' . strtotime("now") . '.' . $file_info['extension'];
        $target = 'uploads/users/' . $target_file;

        if (move_uploaded_file($_FILES['photo']['tmp_name'], $target)) {
            $user = Auth::user();
            // delete previous user photo
            $prev_photo = $user->photo;
            File::delete(Config::get('app.user_img_path').$user->photo);
            $user->photo = $target_file;
            $user->save();
            $path = Image::url(Config::get('app.user_img_path').$target_file ,300,300,array('crop'));
            return Response::json(array('status' => 'SUCCESS', 'message' => 'Profile photo replaced successfully', 'src' => $path));
        } else {
            return Response::json(array('status' => 'ERROR', 'messages' => 'Error while uploading photo.'));
        }
    }

    public function getFollow($slug)
    {
        $user = User::where('slug', '=', $slug)->get();
        
        if (!count($user)) {
            return Response::json(array('status' => 'ERROR', 'message' => 'User does not exists.'));
        }
        $user = $user[0];
        
        $follower_user = Auth::user();
        
        if ($user->id == $follower_user->id) {
            return Response::json(array('status' => 'ERROR', 'message' => 'You can\' follow yourself.'));
        }
        
        $following = Follower::where('user_id','=', $user->id)->where('follower_user_id','=', $follower_user->id)->get();
        
        if (!count($following)) {
            $data = array(
                'user_id' => $user->id,
                'follower_user_id' => $follower_user->id,
            );
            if (Follower::create($data)) {
                $count = count(Follower::getFollowingUsers($follower_user->id));
                return Response::json(array('status' => 'SUCCESS', 'message' => 'Following', 'followers'=> $count));
            } else {
                return Response::json(array('status' => 'ERROR', 'message' => 'Error while following'));    
            }
        }
        
        if (Follower::where('user_id','=', $user->id)->where('follower_user_id','=', $follower_user->id)->delete()) {
            $count = count(Follower::getFollowingUsers($follower_user->id));
            return Response::json(array('status' => 'SUCCESS', 'message' => 'Follow', 'followers'=> $count));
        } else {
            return Response::json(array('status' => 'ERROR', 'message' => 'Error while un-following'));
        }
    }

    public function getDelete($id)
    {
        $ad = Ad::search(array('id' => $id));
        Ad::where('id', '=', $id)->delete();
        return Redirect::route('manageads')->withInput()->with('success', ''.$ad[0]->title.' has been deleted successfully.');
    }
    
    public function getMessages()
    {
        $user = Auth::user();
        $messages = Message::where('sender_id','=',$user->id)->orWhere('reciever_id', '=', $user->id)
            ->join('ads', 'ads.id', '=', 'ad_messages.ad_id')
            ->join('users', 'users.id', '=', 'ad_messages.sender_id')
            ->select('ads.id as adid', 'ads.title', 'ad_messages.subject', 'ad_messages.message', 'ad_messages.id as mid', 'ad_messages.conversation_id', 'users.fname', 'users.lname')
            ->groupBy('ad_messages.conversation_id')
            ->orderBy('ad_messages.id','DESC')
            ->get();
        $view = View::make('user.ads.messages');        
        $view->messages = $messages;
        return $view;        
    }
    
    public function postFacebookLogin()
    {
        $data = Input::all();
        $result = User::getFacebookUser($data['token']);
        
        if ($result['status'] == 'ERROR') {
            return Response::json($result);    
        }
        
        $fb_user_detail = $result['data'];
        $user = User::where('email', '=', $fb_user_detail->email)->get();
        $action = '';
        $redirect_url ='';
        
        //$friends = User::getFacbookFriends($data['token'], $fb_user_detail->id);
        //return Response::json(array('status' => 'Error', 'message' => $friends));
        
        if (count($user)) {
           $user = $user[0];
           $action = 'login';
           
           if (Session::has('redirect_url')) {
               $redirect_url = Session::pull('redirect_url');
           }     
        } else {
            $password = str_random(6);
            $param = array(
                'fname'     =>  $fb_user_detail->first_name,
                'lname'     =>  $fb_user_detail->last_name,
                'email'     =>  $fb_user_detail->email,
                'password'  =>  Hash::make($password), 
                'slug'      =>  User::getSlug($fb_user_detail->first_name.' '.$fb_user_detail->last_name),
                'status'    =>  'Active'
            );
            $user = User::create($param);
            if ($user) {
                return Response::json(array('status' => 'Error', 'message' => 'Here'));
                $action = 'registered';
                Mail::send('emails.auth.password', array('password' => $password, 'username' => $user->fname), function($message) use ($user) {
                    $message->to($user->email, $user->fname)->subject('Your password');
                });
                Session::flash('global_success', 'Welcome to eshtihar.com. We have sent you password, in case you want to login using your email.');
            }
        }
        
        if ($user) {
            Auth::login($user);
            return Response::json(array('status' => 'SUCCESS', 'action' => $action, 'redirect_url' => $redirect_url));
        } else {
            return Response::json(array('status' => 'ERROR', 'message' => 'Error while login procedure'));
        }
    }

    public function search()
    {
        $data = Input::all();       
        
        $view = View::make('user.search.index');
        
        $data = Input::all();
        
        $users = User::search($data, true, Config::get('app.rec_per_page'));
        $view->total_records = $users->getTotal();
        
        $view->users = $users;        
        return $view;
    }

    public function postSaveFavouriteAd()
    {
        $data = Input::all();
        $id = Crypt::decrypt($data['ad']);
        $ad = Ad::find($id);
        
        if (!count($ad)) {
            return Response::json(array('status' => 'ERROR', 'message' => 'Ad not found.'));
        }
        
        $user = Auth::user();
        $fav_count = Favouriteads::where('ad_id', '=', $ad->id)->where('user_id', '=', $user->id)->count();
        
        if ($fav_count) {
            $affected_rows = Favouriteads::where('user_id', '=', $user->id)->where('ad_id', '=', $ad->id)->delete();
        
            if ($affected_rows) {
                return Response::json(array('status' => 'SUCCESS', 'message' => 'Removed from favourite.', 'text' => 'Save Ad'));
            } else {
                return Response::json(array('status' => 'ERROR', 'message' => 'Error while removing from favourites.'));
            }
        } else {
            $param = array(
                'user_id'   => $user->id,
                'ad_id'     => $ad->id
            );
            
            $fav_ad = Favouriteads::create($param);
            if ($fav_ad) {
                return Response::json(array('status' => 'SUCCESS', 'message' => 'Added as favourite.', 'text' => 'Saved'));
            } else {
                return Response::json(array('status' => 'ERROR', 'message' => 'Error while adding as favourite.'));
            }
        }
    }

    public function getMyfavouriteAds()
    {
        $view = View::make('user.savedads.index');
        
        $user = Auth::user();
        $ads = Favouriteads::getSavedAds(array('user_id' => $user->id));
        $view->total_records = Favouriteads::where('user_id', '=', $user->id)->count();
        $view->ads = $ads;
        return $view;
    }
    
    public function getWhotoFollow()
    {
        $view = View::make('user.whotofollow.index');
        $user = Auth::user();
        $users= User::getSuggestedUsers($user->id, Config::get('app.rec_per_page'));
        $view->users = $users;
        $view->total_records = $users->getTotal();
        return $view;
    }  
}