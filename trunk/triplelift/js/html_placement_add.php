<script>
function triplelift_np_admin_html_placement_add(target_div, error_message) {
	if (typeof error_message !== 'undefined') {
		error_message = '<div class="updated" id="message"><p><strong>Error</strong>: '+error_message+'</p></div>';
	} else {
		error_message = '';
	}
	var main_text ='<a href="#" onclick="triplelift_np_admin_add_tag(\''+target_div+'\')">Go back</a><br>&nbsp;<br><form name="logout" method="post" action="options-general.php?page=triplelift_np_admin"><input type="hidden" name="<?php print $this->action_field?>" value="new_html_placement">Please paste the tag below:<br><textarea rows="8" cols="75" id="triplelift_np_admin_html_placement_value" name="triplelift_np_admin_html_placement_value"></textarea><br><input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Submit') ?>" /></form>';
    jQuery("#"+target_div).html(error_message + main_text);
}


</script>
