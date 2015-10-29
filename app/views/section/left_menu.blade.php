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
        <a href="{{ URL::to('/') }}/update-user/{{ $user->slug }}" title="Edit Profile"><span class="fa fa-1x fa-pencil"></span></a>
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