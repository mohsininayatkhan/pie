<?php
if (!count($attributes)) 
{
    return;
}

foreach ($attributes as $attribute) {
    $attribute_name = mb_strtolower(str_replace(" ", "_", $attribute->name));
?>
    <div class="form-group">
            <?php 
            if ($attribute->type == 'text') {
            	echo '<h5 class="attribute-heading">'.$attribute->name.':</h5>';
                if ($attribute->search_by_range == 'false') {
                    echo '<input id="attr_'.$attribute_name.'" name="attr_'.$attribute_name.'" type="text" class="form-control" />';    
                } else {
                    echo '<div class="row price-boxes" >';
                    echo '<div class="col-sm-6">';
                    echo '<input type="text" name="attr_min_'.$attribute_name.'" id="attr_min_'.$attribute_name.'" class="form-control" placeholder="Min">';
                    echo '</div>';
                    echo '<div class="col-sm-6">';
                    echo '<input type="text" name="attr_max_'.$attribute_name.'" id="attr_max_'.$attribute_name.'" class="form-control" placeholder="Max">';
                    echo '</div>';
                    echo '</div>';
                }
                
                //  echo '<div class="form-group"><hr></div>';
            } else if ($attribute->type == 'checkboxlist' || $attribute->type == 'list' || $attribute->type == 'radiogroup') {
            	echo '<h5 class="attribute-heading">'.$attribute->name.':</h5>';
                echo '<div class="row attribute-options">';
                $values = json_decode($attribute->value);
                $count = 0;
                foreach ($values as $k => $v) {
                    $count++;
                    //$val = $k.'_'.$v;
                    $val = $attribute->id.'_'.$k;
                    echo '<div class="col-sm-12">'; 
                    echo '<div class="checkbox checkbox-inline">';
                    echo '<input id="attr_'.$attribute_name.'_'.$k.'" name="attr_'.$attribute_name.'[]" value="' . $val . '" type="checkbox">';
                    echo '<label for="attr_'.$attribute_name.'_'.$k.'" class="attributes-option-label"> ' . $v . '</label></div>';
                    echo '</div>';
                }
                echo '</div>';
            } 
            ?>
    </div>
<?php } ?>