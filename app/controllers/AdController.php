<?php
class AdController extends BaseController 
{

    private static $account_rules = array(
        'first_name'        => 'required|max:50', 
        'last_name'         => 'required|max:50', 
        'email'             => 'required|email|unique:users', 
        'phone'             => 'required|digits:11', 
        'link'	            => 'url',
        'password'          => 'required',
        'confirm_password'  => 'same:password'
    );

    private static $basic_ad_rules = array(
        'title'         => 'required|max:80|min:20', 
        'description'   => 'required|min:50', 
        'cat'           => 'required', 
        'price'         => 'sometimes|required',
        'dealer'        => 'required'
    );

    private static $ad_contact_rules = array(
        'contact_name'  => 'required|max:100', 
        'contact_email' => 'required|email', 
        'contact_phone' => 'required|digits:11'
    );

    private static $attribute_error_messages;

    public function __construct()
    {
    }

    public function getCreate()
    {
        $view = View::make('ad.create.index');
        $main_categories = Category::where('parent_id', '=', '0')->get();
        // States of Pakistan
        $states = State::where('country_id', '=', 1)->get();
        $view->main_categories = $main_categories;
        $view->states = $states;
        
        if (Auth::check()) {
            $view->user = Auth::user();    
        }        
        return $view;
    }

