@extends('layout.dashboard')
@section('page_title')
    Manage My Ads    
@stop

@section('inner_content')
    @include('section.default_errors')    
    
    @if (count($ads) || Request::input('status_user_ads') || Request::input('keyword_user_ads'))
        {{ Form::open(array('url' => 'manage-ads','method'=>'GET', 'name' => 'frmUserAdsSearch', 'id'=>'frmUserAdsSearch', 'class' => 'form-horizontal', 'role' => 'form')) }}
        <div class="row">
            <div class="col-sm-12">
                <div class="col-sm-12 well light-blue-box">
                     <div class="col-sm-12 col-sm-5">
                        {{ Form::text('keyword_user_ads', Request::input('keyword_user_ads'),array('id' => 'keyword_user_ads', 'placeholder' => 'keyword..', 'class'=>'form-control')) }}
                    </div>
                    <div class="col-sm-12 col-sm-5">
                        {{ Form::select('status_user_ads', array('All' => 'All Ads', 'Active' => 'Active', 'Expired' => 'Expired'), Request::input('status_user_ads'), array('id' => 'status_user_ads', 'class'=>'form-control')) }}
                    </div>
                    <div class="col-sm-12 col-sm-2">
                        <button type="submit" class="btn btn-danger pull-right">
                            <span class="glyphicon glyphicon-search"></span> Go
                        </button>
                    </div>
                </div>
            </div>
        </div>
        {{ Form::close() }}
    @endif    
    
    @if (count($ads))
        <div class="row">
            <div class="col-sm-12">
                <h4>{{ $total_records }} ad(s) found.</h4>
            </div>
        
        <div class="col-sm-12 list">
            @foreach ($ads as $ad)
                <?php $ribbon = '';
                $ribbon_txt = '';
                $ad_style = '';
                if ($ad->featured) {
                    $ribbon = 'has-ribbon';
                    $ribbon_txt = 'Featured';
                    $ad_style = 'featured-ad';
                }
                ?>
                <div class="row ads-list">
                    
                    <div class="col-sm-2 list-img">
                        <?php $src = Config::get('app.ad_img_path') . 'noimg.png';
                        if ($ad->file && $ad->file != '') {
                            $src = Config::get('app.ad_img_path') . $ad->file;
                        }
                        ?>
                    
                        <div class="thumbnail <?php echo $ribbon; ?>" data-text="<?php echo $ribbon_txt; ?>">
                            <a href="portfolio-item.html"> <img class="img-responsive img-hover" src="<?php echo Image::url($src,90,90,array('crop'))?>" alt="<?php echo $ad->title; ?>"> </a>
                        </div>
                    </div>
                    
                    <div class="col-sm-9">
                        <h3 class="section-heading"><?php echo $ad->title; ?></h3>
                        <h4><span class="fa fa-1x fa-map-marker"></span> <?php echo $ad->state_name . ', '. $ad->city_name ?></h4>
                        <h4><span class="fa fa-1x fa-clock-o"></span> <?php echo GeneralPurpose::timeAgo($ad->posted_date); ?></h4>
                        <ul class="list-inline">
                            <li><strong>Views: </strong><span class="badge"><?php echo $ad->view_count; ?></span></li>
                            <li><strong>Listing: </strong><span class="badge"><?php echo $ad->listing_count; ?></span></li>
                            <li><strong>Messages: </strong><span class="badge"><?php echo Message::getAdConversationCount($ad->id); ?></span></li>
                            <li><strong>Status: </strong><?php echo ($ad->days_expiry_left<Config::get('app.ad_expiry_days')) ? '<span class="active-ad">Active<span>' : '<span class="expired-ad">Expired<span>'; ?></li>
                        </ul>
                        <p>
                            <?php echo str_limit($ad->detail, 150, '...'); ?>
                        </p>
                        <a class="list-link" href="<?php echo URL::to('/') . '/detail/' . $ad->slug; ?>"><span class="hyperspan"></span></a>
                    </div>
                    
                    <div class="col-sm-1">
                        <div class="dropdown pull-right">
                            <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-expanded="true">Action
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
                                <?php 
                                if ($ad->days_expiry_left<Config::get('app.ad_expiry_days')) {
                                ?>
                                    <li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo URL::to('/') . '/update-ad/' . Crypt::encrypt($ad->id); ?>"><span class="glyphicon glyphicon-pencil"></span> Edit</a></li>
                                <?php    
                                }
                                ?>
                                <li role="presentation"><a title="<?php echo $ad->title; ?>" id="<?php echo Crypt::encrypt($ad->id)?>" role="menuitem" tabindex="-1" class="del-ad" href="#"><span class="glyphicon glyphicon-trash"></span> Delete</a></li>
                                <!--li role="presentation"><a role="menuitem" tabindex="-1" href="#"><span class="glyphicon glyphicon-arrow-up"></span> Promote</a></li -->
                            </ul>
                        </div>
                    </div>
                </div>
                <hr>
            @endforeach
            </div>
        </div>
    @else
        <div class="row">
            <div class="col-sm-12">
                <div class="alert alert-info" role="alert" style="text-align: center;">Sorry! No record found.
                    <button onclick="window.location=base_url+'/create-ad';" id="top-right-post-ad" type="button" class="btn btn-danger navbar-btn"><span class="glyphicon glyphicon-upload"></span> Place Ad</button>
                </div>
            </div>          
        </div>
    @endif
    {{ $ads->appends(Input::all())->links(); }}  
        
    @stop
    
    @section('additional_scripts')
    
    {{ HTML::script('js/jquery.validate.js') }}
    {{ HTML::script('js/jquery.form.min.js') }}
    {{ HTML::script('js/user_account.js') }}

@stop