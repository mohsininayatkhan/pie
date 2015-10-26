@extends('layout.main')

@section('page_title')
{{ $ad->title.' | '.$ad->state_name.' | '.$ad->city_name}}    
@stop

@section('inner_content')

<div class="row">
    <div class="col-sm-12">
        <h1 class="page-header light-heading"><?php echo $ad->title;?>
        <br />
        <small class="small-heading"><span class="fa fa-1x fa-map-marker"></span> <?php echo $ad->state_name;?>, <?php echo $ad->city_name;?><?php if(!empty($ad->town_name)){ echo ', '.$ad->town_name;}?></small><small class="pull-right main-price-text">
            <?php if(isset($ad->price) && $ad->price!=0 && !empty($ad->price)) { echo 'PKR '.number_format($ad->price); } ?>
            <?php if(isset($ad->price_negotiable) && $ad->price_negotiable!=0) { echo '<p class="sm-txt">Negotiable</p>';}?>
        </small></h1>
        <ol class="breadcrumb light-green-box">
            <?php
            if (isset($ad->state_id) && !empty($ad->state_id)) {
                echo '<li><a href="'.URL::to('/').'/search?state='.$ad->state_id.'">'.$ad->state_name.'</a></li>';
            }
            
            if (isset($ad->city_id) && !empty($ad->city_id)) {
                echo '<li><a href="'.URL::to('/').'/search?state='.$ad->state_id.'&city='.$ad->city_id.'">'.$ad->city_name.'</a></li>';
            }
            
            if (isset($ad->town_id) && !empty($ad->town_id)) {
                echo '<li><a href="'.URL::to('/').'/search?state='.$ad->state_id.'&city='.$ad->city_id.'&town='.$ad->town_id.'">'.$ad->town_name.'</a></li>';
            }
            
            $cat_link = '';
            $count=1;
            foreach ($cat_levels as $level) {
                    echo '<li><a href="'.URL::to('/').'/search?state='.$ad->state_id.'city='.$ad->city_id.'&category='.$level['cat_id'].'">'.$level['cat_name'].'</a></li>';
                $count++;
            }
            ?>
        </ol>
    </div>
</div>

<div class="row">
    @if (count($images))
    <div class="col-md-8">
        @include('ad.detail.slider', array('images' => $images))
    </div>
    @else 
        @include('ad.detail.info', array('ad' => $ad))
    @endif    
    <div class="col-sm-4">
        @include('ad.detail.seller', array('ad' => $ad))
        @include('ad.detail.share', array('ad' => $ad))
    </div>
</div>

@if (count($images))
    <div class="row">
    @include('ad.detail.info', array('ad' => $ad))
    </div>
@endif

@if (count($similars))
    @include('ad.detail.similar', array('similars'=>$similars))
@endif

@if (Auth::check())
    @include('ad.detail.message', array('ad' => $ad))
@endif    

@include('ad.detail.report', array('ad' => $ad))

@stop

@section('additional_scripts')

{{ HTML::script('js/jquery.validate.js') }}
{{ HTML::script('js/jquery.form.min.js') }}
{{ HTML::script('js/ad_detail.js') }}

@stop