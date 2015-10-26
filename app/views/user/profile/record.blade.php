@foreach ($ads as $ad)
<?php 
    $ribbon = '';
    $ribbon_txt = '';
    $ad_style = '';
    if ($ad->featured) {
        $ribbon = 'has-ribbon';
        $ribbon_txt = 'Featured';
        $ad_style = 'featured-ad';
    }
    Ad::increaseCount($ad->id, 'listing_count');
?>
<div class="row ads-list user-profile-ads">
      
    <div class="col-sm-2 list-img">
        <?php 
        $src = Config::get('app.ad_img_path').'noimg.png';
        if ($ad->file && $ad->file != '') {
	     $src = Config::get('app.ad_img_path').$ad->file;  
        }
        ?>
        
        <div class="thumbnail <?php echo $ribbon; ?>" data-text="<?php echo $ribbon_txt; ?>">
            <a href="portfolio-item.html"> <img class="img-responsive img-hover" src="<?php echo Image::url($src,300,300,array('crop'))?>" alt="<?php echo $ad->title; ?>"> </a>
        </div>
            
    </div>
    <div class="col-sm-8">
        <h3 class="section-heading"><?php echo $ad->title; ?></h3>
        <h4><span class="fa fa-1x fa-map-marker"></span> <?php echo $ad->state_name . ', '. $ad->city_name; echo ($ad->town_name!='') ? ', '.$ad->town_name : ''; ?></h4>
        <?php 
        if (!empty($ad->keywords)) {
            $attributes = json_decode($ad->keywords);
            $info = '';
            foreach ($attributes as $attr) {
                 $info .= $attr->name.': '.$attr->value.' | ';
            }
            echo '<p class="specs">'.str_limit(trim($info, " | "),95, ' ..').'</p>';
        }
        ?>
        
        <p>
            <?php echo str_limit($ad->detail, 150, '...'); ?>
        </p>
        <!--a class="btn btn-danger" href="<?php echo URL::to('/').'/detail/'.$ad->slug; ?>">View Detail</i></a-->
    </div>
    <div class="col-sm-2">
        <h5 class="list-price-text margin-bottom-0"><?php if(isset($ad->price) && $ad->price!=0 && !empty($ad->price)) { echo 'PKR '.number_format($ad->price); } ?></h5>
        <?php if(isset($ad->price_negotiable) && $ad->price_negotiable!=0) { echo '<p class="sm-txt">Negotiable</p>';}?>
        <p><?php echo GeneralPurpose::timeAgo($ad->posted_date); ?></p>
    </div>
    <a class="list-link" href="<?php echo URL::to('/').'/detail/'.$ad->slug; ?>"> 
        <span class="hyperspan"></span>
    </a>
</div>
<hr>
@endforeach