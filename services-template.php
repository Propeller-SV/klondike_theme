<?php
/**
 * Template name: IT Klondike Services page
 */
?>

<?php get_header(); ?>

<section id="carousel-slides">
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
				<div id="carousel-slides-row">
					<div id="carousel-example-generic" class="carousel slide" data-ride="carousel">

						<!-- Indicators -->
						<ol class="carousel-indicators">
							<li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
							<li data-target="#carousel-example-generic" data-slide-to="1"></li>
							<li data-target="#carousel-example-generic" data-slide-to="2"></li>
						</ol>

						<!-- Wrapper for slides -->
						<div class="carousel-inner" role="listbox">

							<!-- query the first carousel slide -->
							<?php $currentlang = substr(get_bloginfo('language'), 0, 2); ?>
							<?php $loop = new WP_Query(array('post_type' => 'page', 'lang' => $currentlang, 'posts_per_page' => 1, 'orderby' => 'menu_order', 'order'=>'ASC')); ?>
							<?php while ( $loop->have_posts() ) : $loop->the_post(); ?>
								<div class="item active">
									<img class="img-responsive" src="<?php echo wp_get_attachment_image_src( get_post_thumbnail_id($loop->ID), 'full')[0]; ?>" alt="...">
									<div class="carousel-caption">
										<h5 class="text-center">
										<?php
										the_excerpt();
										?></h5>
									</div>
								</div>
							<?php endwhile; ?>
							<?php wp_reset_postdata(); ?>

							<!-- query the the rest of carousel slides -->
							<?php $loop = new WP_Query(array('post_type' => 'page', 'lang' => $currentlang, 'posts_per_page' => 2, 'orderby' => 'menu_order', 'offset' => 1, 'order'=>'ASC')); ?>
							<?php while ( $loop->have_posts() ) : $loop->the_post(); ?>
								<div class="item">
									<img class="img-responsive" src="<?php echo wp_get_attachment_image_src( get_post_thumbnail_id($loop->ID), 'full')[0]; ?>" alt="...">
									<div class="carousel-caption">
										<h5 class="text-center">
										<?php
										the_excerpt();
										?></h5>
									</div>
								</div>
							<?php endwhile; ?>
							<?php wp_reset_postdata(); ?>

					</div><!-- end of carousel-example-generic -->
				</div><!-- end of carousel-slides -->
			</div><!-- end of col-xs-12 -->
		</div><!-- end of row -->
	</div><!-- end of container -->
</section><!-- end of slider -->
<section class="content-klondike">
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
				<?php $currentlang = substr(get_bloginfo('language'), 0, 2); ?>
				<?php $loop = new WP_Query(array('post_type' => 'page', 'lang' => $currentlang, 'posts_per_page' => 3, 'orderby' => 'menu_order', 'order'=>'ASC')); ?>
				<?php while ( $loop->have_posts() ) : $loop->the_post(); ?>
				<div class="col-xs-12 col-sm-4">
					<div class="foto">
					<img class="img-responsive" src="<?php echo wp_get_attachment_image_src( get_post_thumbnail_id($loop->ID), 'full')[0]; ?>" alt="...">
						<p class="text-center">
							<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
						</p>
					</div>
				</div>
				<?php endwhile; ?>
				<?php wp_reset_postdata(); ?>
			</div><!-- end of col-xs-12 -->
		</div><!-- end of row -->
	</div><!-- end of container -->
</section><!-- end of content -->

<?php get_footer(); ?>