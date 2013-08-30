<?php

class Triplelift_np_admin_router {

    public $options_field = 'triplelift_np_data';
    public $action_field = 'triplelift_np_admin_action';
    public $options_object, $active, $initialized, $tags, $theme, $blog_host;

	public $tabs = array( 'manage_tags' => 'Manage Tags', 'new_tag' => 'Create New Tag');
	public $active_page = 'manage_tags';

    function inactive_options() {	
        include(dirname(__FILE__).'/../html/inactive.php');
    }

    function new_install() {
        include(dirname(__FILE__).'/../html/new_install.php');
    }

	function render_tabs() {
        include(dirname(__FILE__).'/../html/tabs.php');
	}

    function route() {
        include(dirname(__FILE__).'/../html/test_adblock.php');
        // fresh install
        if (!$this->initialized) {
            $this->new_install();
            $this->options_object['initialized'] = true;
            update_option( $this->options_field, $this->options_object );
        }
		// if the plugin has been deactivated from showing anything
        elseif (!$this->active && !(isset($_POST[$this->action_field]) && $_POST[$this->action_field] != 'activate' ) ) {
            $this->inactive_options();
			$this->options_field['active'] = true;	
            update_option( $this->options_field, $this->options_object );

		// otherwise
        } else {
			$page_action = 'none';
			$page_include = '';
			if (!isset($_POST[$this->action_field])) {
				if (isset($_GET[$this->action_field])) {
					$_POST[$this->action_field] = $_GET[$this->action_field];
				}
				elseif (isset($_GET['tab'])) {
					$_POST[$this->action_field] = $_GET['tab'];
				}
			}
            switch ($_POST[$this->action_field]) {
				// new html placement
				case 'new_html_placement':

					$this->active_page = 'new_tag';
					$script = isset($_POST['triplelift_np_admin_html_placement_value']) ? htmlspecialchars($_POST['triplelift_np_admin_html_placement_value']) : ''; 
					if (!strpos($script, 'script')) {
						$page_action = 'include';
						$page_include = 'html/canvas_error_function_call.php';
						$this->canvas = '<div id="triplelift_np_admin_canvas"></div>';
						$this->function_call =  ' triplelift_np_admin_html_placement_add("triplelift_np_admin_canvas", "Invalid placement. Please contant support@triplelift.com if you have any questions."); ';
						$this->includes = array('js/add_tag.php');
					} else {
						if ($this->tag_manager->tag_exists($script)) {
							$page_action = 'include';
							$page_include = 'html/canvas_error_function_call.php';
							$this->canvas = '<div id="triplelift_np_admin_canvas"></div>';
							$this->function_call =  ' triplelift_np_admin_html_placement_add("triplelift_np_admin_canvas", "You have already added this placement. You can manage its settings under Manage Tags above."); ';	
							$this->includes = array('js/add_tag.php');

						} else {
							$this->tag_settings = $this->tag_manager->add_tag($script);	
							$this->active_page = 'manage_tags';
							$page_action = 'include';
							$page_include = 'html/manage_single_tag.php';
							$this->heading_message = 'Success! Your tag has been added';
						}
					}	

					break;

				case 'new_placement_from_theme':
					$script = isset($_GET['triplelift_np_admin_script']) ? stripslashes(htmlspecialchars($_GET['triplelift_np_admin_script'])) : '';
					$wp_page_type_include = isset($_GET['triplelift_np_admin_wp_type_include']) ? $_GET['triplelift_np_admin_wp_type_include' ] : '';
					$wp_page_type_exclude = isset($_GET['triplelift_np_admin_wp_type_exclude']) ? $_GET['triplelift_np_admin_wp_type_exclude' ] : '';
					$include_path = isset($_GET['triplelift_np_admin_include_path']) ? $_GET['triplelift_np_admin_include_path' ] : '';
					$exclude_path = isset($_GET['triplelift_np_admin_exclude_path']) ? $_GET['triplelift_np_admin_exclude_path' ] : '';
					$interval = isset($_GET['triplelift_np_admin_interval']) ? $_GET['triplelift_np_admin_interval' ] : '';
					$hook = isset($_GET['triplelift_np_admin_hook']) ? $_GET['triplelift_np_admin_hook' ] : '';

					if ($this->tag_manager->tag_exists($script)) {
						$page_action = 'include';
						$page_include = 'html/canvas_error_function_call.php';
						$this->canvas = '<div id="triplelift_np_admin_canvas"></div>';
						$this->function_call =  ' triplelift_np_admin_html_placement_add("triplelift_np_admin_canvas", "You have already added this placement. You can manage its settings under Manage Tags above."); ';	
						$this->includes = array('js/add_tag.php');
					} else {
						$this->tag_manager->add_tag_from_theme($script, $wp_page_type_include, $wp_page_type_exclude, $include_path, $exclude_path, $interval, $hook);
						$this->active_page = 'manage_tags';
						$page_action = 'include';
						$page_include = 'html/manage_single_tag.php';
						$this->tag_settings = $this->tag_manager->get_tag_settings_by_script($script);
                        $this->heading_message = 'Tag added from theme';
					}
					break;

				case 'delete_tag':
					$this->active_page = 'manage_tags';
					$this->flash_message = 'Tag deleted';
					$this->error = true;	
					$page_action = 'include';
					$page_include = 'html/manage_tags.php';
					$this->tag_manager->delete_tag($_GET['tag']);
					break;

				case 'modify_single_tag_start':
					$this->active_page = 'manage_tags';
					$page_action = 'include';
					$page_include = 'html/manage_single_tag.php';
					$this->tag_settings = $this->tag_manager->get_tag_settings_by_script($_GET['tag']);
					break;

				case 'modify_single_tag':
					$this->active_page = 'manage_tags';
					$this->tag_manager->validate_required_fields();
					$this->tag_manager->validate_post_tag_exists();
					$this->tag_manager->validate_modified_tag();
					$this->tag_manager->update_tag();

					if (!$this->tag_manager->error) {
						$this->tag_settings = $this->tag_manager->updated_tag_settings;
						$page_action = 'include';
						$page_include = 'html/manage_single_tag.php';
						$this->heading_message = 'Success! Your tag has been modified';
					} else {
						print $this->tag_manager->error_message;die;
					}
	
					break;


                case 'activate':
                    $this->new_install();
                    $this->options_object['active'] = true;
                    update_option( $this->options_field, $this->options_object );

                    break;

                case 'new_tag':
					$this->active_page = 'new_tag';
					$page_action = 'include';
					$page_include = 'html/new_tag.php';
					break;

               case 'manage_tags':
					$page_action = 'include';
					$page_include = 'html/manage_tags.php';
					break;

                default:
					if (count($this->tags) == 0) {
						$this->active_page = 'new_tag';
						$page_action = 'include';
						$page_include = 'html/new_tag.php';
					} else {
						$page_action = 'include';
						$page_include = 'html/manage_tags.php';
					}
                    break;
            }
			$this->render_tabs();
			if ($page_action == 'include') {
				include(dirname(__FILE__).'/../'.$page_include);
			}
        }
    }


    function __construct($options_object) {
        $this->options_object = $options_object;
        $this->initialized = (isset($this->options_object['initialized']) ? $this->options_object['initialized'] : false);
        $this->active = (isset($this->options_object['active']) ? $this->options_object['active'] : true);

        $this->tags = (isset($this->options_object['tags']) ? $this->options_object['tags'] : array() );
        
        $this->theme = get_current_theme();
        $this->blog_host = parse_url(get_bloginfo( 'url' ), PHP_URL_HOST);

		$this->tag_manager = new Triplelift_np_admin_tag_manager($this->options_object, $this->options_field);

    }

}

