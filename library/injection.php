<?php

class Triplelift_np_injection {

	public $options_field = TRIPLELIFT_NP_OPTIONS_OBJECT_FIELD, $options_object, $tags;
    public $post_count = 0, $eligible_tags;

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
		foreach ($this->options_object['tags'] as $curr_elt) {
			if (!isset($curr_elt['deleted']) || !$curr_elt['deleted']) {
				$this->tags[] = $curr_elt;
			}
		}
        $this->find_eligible_tags();
		add_action ( 'init' , array('triplelift_np_injection', 'inject_init' ) );
    }	

    function find_eligible_tags() {
        $this->eligible_tags = array();
        $path = parse_url(get_bloginfo( 'url' ), PHP_URL_PATH);

        foreach ($this->tags as $curr_tag) {
            $ineligible = false;
            $path_match = false;
            if ((($curr_tag['admin_preview'] && is_admin()) || !$curr_tag['admin_preview']) && $curr_tag['active'])  {
                if (!$ineligible && !$to_add) {
                    if (is_array($curr_tag['exclude_path']) && count($curr_tag['exclude_path'])) {
                        foreach ($curr_tag['exclude_path'] as $curr_path) {
                            if (strlen($curr_path) && strpos($path,$curr_path) !== false) {
                                $ineligible = true;
                                break;
                            }
                        }
                    }
                    if (is_array($curr_tag['include_path']) && count($curr_tag['include_path'])) {
                        foreach ($curr_tag['include_path'] as $curr_path) {
                            if (strlen($curr_path) && strpos($path,$curr_path) !== false) {
                                $path_match = true;
                                break;
                            }
                        }
                    }

                }

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
           
        }
    }

    function inject_native_ad($content, $hook_type) {
        // assumes php 5.3+        
        global $triplelift_np_injection_register;
        $injection =& $triplelift_np_injection_register;
        foreach ($injection->eligible_tags as $curr_tag) {
            $include_match = false;
            $ineligible = false;
            if ($curr_tag['hook'] == $hook_type) {
                foreach ($injection->wp_fields as $curr_field) {
                    if ($curr_tag['wp_page_type_exclude'][$curr_field] && $curr_field()) {
                        $ineligible = true;
                        break;
                    }
                    if ($curr_tag['wp_page_type_include'][$curr_field] && $curr_field()) {
                        $include_match = true;
                        break;
                    }
                }

                if (!$ineligible && ($include_match || $curr_tag['path_match'])) {
                    // we got a match
                    // are we doing offset stuff
                    if (isset($curr_tag['offset']) && $curr_tag['offset'] != 'n/a') {
						if ($injection->post_count <= $curr_tag['offset']) {
							if ($injection->post_count == $curr_tag['offset']) { 
		                        $injection->post_count++;
		                        return $content.html_entity_decode(stripslashes($curr_tag['script']));
		                    }	
						} else {
		                    if ( ($injection->post_count - $curr_tag['offset']) % $curr_tag['interval'] == 0 && $injection->post_count > 0) { 
		                        $injection->post_count++;
		                        return $content.html_entity_decode(stripslashes($curr_tag['script']));
		                    }
							
						}
                    } else {
	                    if ($injection->post_count % $curr_tag['interval'] == 0 && $injection->post_count > 0) { 
	                        $injection->post_count++;
	                        return $content.html_entity_decode(stripslashes($curr_tag['script']));
	                    }
                    }
                    

                    $injection->post_count++;
                    break;
                }

            }
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

    function inject_init() {
        add_action("the_content" , array('triplelift_np_injection', 'inject_native_ad_content' ));
        add_action("the_excerpt" , array('triplelift_np_injection', 'inject_native_ad_excerpt' ));
    }

}
