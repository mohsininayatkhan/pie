<?php
class Ad extends Eloquent
{
    protected $table = 'ads';

    protected $fillable = array(
        'cat_id',
        'unique_id', 
        'categories',
        'category_names',
        'cat_level_1',
        'cat_level_2',
        'cat_level_3',
        'cat_level_4',
        'user_id', 
        'title',
        'slug', 
        'detail',
        'link', 
        'price', 
        'price_negotiable',
        'seller_name', 
        'seller_email', 
        'seller_phone', 
        'seller_phone_public',
        'seller_type', 
        'state_id', 
        'city_id', 
        'town_id',
        'country_id', 
        'featured', 
        'spotlight', 
        'keywords',
        'created_at',
        'status'
    );
    
    //public $timestamps = false;
    
    public static function search($param = array(), $page = null, $limit = null, $with_count = false, $order_by=array(), $random = false, $cat_level_summary=null, $location_summary= array())
    {
        $res = array();

        $ads = DB::table('ads');
        
        $cat_level = '';
        if ($cat_level_summary!=null) {
            $cat_level = 'ads.cat_level_'.$cat_level_summary;
            $ads->select(DB::raw($cat_level.' as category, ads.id, COUNT(ads.id) AS category_count'));
              
        } else if (count($location_summary)) {
            if ($location_summary['type'] == 'cities') {
                $ads->select(DB::raw('cities.city_name as location_name, cities.id as id,  COUNT(ads.id) AS location_count'));    
            } else if ($location_summary['type'] == 'states') {
                $ads->select(DB::raw('states.state_name as location_name, states.id as id,  COUNT(ads.id) AS location_count'));
            } else if ($location_summary['type'] == 'towns') {
                $ads->select(DB::raw('towns.town_name as location_name, towns.id as id,  COUNT(ads.id) AS location_count'));
            }
            
        } else {
            $ads->select(DB::raw('*, ads.id as id, ads.slug as slug, users.slug as user_slug, ads.created_at as posted_date, users.created_at as membership_date, states.id as state_id, cities.id as city_id, towns.id as town_id, ads.created_at, DATEDIFF(NOW(), ads.created_at) as days_expiry_left'));    
        }
        
        $ads->join('users', 'users.id', '=', 'ads.user_id')
            ->join('cities', 'cities.id', '=', 'ads.city_id')
            ->join('states', 'states.id', '=', 'ads.state_id')
            ->join('categories', 'categories.id', '=', 'ads.cat_id')
            ->leftJoin('towns', 'towns.id', '=', 'ads.town_id')
            ->leftJoin('ad_media', function($join) {
                $join->on('ad_media.ad_id', '=', 'ads.id')
                    ->where('ad_media.main', '=', '1');
            }
        );

        if (isset($param['keyword']) && !empty($param['keyword'])) {
        	$replace_from = array("&", ",");
			$replace_to = array("", "");
			
			$param['keyword'] = str_replace($replace_from, $replace_to, $param['keyword']);
			//echo $param['keyword'];
			//die;
			
            $ads->orWhere(function($query) use ($param) {
            	$tokens = explode(" ", $param['keyword']);
				foreach ($tokens as $token) {
					if ($token=='') {
						continue;
					}
					$query->orwhere('title', 'LIKE', '%' . $token . '%')
                    ->orwhere('detail', 'LIKE', '%' . $token . '%')
                    ->orwhere('keywords', 'LIKE', '%' . $token . '%')
                    ->orwhere('category_names', 'LIKE', '%' . $token . '%')
                    ->orwhere('unique_id', 'LIKE', '%' . $token . '%');					
				}
            });
        }

        if (isset($param['city']) && !empty($param['city'])) {
            $ads->where('ads.city_id', '=', $param['city']);
        }

        // if status is not set, display only active ads
        if (isset($param['status']) && !empty($param['status'])) {
            $ads->where('ads.status', '=', $param['status']);
        } else {
            $ads->where('ads.status', '=', 'Active');
        }

        if (isset($param['state']) && !empty($param['state'])) {
            $ads->where('ads.state_id', '=', $param['state']);
        }
        
        if (isset($param['town']) && !empty($param['town'])) {
            $ads->where('ads.town_id', '=', $param['town']);
        }

        if (isset($param['category']) && !empty($param['category'])) {
            $ads->Where(function($query) use ($param) {
                $query->where('ads.cat_id', '=', $param['category'])
                    ->orwhere('ads.categories', 'REGEXP', '(^|,)' . $param['category'] . '($|,)');
            });
        }

        if (isset($param['min_price']) && !empty($param['min_price']) && isset($param['max_price']) && !empty($param['max_price'])) {
            $ads->whereBetween('ads.price', array($param['min_price'], $param['max_price']));
        }
        
        if (isset($param['expiry_days']) && !empty($param['expiry_days'])) {
            $ads->whereRaw('DATEDIFF(NOW(), ads.created_at) < ?', array($param['expiry_days']));
        }
        
        if (isset($param['expired_ads']) && !empty($param['expired_ads'])) {
            $ads->whereRaw('DATEDIFF(NOW(), ads.created_at) > ?', array(Config::get('app.ad_expiry_days')));
        }

        if (isset($param['active_ads']) && !empty($param['active_ads'])) {
            $ads->whereRaw('DATEDIFF(NOW(), ads.created_at) <= ?', array(Config::get('app.ad_expiry_days')));
        }
        
        if (isset($param['categories']) && !empty($param['categories'])) {
            $attributes = Attribute::whereRaw('category_id in (' . implode(',', $param['categories']) . ')')->get();
            
            if (count($attributes)) {
                $count = 0;
                $ads->Where(function($query) use ($ads, $attributes, $param, $count, $cat_level_summary, $location_summary) {
                    foreach ($attributes as $attribute) {
                        $attribute_name = mb_strtolower(str_replace(" ", "_", $attribute->name));
                        
                        $attribute_with_range_found = false;
                        $range = array(
                            'min_range' => 0,
                            'max_range' => 0, 
                        );
                        
                        if ($attribute->search_by_range=='true') {
                            
                            if ((isset($param['attr_min_' . $attribute_name]) && !empty($param['attr_min_' . $attribute_name])) || (isset($param['attr_max_' . $attribute_name]) && !empty($param['attr_max_' . $attribute_name]))) {
                                $attribute_with_range_found = true;
                                
                                if (isset($param['attr_min_' . $attribute_name])) {
                                    $arr = explode("_", $param['attr_min_' . $attribute_name]);
                                    $range['min'] = isset($arr[1]) ? $arr[1] : $arr[0];
                                    $range['min'] = !empty($range['min']) ? $range['min'] : 0; 
                                }
                                
                                if (isset($param['attr_max_' . $attribute_name])) {
                                    $arr = explode("_", $param['attr_max_' . $attribute_name]);
                                    $range['max'] = isset($arr[1]) ? $arr[1] : $arr[0];
                                    $range['max'] = !empty($range['max']) ? $range['max'] : 0;
                                }
                                
                                $range['min'] = isset($param['attr_min_' . $attribute_name]) ? $param['attr_min_' . $attribute_name] : 0; 
                                $range['max'] = isset($param['attr_max_' . $attribute_name]) ? $param['attr_max_' . $attribute_name] : 0;
                            }
                        }
                        
                        if ((isset($param['attr_' . $attribute_name]) && !empty($param['attr_' . $attribute_name])) || $attribute_with_range_found) {
                            if ($count == 0) {
                                $ads->addSelect(DB::raw('CAST(ad_details.attribute_val AS DECIMAL) as decimal_attribute_val'));
                                $ads->join('ad_details', function($join) {
                                    $join->on('ad_details.ad_id', '=', 'ads.id');
                                });
                            }

                            if ($attribute->type == 'text') {
                                $query->orWhere(function($query) use ($attribute, $param, $attribute_name, $attribute_with_range_found, $range) {
                                    $query->where('ad_details.attribute_id', '=', $attribute->id);
                                    
                                    if ($attribute_with_range_found) {
                                        $query->whereBetween(DB::raw('CAST(ad_details.attribute_val AS DECIMAL)'), array($range['min'], $range['max']));
                                    } else {
                                        $arr = explode("_", $param['attr_' . $attribute_name]);
                                        $attr_val = isset($arr[1]) ? $arr[1] : $arr[0];
                                        $query->where('ad_details.attribute_val', '=', $attr_val);
                                    }
                                });
                                $count++;
                            } elseif ($attribute->type == 'checkboxlist' || $attribute->type == 'list' || $attribute->type == 'radiogroup') {
                                $count++;
                                $options = $param['attr_' . $attribute_name];
                                foreach ($options as $option) {
                                    $query->orWhere(function($query) use ($attribute, $param, $attribute_name, $option) {
                                        $arr = explode("_", $option);
                                        $query->where('ad_details.attribute_id', '=', $attribute->id)->where('ad_details.attribute_val', '=', $arr[1]);
                                    });
                                }
                            }
                        }
                    }

                    if ($count > 0) {
                        $match = ($count==1) ? 2: $count-1;
                        $ads->groupBy('ads.id')
                            ->having('ad_count', '>=', $count)
                            ->addSelect(DB::raw('count(ads.id) as ad_count'));
                        
                        if ($cat_level_summary!=null) {
                            $ads->addSelect(DB::raw('1 as cat_attr_flag'));
                        }
                        
                        if ($location_summary!=null) {
                            $ads->addSelect(DB::raw('1 as loc_attr_flag'));
                        }
                    }
                });
            }
        }

        if (isset($param['ad_id']) && !empty($param['ad_id'])) {
            $ads->where('ads.id', '=', $param['ad_id']);
        }
        
        if (isset($param['slug']) && !empty($param['slug'])) {
            $ads->where('ads.slug', '=', $param['slug']);
        }
        
        if (isset($param['user_id']) && !empty($param['user_id'])) {
            $ads->where('ads.user_id', '=', $param['user_id']);
        }

        if (isset($param['users']) && count($param['users']) && is_array($param['users'])) {
            $ads->whereIn('ads.user_id', $param['users']);
        }

        if (isset($param['featured']) && !empty($param['featured'])) {
            $ads->where('ads.featured', '=', '1');
        }

        if (isset($param['seller_type'])) {
            $ads->where('ads.seller_type', '=', $param['seller_type']);
        }
        
        if (isset($param['spotlight']) && !empty($param['spotlight'])) {
             $ads->where('ads.spotlight', '=', '1');
        }
		
		if (isset($param['skip_categories']) && count($param['skip_categories'])) {
             $ads->whereNotIn('ads.cat_id', $param['skip_categories']);
        }
        
        $max_cat_level = 4;
        for ($count=1; $count<=$max_cat_level; $count++) {
            if (isset($param['cat_level_'.$count]) && is_array($param['cat_level_'.$count]) && count($param['cat_level_'.$count])) {
                $ads->whereIn('ads.cat_level_'.$count, $param['cat_level_'.$count]);
            }
        }

        /*if ($page != null) {
            if (!$page || $page < 0) {
                $page = 1;
            }
            $start = ($page - 1) * $limit;
            $ads->skip($start)->take($limit);
        }*/

        if ($with_count) {
            return count($ads->get());
            //return $ads->count();
        }
        
        if ($cat_level_summary!=null) {
            $ads->whereNotNull($cat_level);
            $ads->groupBy($cat_level);            
        }
        
        if (count($location_summary)) {
            
            if ($location_summary['type'] == 'cities') {
                $ads->whereNotNull('ads.city_id');
                $ads->groupBy('ads.city_id');     
            } else if ($location_summary['type'] == 'states') {
                $ads->whereNotNull('ads.state_id');
                $ads->groupBy('ads.state_id');     
            } else if ($location_summary['type'] == 'towns') {
                $ads->whereNotNull('ads.town_id');
                $ads->groupBy('ads.town_id');     
            }
        }
		
        // if set, will order records randomly (regar)
        if ($random) {
           $ads->orderByRaw("RAND()");  
        }
		
		if (Session::has('user_current_location')) {
			$order_by_user_loc = '';
			
			if (isset($current_location[0]['city'])) {
				$order_by_user_loc .= 'ads.city_id = '.$current_location[0]['city'].' desc';
			}
			
			if (isset($current_location[0]['state'])) {
				if ($order_by_user_loc!=='') {
					$order_by_user_loc .= ', ';
				}
				$order_by_user_loc .= 'ads.state_id = '.$current_location[0]['state'].' desc';
			}
		}
        
        if (count($order_by)) {
            foreach ($order_by as $key => $val) {
                $ads->orderBy($key, $val);
            }
        } else {
            $ads->orderBy('ads.created_at', 'desc');
        }
        //$ads->orderByRaw("ads.state_id=2 asc");  
        
        if ($page==null) {
            $res = $ads->get();
        } else {
            $res = $ads->paginate($limit);
        }    
        //$res = $ads->get();
        //$queries = DB::getQueryLog();
        //$last_query = end($queries);
        //if ($cat_level_summary!=null) {
            //print_r($last_query);
        //}
        //print_r($last_query);
        //die;
        return $res;
    }

