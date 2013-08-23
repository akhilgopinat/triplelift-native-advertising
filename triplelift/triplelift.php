<?php  
/* 
Plugin Name: TripleLift Native Advertising 
Plugin URI: http://www.triplelift.com/ 
Version: 1.0
Author: Triple Lift, Inc. 
Description: TripleLift enables integrated native advertising that fits beautifully without the layout of your site
*/  

if(version_compare(PHP_VERSION, '5.3') >= 0) {

    define('TRIPLELIFT_NP_BASE_FILE', 'triplelift/triplelift.php');
    define('TRIPLELIFT_NP_BASE_URL', 'options-general.php?page=triplelift_np_admin');
    define('TRIPLELIFT_NP_API_URL', 'http://api.triplelift.com/');
	define('TRIPLELIFT_NP_CONSOLE_URL', 'http://console.triplelift.com/');
    define('TRIPLELIFT_NP_OPTIONS_OBJECT_FIELD', 'triplelift_np_data');

    $libraries = array(
        'api', 
        'admin_register', 
        'injection', 
        'auth',
        'router',
        'tag_manager'
    );

    foreach ($libraries as $curr_library) {
        include (dirname(__FILE__).'/library/'.$curr_library.'.php');
    }


    $triplelift_np_admin_register = new Triplelift_np_admin_register();
    $triplelift_np_injection_register = new Triplelift_np_injection();


} else {

    $triplelift_np_admin_register = new Triplelift_np_admin_register(true);
}

