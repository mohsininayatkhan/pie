@extends('layout.main')

@section('page_title')
All classified ads in Pakistan    
@stop

@section('inner_content')
    
    <div class="summary-box">
        <div class="row">
        <div class="col-sm-9">
            <h4><?php echo $summary_text; ?></h4>    
        </div>
        <div class="col-sm-3">
            <select name="sortby" id="sortby" class="form-control">
                <option <?php if (Input::get('sort') == 'most_recent_first') { echo 'selected="selected"'; }?> value="most_recent_first">Most Recent First</option>
                <option <?php if (Input::get('sort') == 'price_lowest_first') { echo 'selected="selected"'; }?> value="price_lowest_first">Price: Low to High</option>
                <option <?php if (Input::get('sort') == 'price_highest_first') { echo 'selected="selected"'; }?> value="price_highest_first">Price: Hight to low</option>
            </select>    
        </div>
        </div>
    </div>
    @include('ad.search.bredcrumb')
    <div class="row">
        <div class="col-sm-3">
            @include('ad.search.refine')
        </div>
        <div class="col-sm-9 list">
            @include('ad.search.featured', array('ads' => $featured_ads))
            @include('ad.search.record', array('ads' => $ads))
            
            <?php echo $ads->appends(Input::all())->links(); ?>
        </div>
    </div>
@stop

@section('additional_scripts')
{{ HTML::script('js/jquery.validate.js') }}
{{ HTML::script('js/jquery.form.min.js') }}
{{ HTML::script('js/search_listing.js') }}
@stop