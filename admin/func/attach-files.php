<?php
/**
 * ----------------------------------------------------------------------------------------
 * Add metabox for files attaching
 * ----------------------------------------------------------------------------------------
 */

function attach_files() {
	// Verify page template
	global $post;
	if (!empty($post)) {
		$pageTemplate = get_post_meta( $post->ID, '_wp_page_template', true );
		  if ($pageTemplate == 'opportunities-template.php' ) {
			add_meta_box(
				'wp_custom_attachment',
				__('Custom Attachments', 'klondike'),
				'wp_custom_attachment',
				'page',
				'normal'
			);
		}
	}
} // end attach_files

// meta_box call-back function
function wp_custom_attachment() {
	global $post;
	$doc = get_post_meta( $post->ID, 'wp_custom_attachment', true );
	$icons = get_post_meta( $post->ID, 'custom_screenshots', true );

	wp_nonce_field(plugin_basename(__FILE__), 'wp_custom_attachment_nonce');

	$html = '<p class="description">';
	$html .= __('Upload your files here', 'klondike') . '&#8594;';
	$html .= ' <input type="file" id="wp_custom_attachment" name="wp_custom_attachment" value="" size="25" />';
	$html .= '</p>';
	if (isset($doc[0])) {
		for ($i=1; $i<count($doc); $i++) {
			$html .= '<div class="field_row" style="border:1px solid"><input type="text" class="wp_custom_attachment_url" name="wp_custom_attachment_url[]" value="' . $doc[$i]['url'] . '" size="80" />';
			// Display the 'Delete' option if a URL to a file exists
			if(strlen(trim($doc[$i]['url'])) > 0) {
				if (isset($icons[$i-1])) { $icon_url = $icons[$i-1]; } else { $icon_url = ''; }
				$html .= '<input class="button" type="button" value="' . __('Remove file', 'klondike') . '" onclick="jQuery(this).closest(\'div\').remove();" /><br>';
				$html .= '<input type="text" class="wp_custom_attachment_screenshot" name="wp_custom_attachment_screenshot[]" value="' . $icon_url . '" placeholder="' . __('Select screenshot', 'klondike') . '" size="80" />';
				$html .= '<input class="button" type="button" value="' . __('Add screenshot', 'klondike') . '" onclick="add_screenshot(this)" />';
				$html .= '<div class="image_wrap"><img src="' . esc_url($icon_url) . '" height="48" width="48" /></div></div>';
			} // end if
		}
	}

	echo $html;

} // end wp_custom_attachment

function print_scripts() {

// Check for correct post_type
global $post;
if( 'page' != $post->post_type )
	return;
?>
<script type="text/javascript">
	function add_screenshot(obj) {
		var parent=jQuery(obj).parent('div.field_row');
		var inputField = jQuery(parent).find("input.wp_custom_attachment_screenshot");

		tb_show('', 'media-upload.php?TB_iframe=true');

		window.send_to_editor = function(html) {
			var url = jQuery(html).find('img').attr('src');
			inputField.val(url);
			jQuery(parent)
			.find("div.image_wrap")
			.html('<img src="'+url+'" height="48" width="48" />');

			tb_remove();
		};
	return false;
	}
</script>
<?php
}

