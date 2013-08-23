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
	if (count($this->tags) > 0) print '<h4>Click on a tag below to edit</h4>';
	else print '<h4>No more tags. Create a new tag above</h4>';
	$tags = array_reverse($this->tag_manager->get_tags());
	print '<ul class="triplelift_np_admin_hovercheck">';
	foreach ($tags as $curr_tag) {
		print '<li><a href="'.TRIPLELIFT_NP_BASE_URL.'&tab=modify_tags&'.$this->action_field.'=modify_single_tag_start&tag='.urlencode($curr_tag['script']).'"><pre>'.stripslashes($curr_tag['script']).'</pre></a>';
		
		if ($curr_tag['active']) {

		    if ($curr_tag['admin_preview']) {
                print 'Preview mode enabled</br>';
            }
			print 'Always included on: ';
			$count = 0;
			foreach ($curr_tag['wp_page_type_include'] as $wp_include_page => $val) {
				if ($val) {
					if ($count > 0) print ', ';
					print $this->tag_manager->field_name_map($wp_include_page);
					$count++;
				}
			}
			if ($count == 0) print 'No pages';
			print '<br>Always excluded on: ';
			$count = 0;
			foreach ($curr_tag['wp_page_type_exclude'] as $wp_exclude_page => $val) {
				if ($val) {
					if ($count > 0) print ', ';
					print $this->tag_manager->field_name_map($wp_exclude_page);
					$count++;
				}
			}
			if ($count == 0) print 'No pages';
			print '<br>Include paths: '.( count($curr_tag['include_path']) ? implode(',', $curr_tag['include_path']) : 'No paths');
			print '<br>Exclude paths: '.( count($curr_tag['exclude_path']) ? implode(',', $curr_tag['exclude_path']) : 'No paths');
		} else {
			print 'Inactive';
		}			

		print '<p><a href="'.TRIPLELIFT_NP_BASE_URL.'&tab=modify_single_tag_start&tag='.urlencode($curr_tag['script']).'">Edit</a></p>';
		print '</li>';
	}	
	print '</ul>';
?>


<?php 
}