    public function postCreate()
    {

        $data = Input::all();

        /**
         * DATA VALIDATION
         *
         */
        $validation_flag = true;
        $validation_error_messages = array();

        $images_validation_flag = true;
        $img_error_messages = array();
        $images_upload = array();
        $ad_images = array();

        $ad_category_attributes = array();

        $rules = self::$basic_ad_rules;

        // IMAGES VALIDATION(EXTENSION AND SIZE)
        if (!empty($_FILES)) {

            // accepted image extensions
            $valid_extensions = array('jpg', 'jpeg', 'png', 'gif');

            for ($i = 0; $i < count($_FILES['file']['name']); $i++) {

                $file_info = pathinfo($_FILES['file']['name'][$i]);

                if (in_array($file_info['basename'], $data['img_names'])) {

                    if (!in_array(mb_strtolower($file_info['extension']), $valid_extensions)) {
                        $images_validation = false;
                        $img_error_messages[] = 'Only jpg, jpeg, png and gif files are allowed.';
                        break;
                    }

                    if ($_FILES["file"]["size"][$i] > 1000000) {
                        $images_validation = false;
                        $img_error_messages[] = 'File size should be less then 1 MB.';
                        break;
                    }

                    $img = array('path' => $_FILES['file']['tmp_name'][$i], 'extension' => $file_info['extension']);
                    array_push($images_upload, $img);
                }
            }
        }

        // CREATE NEW ACCOUNT/ CONTACT INFORMATION VALIDATION
        if (Auth::check()) {
            $rules = array_merge(self::$basic_ad_rules, self::$ad_contact_rules);
        } else {
            $rules = array_merge(self::$basic_ad_rules, self::$account_rules);
        }
         
        self::$attribute_error_messages = array();

        // CATEGORY ATTRIBUTES VALIDATION
        if (Input::get('categories')) {

            $attribute_rules = array();
            Input::get('categories');
            $attributes = Attribute::whereRaw('category_id in (' . Input::get('categories') . ')')->get();

            foreach ($attributes as $attribute) {

                $attribute_name = mb_strtolower(str_replace(" ", "_", $attribute->name));
                $attribute_field = 'attr_' . $attribute->id . '_' . $attribute_name;

                if ($attribute->required == 'true') {
                    $attribute_rules[$attribute_field] = 'required';
                    self::$attribute_error_messages[$attribute_field . '.required'] = $attribute->name . ' field is required.';
                }
                $attr_info = array('field' => $attribute_field, 'id' => $attribute->id, 'name' => $attribute->name, 'type' => $attribute->type);
                array_push($ad_category_attributes, $attr_info);
            }
            $rules = array_merge($rules, $attribute_rules);
        }      

        $validator = Validator::make(Input::all(), $rules, self::$attribute_error_messages);

        if ($validator->fails()) {
            $validation_error_messages = $validator->messages()->all();
            $validation_flag = false;
        }

        if (!$validation_flag || !$images_validation_flag) {
            return Response::json(array('status' => 'ERROR', 'messages' => array_merge($validation_error_messages, $img_error_messages)));
        }

        /*
         * PROCESSING DATA
         *
         */

        // trying to upload images
        foreach ($images_upload as $image) {

            $target_file = md5(uniqid()) . '-' . strtotime("now") . '.' . $image['extension'];
            $target = 'uploads/ads/' . $target_file;

            if (move_uploaded_file($image['path'], $target)) {
                $ad_images[] = $target_file;
            } else {
                return Response::json(array('status' => 'ERROR', 'messages' => 'Error while uploading images.'));
                break;
            }
        }

        DB::beginTransaction();
        
        $user_mode = '';
        $activation_hash = '';

        try {
                
            if (Auth::check()) {
                $user = Auth::user(); 
                $seller_name = input::get('contact_name');
                
                // user already exists
                $user_mode = '1'; 
            } else {
                               
                // new user created     
                $user_mode = '0';
                
                $activation_hash = str_random(60);
                
                // creating user
                $user_data = array(
                    'fname'             => input::get('first_name'), 
                    'lname'             => input::get('last_name'), 
                    'email'             => input::get('email'), 
                    'phone'             => input::get('phone'), 
                    'slug'              => User::getSlug(input::get('first_name').' '.input::get('last_name')),
                    'password'          => Hash::make(input::get('password')), 
                    'activation_hash'   => $activation_hash, 
                    'status'            => 'InActive'
                );
                $user = User::create($user_data);

                //Auth::loginUsingId($user->id);
                if (!$user) {
                    return Response::json(array('status' => 'ERROR', 'messages' => 'Error while saving user information.'));
                }                
                $seller_name = input::get('first_name') . ' ' . input::get('last_name');
            }

            $cat_levels = Category::getAllParents(input::get('cat'));
            
            $category_name = '';
            $cat_sequence = array();
            
            foreach ($cat_levels as $level) {
                $category_name .= $level['cat_name'].' ';
                
                $lev = $level['cat_level'];
                $cat_sequence['cat_level_'.$lev] =  $level['cat_id'];
            } 

            // saving basic ad information
            $ad_data = array(
                'cat_id'                => input::get('cat'),
                'slug'                  => Ad::getSlug(input::get('title')), 
                'categories'            => input::get('categories'),
                'category_names'        => $category_name,
                'user_id'               => $user->id, 
                'title'                 => input::get('title'), 
                'detail'                => input::get('description'), 
                'link'                	=> input::get('link'),
                'price'                 => input::get('price', '0'), 
                'price_negotiable'      => input::get('price_negotiable', '0'),
                'seller_name'           => $seller_name, 
                'seller_email'          => input::get('contact_email', $user->email), 
                'seller_phone'          => input::get('contact_phone', $user->phone),
                'seller_phone_public'   => input::get('seller_phone_public', '0'),
                'seller_type'           => input::get('dealer'),
                'country_id'            => 1, // For Pakistan
                'state_id'              => input::get('state'), 
                'city_id'               => input::get('city'), 
                'town_id'               => input::get('town'), 
                'featured'              => input::get('featured', '0'), 
                'spotlight'             => input::get('spotlight', '0'),
                'status'                => 'Active',
            );
			
            // merging category levels
            $ad_data = array_merge($ad_data, $cat_sequence);
            
            $ad = Ad::create($ad_data);

            if ($ad) {

    	        $catid = input::get('cat');
				$selCat = Category::where('id','=',$catid)->get();
				$numOfAd = $selCat[0]->number_of_ad + 1;
				Category::where('id', '=', $catid)->update(array('number_of_ad' => $numOfAd));	
				
				$catids = explode(',',input::get('categories'));				
				foreach($catids as $cid){
					$cat = Category::where('id','=',$cid)->get();
					$nmOfAd = $cat[0]->number_of_ad + 1;
					Category::where('id', '=', $cid)->update(array('number_of_ad' => $nmOfAd));						
				}
                
                // saving ad images
                if (count($ad_images)) {
                    $count = 0;    
                    foreach ($ad_images as $img) {
                        $img_data = array(
                            'ad_id' => $ad->id, 
                            'type'  => 'Image', 
                            'file'  => $img, 
                            'main'  => ($count==0) ? '1' : '0'
                        );
                        Media::create($img_data);
                        $count++;
                    }
                }
                
                // ad unique id
                $ad->unique_id = '1010'.str_pad($ad->id, 6, '0', STR_PAD_LEFT);
                $ad->update();

                // saving ad attributes
                if (count($ad_category_attributes)) {
                        
                    $ad_specs = array();
                    foreach ($ad_category_attributes as $attr) {
                        
                        $values = array();
                        if (is_array(Input::get($attr['field']))) {
                            $values = Input::get($attr['field']);         
                        } else {
                            $values[] = Input::get($attr['field']);
                        }
                        
                        $keywords_value = array();
                        
                        foreach ($values as $val) {                            
                            $arr_val = explode("_", $val);
                            $keywords_value[] = isset($arr_val[1]) ? $arr_val[1] : $arr_val[0];
                            $attr_data = array(
                                'cat_id'        => input::get('cat'), 
                                'attribute_id'  => $attr['id'], 
                                'ad_id'         => $ad->id, 
                                'attribute_val' => $arr_val[0]
                            );
                            Addetail::create($attr_data);
                        }
                        
                        array_push($ad_specs, array(
                            'name'   => $attr['name'],
                            'type'   => $attr['type'],
                            'value'  => implode(",",$keywords_value)
                        ));
                    }
                    $ad->keywords = json_encode($ad_specs);
                    $ad->save();
                }
            }

        } catch (\Exception $e) {
            DB::rollback();
            // deleting images also
            if (count($ad_images)) {
                foreach ($ad_images as $img) {
                    File::delete(Config::get('app.ad_img_path').$img);
                }
            }
            return Response::json(array(
                'status' => 'ERROR', 
                'messages' => array('Error while saving record.' . $e->getMessage())
            ));
            //throw $e;
        }
        DB::commit();
        Session::forget('create_user');
        
        if (!Auth::check()) {
            Mail::send('emails.auth.activation', array('link' => URL::route('activate-account', $activation_hash), 'username' => $user->fname), function($message) use ($user) {
                $message->to($user->email, $user->fname)->subject('Activate your account');
            });
        }
        return Response::json(array('status' => 'SUCCESS', 'mode' => $user_mode, 'code_1' => Crypt::encrypt($user->id),  'code_2' => Crypt::encrypt($ad->id)));
    }


