<ul class="no-js">
    <li>
        <a id="all-locations" class="clicker active" href="javascript:void(0);" onclick="hideLocation();">
            <i class="glyphicon glyphicon-ok"></i> All Locations
        </a>
    </li>

    <li>
        <ul class="location-options" style="display:block;" id="{{ (isset($cities))?'city-list':'' }}"> 
            
            <?php
            if (isset($states) && count($states))
            {
               if(!isset($cities)) {
                   echo '<div class="nav-states">';
               }
                $c = 1;
                foreach ($states as $state) {
                    $opt = 'state';
                    $cls = 'plus';
                    if(isset($cities)){
                        $opt = 'minus';
                        $cls = 'minus';
                    }
                   
            ?>
                <li id="{{ (isset($cities))?'selstate':'' }}">
                        
                        @if(!isset($cities))
                            <a href="javascript:void(0);" onclick="showLocations('{{ $state->id }}','{{ $opt }}')">
                                {{ $state->state_name }}
                                <i class="red-nav glyphicon glyphicon-{{ $cls }}" onclick="showLocations('{{ $state->id }}','{{ $opt }}')"></i>
                            </a>
                        @else
                        <div class="nav-all-cities">
                            <a href="javascript:void(0);" id="wholestate" onclick="selectedLoc('{{ $state->id }}','state');">
                                <strong>All in {{ $state->state_name }}</strong>
                                <i class="red-nav glyphicon glyphicon-{{ $cls }}" onclick="showLocations('{{ $state->id }}','{{ $opt }}')"></i>
                                
                            </a>
                        </div>
                        @endif
                    
                </li>
                
            <?php
                    $c++;
                }
                if(!isset($cities)) {
                   echo '</div>';
               }
            }
            ?>            
             
            
            <?php
            if( isset($cities)) {
                $c=1;
                echo '<div class="nav-cities">';
                foreach ($cities as $city) {
                ?>                    
                <li>
                    <a data-state="{{ $state_id }}" id="city_{{ $city->id }}" href="javascript:void(0);" onclick="selectedLoc('{{ $city->id }}','city');">
                        <i class="glyphicon"></i> 
                        {{ $city->city_name }}
                    </a>
                </li>
                <?php
                }
                echo '</div>';
            }
            ?>

        </div>                            
        </ul>
    </li>
</ul>
