<div class="row">
    <div class="col-sm-12">
        <h3 class="light-heading">Popular Cities</h3>
    </div>
</div>

<div class="popular-cities row">
    <div class="col-sm-12">
        <ul class="list-group col-sm-12 col-sm-3">
            <?php
            if (isset($popular_cities)) {
                $num_of_columns = 4;
                $city_per_column = ceil(count($popular_cities)/$num_of_columns);
                $i=1;
                foreach ($popular_cities as $city) {
                    //echo '<li class="list-group-item">'.$child->name.'</li>';
                    echo '<a href="'.URL::to('/').'/search?city='. $city->id. '" class="list-group-item">'. $city->city_name.'</a>';
                    
                    if ($i%$city_per_column == 0) {
                        echo '</ul>';
                         if ($i < count($popular_cities)) {
                             echo '<ul class="list-group col-sm-12 col-sm-3">';
                         }
                    }
                    $i++;
                }
                if (($i-1)%$city_per_column != 0) {
                    echo '</ul>';    
                }
            }
            ?>              
        
    </div>
</div>