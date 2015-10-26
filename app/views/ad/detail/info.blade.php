<div class="col-sm-8 ad-detail">
    <div class="row">
        <?php
        $normal_attrib = array();
        $features_attrib = array(); 
        if (!empty($ad->keywords)) {
            $attributes = json_decode($ad->keywords);
            foreach ($attributes as $attr) {
                if ($attr->type == 'checkboxlist') {
                    array_push($features_attrib, $attr);    
                } else {
                    array_push($normal_attrib, $attr);
                }
            }
            $count = count($normal_attrib);
            $attr_per_col = ceil($count/2);
        ?>
        <div class="col-xs-8 col-sm-6">
            <ul class="list-group">
               <?php 
                $i=1;
                foreach ($normal_attrib as $attr) {
               ?> 
                    <li class="list-group-item">
                        <span class="pull-right"><?php echo $attr->value; ?></span>
                        <div class="">
                            <strong><?php echo $attr->name; ?></strong>
                        </div>
                    </li>
                <?php
                    if ($i%$attr_per_col == 0) {
                        echo '</ul></div>';
                
                        if ($i < count($normal_attrib)) {
                            echo '<div class="col-xs-8 col-sm-6"><ul class="list-group">';
                        }
                    }
                $i++;     
                }
                if (($i-1)%$attr_per_col != 0) {
                    echo '</ul></div>';    
                }
                ?>
                        
    <?php 
    }
    ?> 
    </div> 
    
    <?php
    if (count($features_attrib)) {
	    foreach ($features_attrib as $attr) {
	    ?>
	        <div class="rows">
	            <div class="col-sm-12 detail-features">
	            <strong><?php echo $attr->name; ?></strong>
	            </div>
	        </div>             
	         <div class="rows">
	             <?php 
	             $values = explode(",", $attr->value);
	             foreach ($values as $k => $v) {
	                 echo '<div class="col-sm-4"><span class="fa fa-1x fa-check green-text"></span>&nbsp;'.$v.'</div>';
	             }
	             ?>
	        </div>
	    <?php
	    }
    }
    ?>
    
    <div class="rows">
        <div class="col-sm-12">
	        <p>
	            </br>
	            <?php echo $ad->detail; ?>
	        </p>       
        </div>
    </div>
    
    <?php 
    if (isset($ad->link) && !empty($ad->link)) { 
    ?>
	    <div class="rows">
	        <div class="col-sm-12">
	        <strong>Link: </strong><a target="_blank" href="<?php echo $ad->link; ?>"><?php echo $ad->link; ?></a>
	        </div>
	    </div>
    <?php
	} 
    ?>
    
    <div class="rows">
        <div class="col-sm-12">
        <strong>Ad ID: </strong><?php echo $ad->unique_id; ?>
        </div>
    </div>
</div>