    public static function similar($param = array(), $limit=4)
    {
        $ads = DB::table('ads');

        $ads->join('cities', 'cities.id', '=', 'ads.city_id')
            ->join('states', 'states.id', '=', 'ads.state_id')
            ->leftJoin('ad_media', function($join) {
                $join->on('ad_media.ad_id', '=', 'ads.id')
                ->where('ad_media.main', '=', '1');
        });
        $ads->where('ads.cat_id', '=', $param['catid'])
            ->where('ads.id', '!=', $param['similarto'])
            ->where('ads.status', '=', 'Active')
            ->skip(0)->take($limit);
        $res = $ads->get();
        return $res;
    }

    public static function getTotal($param = array())
    {
        return count($ads->get());
    }
    
    
    public static function getSlug($title, $id='')
    {
        $slug = Str::slug($title);
        $ad = DB::table('ads');
        
        if ($id == '') {
            $slugCount = count(self::whereRaw("slug REGEXP '^{$slug}(-[0-9]*)?$'")->get());    
        } else {
            $slugCount = count(self::whereRaw("slug REGEXP '^{$slug}(-[0-9]*)?$'")->where('id', '!=', $id)->get());
        }
        return ($slugCount > 0) ? "{$slug}-{$slugCount}" : $slug;
    }

