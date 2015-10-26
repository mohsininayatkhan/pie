@extends('layout.main')

@section('page_title')
    Contact Us   
@stop

@section('inner_content')
    @include('section.default_heading', array('heading' => 'Contact Us'))
    
    @include('section.default_errors')

	{{ Form::open(array('url' => 'contact-us','method'=>'POST', 'name'=> 'frmContactUs', 'id'=>'frmContactUs')) }}  
	
	<div class="row">
	    <div class="col-sm-3"></div>
	    
	    <div class="col-sm-6">
	    	 <div class="form-group">
	            <label for="Name" class="custom-label">Name:</label>
	            <input type="contact_name" name="contact_name" id="contact_name" value="<?php echo Input::old('contact_name');?>" class="form-control" placeholder="Name"/>
	        </div>
	        <div class="form-group">
	            <label for="Subject" class="custom-label">Subject:</label>
	            <select class="form-control" name="subject" id="subject">
	            	<option value="">Select Subject</option>
	            	<option value="Support Team" <?php echo Input::old('subject') == 'Support Team' ? 'selected="selected"': ''; ?>>Support Team</option>
	            	<option value="Feedback" <?php echo Input::old('subject') == 'Feedback' ? 'selected="selected"': ''; ?>>Feedback</option>
	            	<option value="Legal Issue" <?php echo Input::old('subject') == 'Legal Issue'? 'selected="selected"': ''; ?>>Legal Issue</option>
	            	<option value="Partnership and Business" <?php echo Input::old('subject') == 'Partnership and Business' ? 'selected="selected"': ''; ?>>Partnership and Business</option>
	            </select>
	        </div>
	        <div class="form-group">
	            <label for="Your Email" class="custom-label">Your Email:</label>
	            <input type="email" name="email" id="email" value="<?php echo Input::old('email');?>" class="form-control" placeholder="Your Email"/>
	        </div>
	        <div class="form-group">
	            <label for="Message" class="custom-label">Message:</label>
	            <textarea class="form-control" id="message" name="message"><?php echo Input::old('message');?></textarea>
	        </div>
	        
	        <?php echo Form::token(); ?>
	        
	        {{Form::submit('Submit', ['class' => 'btn btn-primary btn-lg pull-right'])}}
	    </div>
    </div>
	
	{{ Form::close() }}
       
@stop