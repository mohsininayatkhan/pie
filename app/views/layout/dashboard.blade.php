@extends('layout.main')

@section('content')

<?php 
$my_followers = Follower::getFollowersByUser(Auth::id()); 
$my_following = Follower::getFollowingUsers(Auth::id());
$unread = Message::getUnreadMessages(Auth::user()->id);
?>

@include('section.default_header')

<div class="container top-margin-sm">
    
    <div id="main-container" class="well white-backgroup">
        <div class="row" id="dashboard">
            <div class="col-sm-3">
                @include('section.left_menu')
            </div>
            
            <?php $current_route = Route::currentRouteName(); ?>
            <div class="col-sm-9">
                <div class="tabbable-panel">
                    <div class="tabbable-line">
                        <ul class="nav nav-tabs">
                            <li class="<?php echo ($current_route=='timeline')? 'active' : ''; ?>"><a class="tabs-link" href="{{ URL::to('/') }}/timeline">Home</a></li>
                            <li class="<?php echo ($current_route=='following')? 'active' : ''; ?>"><a class="tabs-link" href="{{ URL::to('/') }}/following">Following <span id="my-following-count" class="badge"><?php echo count($my_following);?></span></a></li>
                            <li class="<?php echo ($current_route=='followers')? 'active' : ''; ?>"><a class="tabs-link" href="{{ URL::to('/') }}/followers">Followers <span id="my-followers-count" class="badge"><?php echo count($my_followers);?></span></a></li>
                            <li class="<?php echo ($current_route=='manage-ads')? 'active' : ''; ?>"><a class="tabs-link" href="{{ URL::to('/') }}/manage-ads">My Ads</a></li>
                            <li class="<?php echo ($current_route=='my-favourite-ads')? 'active' : ''; ?>"><a class="tabs-link" href="{{ URL::to('/') }}/my-favourite-ads">Favorite Ads</a></li>
                            <li class="<?php echo ($current_route=='inbox-messages' || $current_route=='sent-messages' || $current_route=='message-detail')? 'active' : ''; ?>"><a class="tabs-link" href="{{ URL::to('/') }}/inbox-messages">Messages <span id="new-message-count" class="badge"><?php echo ($unread>0) ? $unread : '';?></span></a></li>
                        </ul>
                        
                        <div class="tab-content trans-background">
                            <div id="tab_eshtihar" class="tab-pane active">
                                @yield('inner_content')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('section.default_footer')
@stop