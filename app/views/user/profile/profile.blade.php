<div class="row">
    <div class="col-sm-12">
        <?php $src = Config::get('app.user_img_path') . 'noimg.jpg';
        if ($user->photo && $user->photo != '') {
            $src = Config::get('app.user_img_path') . $user->photo;
        }
        ?>

        <div class="thumbnail profile-thumb">
            <img class="img-responsive img-rounded  img-hover" src="<?php echo Image::url($src,300,300,array('crop'))?>" alt="<?php echo ucfirst(strtolower($user->fname)). ' ' .ucfirst(strtolower($user->lname)); ?>">
        </div>
    </div>
    <div class="profile-top col-sm-12"><span class="profile-name"><?php echo ucfirst(strtolower($user->fname)). ' ' .ucfirst(strtolower($user->lname)); ?></span>&nbsp;
        <?php
        if ($my_profile) {
            echo '<a href="'.URL::to('/').'/my-account" title="Edit Profile"><span class="fa fa-1x fa-pencil"></span></a>';
        }
        ?>
        <hr>
    </div>
    <input type="hidden" name="user" id="user" value="<?php echo $user->slug;?>" />    
    <?php 
    if ($user->state) {
        $location = User::getLocation($user->id);
        $loc = $location[0]->state_name;
        $loc.= $location[0]->city_name!='' ? ', '.$location[0]->city_name : ''; 
        $loc.= $location[0]->town_name!='' ? ', '.$location[0]->town_name : '';
        echo '<div class="profile-top col-sm-12"><span class="fa fa-1x fa-map-marker"></span> '.$loc.'</div>';    
    }
    
    echo '<div class="profile-top col-sm-12"><span class="fa fa-1x fa-clock-o"></span> Joined '.GeneralPurpose::timeAgo($user->created_at).'</div>';
    ?>
    
    <div id="<?php echo $user->slug;?>-followers-count" class="profile-top col-sm-12">
        <?php 
        if ($followers_count) {
            echo '<span class="fa fa-1x fa-rss"></span> Followed by <a data-toggle="modal" data-target="#followersModal" id="view-followers" href="javascript:void(0)">'.$followers_count.' people</a>'; 
        }
        ?>
    </div>
    
    <div id="<?php echo $user->slug;?>-following-count" class="profile-top col-sm-12">
        <?php 
        if ($following_count) {
            echo '<span class="fa fa-1x fa-rss"></span> Following <a data-toggle="modal" data-target="#followingModal" id="view-following" href="javascript:void(0)">'.$following_count.' people</a>'; 
        }
        ?>
    </div>
    
    <?php 
    if (isset($total_records) && $total_records>0) {
        if (Auth::check() && $my_profile) {
            echo '<div class="profile-top col-sm-12"><span class="fa fa-1x fa-rss"></span> <a href="'.URL::to('/').'/manage-ads">My Ads</a> </div>';
        } else {
            echo '<div class="profile-top col-sm-12"><span class="fa fa-1x fa-rss"></span> Posted '.$total_records.' ads</div>';            
        }
    }
    ?>
    
    <?php 
    if (!$my_profile) {
    ?>
    <div class="profile-top col-sm-12">
        <?php 
        if (Auth::check()) {
        ?>
            <button id="<?php echo $user->slug;?>" type="button" class="follow-btn profile btn btn-primary"><span class="fa fa-1x fa-rss"></span> <?php echo $following>0 ? 'Following' : 'Follow'; ?></button>
        <?php 
        } else {
        ?>
            <button onclick="window.location=base_url+'/login';" id="register-follow-btn" type="button" class="btn btn-primary"><span class="fa fa-1x fa-rss"></span> Follow</button>
        <?php
        } 
        ?>
    </div>
    <?php 
    }
    ?>
    
</div>
