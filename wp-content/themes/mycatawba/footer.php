<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package MyCatawba
 */

?>

	</div><!-- #content -->

	<footer id="main-footer" class="site-footer">
		<div class="container">
            <div class="row">
                <div class="col-xl-6 col-lg-6 col-md-4 col-sm-12 col-12">
                    <div class="footer-logo">
                        <?php the_custom_logo(); ?>
                    </div>
                    <?php dynamic_sidebar('footer-desc'); ?>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-4 col-sm-12 col-12">
                    <h3 class="footer-title">
                        Company
                    </h3>
                    <?php
                        wp_nav_menu(array(
                            'menu' => 'Footer Menu',
                            'container' => '<ul>',
                            'menu_class' => 'unstyled-list footer-menu'
                        ));
                    ?>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-4 col-sm-12 col-12">
                    <h3 class="footer-title">
                        Contact
                    </h3>
                    <?php dynamic_sidebar('contact-info'); ?>
                </div>
            </div>
            <div class="footer-bottom">
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                        <?php dynamic_sidebar('copyright'); ?>
                    </div>
                </div>
            </div>
        </div>
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
