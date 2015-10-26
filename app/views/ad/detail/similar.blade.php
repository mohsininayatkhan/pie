<div class="row">
    <div class="col-sm-12">
        <h3 class="page-header">Similar Ads</h2>
    </div>

	<?php
	foreach($similars as $ad){
	?>
    <div class="col-sm-3 col-sm-4 col-xs-6">
        <div class="thumbnail">
			<?php     
            $src = Config::get('app.ad_img_path').'noimg.png';    
            if ($ad->file && $ad->file != '') {    
                $src = Config::get('app.ad_img_path').$ad->file;    
            }
            ?>
            <img class="img-responsive similar" src="<?php echo Image::url($src,300,300,array('crop'))?>" alt="<?php echo $ad->title;?>">
            <div class="caption">
                <h4><a href="{{ URL::route('detail',$ad->slug) }}" title="<?php echo $ad->title;?>"><?php echo str_limit($ad->title, $limit = 20, $end = '...'); ?></a>
                <br>
                <span class="fa fa-1x fa-map-marker"></span> <small><?php echo $ad->state_name;?>, <?php echo $ad->city_name;?></small>
                
                <div class="clearfix"></div>
                
                <small class="list-price-text">
                	<?php if(isset($ad->price) && $ad->price!=0 && !empty($ad->price)) { echo 'PKR '.number_format($ad->price); } ?>
            		<?php if(isset($ad->price_negotiable) && $ad->price_negotiable!=0) { echo '<p class="sm-txt">Negotiable</p>';}?>
					<?php //if(isset($ad->price) && !empty($ad->price)) { echo 'PKR '.number_format($ad->price); } ?>
                </small>
                </h4>

                <p>
                    <?php echo str_limit($ad->detail, $limit = 90, $end = '...'); ?>
                </p>
            </div>
        </div>
         <a class="list-link" href="<?php echo URL::to('/').'/detail/'.$ad->slug; ?>"> 
        <span class="hyperspan"></span>
    </a>
    </div>
   
	<?php
	}
	?>
</div>