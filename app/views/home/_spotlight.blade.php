<div class="row">
    <div class="col-md-12">
        @include('section.default_heading', array('heading' => 'Ads in spotlight', 'class' => 'top-margin-zero'))
    </div>

    <div class="row">
        <div class="col-md-12">
        <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
             <div class="carousel-inner testi-slider">
				<?php
				$c =1;
				$total = count($spotlight);
				$l = 1;
				foreach($spotlight as $spot){
					$cls = '';
					if ($l==1) {$cls = 'active';}
					if ($c==1) {
					?>
					 <div class="item {{ $cls }}">
	
						 <div class="testi-content">
	
						 <div class="sliddetail col-md-1"></div>
					<?php
					}
            
                    $src = Config::get('app.ad_img_path').'noimg.png';
            
                    if ($spot->file && $spot->file != '') {			
                        $src = Config::get('app.ad_img_path').$spot->file;
                    }
                    ?>
                     <div class="sliddetail col-md-2">

                         <img class="thumbnail" src="<?php echo Image::url($src,300,300,array('crop'))?>" alt="<?php echo $spot->title;?>">

                         <h4 >
                         <a href="{{ URL::route('detail',$spot->slug) }}" title="<?php echo $spot->slug;?>"> 
						 	<?php echo str_limit($spot->title, $limit = 20, $end = '...'); ?> 
                         </a>
                         </h4>
                         <span><?php if(isset($spot->price) && !empty($spot->price)) { echo 'PKR '.number_format($spot->price); } ?></span>

                     </div>

					<?php
					if($c==5 || $l==$total){
						$c =1;
					?>
                         <div class="sliddetail col-md-1"></div>
                         </div>
                    </div>
                    <?php
					}else{$c++;}
					$l++;
				}
					?>
             </div>
             <a data-slide="prev" href="#carousel-example-generic" class="left carousel-control"> <span class="glyphicon danger-heading glyphicon-chevron-left"></span> </a>
            <a data-slide="next"  href="#carousel-example-generic" class="right carousel-control"> <span class="glyphicon danger-heading glyphicon-chevron-right slider-right"></span> </a>           
        </div>
       </div> 
    </div>
</div>
<br>

