<?php

class Triplelift_np_injection {

	public $options_field = TRIPLELIFT_NP_OPTIONS_OBJECT_FIELD, $options_object, $tags;
    public $post_count = 0, $eligible_tags;

	public $debug = false;
	public $debug_output = array();

    public $wp_fields =  array(
        'is_home',
        'is_front_page',
        'is_404',
        'is_archive',
        'is_author',
        'is_category',
        'is_comments_popup',
        'is_search',
        'is_singular',
        'is_tag',
        'is_time',
        'is_year',
    );




    function __construct() {
        $this->options_object = get_option($this->options_field);
		$this->tags = array();
        if (is_array($this->options_object['tags'])) {
    		foreach ($this->options_object['tags'] as $curr_elt) {
	    		if (!isset($curr_elt['deleted']) || !$curr_elt['deleted']) {
		    		$this->tags[] = $curr_elt;
			    }
    		}
        }
		if (isset($this->options_object['debug_mode']) && $this->options_object['debug_mode'] && isset($_GET['tripleliftDebug']) && $_GET['tripleliftDebug'] ) {
			$this->debug = true;
			$this->debug_output['options_object'] = $this->options_object;
			$this->debug_output['time_started'] = date("Y-m-d H:i:s");
			$this->debug_output['theme'] = get_current_theme();
			$this->debug_output['blog_title'] = get_bloginfo('name');
			$this->debug_output['url'] = get_bloginfo('url');
			$this->debug_output['charset'] = get_bloginfo('charset');
			$this->debug_output['wpversion'] = get_bloginfo('version');
			$this->debug_output['all_tags'] = $this->tags;
			$this->debug_output['tag_debug_log'] = array();
			$this->debug_output['post_debug_log'] = array();
			$this->debug_output['path'] = parse_url(get_bloginfo( 'url' ), PHP_URL_PATH);
			add_action( 'wp_footer' , array('triplelift_np_injection', 'debug_output' ) );
		}
        $this->find_eligible_tags();
		add_action ( 'init' , array('triplelift_np_injection', 'inject_init' ) );

    }	

    function find_eligible_tags() {
        $this->eligible_tags = array();
        $path = parse_url(get_bloginfo( 'url' ), PHP_URL_PATH);
	    if (is_array($this->tags)) {	
            foreach ($this->tags as $curr_tag) {
                $ineligible = false;
                $path_match = false;
	    		if ($this->debug) {
		    		$curr_tag_debug = array();
			    	$curr_tag_debug['admin_preview'] = $curr_tag['admin_preview'];
				    $curr_tag_debug['is_admin'] = is_admin();
    				$curr_tag_debug['active'] = $curr_tag['active'];
	    			$curr_tag_debug['tag'] = $curr_tag;
				
		    	}
		
                if ((($curr_tag['admin_preview'] && is_admin()) || !$curr_tag['admin_preview']) && $curr_tag['active'])  {
                	if ($this->debug) {$curr_tag_debug['branch_1'] = true;}
                    if (!$ineligible && !$to_add) {
                    	
                    	if ($this->debug) {$curr_tag_debug['branch_2'] = true;}
                        if (is_array($curr_tag['exclude_path']) && count($curr_tag['exclude_path'])) {
                    	
                        	if ($this->debug) {$curr_tag_debug['branch_3'] = true;}
                            if (is_array($curr_tag['exclude_path'])) {
                                foreach ($curr_tag['exclude_path'] as $curr_path) {
                                    if (strlen($curr_path) && strpos($path,$curr_path) !== false) {
                                            
                                        if ($this->debug) {$curr_tag_debug['branch_4'] = true;}
                                        $ineligible = true;
                                        break;
                                    }
                                }
                            }
                    }
                    if (is_array($curr_tag['include_path']) && count($curr_tag['include_path'])) {
                    		
                    	if ($this->debug) {$curr_tag_debug['branch_5'] = true;}
                        foreach ($curr_tag['include_path'] as $curr_path) {
                            if (strlen($curr_path) && strpos($path,$curr_path) !== false) {
                            		
                            	if ($this->debug) {$curr_tag_debug['branch_6'] = true;}
                                $path_match = true;
                                break;
                            }
                        }
                    }
                }
	
				if ($this->debug) {$curr_tag_debug['post_ineligible'] = $ineligible;}
                if (!$ineligible) $this->eligible_tags[] = array(
                	'script' => $curr_tag['script'], 
                	'hook' => $curr_tag['hook'], 
                	'path_match' => $path_match, 
                	'interval' => $curr_tag['interval'], 
                	'offset' => (isset($curr_tag['offset']) ? $curr_tag['offset'] : 'n/a'), 
                	'wp_page_type_exclude' => $curr_tag['wp_page_type_exclude'], 
                	'wp_page_type_include' => $curr_tag['wp_page_type_include']
                );
            }
           	if ($this->debug) {
           		array_push($this->debug_output['tag_debug_log'], $curr_tag_debug);
       			$this->debug_output['eligible_tags'] = $this->eligible_tags;
			}
			}
        }
    }

	function debug_output() {
		global $triplelift_np_injection_register;
		print '<script>console.log('. json_encode($triplelift_np_injection_register->debug_output).')</script>';
	}

