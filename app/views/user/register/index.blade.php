@extends('layout.main')

@section('page_title')
Register   
@stop

@section('inner_content')

@include('section.default_heading', array('heading' => 'Create Account'))

@include('section.default_errors')

<div class="row">
    <div class="col-sm-8">
        <a name="fb-login" id="fb-login" class="btn btn-block btn-social btn-facebook">
        <i class="fa fa-facebook"></i> Register in with facebook
        </a>
    </div>
     <div class="col-sm-4"></div>
    
    <div class="col-sm-8">
        <hr>
    </div>
     <div class="col-sm-4"></div>
    <div class="col-sm-8">
        {{ Form::open(array('url' => 'register','method'=>'POST', 'name' => 'frmRegister', 'id'=>'frmRegister')) }}

            <div class="form-group">
                <label for="First Name" class="custom-label">First Name*:</label>
                <input type="text" class="form-control" name="first_name" id="first_name" value="<?php echo Input::old('first_name');?>">
            </div>

            <div class="form-group">
                <label for="Last Name" class="custom-label">Last Name*:</label>
                <input type="type" class="form-control" id="last_name" name="last_name" value="<?php echo Input::old('last_name');?>">
            </div>
            
            <?php 
            $email = Session::get('new_user_email', function() {
                return Input::old('email', '');
            });
            ?>
            
            <div class="form-group">
                <label for="email" class="custom-label">Email*:</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo $email; ?>">
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
    <div class="col-sm-4">
        <div class="well light-blue-box">
        <h3>Get most by your account!</h3>
        
        <p>- Quickly create ads by pre-filled contact information.</p>
        
        <p>- Manage all of your ads from one place.</p>
        
        <p>- View stats of your ads.</p>
        
        <p>- Easily view/reply to your ad messages.</p>
        
        <p>- View ads of your following users.</p>
        </div>
        
    </div>
</div>

<div class="row">
    <div class="col-sm-8"><hr>
        <p class="pull-right" id="login-not-member-yet"> Already have an account?
            <span class="btn-group">
                <button onclick="window.location=base_url+'/login';" type="button" class="btn btn-danger"> Login</button>
            </span>
        </p>
    </div>
   <div class="col-sm-4"></div>
</div>
@stop

@section('additional_scripts')
{{ HTML::script('js/jquery.validate.js') }}
{{ HTML::script('js/user.js') }}
{{ HTML::script('js/fb_login.js') }}
@stop