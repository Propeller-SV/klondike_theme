<?php
/**
 * Template name: IT Klondike Opportunities page
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
<section class="content-opportunities cover">
	<div class="container">
		<div class="row">
			<div class="col-xs-12 opportunities-row">
				<ul class="text-center opportunities-list">
					<?php
					$doc = get_post_meta(get_the_ID(), 'wp_custom_attachment', true);
					$screenshots = get_post_meta(get_the_ID(), 'custom_screenshots', true);
					if (isset($doc[1])) {
						for ($i=1; $i < count($doc); $i++) {
							$filename = basename($doc[$i]['file']);
							$url = $doc[$i]['url'];
							if (isset($screenshots[$i-1])) {
								$screenshot = $screenshots[$i-1];
							}
							?>
							<li>
								<div class="div-foto">
									<h5><?php echo $filename; ?></h5>
									<p><a href="<?php echo $url; ?>"><?php _e('Download', 'klondike'); ?> <i class="fa fa-arrow-circle-down"></i></a></p>
									<img src="<?php if (isset($screenshot)) echo $screenshot; else echo IMAGES . '/brand-foto-list.png'; ?>" />
								</div>
							</li>
							<?php
						}
					}
					?>
				</ul>
			</div>
		</div>
	</div>
</section>

<?php get_footer(); ?>