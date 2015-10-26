@extends('layout.main')

@section('inner_content')
    
    <?php
    $info = array('heading' => 'Welcome '.$user->fname.' '.$user->lname);
    ?>
    @include('section.default_heading', $info)

    @include('section.default_errors')
    
    <p>Thanks for creating your account on Eshtihar.com.</p>
    <p>We will send you an email <?php //echo $user->email;?> to activate your account. Once actiavated, You can manage your ads from your account.</p>
    
@stop