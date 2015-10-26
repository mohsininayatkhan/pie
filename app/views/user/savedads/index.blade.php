@extends('layout.dashboard')
@section('page_title')
    My Favorite Ads    
@stop

@section('inner_content')
    @include('section.default_errors')

    <div class="row">
    @if (!is_null($ads) && count($ads))
        <div class="col-sm-12 list">
        <?php $count = 1; ?>
        @foreach ($ads as $ad)
            <?php $ribbon = '';
            $ribbon_txt = '';
            $ad_style = '';
            if ($ad -> featured) {
                $ribbon = 'has-ribbon';
                $ribbon_txt = 'Featured';
                $ad_style = 'featured-ad';
            }
            ?>
            <div id="rec_<?php echo $count;?>" class="row ads-list">            
                <div class="col-sm-2 list-img">
                    <?php $src = Config::get('app.ad_img_path') . 'noimg.png';
                    if ($ad -> file && $ad -> file != '') {
                        $src = Config::get('app.ad_img_path') . $ad -> file;
                    }
                    ?>            
                    <div class="thumbnail <?php echo $ribbon; ?>" data-text="<?php echo $ribbon_txt; ?>">
                        <a href="portfolio-item.html"> <img class="img-responsive img-hover" src="<?php echo Image::url($src,90,90,array('crop'))?>" alt="<?php echo $ad -> title; ?>"> </a>
                    </div>                
                </div>            
                <div class="col-sm-8">
                    <h3 class="section-heading"><?php echo $ad -> title; ?></h3>
                    <h4><span class="fa fa-1x fa-map-marker"></span> <?php echo $ad->state_name . ', '. $ad->city_name ?></h4>
                    <ul class="list-inline">
                        <li><strong>Views: </strong><?php echo $ad -> view_count; ?></li>
                        <li><strong>Listing: </strong><?php echo $ad -> listing_count; ?></li>
                    </ul>
                    <p>
                        <?php echo str_limit($ad -> detail, 150, '...'); ?>
                    </p>
                    <a id="" class="list-link" href="<?php echo URL::to('/') . '/detail/' . $ad->slug; ?>"><span class="hyperspan"></span></a>
                </div>
                <div class="col-sm-2">
                    <a id="remove_<?php echo Crypt::encrypt($ad->id);?>" name="<?php echo $count;?>" class="btn btn-remove-fav-ad btn-danger btn-block"><span class="glyphicon glyphicon-remove"></span> <span id="btn-save-txt">Remove</span></a>
                </div>
            </div>
            <hr id="hr_<?php echo $count;?>">
        <?php 
        $count++;
        ?>
        @endforeach
    @else
        <div class="row ads-list">
            <div class="col-sm-12">
                <div class="alert alert-info" role="alert" style="text-align: center;">You currently have no ads saved.
                </div>
            </div>          
        </div>
    @endif
    </div>
</div>
@stop

@section('additional_scripts')

{{ HTML::script('js/jquery.validate.js') }}
{{ HTML::script('js/jquery.form.min.js') }}
{{ HTML::script('js/user_account.js') }}

@stop