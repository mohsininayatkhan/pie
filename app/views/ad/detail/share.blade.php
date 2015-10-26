<?php
$media = Media::where('ad_id','=',$ad->id)->take(1)->get();
$img_path = Config::get('app.ad_img_path') . 'noimg.png';                
if (count($media)) {
    $img_path = URL::to('/').'/uploads/ads/'.$media[0]->file;
}

?>
<div class="well light-blue-box">
    <h3 class="page-header margin-top-0">Share Ad</h3>
    <div class="clear"></div>
    <div>
        <ul class="list-inline">
            <li>
                
                <a title="{{ $ad->title }}" href="http://www.facebook.com/sharer.php?u=&t={{ URL::route('detail',$ad->slug)}}" target="_blank">
                    <i class="fa fa-3x fa-facebook-square"></i>
                </a>
            </li>
            <li>
                <a class="share-linkedin" target="_blank" href="http://www.linkedin.com/shareArticle?mini=true&summary={{ $ad->detail }}&title={{ $ad->title }}&url={{ URL::route('detail',$ad->slug)}}"><i class="fa fa-3x fa-linkedin-square"></i></a>
            </li>
            <li>
                <a class="twitter-share-button" href="https://twitter.com/share" data-related="twitterdev" data-size="large" data-count="none" data-text="{{ $ad->title }}" data-url="{{ URL::route('detail',$ad->slug) }}">
                    <i class="fa fa-3x fa-twitter-square"></i>
                </a>
            </li>
            <li>
                <div class="g-plus" data-action="share" data-annotation="none" data-height="24" data-href="{{ URL::route('detail',$ad->slug) }}"><i class="fa fa-3x fa-google-plus-square"></i></div>
                
            </li>
        </ul>
    </div>
</div>

@section('image',$img_path)
@section('title', $ad->title)
@section('url',URL::route('detail',$ad->slug))
@section('description', $ad->detail)

<script>
window.twttr=(function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],t=window.twttr||{};if(d.getElementById(id))return;js=d.createElement(s);js.id=id;js.src="https://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);t._e=[];t.ready=function(f){t._e.push(f);};return t;}(document,"script","twitter-wjs"));
</script>