    public static function increaseCount($ad_id, $field='view_count')
    {
         $myad = array();     
         $myad = Ad::find($ad_id);
         
         $myad->$field = ($myad->$field+1);
         $myad->timestamps = false;
         $myad->save(['timestamps' => false, 'touch' => false]);   
         
        //$qry = "UPDATE ads SET $field = $field+1 WHERE id = $ad_id";
        //DB::statement($qry);
    }
    
    public static function getCategoryBreadcrumbUrl($url, $categories)
    {
        $cat_param ='';     
        $cat_ids = array();
        foreach ($categories as $category) {
            $param = '?';
            
            $cat_ids[] = $category['cat_id'];
            $attributes = Attribute::whereRaw('category_id in (' . implode($cat_ids,',') . ')')->orderBy('order')->get();            
            foreach ($attributes as $attribute) {
                $attribute_name = mb_strtolower(str_replace(" ", "_", $attribute->name));                
                foreach ($url as $key => $val) {
                    if ($key == 'attr_'.$attribute_name) {    
                        if (is_array($val)) {
                            foreach($val as $j) {
                                $param .= '&'.$key.'%5B%5D='.$j;
                            }        
                        } else {
                            $param .= '&'.$key.'='.$val;
                        } 
                    } 
                }
            }
            
            foreach ($url as $key => $val) {
                $is_attr = strpos($key, 'attr_');
                if ($is_attr===false && $key!='category') {
                    if (is_array($val)) {
                        foreach($val as $j) {
                            $param .= '&'.$key.'%5B%5D='.$j;
                        }        
                    } else {
                        $param .= '&'.$key.'='.$val;
                    } 
                }   
            }
            $cat_param = '&category='.$category['cat_id'];    
        }

        return $param.$cat_param;
    }
    
