<div class="row">
    <div class="col-sm-12">
        <?php 
        $src = Config::get('app.user_img_path') . 'noimg.jpg';
        if ($user->photo && $user->photo != '') {
            $src = Config::get('app.user_img_path') . $user->photo;
        }
        ?>
        <div class="thumbnail profile-thumb">
            <img class="img-responsive img-rounded img-hover" src="<?php echo Image::url($src,300,300,array('crop'))?>" alt="<?php echo ucfirst(strtolower($user->fname)). ' ' .ucfirst(strtolower($user->lname)); ?>">
        </div>
    </div>
    <div class="profile-top col-sm-12"><span class="profile-name"><?php echo ucfirst(strtolower($user->fname)). ' ' .ucfirst(strtolower($user->lname)); ?></span>&nbsp;
        <hr>
    </div>  
    
    <?php 
    if ($user->state) {
        $location = User::getLocation($user->id);
        $loc = $location[0]->state_name;
        $loc.= $location[0]->city_name!='' ? ', '.$location[0]->city_name : ''; 
        $loc.= $location[0]->town_name!='' ? ', '.$location[0]->town_name : '';
        echo '<div class="profile-top col-sm-12"><span class="fa fa-1x fa-map-marker"></span> '.$loc.'</div>';
    }    
    echo '<div class="profile-top col-sm-12"><span class="fa fa-1x fa-clock-o"></span> Joined '.GeneralPurpose::timeAgo($user->created_at).'</div>';
    
    if ($user->website) {
        echo '<div class="profile-top col-sm-12"><span class="fa fa-1x fa-globe"></span> Website <a target="blank" href="'.$user->website.'">'.$user->website.'</a></div>';    
    }
    ?>
    
    <div class="profile-top col-sm-12">
        <?php 
        if (Auth::check()) {
            $my_profile = false;            
            if ($user->id==Auth::id() )
            {
                $my_profile = true;
            } else {
                $following = Follower::where('user_id','=', $user->id)->where('follower_user_id','=', Auth::id())->count();    
            }
            
            if (!$my_profile) {
        ?>
            <button id="<?php echo $user->slug;?>" type="button" class="follow-btn profile <?php echo $following>0 ? 'btn-following' : ''; ?> btn btn-primary"><?php echo $following>0 ? 'Following' : 'Follow'; ?></button>
        <?php 
            }
        } else {
        ?>
            <button onclick="window.location=base_url+'/login';" id="register-follow-btn" type="button" class="btn btn-primary">Follow</button>
        <?php
        } 
        ?>
    </div>  
</div>