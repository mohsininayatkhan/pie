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
    });    
    
    // user login
    Route::get('/login', array('as' => 'login', 'uses' => 'UserController@getLogin'));  
});

Route::group(array('before' => 'auth'), function() {
    	
	// User logout
    Route::get('/logout', array('as' => 'logout', 'uses' => 'UserController@getSignOut'));
			
    // dashboard page	
    Route::any('/dashboard', array('as' => 'dashboard', 'uses' => 'UserController@index'));
	
	// list site users
	Route::any('/users', array('as' => 'users', 'uses' => 'UserController@getList'));
	
	// create new user
	Route::get('/create-user', array('as' => 'create-user', 'uses' => 'UserController@getCreateUser'));	
	
    // update user
	Route::get('/update-user/{slug}', array('as' => 'update-user', 'uses' => 'UserController@getUpdateUser'));    
	
	// manage user ads
	Route::get('/user-ads/{slug}', array('as' => 'user-ads', 'uses' => 'UserController@getManageads'));
    
    Route::group(array('before' => 'csrf'), function() {        
        try {        	
			// Create User
			Route::post('/create-user', array('as' => 'create-user', 'uses' => 'UserController@postCreateUser'));
			
			// Update User
			Route::post('/update-user', array('as' => 'update-user', 'uses' => 'UserController@postUpdateUser'));
			
            // set profile photo
            Route::post('/set-user-profile-photo', array('as' => 'set-user-profile-photo', 'uses' => 'UserController@postSetPhoto'));
			
			// save/update email notifications
            Route::post('/email-notification', array('as' => 'email-notification', 'uses' => 'UserController@postEmailNotification'));
        } catch (\Exception $e) {
            return Response::json(array(
                'status' => 'ERROR', 
                'messages' => array('Error while saving record.' . $e->getMessage())
            ));
        } 
    });    
});