<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package oceanica-lite
 */
?>
</div><!-- #content -->
<footer id="colophon" class="site-footer" role="contentinfo">
	<div class="wrapper">
		<?php get_sidebar( 'content-bottom' ); ?>
		<?php if ( has_nav_menu( 'menu-4' ) ) : ?>
			<nav class="footer-navigation clear" role="navigation"
			     aria-label="<?php esc_attr_e( 'Footer Links Menu', 'oceanica-lite' ); ?>">
				<?php wp_nav_menu( array(
					'theme_location' => 'menu-4',
					'menu_id'        => 'footer-navigation',
					'menu_class'     => 'theme-social-menu',
					'depth'          => 1,
					'link_before'    => '<span class="menu-text">',
					'link_after'     => '</span>'
				) ); ?>
			</nav><!-- .footer-navigation -->
		<?php endif; ?>
		<div class="site-info">
			<?php
            $current_date =  new DateTime();
            $current_year = $current_date->format( "Y" );
			echo wp_kses_post(
                apply_filters(
                    'oceanica_lite_site_info',
					sprintf(
						_x( '%2$s &copy; %1$s All Rights Reserved.<br> <span style="font-size: .875em;">Powered by <a href="https://motopress.com/products/oceanica-lite/" rel="nofollow">Oceanica Lite</a> WordPress theme.</span>', '%1$s - current year, %2$s - site title', 'oceanica-lite' ),
						esc_html( $current_year ),
						get_bloginfo('name')
					)
                )
            );
			?>
		</div><!-- .site-info -->
	</div><!-- .wrapper -->
</footer><!-- #colophon -->
</div><!-- #page -->
<?php wp_footer(); ?>
</body>
</html>
