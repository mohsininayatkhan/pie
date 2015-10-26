@extends('layout.main')

@section('inner_content')
    
    @include('section.default_heading', array('heading' => 'Account activated successfully!'))
    
    <p>Congratulations! Your account on eshtihar.com is activated successfully.</p>
    <p>You can log in to your account using the following <a href="<?php echo URL::to('/').'/login'?>">Link</a>.</p>
    
    
@stop