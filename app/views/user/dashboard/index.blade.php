@extends('layout.main')
@section('page_title')
    Dashboard   
@stop

@section('inner_content')
    @include('section.default_errors')
    
    <div class="row">	    	
    	<div class="col-sm-3"><button class="btn-lg btn-danger navbar-btn" type="button"  onclick="window.location=base_url+'/create-ad';"><span class="glyphicon glyphicon-upload"></span> User Management</button></div>
    	<div class="col-sm-3"><button class="btn-lg btn-danger navbar-btn" type="button"  onclick="window.location=base_url+'/create-ad';"><span class="glyphicon glyphicon-upload"></span> Ad Management</button></div>
    	<div class="col-sm-3"><button class="btn-lg btn-danger navbar-btn" type="button"  onclick="window.location=base_url+'/create-ad';"><span class="glyphicon glyphicon-upload"></span> Setting</button></div>
   	</div>
@stop