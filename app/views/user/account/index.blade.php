@extends('layout.main')
@section('page_title')
    My Account | Manage My Ads    
@stop

@section('inner_content')
@include('section.default_errors')

    <ol class="breadcrumb light-green-box">
		<li><a href="<?php echo URL::to('/').'/users'; ?>">Users</a></li>
		<li><a href="<?php echo URL::to('/').'/update-user/'.$user->slug; ?>">Update User</a></li>
	</ol>
    <!-- Profile -->
    @include('user.account.profile', array('user' => $user))
    
    <!-- Change Password -->
    @include('user.account.change_password', array('user' => $user))
    
    <!-- Email notifications-->
    @include('user.account.email_notifications', array('user' => $user))
@stop

@section('additional_scripts')

{{ HTML::script('js/jquery.validate.js') }}
{{ HTML::script('js/jquery.form.min.js') }}
{{ HTML::script('js/user_account.js') }}

@stop