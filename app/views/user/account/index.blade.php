@extends('layout.main')
@section('page_title')
    My Account | Manage My Ads    
@stop

@section('inner_content')
@include('section.default_errors')

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