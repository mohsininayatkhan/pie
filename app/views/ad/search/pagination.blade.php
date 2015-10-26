<?php 
if ($total_records > 0) { 
    echo '<nav>';
    if ($total_records > 0) { 
        echo '<ul class="pagination pagination-sm">';
        
        $param = '?';
        foreach ($_REQUEST as $k => $v) {
            if (is_array($v)) {
                foreach($v as $j) {
                    $param .= '&'.$k.'%5B%5D='.$j;
                }
            } else {
                $param .= '&'.$k.'='.$v;    
            }    
            
        }
        
        if ($page != '1') {
            //echo '<li><a href="'.Config::get('app.url').'/search/'.($page-1).$param.'">&laquo;</a></li></ul>';
            echo '<li><a href="'.URL::to('/').'.search/'.($page-1).$param.'">&laquo;</a></li>';
        }
        
        for ($count=1; $count<= $total_pages; $count++) {
            $class = '';    
            if ($count == $page) {
                $class ='class="active"';
            }
            echo '<li '.$class.'><a href="'.URL::to('/').'/search/'.$count.$param.'">'. $count.'</a></li>';     
        }
        
        if ($page != $total_pages) {
            echo '<li><a href="'.URL::to('/').'/search/'.($page+1).$param.'">&raquo;</a></li>';
        }
        echo '</ul>';
    }
    echo '</nav>';
}
?>