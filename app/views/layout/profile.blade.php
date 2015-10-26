@extends('layout.master')

@section('content')

@include('section.default_header')

<?php 
$followers = Follower::getFollowersByUser($user->id); 
$following = Follower::getFollowingUsers($user->id);
?>

<div class="container top-margin-sm">
    
    <div id="main-container" class="well white-backgroup">
        <div class="row" id="dashboard">
            <div class="col-sm-3">
                @include('user.profile.left_menu')
            </div>
            <?php $current_route = Route::currentRouteName(); ?>
            <div class="col-sm-9">
                <div class="tabbable-panel">
                    <div class="tabbable-line">
                        <ul class="nav nav-tabs">
                            <li class="<?php echo ($current_route=='user-profile')? 'active' : ''; ?>"><a class="tabs-link" href="{{ URL::to('/') }}/user-profile/{{ $user->slug }}">Eshtihar</a></li>
                            <li class="<?php echo ($current_route=='user-following')? 'active' : ''; ?>"><a class="tabs-link" href="{{ URL::to('/') }}/user-following/{{ $user->slug }}">Following <span class="badge"><?php echo count($following);?></span></a></li>
                            <li class="<?php echo ($current_route=='user-followers')? 'active' : ''; ?>"><a class="tabs-link" href="{{ URL::to('/') }}/user-followers/{{ $user->slug }}">Followers <span class="badge"><?php echo count($followers);?></span></a></li>
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

@section('additional_scripts')
    {{ HTML::script('js/followers.js') }}
@stop