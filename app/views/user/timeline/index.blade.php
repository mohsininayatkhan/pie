@extends('layout.dashboard')
@section('page_title')
    My Wall    
@stop

@section('inner_content')
    @include('section.default_errors')   
        
    @if (count($cat_summary))
        @include('user.timeline.category_filter', array('cat_summary' => $cat_summary))
    @endif
    
    @include('user.timeline.record', array('ads' => $ads))
    
    {{ $ads->appends(Input::all())->links(); }}
    
@stop

@section('additional_scripts')
    {{ HTML::script('js/jquery.validate.js') }}
    {{ HTML::script('js/jquery.form.min.js') }}
    {{ HTML::script('js/timeline.js') }}
    {{ HTML::script('js/followers.js') }}
@stop