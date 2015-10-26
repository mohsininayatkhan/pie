@extends('layout.main')

@section('inner_content')
    
    @include('section.default_heading', array('heading' => 'Error while account activation!'))
    
    <p>Sorry! Your account could not activated.</p>
    <p>You are using invalid link or account can be already activated.</p>   
    
@stop