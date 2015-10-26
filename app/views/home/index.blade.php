@extends('layout.landing')

@section('page_title')
Pakistan's no.1 classified site    
@stop

@section('inner_content')

    <!-- Search Form -->
        
    
     <!-- Spotlight Ads -->
    @if (count($spotlight))
        @include('home.spotlight')
    @endif
    <br>
    @include('home.categories')
    
    @if (count($events))
        @include('home.events')
    @endif
    
    @include('home.social')
    
    @if (count($popular_cities))
        @include('home.popular_cities')
    @endif
    
   
    

    
    <!-- Categories -->
    <!--div class="row">
        <div class="col-md-4">
        <?php 
        $count = 0;
        $per_column = ceil(count($categories)/3); 

        foreach ($categories as $category) {

            echo '<div class="list-group list-special "><a href="'.URL::to('/').'/search?category='. $category->id. '" class="list-group-item active"><span><i class="fa '.$category->cat_img.' fa-lg"></i></span>&nbsp;'.$category->name.' ('.$category->number_of_ad.')</a>';

                if (isset($category->children)) {
                    foreach ($category->children as $child) {
                        echo '<a href="'.URL::to('/').'/search?category='. $child->id. '" class="list-group-item">'. $child->name. ' ('.$child->number_of_ad.')</a>';
                    } 
                }
            echo '</div>';
            $count++;

            if ($count%$per_column == 0 ) {
                echo '</div><div class="col-md-4">';
            } 
        }

        if ($count < count($categories)-1) {
            echo '</div>';
        }        
        ?>
        </div>
    </div>
    <!-- End of Categories-->

@stop

@section('additional_scripts')
{{ HTML::script('js/home.js') }}
@stop