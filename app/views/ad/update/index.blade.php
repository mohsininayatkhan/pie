@extends('layout.main')

@section('inner_content')

    @include('section.default_heading', array('heading' => 'Update Ad', 'class' => 'dashboard-page-header'))

    @include('section.default_errors')
    
    <div id="ajax-error-messages"></div>
    <div class="overlay-progress"></div>
    
    <?php 
    //print_r($ad);
    ?>
        
    <br>
    <div class="row">
        <div class="col-sm-12">
            <h4 class="page-header section-heading top-margin-zero">Ad Detail</h4>
        </div>
    </div>
    
    {{ Form::open(array('url' => 'update-ad','files'=>true,'id'=>'frmUpdateAd', 'name'=>'frmUpdateAd', 'action' =>'POST', 'class' => 'form-horizontal', 'role' => 'form', 'onsubmit' => 'return validateForm();')) }}
        <input type="hidden" name="cat" id="cat" value="<?php echo $ad->cat_id; ?>"/>
        <input type="hidden" name="categories" id="categories" value="<?php echo $ad->categories; ?>"/>
        <input type="hidden" name="ad" id="ad" value="<?php echo $ad->id; ?>"/>
        <?php echo Form::token(); ?>       
        
        <div class="form-group">
            <label for="Category" class="col-sm-2 control-label custom-label">Category:</label>
            <div class="col-sm-10">
               <div class="input-group">
               <input disabled="disabled" type="text" class="form-control" id="ad-category" value="<?php echo $category_lavels; ?>" name="ad-category">
               <span class="input-group-btn">
                   <button title="locked" class="btn btn-default" type="button">
                       <i class="fa fa-lock"></i>
                   </button>
               </span>
               </div>               
            </div>
        </div>
        
        <div class="form-group">
            <label for="Category" class="col-sm-2 control-label custom-label">Location:</label>
            <div class="col-sm-10">
               <div class="input-group">
               <input disabled="disabled" type="text" class="form-control" id="ad-location" value="<?php echo $ad->state_name.' > '.$ad->city_name; echo (!empty($ad->town_name)) ? ' > '.$ad->town_name: ''; ?>" name="ad-location">
               <span class="input-group-btn">
                   <button title="locked" class="btn btn-default" type="button">
                       <i class="fa fa-lock"></i>
                   </button>
               </span>
               </div>
               
            </div>
        </div>
        
        <div class="form-group">
            <label for="Description" class="col-sm-2 control-label custom-label">Are you a company*:</label>
            <div class="col-sm-10 ">
                <div class="radio radio-inline">
                    <input type="radio" value="1" <?php echo $ad->seller_type == 1? 'checked="checked"' : '' ?> name="dealer" id="dealer_yes">
                    <label class="custom-label" for="dealer_yes">Yes</label>
                </div>
                <div class="radio radio-inline">
                    <input type="radio" value="0" <?php echo $ad->seller_type == 0? 'checked="checked"' : '' ?>  name="dealer" id="dealer_no">
                    <label class="custom-label" for="dealer_no">No</label>
                </div>
            </div>
        </div>     
        
        <div class="form-group">
            <label for="Title" class="col-sm-2 control-label custom-label">Title*:</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="title" name="title" value="<?php echo $ad->title; ?>">
            </div>
        </div>
        
        
        @if ($ad_category['ask_for_price'] == 1)
        <div class="form-group">
            <label for="Price" class="col-sm-2 control-label custom-label">Price*:</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" id="price" name="price" value="<?php echo $ad->price; ?>">
            </div>
            <div class="col-sm-2">
                <div class="checkbox">
                    <input type="checkbox" id="price_negotiable" <?php echo ($ad->price_negotiable) ? 'checked="checked"' : '';?> name="price_negotiable" value="1">
                    <label for="price_negotiable">Negotiable?</label>
                </div>
            </div>
        </div>
        @endif

        <div class="form-group">
            <label for="Description" class="col-sm-2 control-label custom-label">Description*:</label>
            <div class="col-sm-10 ">
                <textarea class="form-control" id="description" name="description"><?php echo $ad->detail;?></textarea>
            </div>
        </div>
        
        <div class="form-group">
            <label for="Link" class="col-sm-2 control-label custom-label">Link:</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="link" name="link" value="<?php echo $ad->link; ?>">
            </div>
        </div>

          
        
        <div id="cat-specs-section" class="<?php echo count($attributes) ? '' : 'hidden'; ?>">
        <div class="row">
            <div class="col-sm-12">
                <h4 class="page-header section-heading top-margin-zero">Specification</h4>
            </div>
        </div>

        <!-- Category specifications-->
        <div id="cat-specs">
            @include('ad.create.attribute', array('attributes' => $attributes, 'ad_id' => $ad->id))
            
        </div>
        </div>
        
        <br>
        @if ($ad_category['ask_for_images'] == 1)
        <div id="ad-images">
            <div class="row">
                <div class="col-sm-12">
                    <h4 class="page-header section-heading top-margin-zero">Images</h4>
                    <p class="text-danger">Up to 6 Images (jpeg, jpg, png, gif only).</p>
                </div>
            </div>
            
            <div class="row">
                <div class="images-section col-sm-12">
                    <?php 
                    $count = 0;
                    ?>
                    @foreach ($images as $image) 
                        <?php $src = Config::get('app.ad_img_path').$image->file; ?>
                        <div class="col-xs-2 col-sm-2" id="file-<?php echo ++$count;?>" >
                            <div class="img-thumbs">
                                <img class="img-responsive" src="<?php echo Image::url($src,200,150,array('crop'))?>"/>
                                <div class="clearfix"></div>
                                <input type="hidden" name="img_names[]" value="">
                            </div>
                            <button onclick="removeExistingImage('<?php echo $count;?>', '<?php echo $image->file;?>')" type="button" class="btn btn-sm btn-danger img-remove-btn"><i class="glyphicon glyphicon-remove"></i>Remove</button>
                        </div>
                    @endforeach
                    <input type="hidden" name="adimages_count" id="adimages_count" value="<?php echo $count; ?>"/>
                </div>
            </div>
            
            <div class="row top-margin-sm">
                <div class="col-sm-12">
                    <button type="button" id="upload_img" name="upload_img" class="btn btn-lg btn-success">Upload Images</button>
                </div>
            </div>
        </div>
        @endif
        
        <br>
        <div class="row">
            <div class="col-sm-12">
                <h4 class="page-header section-heading top-margin-zero">Contact Information</h4>
            </div>
        </div>

        <div class="form-group">
            <label for="Name" class="col-sm-2 control-label custom-label">Name*:</label>
            <div class="col-sm-10">
                <input type="text" class="form-control custom-label" id="contact_name" value="<?php echo $ad->seller_name;?>" name="contact_name">
            </div>
        </div>

        <div class="form-group">
            <label for="Email" class="col-sm-2 control-label custom-label">Email*:</label>
            <div class="col-sm-10">
                <input type="text" class="form-control custom-label" id="contact_email" name="contact_email" value="<?php echo $ad->seller_email;?>">
            </div>
        </div>

        
        <div class="form-group">
            <label for="Phone" class="col-sm-2 control-label custom-label">Phone*:</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" maxlength="11" name="contact_phone" id="contact_phone" value="<?php echo Input::get('contact_phone', $ad->phone); ?>" placeholder="XXXXXXXXXXX">
            </div>
            <div class="col-sm-2">
                <div class="checkbox"><input type="checkbox" value="1" <?php echo ($ad->seller_phone_public) ? 'checked="checked"' : '';?> name="seller_phone_public" id="seller_phone_public"><label for="seller_phone_public">Display?</label></div>
            </div>
        </div>
        
        <br>
        <div class="row">
            <div class="col-sm-12">
                <h4 class="page-header section-heading top-margin-zero">Promote Your Ad</h4>
            </div>
        </div>
        
        <div class="checkbox checkbox-danger">
            <input type="checkbox" id="featured" name="featured" <?php echo ($ad->featured==1) ? 'checked="checked"' : ''; ?> value="1">
            <label for="featured">Featured (Your ad will be displayed on top of listing)</label>
        </div>
        
        <div class="checkbox checkbox-warning">
            <input type="checkbox" id="spotlight" name="spotlight" <?php echo ($ad->spotlight==1) ? 'checked="checked"' : ''; ?> value="1">
            <label for="spotlight">Spot light (Your ad will be displayed on home page)</label>
        </div>
        
        <div class="row pull-right">
            <div class="col-sm-12">
                <button type="submit" class="btn btn-lg btn-primary">
                    Submit
                </button>
                <a href="<?php echo URL::to('/').'/manage-ads';?>">
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
{{ HTML::script('js/update_ad.js') }}
{{ HTML::script('js/file_uploader.js') }}
{{ HTML::script('js/moment-with-locales.js') }}
{{ HTML::script('js/bootstrap-datetimepicker.js') }}

@stop