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
        $users = User::search($data, true, Config::get('app.rec_per_page'));
        $view->total_records = $users->getTotal();
        $view->users = $users;
        return $view;
	}
	
	public function getCreateUser()
    {
        $view = View::make('user.create.index');
		
		$states = State::where('country_id', '=', 1)->get();
		
		$view->states = array('' => 'Select State');
		foreach ($states as $state) {
            $view->states[$state->id] = $state->state_name;     
        }       
        
        $view->cities = array();
        $view->towns = array();
        return $view;
    }  
    
    public function postCreateUser()
    {
        $rules = array(
            'first_name'        => 'required|max:50', 
            'last_name'         => 'required|max:50', 
            'email'             => 'required|email|unique:users', 
            'phone'             => 'digits:11',
            'website'           => 'url',
            'password'          => 'min:6',
            'confirm_password'  => 'required|same:password'
        );
        $validator = Validator::make(Input::all(), $rules);
        
        if ($validator->fails()) {
            return Redirect::route('users')->withErrors($validator)->withInput();
        }

        $activation_hash = str_random(60);
        
        $user_data = array(
            'fname'             => input::get('first_name'), 
            'lname'             => input::get('last_name'), 
            'email'             => input::get('email'), 
            'phone'             => input::get('phone'),
            'slug'              => User::getSlug(input::get('first_name').' '.input::get('last_name')),
            'password'          => Hash::make(input::get('password')), 
            'activation_hash'   => $activation_hash, 
            'status'            => 'Active'
        );
		
		$data = Input::all();
		
		if (isset($data['state']) && !empty($data['state'])) {
            $user_data['state'] = $data['state'];
        } else {
            $user_data['state'] 	= '';
            $user_data['city']  	= '';
            $user_data['town']		= '';
        }
        
        if (isset($data['city']) && !empty($data['city'])) {
            $user_data['city'] = $data['city'];
        } else {
            $user_data['city']  = '';
            $user_data['town']  = '';
        }
        
        if (isset($data['town']) && !empty($data['town'])) {
           $user_data['town'] = $data['town'];
        }
        
        if (isset($data['website']) && !empty($data['website'])) {
            $user_data['website'] = $data['website'];
        }

        $user = User::create($user_data);
        
        if ($user) {
            /*Mail::send('emails.auth.activation', array('link' => URL::route('activate-account', $activation_hash), 'username' => $user->fname), function($message) use ($user) {
                $message->to($user->email, $user->fname)->subject('Activate your account');
            });**/
            return Redirect::route('users')->withInput()->with('global_success', 'User created successfully.');
        }
		else {
			return Redirect::route('users')->withInput()->with('global_error', 'Error while creating user.');
		}
        App::abort(404);
    }    

    public function getManageads($slug='')
    {
    	$view = View::make('user.ads.index');
		
    	$users = User::where('slug', '=', $slug)->get();
		
		if (!count($users)) {
            return Response::view('error', array(), 404);
        }
		$user = $users[0];
		
        $param = array('user_id' => $user->id);
        
        if (Request::input('keyword_user_ads')) {
            $param['keyword'] = Request::input('keyword_user_ads');
        }
        
        if (Request::input('status_user_ads') && Request::input('status_user_ads')=='Expired') {
            $param['expired_ads'] = true;
        } else if (Request::input('status_user_ads') && Request::input('status_user_ads')=='Active') {
            $param['active_ads'] = true;
        }
        
        $view->user = $user;        
                
        $ads = Ad::search($param, true, Config::get('app.rec_per_page'), false);
        
        $view->total_records = $ads->getTotal(); 
        $view->ads = $ads; 
        return $view;
    }    
    
    public function getUpdateUser($slug='')
    {
        $view = View::make('user.account.index');
        
        $users = User::where('slug', '=', $slug)->get();
		
		if (!count($users)) {
            return Response::view('error', array(), 404);
        }
		$user = $users[0];
		
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
    
    public function postUpdateUser()
    {
        $rules = array(
            'first_name'        => 'required|max:50', 
            'last_name'         => 'required|max:50', 
            'phone'             => 'digits:11',
            'website'           => 'url' 
        );
        
		$new_slug_flag = false;
		
        $data = Input::all();
        
        $validator = Validator::make(Input::all(), $rules);
        
        if ($validator->fails()) {
            if (Request::ajax()) {    
                return Response::json(array('status' => 'ERROR', 'message' => $validator->messages()->all()));
            }
            return Redirect::route('my-account')->withInput()->withErrors($validator)->withInput();
        }
        
        $user = User::find($data['id']);
		
		if ($user->fname != $data['first_name'] || $user->lname != $data['last_name']) {
			$user->slug = User::getSlug(input::get('first_name').' '.input::get('last_name'));
			$new_slug_flag = true;	
		}		
		
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
                return Response::json(array('status' => 'SUCCESS', 'refresh' => $new_slug_flag, 'user' => $user->slug, 'message' => 'Profile updated successfully!'));
            } 
            return Redirect::route('my-account')->withInput()->with('success', 'Profile updated successfully.'); 
        } else {
            if (Request::ajax()) {
                return Response::json(array('status' => 'ERROR', 'refresh' => $new_slug_flag, 'message' => 'Error while updating profile.'));
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
        
        $user = User::find($data['id']);
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
        
        $user = User::find($data['id']);
        
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
        //$target = 'uploads/users/' . $target_file;
		$path = $_SERVER['DOCUMENT_ROOT'].Config::get('app.user_img_path');
		//$path = substr($path, 1, strlen($path));
		$target = $path. $target_file;
		

        if (move_uploaded_file($_FILES['photo']['tmp_name'], $target)) {
            $data = Input::all();        
        	$user = User::find($data['id']);
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
}