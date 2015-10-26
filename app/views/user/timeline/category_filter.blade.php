<div class="row">
    <div class="col-sm-12">
        <div class="vertical-center well light-blue-box">
            {{ Form::open(array('url' => 'timeline','name'=> 'frmRefineTimeline','id'=>'frmRefineTimeline', 'method' => 'GET')) }}
                <div class="row">
                    <?php
                    foreach ($cat_summary as $item) {
                        $cat = Category::find($item->category);
                        
                        $checked = 'checked="checked"';
                        if (isset($filters)) {
                            if (in_array($item->category, $filters)) {
                                $checked = 'checked="checked"';
                            } else {
                                $checked = '';
                            }
                        } else if (isset($is_filter)) {
                            $checked = '';
                        }
                        
                        echo '<div class="col-sm-3">';
                        echo '<div class="checkbox checkbox-default checkbox-inline">';
                        echo '<input id="cat_filter_'.$item->category.'" '.$checked.' name="cat_filter[]" value="'.$item->category.'" type="checkbox">';
                        echo '<label for="cat_filter_'.$item->category.'" class="custom-label"> ' . $cat->name .'('.$item->category_count.')</label></div>';
                        echo '</div>';
                    } 
                    ?>    
                </div>
                <input type="hidden" name="is_filter" value="1">
            {{ Form::close() }}
        </div>
    </div>
</div>