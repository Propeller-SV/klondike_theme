<?php
/**
 * Template name: IT Klondike Custom page
 */
?>

<?php get_header(); ?>

<section class="brand-foto">
	<div class="container">
		<div class="row">
			<div class="col-xs-12 brand-businesses">
				<div class="item">
					<img class="img-responsive" src="<?php echo IMAGES; ?>/brand-foto.png" alt="...">
					<div class="carousel-caption">
						<h5 class="text-cenetr"><?php the_title(); ?></h5>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<section class="content-businesses cover">
	<div class="container">
		<div class="row">
			<div class="col-xs-12 businesses-text">
				<?php
				while ( have_posts() ) : the_post();
					the_content();
				endwhile;
				?>
			</div><!-- end of col-xs-12 -->
		</div><!-- end of row -->
	</div><!-- end of container -->
</section><!-- end of content-businesses -->

<?php get_footer(); ?>