<?php
if (count($this->tags) ==  0) {
	print '<br><div class="updated" id="message"><p><strong>Error:</strong> You have no tags associated with your WordPress account. Please click <a href="options-general.php?page=triplelift_np_admin&tab=new_tag">create new tag</a> above to begin.</p></div>';
} else {
	if (isset($this->flash_message) && $this->flash_message) {
		print '<br><div class="updated" id="message"><p>'.$this->flash_message.'</p></div>';
	}
	if (isset($this->error_message) && $this->error_message) {
		print '<br><div class="updated" id="message"><p><strong>Error:</strong> '.$this->error_message.'</p></div>';
	}
	$tags = array_reverse($this->tag_manager->get_tags());
	if (count($tags) > 0) print '<h4>Click on a tag below to edit</h4>';
	else print '<h4>No tags. Create a new tag above</h4>';
	print '<ul class="card-list">';
	foreach ($tags as $curr_tag) {
		print '<li><a href="'.TRIPLELIFT_NP_BASE_URL.'&tab=modify_tags&'.$this->action_field.'=modify_single_tag_start&tag='.urlencode($curr_tag['script']).'">';
		
		$inv_code_start = strpos($curr_tag['script'], 'inv_code')+9;
		$inv_code_end_amp = strpos($curr_tag['script'], '&', $inv_code_start);
		$inv_code_end_slash = strpos($curr_tag['script'], '\\', $inv_code_start);
		
		if ($inv_code_end_slash && $inv_code_end_slash < $inv_code_end_amp) {
			$inv_code_end = $inv_code_end_slash;
		} else {
			$inv_code_end = $inv_code_end_amp;
		}
		
		print '<strong>'.substr($curr_tag['script'], $inv_code_start, $inv_code_end - $inv_code_start).'</strong>'.
			'<br>&nbsp;<br><span class="card-list-text">';
		
		if ($curr_tag['active']) {
			print 'Active';
		} else {
			print 'Inactive';
		}			
		print '</span></li>';
	}	
	print '</ul>';
?>


<?php 
}
