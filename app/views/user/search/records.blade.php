@if (!is_null($users) && count($users))
    @foreach ($users as $user)
        <div class="row">
            <div class="col-sm-3 list-img">
                <?php $src = Config::get('app.user_img_path') . 'noimg.jpg';
                if ($user->photo && $user->photo != '') {
                    $src = Config::get('app.user_img_path') . $user->photo;
                }
                ?>
                
                <div class="thumbnail profile-thumb">
                    <a href="<?php echo URL::to('/').'/user-profile/'.$user->slug?>"> <img class="img-responsive img-hover img-rounded" src="<?php echo Image::url($src,90,90,array('crop'))?>" alt="<?php echo $user->fname; ?>"> </a>
                </div>
                
            </div>
            
            <div class="col-sm-7">
                <a href="<?php echo URL::to('/').'/user-profile/'.$user->slug?>"><h3 class="section-heading"><?php echo $user->fname.' '.$user->lname; ?></h3></a>
                <h5>
                <?php 
                if ($user->state) {
                    $location = User::getLocation($user->user_id);
                    $loc = $location[0]->state_name;
                    $loc.= $location[0]->city_name!='' ? ', '.$location[0]->city_name : ''; 
                    $loc.= $location[0]->town_name!='' ? ', '.$location[0]->town_name : '';
                    echo '<h5><div class=""><span class="fa fa-1x fa-map-marker"></span> '.$loc.'</div></h5>';    
                }
                
                echo '<div id="'.$user->slug.'-followers-count" class="">';
                if (!empty($user->num_of_followers) && $user->num_of_followers>0) {
                    echo '<h5><span class="fa fa-1x fa-rss"></span> '.$user->num_of_followers.' followers</h5>';
                }
                echo '</div>';
                ?>
                <div><h5><span class="fa fa-1x fa-clock-o"></span> Joined <?php echo GeneralPurpose::timeAgo($user->joined_date) ?></h5></div>
            </div>            
            
            <div class="col-sm-2">
                <?php 
                if (Auth::check()) {
                    $login_user = Auth::user();
                    $following = Follower::where('user_id', '=', $user->user_id)->where('follower_user_id', '=', $login_user->id)->count();
                    if ($login_user->id != $user->user_id) {
                ?>
                        <button id="<?php echo $user->slug;?>" type="button" class="pull-right btn <?php echo $following>0 ? 'btn-following' : ''; ?> follow-btn btn-primary"><?php echo $following>0 ? 'Following' : 'Follow'; ?></button>
                <?php
                    }
                } else {     
                ?>
                    <button onclick="save_url('<?php echo Request::url();?>');window.location=base_url+'/login';" id="register-follow-btn" type="button" class="pull-right btn btn-primary">Follow</button>
                <?php 
                }
                ?>
            </div>
        </div>
        <hr>
    @endforeach
    {{ $users->appends(Input::all())->links(); }}
@else
    <div class="row">
        <div class="col-sm-12">
            <div class="alert alert-danger" role="alert" style="text-align: center;">Sorry! We could not find any user against your search.
            </div>
        </div>          
    </div>
@endif