@extends('layout.main')

@section('inner_content')

    @include('section.default_heading', array('heading' => 'Page not found!'))

   

    <div class="alert alert-error">

        <strong>Error!</strong> Sorry the page you are trying to access, does not exists.

    </div>

@stop