    public function getUpdate($id='')
    {            
        $ads = Ad::search(array('user_id' => Auth::id(),'ad_id' => Crypt::decrypt($id)));
        if (!$ads) {
            return Response::view('error', array(), 404);
        }
            
        $view = View::make('ad.update.index');
        
        // category level
        $cat_levels = Category::getAllParents($ads[0]->cat_id);
        
        // ad category
        $ad_category = Category::where('id', '=', $ads[0]->cat_id)->get();
        $view->ad_category = $ad_category[0];
        
        // category attributes
        $categories =  $ads[0]->categories.','.$ads[0]->cat_id;
        
        // ad category levels
        $cat = '';
        foreach ($cat_levels as $level) {
            $cat .= $level['cat_name'].' > '; 
        }

        $view->ad = $ads[0];        
        $view->category_lavels = $cat;
        $view->attributes = Attribute::whereRaw('category_id in (' . $categories . ')')->get();
        $view->images = Media::where('ad_id', '=', $ads[0]->id)->where('type', '=', 'Image')->get();
        
        return $view;
    }
    
    public function postUpdate()
    {
        $data = Input::all();
        
        $ad = Ad::where('id', '=', $data['ad'])->where('user_id', '=', Auth::id())->get();
        $ad = $ad->first();
        
        if (!$ad) {
            return Response::view('error', array(), 404);
        }
        
        $validation_flag = true;
        $validation_error_messages = array();

        $images_validation_flag = true;
        $img_error_messages = array();        
        $ad_category_attributes = array();
        
        $images_upload = array();
        $ad_images = array();        
        
        if (!empty($_FILES)) {
    
            // accepted image extensions
            $valid_extensions = array('jpg', 'jpeg', 'png', 'gif');
    
            for ($i = 0; $i < count($_FILES['file']['name']); $i++) {
    
                $file_info = pathinfo($_FILES['file']['name'][$i]);
    
                if (in_array($file_info['basename'], $data['img_names'])) {
    
                    if (!in_array(mb_strtolower($file_info['extension']), $valid_extensions)) {
                        $images_validation = false;
                        $img_error_messages[] = 'Only jpg, jpeg, png and gif files are allowed.';
                        break;
                    }
    
                    if ($_FILES["file"]["size"][$i] > 1000000) {
                        $images_validation = false;
                        $img_error_messages[] = 'File size should be less then 1 MB.';
                        break;
                    }
    
                    $img = array('path' => $_FILES['file']['tmp_name'][$i], 'extension' => $file_info['extension']);
                    array_push($images_upload, $img);
                }
            }
        }

        foreach ($images_upload as $image) {

            $target_file = md5(uniqid()) . '-' . strtotime("now") . '.' . $image['extension'];
            $target = 'uploads/ads/' . $target_file;

            if (move_uploaded_file($image['path'], $target)) {
                $ad_images[] = $target_file;
            } else {
                return Response::json(array('status' => 'ERROR', 'messages' => 'Error while uploading images.'));
                break;
            }
        }     

        $rules = array_merge(self::$basic_ad_rules, self::$ad_contact_rules);
        
        self::$attribute_error_messages = array();
        
        // CATEGORY ATTRIBUTES VALIDATION
        if (Input::get('categories')) {

            $attribute_rules = array();
            Input::get('categories');
            $attributes = Attribute::whereRaw('category_id in (' . Input::get('categories') . ')')->get();

            foreach ($attributes as $attribute) {

                $attribute_name = mb_strtolower(str_replace(" ", "_", $attribute->name));
                $attribute_field = 'attr_' . $attribute->id . '_' . $attribute_name;

                if ($attribute->required == 'true') {
                    $attribute_rules[$attribute_field] = 'required';
                    self::$attribute_error_messages[$attribute_field . '.required'] = $attribute->name . ' field is required.';
                }
                $attr_info = array('field' => $attribute_field, 'id' => $attribute->id, 'name' => $attribute->name, 'type' => $attribute->type);
                array_push($ad_category_attributes, $attr_info);
            }
            $rules = array_merge($rules, $attribute_rules);
        }      

        $validator = Validator::make(Input::all(), $rules, self::$attribute_error_messages);

        if ($validator->fails()) {
            $validation_error_messages = $validator->messages()->all();
            $validation_flag = false;
        }
        
        if (!$validation_flag || !$images_validation_flag) {
            return Response::json(array('status' => 'ERROR', 'messages' => array_merge($validation_error_messages, $img_error_messages)));
        }        
        
        DB::beginTransaction();
        
        try {
            $ad->cat_id                 = input::get('cat');
            $ad->slug                   = Ad::getSlug(input::get('title'), $data['ad']);
            $ad->title                  = input::get('title');
            $ad->detail                 = input::get('description');
            $ad->price                  = input::get('price', '0');
			$ad->link                	= input::get('link');
            $ad->price_negotiable       = input::get('price_negotiable', '0');
            $ad->seller_type            = input::get('dealer');
            $ad->seller_name            = input::get('contact_name');
            $ad->seller_email           = input::get('contact_email');
            $ad->seller_phone           = input::get('contact_phone');
            $ad->seller_phone_public    = input::get('seller_phone_public', '0');
            $ad->featured               = input::get('featured', '0');
            $ad->spotlight              = input::get('spotlight', '0');
            $ad->save();        
        
            // saving ad attributes
            if (count($ad_category_attributes)) {
                    
                $ad_specs = array();
                Addetail::where('ad_id', '=', $data['ad'])->delete();
                foreach ($ad_category_attributes as $attr) {
                    
                    $values = array();
                    if (is_array(Input::get($attr['field']))) {
                        $values = Input::get($attr['field']);         
                    } else {
                        $values[] = Input::get($attr['field']);
                    }
                    
                    $keywords_value = array();
                    
                    foreach ($values as $val) {
                        
                        $arr_val = explode("_", $val);
                        $keywords_value[] = isset($arr_val[1]) ? $arr_val[1] : $arr_val[0];
                        
                        $attr_data = array(
                            'cat_id'        => $data['cat'], 
                            'attribute_id'  => $attr['id'], 
                            'ad_id'         => $data['ad'], 
                            'attribute_val' => $arr_val[0]
                        );
                        Addetail::create($attr_data); 
                    }
                    
                    array_push($ad_specs, array(
                        'name'    => $attr['name'],
                        'type'    => $attr['type'],
                        'value'  => implode(",",$keywords_value)
                    ));
                    
                }
                $ad->keywords = json_encode($ad_specs);
                $ad->save();
            }
            
            if (count($ad_images)) {
                
                $main_img = Media::where('ad_id', '=', $data['ad'])->where('main', '=', '1')->count();
                
                $count = 0;    
                foreach ($ad_images as $img) {
                    $img_data = array(
                        'ad_id' => $ad->id, 
                        'type'  => 'Image', 
                        'file'  => $img, 
                        'main'  => ($count==0 && $main_img<=0) ? '1' : '0'
                    );
                    Media::create($img_data);
                    $count++;
                }
            }
        } catch (\Exception $e) {
            DB::rollback();
            return Response::json(array(
                'status' => 'ERROR', 
                'messages' => array('Error while saving record.' . $e->getMessage())
            ));
            //throw $e;
        }
        DB::commit();
        return Response::json(array('status' => 'SUCCESS'));
    }

