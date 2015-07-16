<?php
/**
 * Template name: IT Klondike Custom page
 */
?>

<?php get_header(); ?>

<section class="item">
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
				<div class="brand-businesses">
					<?php $brand = get_post_meta( get_the_id(), 'brand_photo_attachment', true ); ?>
					<img class="img-responsive" src="<?php if ($brand) echo $brand; else echo IMAGES . '/brand-foto.png'; ?>" alt="...">
					<div class="carousel-caption">
						<h5 class="text-cenetr"><?php the_title(); ?></h5>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<section class="content-businesses">
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