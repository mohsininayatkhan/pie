<div class="row eshtihar-categories">
    <div class="col-xs-12">

        <div class="tabbable-panel cat-tabbable-panel ">
            <div class="tabbable-line">
                <ul class="nav nav-tabs">
                <?php 
                $count = 0;
                foreach($categories as $category) {
                    if ($count >8) break;
                ?>
                
                    <li class="<?php echo ($count==0) ? 'active' : ''; ?>">
                        <a class="tabs-link" href="#tab_<?php echo $category->id?>" data-toggle="tab"><?php echo '<span><i class="fa '.$category->cat_img.' fa-lg"></i></span>&nbsp;'.$category->name; ?> </a>
                    </li>
                <?php
                $count++;
                } 
                ?>
                </ul>               
                
                <div class="tab-content cat-tab-content">
                <?php 
                $count = 0;
                foreach($categories as $category) {
                    if ($count >8) break;
                ?>    
                    <div class="tab-pane <?php echo ($count==0) ? 'active' : ''; ?>" id="tab_<?php echo $category->id;?>">
                        <div class="row">
                            <div class="col-xs-12">
                                <ul class="view-all-cat list-group col-xs-12">
                                    <?php echo '<a href="'.URL::to('/').'/search?category='. $category->id. '" class="list-group-item home-cat">View all in '. strtolower($category->name).'</a>'; ?>
                                </ul>
                            </div>
                        </div>        
                        <div class="row">
                            <div class="col-xs-12">
                                <ul class="list-group col-xs-12 col-sm-3">
                                <?php
                                if (isset($category->children)) {
                                    $num_of_columns = 4;
                                    $cat_per_column = ceil(count($category->children)/$num_of_columns);
                                    $i=1;
                                    foreach ($category->children as $child) {
                                        //echo '<li class="list-group-item">'.$child->name.'</li>';
                                        echo '<a href="'.URL::to('/').'/search?category='. $child->id. '" class="list-group-item home-cat">'. $child->name.'</a>';
                                        
                                        if ($i%$cat_per_column == 0) {
                                            echo '</ul>';
                                             if ($i < count($category->children)) {
                                                 echo '<ul class="list-group col-xs-12 col-sm-3">';
                                             }
                                        }
                                        $i++;
                                    }
                                    if (($i-1)%$cat_per_column != 0) {
                                        echo '</ul>';    
                                    }
                                }
                                ?>        
                            </div>
                        </div>
                    </div>
                <?php
                $count++;
                }
                ?>    
                </div>
            </div>
        </div>
    </div>
</div>
<br>