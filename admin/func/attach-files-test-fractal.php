<?php
/**
 * ----------------------------------------------------------------------------------------
 * Add metabox for files attaching
 * ----------------------------------------------------------------------------------------
 */

add_action( 'add_meta_boxes', 'attach_files' );
add_action( 'admin_head-post.php', 'print_scripts' );
add_action( 'admin_head-post-new.php', 'print_scripts' );
add_action( 'save_post', 'save_custom_meta_data', 10, 2 );

function attach_files()
{
  // Verify page template
  global $post;
  if (!empty($post)) {
	$pageTemplate = get_post_meta( $post->ID, '_wp_page_template', true );
	if ($pageTemplate == 'opportunities-template.php') {
		add_meta_box(
			'wp_custom_attachment',
			'Custom Attachment',
			'wp_custom_attachment',
			'page',
			'normal'
		);
	}
  }
}

/**
 * Print the Meta Box content
 */
function wp_custom_attachment()
{
  global $post;
  $doc = get_post_meta( $post->ID, 'wp_custom_attachment', true );

  // Use nonce for verification
  wp_nonce_field( plugin_basename( __FILE__ ), 'wp_custom_attachment_nonce' );
?>

<div id="dynamic_form">
  <div id="field_wrap">
  <?php
  if ( isset( $doc['url'] ) )
  {
	for( $i = 0; $i < count( $doc['url'] ); $i++ )
	{
	?>
	<div class="field_row">
	  <div class="field_left">
		<div class="form_field">
		  <label>File URL</label>
		  <input type="text"
			 class="meta_image_url"
			 name="gallery[image_url][]"
			 value="<?php esc_html_e( $doc['url'][$i] ); ?>"
		  />
		</div>
	  </div>

	  <div class="field_right">
	  <input class="button" type="button" value="Choose File" onclick="add_customer_image(this)" /><br />
	  <input class="button" type="button" value="Remove" onclick="remove_field(this)" />
	  </div>

	  <div class="clear" /></div>
	</div>
	<?php
	} // endforeach
  } // endif
  ?>
  </div>

  <div style="display:none" id="master-row">
	<div class="field_row">
	  <div class="field_left">
		<div class="form_field">
		  <label>Logo URL</label>
		  <input class="meta_image_url" value="" type="text" name="gallery[image_url][]" />
		</div>
	  </div>
	  <div class="field_right">
		<input type="button" class="button" value="Choose File" onclick="add_customer_image(this)" />
		<br />
		<input class="button" type="button" value="Remove" onclick="remove_field(this)" />
	  </div>
	  <div class="clear"></div>
	</div>
  </div>

  <div id="add_field_row">
	<input class="button" type="button" value="Add Field" onclick="add_field_row();" />
  </div>

</div>

  <?php
}

/**
 * Print styles and scripts
 */
function print_scripts()
{
  // Check for correct post_type
  global $post;
  if( 'page' != $post->post_type )
	return;
  ?>

  <script type="text/javascript">
	function add_customer_image(obj) {
	  var parent=jQuery(obj).parent().parent('div.field_row');
	  var inputField = jQuery(parent).find("input.meta_image_url");

	  tb_show('', 'media-upload.php?TB_iframe=true');

	  window.send_to_editor = function(html) {
		var url = jQuery(html).find('input').attr('value');
		inputField.val(url);
		console.log(url);
		// jQuery(parent)
		// .find("div.image_wrap")
		// .html('<img src="'+url+'" height="48" width="48" />');

		// inputField.closest('p').prev('.awdMetaImage').html('<img height=120 width=120 src="'+url+'"/><p>URL: '+ url + '</p>');

		tb_remove();
	  };

	  return false;
	}

	function remove_field(obj) {
	  var parent=jQuery(obj).parent().parent();
	  parent.remove();
	}

	function add_field_row() {
	  var row = jQuery('#master-row').html();
	  jQuery(row).appendTo('#field_wrap');
	}
  </script>
  <?php
}

/**
 * Save post action, process fields
 */
function save_custom_meta_data( $post_id, $post_object )
{
  // Doing revision, exit earlier **can be removed**
  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
	return;

  // Doing revision, exit earlier
  if ( 'revision' == $post_object->post_type )
	return;

  // Verify page template
  global $post;
  if (!empty($post)) {
	$pageTemplate = get_post_meta( $post->ID, '_wp_page_template', true );
	if ($pageTemplate !== 'home-template.php') {
	  return;
	}
  }

  // Verify authenticity
  if ( !wp_verify_nonce( $_POST['wp_custom_attachment_nonce'], plugin_basename( __FILE__ ) ) )
	return;

  // Correct post type
  if ( 'page' != $_POST['post_type'] )
	return;

  if ( $_POST['gallery'] )
  {
	// Build array for saving post meta
	$doc = array();
	for ($i = 0; $i < count( $_POST['gallery']['url'] ); $i++ )
	{
	  if ( '' != $_POST['gallery']['url'][ $i ] )
	  {
		$doc['url'][]  = esc_url($_POST['gallery']['url'][ $i ]);
		// $doc['file'][] = esc_url($_POST['gallery']['file'][ $i ]);
	  }
	}

	if ( $doc )
	  update_post_meta( $post_id, 'wp_custom_attachment', $doc );
	else
	  delete_post_meta( $post_id, 'wp_custom_attachment' );
  }
  // Nothing received, all fields are empty, delete option
  else
  {
	delete_post_meta( $post_id, 'wp_custom_attachment' );
  }
}
?>