    function inject_native_ad($content, $hook_type) {
        // assumes php 5.3+        
        global $triplelift_np_injection_register;
        $injection =& $triplelift_np_injection_register;
		if ($injection->debug) {
			$curr_post_debug = array();
			$curr_post_debug['content'] = $content;
			$curr_post_debug['hook_type'] = $hook_type;
			$curr_post_debug['page_post_type'] = array(
				'is_home' => is_home(),
				'is_front_page' => is_front_page(),
				'is_404' => is_404(),
				'is_archive' => is_archive(),
				'is_author' => is_author(),
				'is_category' => is_category(),
				'is_comments_popup' => is_comments_popup(),
				'is_search' => is_search(),
				'is_singular' => is_singular(),
				'is_tag' => is_tag(),
				'is_time' => is_time(),
				'is_year' => is_year()
			);
			$curr_post_debug['eligible_tag_debug_log'] = array();
			$curr_eligible_tag = array();
		}
        if (!is_array($injection->eligible_tags)) {
            $injection->eligible_tags = array();
        }
        foreach ($injection->eligible_tags as $curr_tag) {
            $include_match = false;
            $ineligible = false;
			if ($injection->debug) {
				$curr_eligible_tag = array();
				$curr_eligible_tag['tag'] = $curr_tag;	
			}
            if ($curr_tag['hook'] == $hook_type) {
            	
            	if ($injection->debug) {$curr_eligible_tag['branch_1'] = true;}
                if (!is_array($injection->wp_fields)) {
                    $injection->wp_fields = array();
                }
                foreach ($injection->wp_fields as $curr_field) {
                	if ($injection->debug) {$curr_eligible_tag[$curr_field] = $curr_field();}
                    if ($curr_tag['wp_page_type_exclude'][$curr_field] && $curr_field()) {
                    	
						if ($injection->debug) {$curr_eligible_tag['branch_2'] = true;}
                        $ineligible = true;
                        break;
                    }
                    if ($curr_tag['wp_page_type_include'][$curr_field] && $curr_field()) {
                    	
						if ($injection->debug) {$curr_eligible_tag['branch_3'] = true;}
                        $include_match = true;
                        break;
                    }
                }

                if (!$ineligible && ($include_match || $curr_tag['path_match'])) {
                	
					if ($injection->debug) {$curr_eligible_tag['branch_4'] = true;}
                    // we got a match
                    // are we doing offset stuff
                    if (isset($curr_tag['offset']) && $curr_tag['offset'] != 'n/a') {
                    	
						if ($injection->debug) {$curr_eligible_tag['branch_5'] = true;}
						if ($injection->post_count <= $curr_tag['offset']) {
							
							if ($injection->debug) {$curr_eligible_tag['branch_6'] = true;}
							if ($injection->post_count == $curr_tag['offset']) {
								
								if ($injection->debug) {$curr_eligible_tag['branch_7'] = true;} 
		                        $injection->post_count++;
		                        return $content.html_entity_decode(stripslashes($curr_tag['script']));
		                    }	
						} else {
							
							if ($injection->debug) {$curr_eligible_tag['branch_8'] = true;}
		                    if ( ($injection->post_count - $curr_tag['offset']) % $curr_tag['interval'] == 0 && $injection->post_count > 0) {
		                    	
								if ($injection->debug) {$curr_eligible_tag['branch_9'] = true;} 
		                        $injection->post_count++;
		                        return $content.html_entity_decode(stripslashes($curr_tag['script']));
		                    }
							
						}
                    } else {
                    	
						if ($injection->debug) {$curr_eligible_tag['branch_10'] = true;}
	                    if ($injection->post_count % $curr_tag['interval'] == 0 && $injection->post_count > 0) {
	                    	
							if ($injection->debug) {$curr_eligible_tag['branch_11'] = true;} 
	                        $injection->post_count++;
	                        return $content.html_entity_decode(stripslashes($curr_tag['script']));
	                    }
                    }
                    
                    $injection->post_count++;
                    break;
                }

            }
			if ($injection->debug) {
				array_push($curr_post_debug['eligible_tag_debug_log'], $curr_eligible_tag);
			}
        }
        if ($injection->debug) {
    		array_push($injection->debug_output['post_debug_log'], $curr_post_debug);
        }
        return $content;
    }

    function inject_native_ad_content($content) {
        $hook_type = 'content';
        return Triplelift_np_injection::inject_native_ad($content, $hook_type); 
    }

    function inject_native_ad_excerpt($content) {
        $hook_type = 'excerpt';
        return Triplelift_np_injection::inject_native_ad($content, $hook_type); 
    }

	function inject_native_ad_title($content) {
        $hook_type = 'title';
        return Triplelift_np_injection::inject_native_ad($content, $hook_type); 
    }


    function inject_init() {
        add_action("the_content" , array('triplelift_np_injection', 'inject_native_ad_content' ));
        add_action("the_excerpt" , array('triplelift_np_injection', 'inject_native_ad_excerpt' ));
        add_action("the_title"   , array('triplelift_np_injection', 'inject_native_ad_title' ));
    }

}
