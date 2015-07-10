<?php
/**
 * ----------------------------------------------------------------------------------------
 * Create pages and insert into database
 * ----------------------------------------------------------------------------------------
 */
function addThisPage() {

	// add services page
	$page_services = array(
		'post_title'	=> 'Services',
		'post_status'	=> 'publish',
		'post_type'		=> 'page',
		'post_content'	=> 'Some default content',
		'menu_order'	=> 5
		);
	$page_services_exists = get_page_by_title( $page_services['post_title'] );

	if( ! $page_services_exists) {
		$insert_services_id = wp_insert_post( $page_services );
		if( $insert_services_id ) {

			// set page template
			update_post_meta( $insert_services_id, '_wp_page_template', 'services-template.php' );

			// Set "static page" as the option
			update_option( 'show_on_front', 'page' );

			// Set the front page ID
			update_option( 'page_on_front', $insert_services_id );
		}
	}

	// add main pages
	$pages = ['New opportunities', 'For businesses', 'For professionals' ];
	$excerpts = ['Steady growth, for your own!', 'Comfort is our top priority!', 'Consulting interior resources!'];
	$templates = ['opportunities-template.php', 'custom-template.php', 'custom-template.php'];

	for ($i=0; $i<count($pages); $i++) {
		$new_page = array(
			'post_title'	=> $pages[$i],
			'post_status'	=> 'publish',
			'post_type'		=> 'page',
			'post_excerpt'	=> $excerpts[$i],
			'post_content'	=> 'Suspendisse in orci enim. This is Photoshop\'s version of Lorem Ipsum. Proin gravida nibh vel velit auctor aliquet. Aenean sollicitudin, lorem quis bibendum auctor, nisi elit consequat ipsum, nec sagittis sem nibh id elit. Duis sed odio sit amet nibh vulputate cursus a sit amet mauris. Morbi accumsan ipsum velit. Nam nec tellus a odio tincidunt auctor a ornare odio. Sed non mauris vitae erat consequat auctor eu in elit. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Mauris in erat justo. Nullam ac urna eu felis dapibus condimentum sit amet a augue. Sed non neque elit. Sed ut imperdiet nisi. Proin condimentum fermentum nunc. Etiam pharetra, erat sed fermentum feugiat, velit mauris egestas quam, ut aliquam massa nisl quis neque. Suspendisse in orci enim.',
			'menu_order'	=> ($i+1)
			);
		$page_exists = get_page_by_title( $new_page['post_title'] );

		if( !$page_exists ) {
			$page_id = wp_insert_post( $new_page );
			if ($page_id) {

				// set page template
				update_post_meta( $page_id, '_wp_page_template', $templates[$i] );

				// upload and set up the post thumbnail
				$image_url = IMAGES . '/slide-' . ($i+1) . '.png';
				$upload_dir = wp_upload_dir();
				$image_data = file_get_contents($image_url);
				$filename = basename($image_url);
				if(wp_mkdir_p($upload_dir['path']))
					$file = $upload_dir['path'] . '/' . $filename;
				else
					$file = $upload_dir['basedir'] . '/' . $filename;
				file_put_contents($file, $image_data);

				$wp_filetype = wp_check_filetype($filename, null );
				$attachment = array(
					'post_mime_type'	=> $wp_filetype['type'],
					'post_title'		=> sanitize_file_name($filename),
					'post_content'		=> '',
					'post_status'		=> 'inherit'
				);
				$attach_id = wp_insert_attachment( $attachment, $file, $page_id );
				require_once(ABSPATH . 'wp-admin/includes/image.php');
				$attach_data = wp_generate_attachment_metadata( $attach_id, $file );
				wp_update_attachment_metadata( $attach_id, $attach_data );

				set_post_thumbnail( $page_id, $attach_id );
			}
		}
	}

	// add contact page
	$page_contact = array(
		'post_title'	=> 'Contacts',
		'post_status'	=> 'publish',
		'post_type'		=> 'page',
		'post_content'	=> 'Suspendisse in orci enim. This is Photoshop\'s version of Lorem Ipsum. Proin gravida nibh vel velit auctor aliquet. Aenean sollicitudin, lorem quis bibendum auctor, nisi elit consequat ipsum, nec sagittis sem nibh id elit. Duis sed odio sit amet nibh vulputate cursus a sit amet mauris. Morbi accumsan ipsum velit. Nam nec tellus a odio tincidunt auctor a ornare odio. Sed non mauris vitae erat consequat auctor eu in elit. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Mauris in erat justo. Nullam ac urna eu felis dapibus condimentum sit amet a augue. Sed non neque elit. Sed ut imperdiet nisi. Proin condimentum fermentum nunc. Etiam pharetra, erat sed fermentum feugiat, velit mauris egestas quam, ut aliquam massa nisl quis neque. Suspendisse in orci enim.',
		'menu_order'	=> 4
		);
	$page_contact_exists = get_page_by_title( $page_contact['post_title'] );

	if( !$page_contact_exists ) {
		$insert_contact_id = wp_insert_post( $page_contact );
		if ($insert_contact_id) {

			// set page template
			update_post_meta( $insert_contact_id, '_wp_page_template', 'custom-template.php' );
		}
	}
}
add_action( 'after_switch_theme', 'addThisPage' );
?>