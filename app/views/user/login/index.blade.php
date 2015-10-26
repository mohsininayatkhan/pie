@extends('layout.main')

@section('page_title')
Login   
@stop

@section('inner_content')

@include('section.default_heading', array('heading' => 'Login'))

@include('section.default_errors')

{{ Form::open(array('url' => 'login','method'=>'POST', 'name'=> 'frmLogin', 'id'=>'frmLogin')) }}
<div class="row">
    <div class="col-sm-3"></div>
    
    <div class="col-sm-6">
        <div class="form-group">
            <label for="email" class="custom-label">Email:</label>
            <input type="text" name="email" id="email" value="<?php echo Input::old('email');?>" class="form-control" placeholder="Enter your email"/>
        </div>
        <div class="form-group">
            <label for="pwd" class="custom-label">Password:</label>
            <input type="password" name="pwd" id="pwd" value="" class="form-control" placeholder="Enter password.."/>
        </div>
        <div class="form-group">
            <a id="login-forgot" href="<?php echo URL::to('/').'/forgot-password';?>">Forgot Password?</a>
        </div>
        <?php echo Form::token(); ?>
        
        {{Form::submit('Continue', ['class' => 'btn btn-primary btn-lg pull-right'])}}
    </div>    
</div>
{{ Form::close() }}
@stop