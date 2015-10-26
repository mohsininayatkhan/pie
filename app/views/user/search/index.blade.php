@extends('layout.main')
@section('page_title')
    Search Users 
@stop

@section('inner_content')

@include('section.default_errors')

<div class="row">
    <div class="col-sm-12">@include('section.default_heading', array('heading' => 'Users'))</div>
</div>
<div class="row">
    
    <div class="col-sm-7">
        @include('user.search.bar')
        
        @include('user.search.records')
    </div>
    <div class="col-sm-5">
         <div class="well light-blue-box">
        <h3>Get most of being social!</h3>  
        
        <p>- Build personal network.</p>
        
        <p>- Get relevent updates.</p>
        
        <p>- Hit target audience.</p>
        
        <p>- Have more exposure.</p>
        
        </div>
    </div>
</div>
@stop

@section('additional_scripts')
{{ HTML::script('js/followers.js') }}
@stop
