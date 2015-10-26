{{ Form::open(array('url' => 'search','name'=> 'frmMainSearch','id'=>'frmMainSearch', 'method' => 'GET')) }}
<?php 
if (Session::has('user_current_location')) {
	$current_location = Session::get('user_current_location');
}
?> 
<input type="hidden" name="category" id="category" value=""/>
<input type="hidden" name="state" id="state" value="<?php if(isset($current_location[0]['state'])){ echo $current_location[0]['state'];}?>"/>
<input type="hidden" name="city" id="city" value="<?php if(isset($current_location[0]['city'])){ echo $current_location[0]['city'];}?>"/>


<div class="jumbotron">
  <div id="home-search-form" class="row">    
            <div class="col-sm-3 search-cat">
                <div class="form-group">
                    
                        <div class="searchField">
                            <input type="text" name="keyword" id="keyword" class="form-control txtbox-search" id="txt-seach" placeholder="Searching for">
                        </div>
                      
                        <!-- Clickable Nav -->
                        <div class="click-nav" id="categories">
                            <ul class="no-js">
                                <li>
                                    <a class="clicker" href="javascript:void(0);" onclick="hideSelect();"><i class="glyphicon glyphicon-ok"></i> All Categories</a>
                                    <ul class="nav-option-section">
                                        <?php
                                        foreach ($categories as $category) {
                                        ?>
                                            <li class="nav-option">
                                                <a href="javascript:void(0);" onclick="showCats('{{ $category->id }}','plus')">
                                                    <i class="fa {{ $category->cat_img }} fa-lg"></i> 
                                                    {{ $category->name }}
                                                </a>
                                                <i class="red-nav glyphicon glyphicon-plus" onclick="showCats('{{ $category->id }}','plus')"></i>
                                            </li>
                                        <?php
                                        }
                                        ?>                                             
                                    </ul>
                                </li>
                            </ul>
                        </div>
                        <!-- /Clickable Nav -->                            

                </div>
            </div>

            <div class="col-sm-4">
                <div class="form-group">
                    <div class="input-group">
                        <!--<div class="searchField">
                            <input type="text" name="keyword" id="keyword" class="form-control txtbox-search" id="txt-seach" placeholder="All Pakistan">
                        </div>                        
                        <span class="input-group-addon">in</span> -->
                        <!-- Clickable Nav -->
                        <div class="click-nav" id="location">
                            <ul class="no-js">
                                <li>
                                    <a class="clicker"><i class="glyphicon glyphicon-ok"></i> All Locations</a>
                                </li>
                                <div class="nav-states">
                                <li class="nav-option-section">
                                    <ul class="location-options">
                                        <?php
                                        foreach ($states as $state) {
                                        ?>
                                                <li class="nav-option">
                                                    <a href="javascript:void(0);" onclick="showLocations('{{ $state->id }}','state')">{{ $state->state_name }}
                                                    <i class="red-nav glyphicon glyphicon-plus"></i>
                                                    </a>
                                                    
                                                </li>
                                        <?php
                                        }
                                        ?>                                             
                                    </ul>
                                </li>
                                </div>
                            </ul>
                        </div>
                        <!-- /Clickable Nav -->                    
                    
                        <!--<select id="location-sel">
                            <option value="">All Locations</option>
                            <?php 
                            /*foreach ($cities as $city) {
                                echo '<option value="' . $city->id . '">' . $city->city_name . '</option>';
                            }*/
                            ?>                       
                        </select>                    -->
                    </div>
                </div>
            </div>
            
            <div class="col-sm-1">
                <button type="submit" class="btn btn-danger">
                    <span class="glyphicon glyphicon-search"></span> Go
                </button>
            </div>
        </div>
  <center>
  <p class="lead">Pakistan's 1st social classified site</p>
  <p><a class="btn btn-danger btn-lg" href="<?php echo URL::to('/').'/create-ad'?>" role="button">Post Ad</a></p></center>
</div>
<!--div class="vertical-center "><img src="{{ URL::to('/'); }}/img/__banner.png"></div -->

{{ Form::close() }}