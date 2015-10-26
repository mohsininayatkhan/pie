<div class="row">
    <div class="col-sm-9">
        <h3 class="light-heading">Spotlight Ads</h3>
    </div>
    <div class="col-sm-3">
        <!-- Controls -->
        <div class="controls pull-right hidden-xs">
            <a class="left fa fa-chevron-left btn btn-primary" href="#spotlight-ads"
                data-slide="prev"></a><a class="right fa fa-chevron-right btn btn-primary" href="#spotlight-ads"
                    data-slide="next"></a>
        </div>
    </div>
</div>
<div id="spotlight-ads" class="carousel slide hidden-xs" data-ride="carousel">
    <!-- Wrapper for slides -->
    <div class="carousel-inner">
    <?php
    $count = 0;
    $ads_per_row = 6;
    foreach($spotlight as $spot) {
         $active = '';
        $src = Config::get('app.ad_img_path').'noimg.png';

        if ($spot->file && $spot->file != '') {         
            $src = Config::get('app.ad_img_path').$spot->file;
        }
    ?>   
        <?php
        if ($count%$ads_per_row== 0) {
            
            if($count==0) {
                $active = 'active';
            } else {
                echo '</div></div>';
            }
            echo '<div class="item '.$active.'"><div class="row">';
        }
        ?>
            <div class="col-sm-2">
                <div class="col-item" onclick="location.href=base_url+'/detail/<?php echo $spot->slug;?>';">
                    <div class="photo thumb">
                        <img class="thumbnail" src="<?php echo Image::url($src,200,200,array('crop'))?>" alt="<?php echo $spot->title;?>">
                    </div>
                    <div class="info">
                        <div class="row">
                            <div class="price col-sm-12">
                                <h5 class="section-heading">
                                    <?php echo str_limit($spot->title, $limit = 35, $end = '...'); ?>
                                </h5>
                                <?php if (isset($spot->price) && $spot->price != 0 && !empty($spot->price)) { ?>
                                <h5 class="price-text-color">
                                	<?php echo 'PKR ' . number_format($spot->price); ?>
                                </h5>
                                <?php } ?>
                            </div>
                            
                        </div>
                        <div class="separator">
                            <p><i class="fa fa-map-marker"></i><?php echo $spot->state_name . ', '. $spot->city_name; echo ($spot->town_name!='') ? ', '.$spot->town_name : ''; ?></p>
                            
                        </div>
                        <div class="clearfix">
                        </div>
                    </div>
                </div>
            </div>
    <?php 
        $count++;
    }
    if ($count%$ads_per_row !== 0) {
        echo '</div></div>';
    }
    ?>    
    </div>
</div>