    public function getAttributes($ids = '')
    {
        $view = View::make('ad.create.attribute');
        $attributes = Attribute::whereRaw('category_id in (' . $ids . ')')->orderBy('order')->get();
        $view->attributes = $attributes;
        return $view;
    }

    public function thankYou()
    {
        $view = View::make('ad.create.thanks.index');
        return $view;
    }
    
    public function detail($slug='')
    {
        $view = View::make('ad.detail.index');
        $ads = Ad::search(array('slug' => $slug));
        
        if (!$ads) {
            return Response::view('error', array(), 404);
        }
        
        $view->cat_levels = Category::getAllParents($ads[0]->cat_id);        
        $view->images =  Media::where('ad_id', '=', $ads[0]->id)->where('type', '=', 'Image')->get();
        $similars =  Ad::similar(array('catid' => $ads[0]->cat_id, 'similarto' => $ads[0]->id));//Ad::where('cat_id', '=', $ads[0]->cat_id)->get();		
		$view->similars = $similars;	
        $view->ad = $ads[0];
        Ad::increaseCount($ads[0]->id);
        return $view;
    }

    public function postDeleteImage()
    {
        $data = Input::all();
        
        $ad = Ad::where('id', '=', $data['ad'])->where('user_id', '=', Auth::id())->get();
        $ad = $ad->first();
        
        if (!$ad) {
            return Response::json(array('status' => 'ERROR', 'message' => 'Permission denied' ));
        }
        
        try {
            $affected_rows = Media::where('file', '=', $data['img'])->delete();
        
            if ($affected_rows) {
                File::delete(Config::get('app.ad_img_path').$data['img']);
                
                // if main ad image
                $main_img_count = Media::where('ad_id', '=', $data['ad'])->where('type', '=', 'Image')->where('main', '=', '1')->count();
                
                if ($main_img_count<=0) {
                    $images = Media::where('ad_id', '=', $data['ad'])->where('type', '=', 'Image')->get();
                    $img = $images->first();
                    
                    $img->main = '1';
                    $img->save(); 
                }
                return Response::json(array('status' => 'SUCCESS' ));            
            }
            
            return Response::json(array('status' => 'ERROR', 'message' => 'File does not exists'));    
        } catch (\Exception $e) {
            DB::rollback();
            return Response::json(array(
                'status' => 'ERROR', 
                'messages' => array('Error while deleting.' . $e->getMessage())
            ));
        }
    }

    public function postDeleteUserAd() {
        $data = Input::all();
        
        $id = Crypt::decrypt($data['ad']);
        $ad = Ad::where('id', '=', $id)->where('user_id', '=', Auth::id())->get();
        $ad = $ad->first();
        
        if (!$ad) {
            if (Request::ajax()) {
                return Response::json(array('status' => 'ERROR', 'message' => 'You are not allowed to perform this action'));
            }
            return Response::view('error', array(), 404);
        }
        
        $ad->status = 'Delete';
        
        if ($ad->save()) {
            $categories = $ad->cat_id.','.$ad->categories;
            $qry = "UPDATE categories SET number_of_ad = number_of_ad-1 WHERE id IN($categories)";
            DB::statement($qry);
            if (Request::ajax()) {
                return Response::json(array('status' => 'SUCCESS', 'message' => 'Ad deleted successfully!'));
            } 
            return Redirect::route('my-account')->withInput()->with('success', 'Ad deleted successfully.'); 
        } else {
            if (Request::ajax()) {
                return Response::json(array('status' => 'ERROR', 'message' => 'Error while deleting ad.'));
            }
            return Redirect::route('my-account')->withInput()->with('error', 'Error while deleting ad.'); 
        }
    }
}
