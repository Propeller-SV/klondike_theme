<?php
/**
 * The template for displaying the header
 *
 * Displays all of the head element and everything up until the "site-content" div.
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="<?php bloginfo( 'description' ); ?>">
	<meta name="author" content="">

	<title><?php wp_title( '' ); ?></title>

	<!-- Just for debugging purposes. Don't actually copy this line! -->
	<!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->

	<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
	  <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
	  <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
	<![endif]-->

<?php wp_head(); ?>

</head>
<body class="cover" <?php body_class(); ?>>

<header>
	<div class="navbar navbar-inverse navbar-header-custom navbar-fixed-top cover" role="navigation">
		<div class="container">
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-lg-3">
					<div class="navbar-header">
						<div class="brand col-xs-9 col-sm-9">
							<div class="brand">
								<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="logo"><?php bloginfo( 'name' ); ?></a>
							</div>
						</div>
						<div class="col-xs-3">
							<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar">
								<span class="sr-only">Toggle navigation</span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
							</button>
						</div>
						<!-- Polylang language switcher -->
						<?php
						// check if plugin is active and languages added
						include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
						global $polylang;
						if ( is_plugin_active( 'polylang/polylang.php' && $polylang ) ) { ?>
						<div class="hidden-xs col-sm-3">
							<ul class="pull-right navbar-header-list">
								<li class="dropdown">
								<?php
								$switcher = pll_the_languages(array('raw'=>1));
								$lang = array();
								for ($i=0; $i<count($switcher); $i++) {
									$lang[]=$switcher[$i]['classes'];
									if (in_array('current-lang', $lang[$i])) { ?>
										<a href="#" class="dropdown-toggle" data-toggle="dropdown">
											<img src="<?php echo $switcher[$i]['flag']; ?>"/>
											<i class="fa fa-sort-desc"></i>
										</a>
									<?php } ?>
								<?php } ?>
										<ul class="dropdown-menu">
											<?php for ($i=0; $i<count($switcher); $i++) { ?>
											<li>
												<a href="<?php echo $switcher[$i]['url']; ?>"<?php if (in_array('current-lang', $lang[$i])) echo('class="custom-active"'); ?>>
													<img src="<?php echo $switcher[$i]['flag']; ?>" /><?php echo ' ' . $switcher[$i]['slug']; ?>
												</a>
											</li>
											<?php } ?>
										</ul>
								</li>
							</ul>
						</div>
						<?php } ?> <!-- End of language switcher -->
					</div><!-- end of navbar-header -->
				</div><!-- end of col-xs-12 -->
				<div class="col-xs-12 col-sm-12 col-lg-9">
					<div id="navbar" class="navbar-collapse collapse">
						<?php
						$currentlang = get_bloginfo('language');
						if ($currentlang !== "pt-PT") :

							// Check if the menu exists
							$menu_exists = wp_get_nav_menu_object( 'Top menu EN' );

							// If it doesn't exist, let's create it.
							if( !$menu_exists ){
								$menu_id = wp_create_nav_menu( 'Top menu EN' );

								// Set up default menu items
								$pages = ['Services', 'New opportunities', 'For businesses', 'For professionals', 'Contacts' ];
								for ($i=0; $i<count($pages); $i++) {
									wp_update_nav_menu_item($menu_id, 0, array(
									'menu-item-title'		=> $pages[$i],
									'menu-item-object'		=> 'page',
									'menu-item-object-id'	=> get_page_by_title($pages[$i])->ID,
									'menu-item-type'		=> 'post_type',
									'menu-item-status'		=> 'publish'));
								}
							};
							wp_nav_menu( array(
								'theme_location'	=> 'primary',
								'menu'				=> 'Top menu EN',
								'container'			=> '',
								'menu_class'		=> 'navbar-main nav',
							));
						elseif ($currentlang == "pt-PT") :
							// Check if the menu exists
							$menu_exists = wp_get_nav_menu_object( 'Top menu PT' );

							// If it doesn't exist, let's create it.
							if( !$menu_exists ){
								$menu_id = wp_create_nav_menu( 'Top menu PT' );
							};

							wp_nav_menu( array(
								'theme_location'	=> 'primary',
								'menu'				=> 'Top menu PT',
								'container'			=> '',
								'menu_class'		=> 'navbar-main nav',
							));
						endif; ?>
						<ul class="hidden-sm hidden-md hidden-lg list-in">
							<li>
								<a href="<?php echo $switcher[0]['url']; ?>" class="custom-active">
									<img src="<?php echo $switcher[0]['flag']; ?>" />
								</a>
							</li>
							<li>
								<a href="<?php echo $switcher[1]['url']; ?>" >
									<img src="<?php echo $switcher[1]['flag']; ?>" />
								</a>
							</li>
						</ul>
					</div><!-- end of navbar-collapse -->
				</div><!-- end of col-xs-12 -->
			</div><!-- end of row -->
		</div><!-- end of container -->
	</div><!-- end of navbar-fixed-top -->
</header>