function save_custom_meta_data($id) {

	/* --- security verification --- */
	if( isset($_POST['wp_custom_attachment_nonce']) && !wp_verify_nonce($_POST['wp_custom_attachment_nonce'], plugin_basename(__FILE__))) {
	  return $id;
	} // end if

	if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
	  return $id;
	} // end if

	if(isset($_POST['post_type']) && 'page' == $_POST['post_type']) {
		if(!current_user_can('edit_page', $id)) {
		return $id;
		} // end if
	} else {
		if(!current_user_can('edit_page', $id)) {
			return $id;
		} // end if
	} // end if
	/* - end security verification - */

	// uploading docs screenshots

	if ( isset($_POST['wp_custom_attachment_screenshot']) && $_POST['wp_custom_attachment_screenshot'] ) {
		// Build array for saving post meta
		$custom_screenshots = array();
		for ($i = 0; $i < count( $_POST['wp_custom_attachment_screenshot'] ); $i++ ) {
	    	if ( '' != $_POST['wp_custom_attachment_screenshot'][ $i ] ) {
				$custom_screenshots[]  = esc_url($_POST['wp_custom_attachment_screenshot'][ $i ]);
			}
		}
		if ( $custom_screenshots ) {
			update_post_meta( $id, 'custom_screenshots', $custom_screenshots );
	    }
		else
	    	delete_post_meta( $id, 'custom_screenshots' );
	}
	// Nothing received, all fields are empty, delete option
	else {
		delete_post_meta( $id, 'custom_screenshots' );
	}
	// uploading docs

	if(!empty($_FILES['wp_custom_attachment']['name'])) {
		// Setup the array of supported file types. In this case, it's PDF, doc, docx, xls, xlsx, ppt, pptx .
		$supported_types = array('application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-powerpoint', 'application/vnd.openxmlformats-officedocument.presentationml.presentation');

		// Get the file type of the upload
		$arr_file_type = wp_check_filetype(basename($_FILES['wp_custom_attachment']['name']));
		$uploaded_type = $arr_file_type['type'];

		// Check if the type is supported. If not, throw an error.
		if(in_array($uploaded_type, $supported_types)) {

			$upload = array();
			// Use the WordPress API to upload the file
			$upload = (array) get_post_meta( get_the_id(), 'wp_custom_attachment', true );
			$upload[] = wp_upload_bits($_FILES['wp_custom_attachment']['name'], null, file_get_contents($_FILES['wp_custom_attachment']['tmp_name']));
			if(isset($upload[1]['error']) && $upload[1]['error'] != 0) {
				wp_die(__('There was an error uploading your file. The error is: ', 'klondike') . $upload[1]['error']);
			} else {
				add_post_meta($id, 'wp_custom_attachment', $upload);
				update_post_meta($id, 'wp_custom_attachment', $upload);
			} // end if/else

		} else {
			wp_die(__('The file type that you\'ve uploaded is not a PDF, doc, xls or ppt', 'klondike'));
		} // end if/else
	} else {

		if (isset($_POST['wp_custom_attachment_url'])) {
			if ($_POST['wp_custom_attachment_url']) {
				$visible_files = array();
				for ($i=0; $i<count($_POST['wp_custom_attachment_url']); $i++) {
					$visible_files[] = basename($_POST['wp_custom_attachment_url'][$i]);
				}
				$doc = get_post_meta($id, 'wp_custom_attachment', true);
				$uploaded = array();
				for ($i=1; $i<count($doc); $i++) {
					$uploaded[] = basename($doc[$i]['file']);
				}
				for ($i=0; $i<count($uploaded); $i++) {
					if (!in_array($uploaded[$i], $visible_files)) {
						if (unlink($doc[$i+1]['file'])) {
							unset($doc[$i+1]);
						} else {
							wp_die(__('There was an error trying to delete your file.', 'klondike'));
						} // end if/else
					} // end if
				} // end for
				$doc = array_values($doc);
				// Delete succeeded so reset the WordPress meta data
				update_post_meta($id, 'wp_custom_attachment', $doc);
			} // end if
		} else {
			$doc = get_post_meta($id, 'wp_custom_attachment', true);
			$uploaded = array();
			for ($i=1; $i<count($doc); $i++) {
				$uploaded[] = $doc[$i]['file'];
			}
			for ($i=0; $i<count($uploaded); $i++) {
				if (unlink($uploaded[$i])) {
					// Delete succeeded so reset the WordPress meta data
					delete_post_meta($id, 'wp_custom_attachment');
					// delete_post_meta($id, 'wp_custom_attachment_screenshot');
				} else {
					wp_die(__('There was an error trying to delete your file.', 'klondike'));
				} // end if/else
			} // end for
		} // end if/else
	} // end if/else

} // end save_custom_meta_data

add_action('add_meta_boxes', 'attach_files');
add_action('admin_head-post.php', 'print_scripts' );
add_action('save_post', 'save_custom_meta_data');
