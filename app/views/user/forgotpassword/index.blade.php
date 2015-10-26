@extends('layout.main')

@section('page_title')
Forgotten password  
@stop

@section('inner_content')

@include('section.default_heading', array('heading' => 'Forgotten password'))

@include('section.default_errors')

{{ Form::open(array('url' => 'forgot-password','method'=>'POST', 'name'=> 'frmForgotPassword', 'id'=>'frmForgotPassword')) }}

<p id="global-message">
    @if(Session::has('global_error'))
     {{ Session::get('global_error') }}
    @endif
</p>

<div class="row">
    <div class="col-sm-3"></div>
    <div class="col-sm-6">
        <div class="form-group">
            <label for="email" class="custom-label">Email:</label>
            <input type="text" name="email" id="email" value="<?php echo Input::old('email');?>" class="form-control" placeholder="Enter your registered email"/>
        </div>
        <?php echo Form::token(); ?>
        
        {{Form::submit('Reset Password', ['class' => 'btn btn-primary btn-md pull-right'])}}
    </div>
</div>
{{ Form::close() }}

@stop