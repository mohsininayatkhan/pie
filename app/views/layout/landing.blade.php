@extends('layout.master')

@section('content')

@include('section.default_header')

@include('home.banner')   

<div class="container top-margin-sm">
    
    <div id="" class="">
	
	@yield('inner_content')
	
	</div>
    
</div>
@include('section.default_footer')
@stop