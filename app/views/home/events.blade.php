<div class="row">
    <div class="col-sm-9">
        <h3 class="light-heading">Local Events</h3>
    </div>
    <div class="col-sm-3">
        <!-- Controls -->
        <div class="controls pull-right hidden-xs">
            <a class="left fa fa-chevron-left btn btn-primary" href="#event-ads"
                data-slide="prev"></a><a class="right fa fa-chevron-right btn btn-primary" href="#event-ads"
                    data-slide="next"></a>
        </div>
    </div>
</div>
<div id="event-ads" class="carousel slide hidden-xs" data-ride="carousel">
    <!-- Wrapper for slides -->
    <div class="carousel-inner">
    <?php
    $count = 0;
    $ads_per_row = 6;
    foreach($events as $event) {
        $active = '';
        $src = Config::get('app.ad_img_path').'noimg.png';

        if ($event->file && $event->file != '') {         
            $src = Config::get('app.ad_img_path').$event->file;
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
                <div class="col-item" onclick="location.href=base_url+'/detail/<?php echo $event->slug;?>';">
                    <div class="photo thumb">
                        <img class="thumbnail" src="<?php echo Image::url($src,200,200,array('crop'))?>" alt="<?php echo $event->title;?>">
                    </div>
                    <div class="info">
                        <div class="row">
                            <div class="col-sm-12">
                                <h5 class="section-heading">
                                    <?php echo str_limit($event->title, $limit = 40, $end = '...'); ?>
                                </h5>
                                <?php if (isset($event->price) && $event->price != 0 && !empty($event->price)) { ?>
                                <h5 class="price-text-color">
                                	<?php echo 'PKR ' . number_format($event->price); ?>
                                </h5>
                                <?php } ?>
                            </div>
                            
                        </div>
                        <div class="separator">
	                        <?php 
	                        if (!empty($event->keywords)) {
	                        	$attributes = json_decode($event->keywords);
								$normal_attrib = array();
						        $features_attrib = array(); 
						        foreach ($attributes as $attr) {
					                if ($attr->type == 'checkboxlist') {
					                    array_push($features_attrib, $attr);    
					                } else {
					                    array_push($normal_attrib, $attr);
					                }
					            }
								foreach ($normal_attrib as $attr) {
									if ($attr->name == 'Date and Time') {
										echo '<p><i class="fa fa-calendar"></i>'.$attr->value.'</i></p>';
									}
								}
							}
	                        ?>
                        	<p><i class="fa fa-map-marker"></i><?php echo $event->state_name . ', '. $event->city_name; echo ($event->town_name!='') ? ', '.$event->town_name : ''; ?></p>
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