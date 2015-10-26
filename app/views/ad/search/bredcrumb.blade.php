<div class="row">
    <div class="col-sm-12">
        <?php
        $cat_data = array();
        echo '<ol class="breadcrumb light-green-box">';
        echo '<li><a href="'.URL::to('/').'/search/1">All in Pakistan</a></li>';
        if (isset($current_state) && !empty($current_state)) {
            $state = State::find($current_state);
            if ($state) {
                echo '<li><a href="'.URL::to('/').'/search/1?&state='.$current_state.'">'.$state->state_name.'</a></li>';    
            }
            
            if (isset($current_city) && !empty($current_city)) {
                $city = City::find($current_city);
                if ($city) {
                    echo '<li><a href="'.URL::to('/').'/search/1?&state='.$current_state.'&city='.$current_city.'">'.$city->city_name.'</a></li>';    
                }
            }
        }
        
        if (isset($cat_parents) && count($cat_parents)) {
            foreach($cat_parents as $cat) {
                array_push($cat_data, $cat);
                $url = Ad::getCategoryBreadcrumbUrl($_REQUEST, $cat_data);
                echo '<li><a href="'.URL::to('/').'/search/1'.$url.'">'.$cat['cat_name'].'</a></li>';
            }
        }
        echo '</ol>';
        ?>
    </div>
</div>