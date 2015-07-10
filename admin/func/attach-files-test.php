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
				'Custom Attachment',
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

	wp_nonce_field(plugin_basename(__FILE__), 'wp_custom_attachment_nonce');
	?>
	<div id="file_wrap">
		<?php
		$html = '<div><p class="description">';
		$html .= 'Upload your files here.';
		$html .= '</p>';
		if ( isset( $doc['url'] ) ) {
			for( $i = 0; $i < count( $doc['url'] ); $i++ )	{
				$html .= '<input type="text" class="wp_custom_attachment_url" name="wp_custom_attachment_url" value="' . $doc['url'] . '" size="80" />';
				// Display the 'Delete' option if a URL to a file exists
				if(strlen(trim($doc['url'][$i])) > 0) {
					$html .= '<input class="button" type="button" value="Remove file" onclick="jQuery(this).closest(\'div\').remove();" /><br>';
				} // end if
			}
		}

		$html .= '<input type="button" id="wp_custom_attachment" name="wp_custom_attachment[]" size="25" value="Choose File" onclick="add_custom_file(this)" /></div>';

		echo $html;
		?>
	</div>


	<?php
	$html = '<div id="file_row" style="clear:both; display:none"><div><p class="description">';
	// $html .= 'Upload your files here.';
	$html .= '</p>';
	// if ( isset( $doc['url'] ) ) {
	// 	for( $i = 0; $i < count( $doc['url'] ); $i++ )	{
			$html .= '<input type="text" class="wp_custom_attachment_url" name="wp_custom_attachment_url" value="" size="80" />';
			// Display the 'Delete' option if a URL to a file exists
			if(strlen(trim($doc['url'])) > 0) {
				$html .= '<input class="button" type="button" value="Remove file" onclick="jQuery(this).closest(\'div\').remove();" /><br>';
			} // end if
	// 	}
	// }

	$html .= '<input type="button" id="wp_custom_attachment" name="wp_custom_attachment[]" size="25" value="Choose File" onclick="add_custom_file(this)" /></div></div>';

	echo $html;
	?>
	<div id="add_field_row">
	  <input class="button" type="button" value="Add Field" onclick="jQuery('#file_wrap').append(jQuery('#file_row').html())" />
	</div>
	<?php

} // end wp_custom_attachment

function save_custom_meta_data($id) {

	/* --- security verification --- */
	if(!wp_verify_nonce($_POST['wp_custom_attachment_nonce'], plugin_basename(__FILE__))) {
	  return $id;
	} // end if

	if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
	  return $id;
	} // end if

	if('page' == $_POST['post_type']) {
		if(!current_user_can('edit_page', $id)) {
		return $id;
		} // end if
	} else {
		if(!current_user_can('edit_page', $id)) {
			return $id;
		} // end if
	} // end if
	/* - end security verification - */

	// Make sure the file array isn't empty
	if(!empty($_FILES['wp_custom_attachment']['name'])) {
		// Setup the array of supported file types. In this case, it's just PDF.
		$supported_types = array('application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document');

		// Get the file type of the upload
		$arr_file_type = wp_check_filetype(basename($_FILES['wp_custom_attachment']['name']));
		$uploaded_type = $arr_file_type['type'];

		// Check if the type is supported. If not, throw an error.
		if(in_array($uploaded_type, $supported_types)) {

			$upload = array();

			// for ($i=0; $i=count($_POST['wp_custom_attachment']); $i++) {
				// Use the WordPress API to upload the file
				$upload[] = get_post_meta( $id, 'wp_custom_attachment' );
				$upload[] .= wp_upload_bits($_FILES['wp_custom_attachment']['name'], null, file_get_contents($_FILES['wp_custom_attachment']['tmp_name']));
			// }
			if(isset($upload['error']) && $upload['error'] != 0) {
				wp_die('There was an error uploading your file. The error is: ' . $upload['error']);
			} else {
				add_post_meta($id, 'wp_custom_attachment', $upload);
				update_post_meta($id, 'wp_custom_attachment', $upload);
			} // end if/else

		} else {
			wp_die("The file type that you've uploaded is not a PDF or doc");
		} // end if/else
	} else {

		// Grab a reference to the file associated with this post
		$doc = get_post_meta($id, 'wp_custom_attachment', true);

		// Grab the value for the URL to the file stored in the text element
		$delete_flag = get_post_meta($id, 'wp_custom_attachment_url', true);

		// Determine if a file is associated with this post and if the delete flag has been set (by clearing out the input box)
		if(strlen(trim($doc['url'])) > 0 && strlen(trim($delete_flag)) == 0) {

			// Attempt to remove the file. If deleting it fails, print a WordPress error.
			if(unlink($doc['file'])) {

				// Delete succeeded so reset the WordPress meta data
				update_post_meta($id, 'wp_custom_attachment', null);
				update_post_meta($id, 'wp_custom_attachment_url', '');

			} else {
				wp_die('There was an error trying to delete your file.');
			} // end if/el;se
		} // end if
	} // end if/else
} // end save_custom_meta_data

/**
 * Print styles and scripts
 */
function print_scripts() {
	?>
	<script>
		function add_custom_file(obj) {
			var parent=jQuery(obj).parent('div#file_row');
			var inputField = jQuery(parent).find('input.custom_attachment_url');
			console.log(inputField);

			tb_show('', 'media-upload.php?TB_iframe=true');

			window.send_to_editor = function(html) {
				var url = jQuery(html).find('input.urlfield').attr('value');
				console.log(url);
				inputField.val(url);
				// jQuery(parent)
				// .find("div.image_wrap")
				// .html('<img src="'+url+'" height="48" width="48" />');

				// inputField.closest('p').prev('.awdMetaImage').html('<img height=120 width=120 src="'+url+'"/><p>URL: '+ url + '</p>');

				tb_remove();
			};

			return false;
		}
	</script>
	<?php
}
add_action('save_post', 'save_custom_meta_data');
add_action( 'admin_head-post.php', 'print_scripts' );
add_action( 'admin_head-post-new.php', 'print_scripts' );
add_action('add_meta_boxes', 'attach_files');