<div id="carousel-example-generic" class="carousel slide carousel-box" data-ride="carousel">

    <ol class="carousel-indicators">
        <?php 
        $count = 0;
        for($i=0; $i<count($images); $i++) {
            $active = ($count==0) ? 'class="active"' : '';
            echo '<li data-target="#carousel-example-generic" '.$active.' data-slide-to="'.$i.'"></li>';
        }
        ?>
    </ol>

    <div class="carousel-inner">
        <?php 
        $count = 0;
        foreach($images as $image) {
            $active = ($count==0) ? 'active' : '';
            echo '<div class="item '.$active.'"><div style="background-image:url(\''.Image::url(Config::get('app.ad_img_path').$image->file,750,500,array('crop')).'\'); background-size:cover;" class="slider-size"></div></div>';
            $count++;
        }
        ?>
    </div>
    
    <a class="left carousel-control" href="#carousel-example-generic" data-slide="prev"> <span class="glyphicon glyphicon-chevron-left"></span> </a>
    <a class="right carousel-control" href="#carousel-example-generic" data-slide="next"> <span class="glyphicon glyphicon-chevron-right"></span> </a>
</div>
<br>