@extends('layout.main')

@section('content')

@include('section.default_header')

<div class="container top-margin-sm">
    
    <div id="main-container" class="well white-backgroup">
        <div class="row" id="dashboard">
            <div class="col-sm-3">
                @yield('left_menu')
                <!-- @include('section.left_menu') -->
            </div>
            
            <?php $current_route = Route::currentRouteName(); ?>
            <div class="col-sm-9">
                 @yield('inner_content')
            </div>
        </div>
    </div>
</div>
@include('section.default_footer')
@stop