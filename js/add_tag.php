<?php
include_once(dirname(__FILE__).'/../js/placement_select.php');
include_once(dirname(__FILE__).'/../js/html_placement_add.php');
include_once(dirname(__FILE__).'/../js/theme_placement_add.php');
?>
<script>
function triplelift_np_admin_add_tag(target_div) {
    jQuery("#"+target_div).html('Please select from the following: <br><div class="fancylist"><ol><li><a href="#" id="triplelift_np_admin_sent_html">TripleLift sent me tags, which look like this:</a><pre style="font-size: 11px">&lt;script src="http://ib.3lift.com/ttj?inv_code=sample_code&member=123456789"&gt;&lt;/script&gt;</pre></li><li><a href="#" id="triplelift_np_admin_theme_placement">I would like this plugin to make and determine the most appropriate placement for my theme</a></li><li><a href="#" id="triplelift_np_admin_existing_placement">I have an existing placement that I would like to use (advanced)</a></li></ol></div>');
    
	jQuery("#triplelift_np_admin_existing_placement").click(function() {
    	triplelift_np_admin_load_publishers(target_div, false);
	});

	jQuery("#triplelift_np_admin_sent_html").click(function() {
    	triplelift_np_admin_html_placement_add(target_div);
	});

	jQuery("#triplelift_np_admin_theme_placement").click(function() {
    	triplelift_np_admin_theme_placement_add(target_div);
	});
}

</script>
