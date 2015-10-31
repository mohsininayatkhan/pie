@extends('layout.main')

@section('page_title')
    Place your ad    
@stop

@section('inner_content')
    
    <?php 
    $heading = array('heading' => 'Place Your Ad');
    if (Auth::check()) {
        $heading['class'] = 'dashboard-page-header2';     
    }
    ?>

    @include('section.default_heading', $heading)

    @include('section.default_errors')
    
    <div id="ajax-error-messages"></div>
    <div class="overlay-progress"></div>
    
    
    <div class="row">
        <div class="col-sm-12">
            <h4 class="page-header section-heading top-margin-zero">Select Category</h4>
        </div>
    </div>
    <div id="cat-error" class="col-sm-12"></div>
    
    <div class="row categories-selection">
        <div class="col-sm-3">
            <div id="level-1" class="list-group list-special cat-box">
                @foreach ($main_categories as $category)
                    <a href="#" id="{{ $category->id}}" class="list-group-item cat-list-item"> {{$category->name}} </a>
                @endforeach
            </div>
        </div>

        <div class="col-sm-3">
            <div id="level-2" class="list-group list-special cat-box"></div>
        </div>

        <div class="col-sm-3">
            <div id="level-3" class="list-group list-special cat-box"></div>
        </div>

        <div class="col-sm-3">
            <div id="level-4" class="list-group list-special cat-box"></div>
        </div>
    </div>
    
    <br>
    <div class="row">
        <div class="col-sm-12">
            <h4 class="page-header section-heading top-margin-zero">Select Location</h4>
        </div>
    </div>
    <div id="loc-error" class="col-sm-12"></div>
    <div class="row locations-selection">
        <div class="col-sm-4">
            <div id="level-1" class="list-group list-special cat-box">
                @foreach ($states as $state)
                    <a href="#" id="{{ $state->id}}" class="list-group-item cat-list-item"> {{$state->state_name}} </a>
                @endforeach
            </div>
        </div>
        <div class="col-sm-4">
            <div id="level-2" class="list-group list-special cat-box"></div>
        </div>
        <div class="col-sm-4">
            <div id="level-3" class="list-group list-special cat-box"></div>
        </div>
    </div>
    
    <br>
    <div class="row">
        <div class="col-sm-12">
            <h4 class="page-header section-heading top-margin-zero">Ad Detail</h4>
        </div>
    </div>
    
    {{ Form::open(array('url' => 'create-ad','files'=>true,'id'=>'frmCreateAd', 'name'=>'frmCreateAd', 'action' =>'POST', 'class' => 'form-horizontal', 'role' => 'form', 'onsubmit' => 'return validateForm();')) }}
        <input type="hidden" name="cat" id="cat" value=""/>
        <input type="hidden" name="categories" id="categories" value=""/>
        <input type="hidden" name="state" id="state" value=""/>
        <input type="hidden" name="city" id="city" value=""/>
        <input type="hidden" name="town" id="town" value=""/>
        <input type="hidden" name="user_id" id="user_id" value="{{ $user->id }}"/>
        <?php echo Form::token(); ?>
        
        <div class="form-group">
            <label for="inputEmail3" class="col-sm-2 control-label custom-label">Title*:</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="title" name="title">
            </div>
        </div>
        
        <div id="ad-price"></div>        

        <div class="form-group">
            <label for="Description" class="col-sm-2 control-label custom-label">Description*:</label>
            <div class="col-sm-10 ">
                <textarea class="form-control" id="description" name="description"></textarea>
            </div>
        </div>
        
        <div class="form-group">
            <label for="Link" class="col-sm-2 control-label custom-label">Link:</label>
            <div class="col-sm-10 ">
                <input type="text" class="form-control" id="link" name="link" placeholder="http://eshtihar.com">
            </div>
        </div>
        
        <div class="form-group">
            <label for="Description" class="col-sm-2 control-label custom-label">Are you a company*:</label>
            <div class="col-sm-10 ">
                <div class="radio radio-inline">
                    <input type="radio" value="1" name="dealer" id="dealer_yes">
                    <label class="custom-label" for="dealer_yes">Yes</label>
                </div>
                <div class="radio radio-inline">
                    <input type="radio" value="0" checked="checked" name="dealer" id="dealer_no">
                    <label class="custom-label" for="dealer_no">No</label>
                </div>
            </div>
        </div>

        <br>
        <div id="cat-specs-section" class="hidden">
        <div class="row">
            <div class="col-sm-12">
                <h4 class="page-header section-heading top-margin-zero">Specification</h4>
            </div>
        </div>

        <!-- Category specifications-->
        <div id="cat-specs"></div>
        </div>
        
        <br>
        <div id="ad-images" class="hidden">
            <div class="row">
                <div class="col-sm-12">
                    <h4 class="page-header section-heading top-margin-zero">Images</h4>
                    <p class="text-danger">Up to 6 Images (jpeg, jpg, png, gif only).</p>
                </div>
            </div>
            
            <div class="row">
                <div class="images-section col-sm-12"></div>
            </div>
            
            <div class="row top-margin-sm">
                <div class="col-sm-12">
                    <button type="button" id="upload_img" name="upload_img" class="btn btn-lg btn-success">Upload Images</button>
                </div>
            </div>
        </div>
        
        <br>
        <div class="row">
            <div class="col-sm-12">
                <h4 class="page-header section-heading top-margin-zero">Contact Information</h4>
            </div>
        </div>

        <div class="form-group">
            <label for="Name" class="col-sm-2 control-label custom-label">Name*:</label>
            <div class="col-sm-10">
                <input type="text" class="form-control custom-label" id="contact_name" value="<?php echo Input::get('contact_name', $user->fname.' '.$user->lname); ?>" name="contact_name">
            </div>
        </div>

        <div class="form-group">
            <label for="Email" class="col-sm-2 control-label custom-label">Email*:</label>
            <div class="col-sm-10">
                <input type="text" class="form-control custom-label" id="contact_email" name="contact_email" value="<?php echo Input::get('contact_email', $user->email); ?>">
            </div>
        </div>

        <div class="form-group">
            <label for="Phone" class="col-sm-2 control-label custom-label">Phone*:</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" maxlength="11" name="contact_phone" id="contact_phone" value="<?php echo Input::get('contact_phone', $user->phone); ?>" placeholder="XXXXXXXXXXX">
            </div>
            <div class="col-sm-2">
                <div class="checkbox"><input type="checkbox" value="1" name="seller_phone_public" checked="checked" id="seller_phone_public"><label for="seller_phone_public">Display?</label></div>
            </div>
        </div>
        
        <br>
        <div class="row">
            <div class="col-sm-12">
                <h4 class="page-header section-heading top-margin-zero">Promote Your Ad</h4>
            </div>
        </div>
        
        <div class="checkbox checkbox-danger">
            <input type="checkbox" id="featured" name="featured" value="1">
            <label for="featured">Featured (Your ad will be displayed on top of listing)</label>
        </div>
        
        <div class="checkbox checkbox-warning">
            <input type="checkbox" id="spotlight" name="spotlight" value="1">
            <label for="spotlight">Spot light (Your ad will be displayed on home page)</label>
        </div>
        
        <div class="row pull-right">
            <div class="col-sm-12">
                <button type="submit" class="btn btn-lg btn-primary">Submit</button>
                <?php 
                $cancel_link = URL::to('/');
                if (Auth::check()) {
                    $cancel_link = URL::to('/').'/manage-ads';
                }
                ?> 
                <a href="<?php echo $cancel_link; ?>">
                    <button type="button" class="btn btn-lg btn-default">Cancel</button>
                </a>
            </div>
        </div>
        <div class="clearfix"></div>
    {{ Form::close() }}   
    
@stop

@section('additional_scripts')

{{ HTML::script('js/jquery.validate.js') }}
{{ HTML::script('js/jquery.form.min.js') }}
{{ HTML::script('js/create_ad.js') }}
{{ HTML::script('js/file_uploader.js') }}
{{ HTML::script('js/moment-with-locales.js') }}
{{ HTML::script('js/bootstrap-datetimepicker.js') }}

@stop