<?php
$user = Auth::user();
$suggested_followers = User::getSuggestedUsers($user->id, 5); 
?>
<div class="row">
    <div class="col-sm-12">
        <?php $src = Config::get('app.user_img_path') . 'noimg.jpg';
        if ($user->photo && $user->photo != '') {
            $src = Config::get('app.user_img_path') . $user->photo;
        }
        ?>
        <div class="thumbnail profile-thumb">
            <img class="img-responsive img-hover img-rounded" src="<?php echo Image::url($src,300,300,array('crop'))?>" alt="<?php echo ucfirst(strtolower($user->fname)). ' ' .ucfirst(strtolower($user->lname)); ?>">
        </div>
    </div>
    <div class="profile-top col-sm-12"><span class="profile-name"><?php echo ucfirst(strtolower($user->fname)). ' ' .ucfirst(strtolower($user->lname)); ?></span>&nbsp;
        <a href="{{ URL::to('/') }}/my-account" title="Edit Profile"><span class="fa fa-1x fa-pencil"></span></a>
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
    ?>  
</div>

 
@if (count($suggested_followers))
<br><br>
<div id="who-to-follow" class="well">
    <div class="row">
	    <div class="profile-top col-sm-12"><span class="who-to-follow">Who to Follow</span>&nbsp;
	        <a class="pull-right section-heading" href="{{ URL::to('/').'/who-to-follow'   }}">View All</a>
	        <hr>
	    </div>
    </div>
    <div id="who-to-follow-records">
	    @foreach ($suggested_followers as $user)
	        <?php 
	        $src = Config::get('app.user_img_path') . 'noimg.jpg';
	        if ($user->photo && $user->photo != '') {
	            $src = Config::get('app.user_img_path') . $user->photo;
	        }
	        ?>
	        <div class="row">
	            <div class="col-sm-3">
	                <?php 
	                $src = Config::get('app.user_img_path') . 'noimg.jpg';
	                if ($user->photo && $user->photo != '') {
	                    $src = Config::get('app.user_img_path') . $user->photo;
	                }
	                ?>
	                <a class="pull-left" href="<?php echo URL::to('/').'/user-profile/'.$user->slug?>">
	                    <img class="media-object img-rounded" src="<?php echo Image::url($src,62,62,array('crop'))?>" alt="<?php echo ucfirst(strtolower($user->fname)). ' ' .ucfirst(strtolower($user->lname)); ?>">
	                </a>
	            </div>
	            <div class="col-sm-9"><a class="" href="<?php echo URL::to('/').'/user-profile/'.$user->slug?>">{{ $user->fname.' '.$user->lname}}</a><div class="clear"></div>
	            <?php 
	                if (Auth::check()) {
	                    $curr_user = Auth::user();
	                    $following = Follower::where('user_id', '=', $user->id)->where('follower_user_id', '=', $curr_user->id)->count();
	                    if ($curr_user->id != $user->id) { 
	                ?>
	                    <button id="<?php echo $user->slug;?>" type="button" class="btn follow-btn <?php echo $following>0 ? 'btn-following' : ''; ?> btn-primary pull-right"><?php echo $following>0 ? 'Following' : 'Follow'; ?></button>
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
	        <div class="row">
	            <div class="col-sm-12"><hr></div>
	        </div>
	    @endforeach
    </div>
</div>    
@endif     