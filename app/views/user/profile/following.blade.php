@extends((Route::currentRouteName()=='user-following') ? 'layout.profile' : 'layout.dashboard')

@section('page_title')
    {{ $page_title}}  
@stop

@section('inner_content')
    <?php 
    if (!count($followings)){
    ?> 
     <div class="row">
        <div class="col-sm-12">
            <div class="alert alert-info" role="alert" style="text-align: center;">Sorry! There is nobody in following list.</div>
        </div>    
    </div>
    <?php            
    } else {
        foreach ($followings as $following_user) {
        ?> 
        <div class="row followers">
            <div class="col-sm-2">
                <?php $src = Config::get('app.user_img_path') . 'noimg.jpg';
                if ($following_user->photo && $following_user->photo != '') {
                    $src = Config::get('app.user_img_path') . $following_user->photo;
                }
                ?>
                
                <div class="thumbnail profile-thumb">
                    <a href="<?php echo URL::to('/').'/user-profile/'.$following_user->user_slug?>"> <img class="img-rounded img-responsive img-hover" src="<?php echo Image::url($src,300,300,array('crop'))?>" alt=""> </a>
                </div>
            </div>
            <div class="col-sm-7">
                <div class="row">
                    <div class="col-sm-7">
                        <h3><a class="section-heading" href="<?php echo URL::to('/').'/user-profile/'.$following_user->user_slug?>"><?php echo ucfirst(strtolower($following_user->fname)). ' ' .ucfirst(strtolower($following_user->lname)); ?></a></h3>                        
                                
                    </div>
                </div>
                <?php 
                if ($following_user->state) {
                    $location = User::getLocation($following_user->user_id);
                    $loc = $location[0]->state_name;
                    $loc.= $location[0]->city_name!='' ? ', '.$location[0]->city_name : ''; 
                    $loc.= $location[0]->town_name!='' ? ', '.$location[0]->town_name : '';
                    echo '<div class="row"><div class="col-sm-12"><span class="fa fa-1x fa-map-marker"></span> '.$loc.'</div></div>';
                } ?>
                
                <div class="row">
                    <div class="col-sm-12">
                        <span class="fa fa-1x fa-clock-o"></span> Joined <?php echo GeneralPurpose::timeAgo($following_user->created_at) ?>
                    </div>    
                </div>
            </div>
            <div class="col-sm-3">
                <?php 
                if (Auth::check()) {
                    $curr_user = Auth::user();
                    $following = Follower::where('user_id', '=', $following_user->user_id)->where('follower_user_id', '=', $curr_user->id)->count();
                    if ($curr_user->id != $following_user->user_id) { 
                ?>
                    <button id="<?php echo $following_user->user_slug;?>" type="button" class="btn follow-btn btn-primary <?php echo $following>0 ? 'btn-following' : ''; ?> pull-right"><?php echo $following>0 ? 'Following' : 'Follow'; ?></button>
                <?php 
                    }
                } else {
                ?>
                    <button onclick="window.location=base_url+'/login';" id="register-follow-btn" type="button" class="btn btn-primary pull-right">Follow</button>
                <?php
                } 
                ?>
            </div>
        </div>
        <hr>
        <?php 
        }
        echo $followings->appends(Input::all())->links();
    }
    ?>
@stop

@section('additional_scripts')
{{ HTML::script('js/followers.js') }}
@stop
          