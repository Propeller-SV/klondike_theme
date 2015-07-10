<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the "site-content" div and all content after.
 */
?>
<footer>
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <div class="brand">
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="logo"><?php bloginfo( 'name' ); ?></a>
                </div>
                <p><?php _e('Copyright', 'klondike'); ?> &#169; <?php the_time( 'Y' ); ?> IT Klondike. <?php _e('Designed by', 'klondike') ?> Fractal Soft.Com</p>
            </div>
        </div>
    </div>
</footer>
<?php wp_footer(); ?>
</body>
</html>