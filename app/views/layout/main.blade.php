@extends('layout.master')

@section('content')

@include('section.default_header')

<div class="container top-margin-sm">
    
    <div id="main-container" class="well white-backgroup">
	
	@yield('inner_content')
	
	</div>
    
</div>
@include('section.default_footer')
@stop