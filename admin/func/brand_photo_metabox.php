<?php
/**
 * ----------------------------------------------------------------------------------------
 * Add metabox for brand-photo adding
 * ----------------------------------------------------------------------------------------
 */

function brand_photo_change() {
	// Verify page template
	global $post;
	if (!empty($post)) {
		$pageTemplate = get_post_meta( $post->ID, '_wp_page_template', true );
		  if ($pageTemplate == 'opportunities-template.php' || $pageTemplate == 'custom-template.php' ) {
			add_meta_box(
				'brand_photo_attachment',
				__('Brand photo', 'klondike'),
				'brand_photo_attachment',
				'page',
				'normal'
			);
		}
	}
} // end brand_photo_change

// meta_box call-back function
function brand_photo_attachment() {
	global $post;
	$brand = get_post_meta( $post->ID, 'brand_photo_attachment', true );

	wp_nonce_field(plugin_basename(__FILE__), 'brand_photo_attachment_nonce');

	$html = '<div class="brand_row"><p class="description">';
	$html .= __('Upload your brand photo here', 'klondike') . '&#8594;';
	$html .= '<input class="button" type="button" value="' . __('Upload image', 'klondike') . '" onclick="add_brand_image(this)" /><br>';
	$html .= '</p>';
	$html .= '<input type="text" class="brand_photo" name="brand_photo" value="' . $brand . '" placeholder="' . __('Image URL', 'klondike') . '" size="80" />';
	$html .= '<div class="image_wrap"><img src="' . $brand . '" width="320" alt="' . __('Image thumbnail', 'klondike') . '" /></div></div>';

	echo $html;

} // end brand_photo_attachment

function print_brand_photo_scripts() {

// Check for correct post_type
global $post;
if( 'page' != $post->post_type )
	return;
?>
<script type="text/javascript">
	function add_brand_image(obj) {
		var parent=jQuery(obj).parent().parent('div.brand_row');
		var inputField = jQuery(parent).find('input.brand_photo');

		tb_show('', 'media-upload.php?TB_iframe=true');

		window.send_to_editor = function(html) {
			var url = jQuery(html).find('img').attr('src');
			inputField.val(url);
			jQuery(parent)
			.find("div.image_wrap")
			.html('<img src="'+url+'" height="160" width="320" />');

			tb_remove();
		};
	return false;
	}
</script>
<?php
}

function save_brand_photo_meta_data($id) {

	/* --- security verification --- */
	if( isset($_POST['brand_photo_attachment_nonce']) && !wp_verify_nonce($_POST['brand_photo_attachment_nonce'], plugin_basename(__FILE__))) {
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

	// add brand photo URL to database if exist

	if ( isset($_POST['brand_photo']) ) {
		// Build array for saving post meta
    	if ( '' != $_POST['brand_photo'] ) {
			$page_brand_photo  = esc_url($_POST['brand_photo']);
		}
		if ( isset($page_brand_photo) ) {
			update_post_meta( $id, 'brand_photo_attachment', $page_brand_photo );
	    }
		else
	    	delete_post_meta( $id, 'brand_photo_attachment' );
	}
	// Nothing received, all fields are empty, delete option
	else {
		delete_post_meta( $id, 'brand_photo_attachment' );
	}

} // end save_brand_photo_meta_data

add_action('add_meta_boxes', 'brand_photo_change');
add_action('admin_head-post.php', 'print_brand_photo_scripts' );
add_action('save_post', 'save_brand_photo_meta_data');
