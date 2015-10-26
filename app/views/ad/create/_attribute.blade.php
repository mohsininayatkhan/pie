<?php 
if (!count($attributes)) 
{
    return;
}
?>
<div class="form-group">
    <?php 
    $count = 1; 
    foreach ($attributes as $attribute) {
    ?>
    <label class="col-lg-2 control-label custom-label"><?php echo $attribute->name?></label>
    <div class="col-lg-4">
        <?php
        $req = '';
        if ($attribute->required) {
            $req = 'required';
        }
        
        if ($attribute->type == 'text') {
            echo '<input type="text" class="form-control '. $req .'" />';
        } else if ($attribute->type == 'list') {
            echo '<select class="form-control '. $req .'">';
            echo '<option value="">Select ' . $attribute->name . '</option>';
            $values = json_decode($attribute->value);
            foreach ($values as $k => $v) {
                echo '<option value="' . $k . '">' . $v . '</option>';
            }
            echo '</select>';
        } else if ($attribute->type == 'checkboxlist') {
            $values = json_decode($attribute->value);
            foreach ($values as $k => $v) {
                echo '<div class="checkbox-inline"><label class="custom-label"><input value="'.$k.'" type="checkbox"> ' . $v . '</label></div>';
            }
        } else if ($attribute->type == 'radiogroup') {
            $values = json_decode($attribute->value);
            foreach ($values as $k => $v) {
                echo '<div class="radio-inline"><label class="custom-label"><input value="'.$k.'" type="radio"> ' . $v . '</label></div>';
            }
        }
        ?>
    </div>
    <?php
    if ($count % 2 == 0) {
        echo '</div>';

        if ($count < count($attributes)) {
            echo '<div class="form-group">';
        }
    }
    $count++;
    }
    ?>
</div>