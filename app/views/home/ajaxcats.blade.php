<ul class="no-js">
    <li>
        <a class="clicker cat-clicker active" href="javascript:void(0);" onclick="hideSelect();">
            <i class="glyphicon glyphicon-ok"></i> All Categories
        </a>
        <ul class="nav-option-section" style="display:block;">
            <?php
            $c = 1;
            foreach ($parents as $category) {
                $opt = 'minus';
                $cls = 'minus';
                if (isset($category['parent'])) {
                    $opt = 'plus';    
                    $cls = 'plus';
                } elseif ($c==1) {
                    $opt = 'main';
                }
            ?>
                
                <li class="nav-option">
                    <a href="javascript:void(0);" onclick="showCats('{{ $category['cat_id'] }}','{{ $opt }}')">
                        <i class="fa {{ $category['cat_img'] }} fa-lg"></i> 
                        {{ $category['cat_name'] }}
                    </a>
                    <i class="red-nav glyphicon glyphicon-{{ $cls }}" onclick="showCats('{{ $category['cat_id'] }}','{{ $opt }}')"></i>
                </li>

            <?php
                $c++;
            }
            ?>                                             
           

            <?php
            if (isset($categories)){
                foreach ($categories as $cat) {
                    $opt = 'plus';
                    if ($cat['parent']=='no') {
                    ?>
                    <li class="nav-option">
                        <a id="selected_{{ $cat['cat_id'] }}" href="javascript:void(0);" onclick="selectedText('{{ $cat['cat_id'] }}');">
                            <i class="glyphicon"></i> 
                            {{ $cat['cat_name'] }}
                        </a>
                   </li>                         
                <?php            
                    } else {
                ?>
                    <li class="nav-option">
                        <a href="javascript:void(0);" onclick="showCats('{{ $cat['cat_id'] }}','plus')">
                            <i class="fa {{ $cat['cat_img'] }} fa-lg"></i> 
                            {{ $cat['cat_name'] }}
                        </a>
                        <i class="red-nav glyphicon glyphicon-{{ $opt }}" onclick="showCats('{{ $cat['cat_id'] }}','plus')"></i>
                    </li>                        
                <?php
                    }
                }
            }
            ?>
        </ul>
    </li>
</ul>