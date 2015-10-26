<?php
class Category extends Eloquent
{
    protected $table = 'categories';
    
    private static $result;
    
    private static $parent_cat;
    
    public static $level =1;
    
    public static function getSelectTree($id){

         self::$result = array();
         $parents = self::allParents($id);
         self::childCategories($id); 
         $menuCats = array();
         
         foreach ($parents as $category) {
            $menuCats[] = $category;
         }
 
         foreach (self::$result as $category) {
            $menuCats[]['child'] = $category;    
         }         
         return $menuCats;
    }
    
    public static function childCategories($cat_id)
    {
        $categories = self::where('parent_id', '=', $cat_id)->orderBy('order')->get(array('id','name','cat_img'));        
        foreach ($categories as $category) {
                                  
            if (self::hasChild($category->id)) {
               $final[] = array(
                    'cat_id'    => $category->id,
                    'cat_name'  => $category->name,
                    'cat_img'  =>  $category->cat_img,                    
                    'parent' => 'yes'
                );
            } else {
                 $final[] = array(
                    'cat_id'    => $category->id,
                    'cat_name'  => $category->name,
                    'cat_img'  =>  $category->cat_img,                    
                    'parent' => 'no'
                );
            }
        }
        return $final;        
    }
    
    public static function allParentsExcludes($cat_id=0)
    {
        $category = self::find($cat_id);
        $category->parent_id;
        
        $parents = self::allParents($cat_id, false);
        $children = self::childCategories($category->parent_id);
        return array_merge($parents, $children);
    }
    
        
    public static function allMain($cat_id=0)
    {
        $categories = self::where('parent_id', '=', $cat_id)->orderBy('order')->get();
        $final = array();
        foreach ($categories as $category) {                       
            if (self::hasChild($category->id)) {
                $final[] = array(
                    'cat_id'    => $category->id,
                    'cat_name'  => $category->name,
                    'cat_img'  =>  $category->cat_img,                    
                    'parent' => 'yes',                   
                    'cat_level' => 1
                );
            }
        }
        return $final;        
    }
    
    
    public static function allParents($cat_id=0, $including_me=true)
    {
        self::$parent_cat = array();    
        self::getParents($cat_id);
        $count = count(self::$parent_cat);
        $final = array();
        
        if ($count) {
            foreach (self::$parent_cat as $cat) {
                if ($cat['id'] == $cat_id && !$including_me) {
                    continue;
                }
                    
                $final[] = array(
                    'cat_id'    => $cat['id'],
                    'cat_name'  => $cat['name'],
                    'cat_img'  =>  $cat['cat_img'],                    
                    'cat_level' => ($count--)
                );
            }    
        }        
        return array_reverse($final);
    }    
    
    public static function getTree()
    {
         self::$result = array();
         self::getHomePageChildCategories();
         
         $children = array();
         
         foreach (self::$result as $category) {
            $children[$category->parent_id][] = $category;
         }
        
         foreach (self::$result as $category) {
            if (isset($children[$category->id])) {
                $category->children = $children[$category->id];    
            }
         }
         return $children[0];
    }
    
    public static function getHomePageChildCategories($cat_id=0, $get_grand_children=true)
    {
        $categories = self::where('parent_id', '=', $cat_id)->orderBy('order')->get();
        
        foreach ($categories as $category) {
            self::$result[] = $category;
            
            if (!$get_grand_children) {
                continue;
            }            
            if (self::hasChild($category->id)) {
                self::getHomePageChildCategories($category->id, false);
            }
        }
        return;
    }
    
    public static function getChildCategories($cat_id=0, $get_grand_children=true)
    {
        $categories = self::where('parent_id', '=', $cat_id)->orderBy('order')->get();
        
        foreach ($categories as $category) {
            self::$result[] = $category;
            
            if (!$get_grand_children) {
                continue;
            }            
            if (self::hasChild($category->id)) {
                self::getChildCategories($category->id, $get_grand_children);
            }
        }
        return;
    }
    
    public static function hasChild($cat_id)
    {
        if (self::where('parent_id', '=', $cat_id)->count()) {
            return true;
        }
        return false;
    }
    
    public static function getAllParents($cat_id=0)
    {
        self::$parent_cat = array();
        self::getParents($cat_id);
        $count = count(self::$parent_cat);
        $final = array();
        
        if ($count) {
            foreach (self::$parent_cat as $cat) {
                $final[] = array(
                    'cat_id'    => $cat['id'],
                    'cat_name'  => $cat['name'],
                    'cat_level' => ($count--)
                );
            }    
        }        
        return array_reverse($final);
    }
    
    public static function getParents($cat_id)
    {
        $category = self::find($cat_id);
        self::$parent_cat[] = array('id'=> $category->id, 'name' => $category->name, 'cat_img' => $category->cat_img);  
        if ($category->parent_id) {
            self::getParents($category->parent_id);
        }
        return;         
    }
    //////////////////////////////////////////////////////////////////////////
}
