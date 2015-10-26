<?php

/*
 |--------------------------------------------------------------------------
 | Application Routes
 |--------------------------------------------------------------------------
 |
 | Here is where you can register all of the routes for an application.
 | It's a breeze. Simply tell Laravel the URIs it should respond to
 | and give it the Closure to execute when that URI is requested.
 |
 */

// home page
Route::get('/', function() {
	
	if (!Auth::check()) {
		return Redirect::to('/login');
	}
	$view = View::make('user.dashboard.index');
	return $view;
});

// page not found
App::missing(function($exception) {
    return Response::view('error', array(), 404);
});

// checks user email for already exists
Route::post('/check-email', function() {    
    $user = User::where('email', '=', Input::get('email'))->get();    
    if ($user->count()) {
        return 'false';
    } else {
        return 'true';
    }
});

// get categories attributes of ad
Route::get('/ad/attributes/{id}', array('as' => 'attributes', 'uses' => 'AdController@getAttributes'));

// get category attributes to refine serarch
Route::get('/search/attributes/{id}', array('as' => 'searcg-attributes', 'uses' => 'SearchController@getAttributes'));

// get child categories
Route::get('/get-child-categories/{id}', function($id) {
        
    $categories = Category::where('parent_id', '=', $id)->orderBy('order')->orderBy('name')->get();
    
    if ($categories) {
        return Response::json(array('status' => 'SUCCESS', 'categories' => $categories ));
    } else {
        return Response::json(array('status' => 'ERROR', 'categories' => '', 'message' => 'Error while getting categories'));
    }
});

// Get locations
Route::get('/get-locations/{location}/{id}', function($location, $id) {
    if ($location == 'cities') {
        $locations = City::where('state_id', '=', $id)->get();
    } else if ($location == 'towns') {
        $locations = Town::where('city_id', '=', $id)->get();
    } else {
        return Response::json(array('status' => 'ERROR', 'locations' => '', 'message' => 'Error while getting locations'));
    }
    return Response::json(array('status' => 'SUCCESS', 'locations' => $locations ));
});

// Update ad
Route::get('/user-profile/{id}', array('as' => 'user-profile', 'uses' => 'UserController@getPublicProfile'));




// csrf check
Route::group(array('before' => 'csrf'), function() {
    // post/save ad information    
    Route::post('/create-ad', array('as' => 'create-ad-post', 'uses' => 'AdController@postCreate'));
	Route::post('/contact-us', array('uses' => 'ContactController@postContact'));
});
    
     
Route::group(array('before' => 'guest'), function() {
    
    Route::group(array('before' => 'csrf'), function() {
        // check login    
        Route::post('/login', array('as' => 'login', 'uses' => 'UserController@postLogin'));
        
        // register (save)
        Route::post('/register', array('as' => 'register', 'uses' => 'UserController@postRegister'));
        
        // forgot password (save)
        Route::post('/forgot-password', array('as' => 'forgot-password', 'uses' => 'UserController@postForgotPassword'));
    });    
    
    // user login
    Route::get('/login', array('as' => 'login', 'uses' => 'UserController@getLogin'));
    
    // user registration
    Route::get('/register', array('as' => 'register', 'uses' => 'UserController@getRegister'));    
    
    // user login
    Route::get('/forgot-password', array('as' => 'forgot-password', 'uses' => 'UserController@getForgotPassword')); 
});

Route::group(array('before' => 'auth'), function() {
    	
    // dashboard page	
    Route::any('/dashboard', array('as' => 'dashboard', 'uses' => 'UserController@index'));
	
	// list site users
	Route::any('/users', array('as' => 'users', 'uses' => 'UserController@getList'));
	
    // User logout
    Route::get('/logout', array('as' => 'logout', 'uses' => 'UserController@getSignOut'));
    
    // Update ad
    Route::get('/update-ad/{id}', array('as' => 'update-ad', 'uses' => 'AdController@getUpdate'));   
    
    // post/update ad information    
    Route::post('/update-ad', array('as' => 'update-ad-post', 'uses' => 'AdController@postUpdate'));
    
    // user ads
    Route::get('/manage-ads', array('as' => 'manage-ads', 'uses' => 'UserController@getManageads'));    
        
    // delete user ad
    Route::post('/delete-user-ad/', array('as' => 'delete-user-ad', 'uses' => 'AdController@postDeleteUserAd'));
    
       
    
    // delete user ad
    Route::get('/delete/{id}', array('as' => 'delete-ads', 'uses' => 'UserController@getDelete'));    
    
    
    Route::group(array('before' => 'csrf'), function() {        
        try {
            // change password    
            Route::post('/change-password', array('as' => 'change-password', 'uses' => 'UserController@postChangePassword'));
            
            // update user profile
            Route::post('/update-profile', array('as' => 'update-profile', 'uses' => 'UserController@postUpdateProfile'));
            
            // save/update email notifications
           	Route::post('/email-notification', array('as' => 'email-notification', 'uses' => 'UserController@postEmailNotification'));
			            
            // report ad            
            Route::post('/report', array('as' => 'report', 'uses' => 'MessageController@postReport'));
            
                
            Route::post('/message/{id}', array('as' => 'message-detail', 'uses' => 'MessageController@postMessageDetail'));
            
            // set profile photo
            Route::post('/set-user-profile-photo', array('as' => 'set-user-profile-photo', 'uses' => 'UserController@postSetPhoto'));
        } catch (\Exception $e) {
            return Response::json(array(
                'status' => 'ERROR', 
                'messages' => array('Error while saving record.' . $e->getMessage())
            ));
        } 
    });    
});

// Routes to create new ad
Route::group(array('before' => 'create-new-ad'), function() {        
    Route::get('/create-ad', array('as' => 'create-ad', 'uses' => 'AdController@getCreate'));    
});



Route::group(array('prefix', 'admin', 'before' => 'admin'), function()
{
    //
    Route::get('/terms', array('as' => 'terms', function() {
	    $view = View::make('pages.terms-conditions');
	    return $view;
	}));
});