<div class="panel panel-default left-search-box">
    {{ Form::open(array('url' => 'search','name'=> 'frmRefineSearch','id'=>'frmRefineSearch', 'method' => 'GET')) }}
        <input type="hidden" name="category" id="category" value="<?php echo Input::get('category','')?>" />
        <input type="hidden" name="sort" id="sort" value="<?php echo Input::get('sort','')?>" />
        <div class="panel-body">
            <h4 class="light-heading">Refine Your Search</h4>
            <div class="form-group input-group">
                <input id="keyword" name="keyword" type="text" autocomplete="off" value="<?php echo Input::get('keyword') ?>" class="form-control typeahead" placeholder="I am looking for">
                <span class="input-group-btn">
                    <button class="btn btn-default" type="button" id="search-listing-btn">
                        <i class="fa fa-search"></i>
                    </button> </span>
            </div>
            
            <div class="form-group">
                <hr>
            </div>
            <div class="form-group">
                <h5 class="attribute-heading">Category:</h5>
                <div class="row tree">
                    <?php
                    $total_cat_count = count($cat_tree);
                    $all_categories_down_arrow = (count($cat_summary) || $total_cat_count) ? '<span class="fa fa-angle-right"></span>' : '';
                    $url = Ad::getCategorySummaryUrl($_REQUEST, '');
                    echo '<div class="col-sm-12"><a href="'.URL::to('/').'/search/1'.$url.'">'.$all_categories_down_arrow.' All Categories</a></div>';
                    $level =1;
                    
                    foreach ($cat_tree as $node) {
                        $url = '';
                        $url = Ad::getCategorySummaryUrl($_REQUEST, $node['cat_id']);
                        $active = Input::get('category') == $node['cat_id'] ? 'active' : '';
                        
                        $child_arrow = '';
                        if ($level < $total_cat_count) {
                            $child_arrow = '<span class="fa fa-angle-right"></span> ';
                        } else if($level == $total_cat_count && count($cat_summary) && $level<4) {
                            $child_arrow = '<span class="fa fa-angle-right"></span> ';
                        }
                        echo '<div class="col-sm-12 indent-'.($level).'"><a class="'.$active.'" href="'.URL::to('/').'/search/1'.$url.'">'. $child_arrow.$node['cat_name'].'</a></div>';
                        $level++;                        
                    }
                    if ($level<=4) {
                        foreach ($cat_summary as $key => $val) {
                            $url = '';
                            $url = Ad::getCategorySummaryUrl($_REQUEST, $key);
                            $cat = Category::find($key);
                            if ($cat) {
                                $active = Input::get('category') == $key ? 'active' : '';
                                echo '<div class="col-sm-12 indent-'.($level).'"><a class="'.$active.'" href="'.URL::to('/').'/search/1'.$url.'">'. $cat->name .'('.$val.')</a></div>';    
                            }
                        }    
                    } 
                    ?>
                </div>
            </div>
            <div class="form-group">
                <hr>
            </div>
            <div class="form-group">
                <h5 class="attribute-heading">Location:</h5>
                <div class="row tree">
                    <?php
                    $state_child_flag = false;
                    $city_child_flag = false;
                    
                    if ($loc_type == 'towns') {
                        $state_child_flag = true;
                        if (count($loc_summary)) {
                            $city_child_flag = true;
                        }
                    }
                    
                    if ($loc_type == 'cities' && count($loc_summary)) {
                        $state_child_flag = true;
                    }                    
                    
                    $url = Ad::getLocationSummaryUrl($_REQUEST, 'all');
                    echo '<div class="col-sm-12"><a href="'.URL::to('/').'/search/1'.$url.'"><span class="fa fa-angle-right"></span> All Pakistan</a></div>';
                    if (Input::get('state')) {
                        
                        $state_active = '';
                        if (!Input::get('city') && !Input::get('town')) {
                            $state_active = 'active';
                        }
                        
                        $level = 1;
                        $url = Ad::getLocationSummaryUrl($_REQUEST, 'state', Input::get('state'));
                        $loc = State::find(Input::get('state'));
                        $loc_name = $loc->state_name;
                        $active = '';
                        $child_arrow = '';
                        if ($state_child_flag) {
                            $child_arrow = '<span class="fa fa-angle-right"></span> ';    
                        }                        
                        echo '<div class="col-sm-12 indent-'.($level).'"><a class="'.$state_active.'" href="'.URL::to('/').'/search/1'.$url.'">'. $child_arrow.$loc_name .'</a></div>';
                    }
                    
                    if (Input::get('city')) {
                            
                        $city_active = '';
                        if (Input::get('state') && !Input::get('town')) {
                            $city_active = 'active';
                        }
                        $level = 2;
                        $url = Ad::getLocationSummaryUrl($_REQUEST, 'city', Input::get('city'));
                        $loc = City::find(Input::get('city'));
                        $loc_name = $loc->city_name;
                        $child_arrow = '';
                        if ($city_child_flag) {
                            $child_arrow = '<span class="fa fa-angle-right"></span> ';    
                        }       
                        echo '<div class="col-sm-12 indent-'.($level).'"><a class="'.$city_active.'" href="'.URL::to('/').'/search/1'.$url.'">'. $child_arrow.$loc_name .'</a></div>';
                    }                   
                    
                    if (count($loc_summary)) {
                        foreach ($loc_summary as $key => $val) {
                            $url = '';
                            $active = '';
                            if ($loc_type == 'states') {
                                $level = 1;
                                $loc = State::find($key);
                                $loc_name = $loc->state_name;
                                $url = Ad::getLocationSummaryUrl($_REQUEST, 'state', $key);
                            } else if ($loc_type == 'cities') {
                                $level = 2;
                                $loc = City::find($key);
                                $loc_name = $loc->city_name;
                                $url = Ad::getLocationSummaryUrl($_REQUEST, 'city', $key);
                            } else if ($loc_type == 'towns') {
                                $level = 3;
                                $loc = Town::find($key);
                                $loc_name = $loc->town_name;
                                $url = Ad::getLocationSummaryUrl($_REQUEST, 'town', $key);
                                $active = Input::get('town') == $key ? 'active' : '';
                            }
                            echo '<div class="col-sm-12 indent-'.($level).'"><a class="'.$active.'" href="'.URL::to('/').'/search/1'.$url.'">'. $loc_name .'('.$val.')</a></div>';
                        }    
                    } 
                    ?>
                </div>
            </div>
            
            <div id='ask-for-price' class="form-group">
            </div>
            
            <div class="form-group">
                <hr>
            </div>
            
            <div class="form-group">
                <h5 class="attribute-heading">Posted By:</h5>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="checkbox checkbox-inline">
                            <input type="checkbox" value="individual" <?php echo (Request::input('post_type') && in_array('individual', Request::input('post_type'))) ? 'checked="checked"' : '' ?> name="post_type[]" id="post_type_individual"><label class="post-type-label" for="post_type_individual"> Individual</label>
                         </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="checkbox checkbox-inline">
                            <input type="checkbox" value="company" <?php echo (Request::input('post_type') && in_array('company', Request::input('post_type'))) ? 'checked="checked"' : '' ?> name="post_type[]" id="post_type_company"><label class="post-type-label" for="post_type_company"> Company</label>
                         </div>
                    </div>
                </div>
            </div>
            
            <div class="form-group" id="search-attributes"></div>
            
            <div class="clear"></div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary search-bar-btn pull-right">
                    <span class="glyphicon glyphicon-search"></span> Search
                </button>
            </div>
        </div>
        
    {{ Form::close() }}
</div>