    public static function getCategorySummaryUrl($url, $category='')
    {
        $param = '?';
        
        foreach ($url as $key => $val) {
            
            if ($key=='category' || $key == 'page') {
                continue;
            }
            
            // for all categories, also skip the attributes
            if ($category == '' && (strpos($key, 'attr_') !== false  || strpos($key, 'max_') !== false  || strpos($key, 'min_') !== false)) {
                continue;
            }
            
            if (is_array($val)) {
                foreach($val as $j) {
                    $param .= '&'.$key.'%5B%5D='.$j;
                }        
            } else {
                $param .= '&'.$key.'='.$val;    
            }
        }
        
        if ($category!='') {
            $param .= '&category'.'='.$category;    
        }        
        return $param;
    }
    
    public static function getLocationSummaryUrl($url, $type, $id='')
    {
        $skip = array();     
        if ($type == 'state') {
            $skip = array('state', 'city', 'town');
        } else if ($type == 'city') {
            $skip = array('city', 'town');
        } else if ($type == 'town') {
            $skip = array('town');
        } else if ($type == 'all') {
            $skip = array('state', 'city', 'town'); 
        }
        
        $param = '?';
        foreach ($url as $key => $val) {
            
            if (in_array($key, $skip)) {
                continue;
            }
            
            if (is_array($val)) {
                foreach($val as $j) {
                    $param .= '&'.$key.'%5B%5D='.$j;
                }        
            } else {
                $param .= '&'.$key.'='.$val;    
            }
        }
        
        if ($type!=='all') {
            $param .= '&'.$type.'='.$id;    
        }
        
        return $param;
    }
    
