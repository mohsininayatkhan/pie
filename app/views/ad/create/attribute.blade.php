<?php 
//print_r($attributes);
//die;
if (!count($attributes)) 
{
    return;
}

foreach ($attributes as $attribute) {
    $req = '';
    $mandatory = '';
    $rules = '';
    if ($attribute->required=='true') {
        $req = 'attrib_required';
        $mandatory = '*';
    }
    
    if (isset($attribute->rule) && !empty($attribute->rule)) {
        $rules = $attribute->rule;
    }
    
    $attribute_name = mb_strtolower(str_replace(" ", "_", $attribute->name));
    
    if (isset($ad_id)) {
        $attrib_detail = Addetail::getAttribVal($ad_id, $attribute->id);    
    }
    
?>
<div class="form-group">
    <label for="<?php echo $attribute->name?>" class="col-sm-2 control-label custom-label"><?php echo $attribute->name.$mandatory?>:</label>
    <div class="col-sm-10 ">
        <?php 
        if ($attribute->type == 'text') {
            $attr_val = (isset($attrib_detail) && count($attrib_detail)) ? $attrib_detail[0]->attribute_val : '';  
            echo '<input id="attr_'.$attribute->id.'_'.$attribute_name.'" value="'.$attr_val.'" name="attr_'.$attribute->id.'_'.$attribute_name.'" type="text" class="form-control '.$req. ' '.$rules.' " />';
        } else if ($attribute->type == 'datetime') {
            $attr_val = (isset($attrib_detail) && count($attrib_detail)) ? $attrib_detail[0]->attribute_val : ''; 
            echo '<div class="input-group date" id="div_'.$attribute->id.'_'.$attribute_name.'"><input id="attr_'.$attribute->id.'_'.$attribute_name.'" value="'.$attr_val.'" name="attr_'.$attribute->id.'_'.$attribute_name.'" type="text" class="form-control '.$req. ' '.$rules.' " />';
			echo '<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span></div>';
			echo '<script type="text/javascript">$(function () {$("#div_'.$attribute->id.'_'.$attribute_name.'").datetimepicker({ sideBySide: true });});</script>';
        }
        else if ($attribute->type == 'list') {
            $attr_val = (isset($attrib_detail) && count($attrib_detail)) ? $attrib_detail[0]->attribute_val : '';
            $values = json_decode($attribute->value);
            if (count($values)) {
                echo '<select id="attr_'.$attribute->id.'_'.$attribute_name.'" name="attr_'.$attribute->id.'_'.$attribute_name.'" class="form-control ' . $req . '">';
                echo '<option value="">Select ' . $attribute->name . '</option>';
                
                foreach ($values as $k => $v) {
                    $val = $k.'_'.$v;
                    $selected = ($k == $attr_val) ? 'selected="selected"' : '';
                    echo '<option '.$selected.' value="' . $val . '">' . $v . '</option>';
                }
                echo '</select>';
            }
        } else if ($attribute->type == 'checkboxlist') {
            
            $attr_val = array();
            if (isset($attrib_detail) && count($attrib_detail)) {
                foreach ($attrib_detail as $attr) {
                    $attr_val[] = $attr->attribute_val;
                }
            }

            $values = json_decode($attribute->value);
            $count = 0;
            echo '<div class="row">';
            foreach ($values as $k => $v) {
                $count++;
                $val = $k.'_'.$v;
                $checked = in_array($k, $attr_val) ? 'checked="checked"' : '';
                echo '<div class="col-sm-4">'; 
                echo '<div class="checkbox checkbox-default checkbox-inline">';
                echo '<input id="attr_'.$attribute->id.'_'.$attribute_name.'_'.$count.'" '.$checked.' name="attr_'.$attribute->id.'_'.$attribute_name.'[]" value="' . $val . '" type="checkbox">';
                echo '<label for="attr_'.$attribute->id.'_'.$attribute_name.'_'.$count.'" class="custom-label"> ' . $v . '</label></div>';
                echo '</div>';
            }
            echo '</div>';
        } else if ($attribute->type == 'radiogroup') {
            $attr_val = (isset($attrib_detail) && count($attrib_detail)) ? $attrib_detail[0]->attribute_val : '';
            $values = json_decode($attribute->value);
            $count =0;
            foreach ($values as $k => $v) {
                $val = $k.'_'.$v;
                $checked = '';
                
                if (!empty($attr_val) && $k==$attr_val) {
                    $checked = 'checked="checked"';
                }
                
                if (empty($attr_val) && $count==0) {
                    $checked = 'checked="checked"';
                }
                $count++;
                echo '<div class="radio radio-inline"><input id="attr_'.$attribute->id.'_'.$attribute_name.'_'.$count.'" name="attr_'.$attribute->id.'_'.$attribute_name.'" ' . $checked . ' value="' . $val . '" type="radio"><label for="attr_'.$attribute->id.'_'.$attribute_name.'_'.$count.'" class="custom-label">'. $v . '</label></div>';
                $count++;
            }
        }
        ?>
     </div>
</div>
<?php } ?>