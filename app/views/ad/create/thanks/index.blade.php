@extends('layout.main')

@section('inner_content')

    @include('section.default_heading', array('heading' => 'Thanks!'))

    @include('section.default_errors')
    
    <p>Thanks for posting your ad '<?php echo $ad->title;?>'. It can sometimes take a few hours for your ad to appear in the listings.</p>
    <p>However, you can edit or delete your ad in future.</p>
    
@stop