    public static function getLocationSummary($data)
    {
        $summary = array();        
        $is_duplication = false;
        
        if (count($data)) {
            
            $i =0;
            foreach ($data as $location) {
                if (empty($location->id)) {
                    continue;    
                }
                
                if (isset($summary[$location->id])) {
                    $is_duplication = true;
                    break;    
                } else {
                    if (isset($location->loc_attr_flag)) {
                        $summary[$location->id]= ++$i;
                    } else {
                        $summary[$location->id]= $location->location_count;    
                    }    
                }
            }
        }
        
        if ($is_duplication) {
           $summary = array();
           foreach ($data as $location) {
              if (empty($location->id)) {
                  continue;    
              }
              
              if (isset($summary[$location->id])) {
                  $summary[$location->id]++;
              } else {
                  $summary[$location->id] = 1;
              }
           }  
        }
        return $summary;
    }

    public static function getCategorySummary($data)
    {
        $summary = array();
        $is_duplication = false;
        if (count($data)) {
            
        	foreach ($data as $cat) {
                if (empty($cat->category)) {
                    continue;    
                }
                
                $cat_id = ($cat->category==0) ? $data['category_id'] : $cat->category;
                
                if (isset($summary[$cat_id])) {
                    $is_duplication = true;
                    break;    
                } else {
                    if (isset($cat->cat_attr_flag)) {
                        if (isset($summary[$cat_id])) {
							$summary[$cat_id]++;
						} else {
							$summary[$cat_id] = 1;
						}
                    } else {
                        $summary[$cat_id]= $cat->category_count;    
                    }
                }
            }
        }
        
        if ($is_duplication) {
        	$summary = array();
        	foreach ($data as $cat) {
        		//echo $cat->category.'<br>';
          		if (empty($cat->category)) {
              		continue;    
          		}
          
          		if (isset($summary[$cat->category])) {
              		$summary[$cat->category]++;
          		} else {
              		$summary[$cat->category] = 1;
          		}
       		}  
        }
        return $summary;
    }
}