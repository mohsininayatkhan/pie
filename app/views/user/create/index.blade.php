@extends('layout.main')

@section('page_title')
Register   
@stop

@section('inner_content')

@include('section.default_heading', array('heading' => 'Create User'))

<ol class="breadcrumb light-green-box">
	<li><a href="<?php echo URL::to('/').'/users'; ?>">Users</a></li>
	<li><a href="<?php echo URL::to('/').'/create-user'; ?>">Create User</a></li>
</ol>

@include('section.default_errors')

<div class="row">
    <div class="col-sm-12">
        {{ Form::open(array('url' => 'create-user','method'=>'POST', 'name' => 'frmCreateUser', 'id'=>'frmCreateUser')) }}

            <div class="form-group">
                <label for="First Name" class="custom-label">First Name*:</label>
                {{ Form::text('first_name', Input::old('first_name'), array('id' => 'first_name','class'=>'form-control custom-label')) }}                
            </div>

            <div class="form-group">
                <label for="Last Name" class="custom-label">Last Name*:</label>
                {{ Form::text('last_name', Input::old('last_name'), array('id' => 'last_name','class'=>'form-control custom-label')) }}                
            </div>           
            
            <div class="form-group">
                <label for="email" class="custom-label">Email*:</label>
                {{ Form::text('email', Input::old('last_name'), array('id' => 'email','class'=>'form-control custom-label')) }}                
            </div>
            
            <div class="form-group">
                <label for="email" class="custom-label">Phone:</label>
                {{ Form::text('phone', Input::old('phone'), array('id' => 'phone','class'=>'form-control custom-label','placeholder'=>'XXXXXXXXXXX','maxlength'=>'11')) }}
            </div>
            
            <div class="form-group">
                <label for="email" class="custom-label">Website:</label>
                {{ Form::text('website', Input::old('website'), array('id' => 'website','class'=>'form-control custom-label','placeholder'=>'http://example.com')) }}
            </div>
            
            <div id="user-state" class="form-group">
                <label for="Phone" class="custom-label">State:</label>
                    {{ Form::select('state', $states, '', array('id' => 'state', 'class'=>'form-control custom-label location')) }}
            </div>
                                      
            <div id="user-city" class="form-group">
            <?php 
            if (count($cities)) {
            ?>
            	<label for="City" class="custom-label">City:</label>
                {{ Form::select('city', $cities, '', array('id' => 'city', 'class'=>'form-control custom-label location')) }}
            <?php 
            }
            ?>
            </div>
                            
            <div id="user-town" class="form-group">
            <?php 
            if (count($towns)) {
            ?>
            	<label for="Phone" class="custom-label">Town:</label>
                {{ Form::select('town', $towns, '', array('id' => 'town', 'class'=>'form-control custom-label location')) }}
            <?php 
            }
            ?>
            </div>

            <div class="form-group">
                <label for="pwd" class="custom-label">Password*:</label>
                <input type="password" class="form-control" id="password" name="password">
            </div>

            <div class="form-group">
                <label for="pwd" class="custom-label">Confirm Password*:</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password">
            </div>

            <?php echo Form::token(); ?>

            <button type="submit" class="btn btn-lg btn-primary pull-right">Create Account</button>
        {{ Form::close() }}
    </div>   
</div>
@stop

@section('additional_scripts')
{{ HTML::script('js/jquery.validate.js') }}
{{ HTML::script('js/jquery.form.min.js') }}
{{ HTML::script('js/user_account.js') }}
@stop