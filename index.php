<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * e.g., it puts together the home page when no home.php file exists.
 *
 * Learn more: {@link https://codex.wordpress.org/Template_Hierarchy}
 */

get_header(); ?>

<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">

	<?php if ( have_posts() ) : ?>
		<?php
		// Start the loop.
		while ( have_posts() ) : the_post();
			the_content();
		// End the loop.
		endwhile;
	// If no content.
	else : ?>
		<p><?php _e('Sorry, no posts matched your criteria.'); ?></p>
	<?php
	endif;?>

	</main><!-- .site-main -->
</div><!-- .content-area -->

<?php get_footer(); ?>
