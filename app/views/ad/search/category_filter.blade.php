<?php
    if (count($cat_summary)) {
?>
        <div class="row">
            <div class="col-sm-12">
                <div id="cat-browser-box" class="vertical-center well">
                    <div class="row">
                        <?php
                        foreach ($cat_summary as $key => $val) {
                            $url = '';
                            $url = Ad::getCategorySummaryUrl($_REQUEST, $key, $cat_summary_level);
                            $cat = Category::find($key);
                            echo '<div class="col-sm-4"><a href="'.URL::to('/').'/search/1'.$url.'">'. $cat->name .'('.$val.')</a></div>';
                        }
                        ?>    
                    </div>
                </div>
            </div>
        </div>
<?php 
    }
?>
