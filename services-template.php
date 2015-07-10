<?php
/**
 * Template name: IT Klondike Services page
 */
?>

<?php get_header(); ?>

<section class="slider">
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
				<div id="carousel-slides">
					<div id="carousel-example-generic" class="carousel slide" data-ride="carousel">

						<!-- Indicators -->
						<ol class="carousel-indicators">
							<li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
							<li data-target="#carousel-example-generic" data-slide-to="1"></li>
							<li data-target="#carousel-example-generic" data-slide-to="2"></li>
						</ol>

						<!-- Wrapper for slides -->
						<div class="carousel-inner" role="listbox">
							<div class="item active">
								<?php $page = get_page_by_title( 'New opportunities' ); ?>
								<img class="img-responsive" src="<?php echo wp_get_attachment_image_src( get_post_thumbnail_id($page->ID), 'full')[0]; ?>" alt="...">
								<div class="carousel-caption">
									<h5 class="text-center">
									<?php
									echo $page->post_excerpt;
									?></h5>
								</div>
							</div>
							<div class="item">
								<?php $page = get_page_by_title( 'For businesses' ); ?>
								<img class="img-responsive" src="<?php echo wp_get_attachment_image_src( get_post_thumbnail_id($page->ID), 'full')[0]; ?>" alt="...">
								<div class="carousel-caption">
									<h5 class="text-center">
									<?php
									echo $page->post_excerpt;
									?></h5>
									</h5>
								</div>
							</div>
							<div class="item">
								<?php $page = get_page_by_title( 'For professionals' ); ?>
								<img class="img-responsive" src="<?php echo wp_get_attachment_image_src( get_post_thumbnail_id($page->ID), 'full')[0]; ?>" alt="...">
								<div class="carousel-caption">
									<h5 class="text-center">
									<?php
									echo $page->post_excerpt;
									?></h5>
									</h5>
								</div>
							</div>
						</div><!-- end of carousel-inner -->

					</div><!-- end of carousel-example-generic -->
				</div><!-- end of carousel-slides -->
			</div><!-- end of col-xs-12 -->
		</div><!-- end of row -->
	</div><!-- end of container -->
</section><!-- end of slider -->
<section class="content-klondike cover">
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
				<?php $currentlang = substr(get_bloginfo('language'), 0, 2); ?>
				<?php $loop = new WP_Query(array('post_type' => 'page', 'lang' => $currentlang, 'posts_per_page' => 3, 'orderby' => 'menu_order', 'order'=>'ASC')); ?>
				<?php while ( $loop->have_posts() ) : $loop->the_post(); ?>
				<div class="col-xs-12 col-sm-4">
					<div class="foto">
					<?php the_post_thumbnail(array(430, 207)); ?>
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