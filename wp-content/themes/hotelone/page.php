<?php 
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 */
 
get_header(); 

$layout = hotelone_get_layout();
$col = '8';
if($layout=='none' || ! is_active_sidebar( 'sidebar-1' )){
	$col = '12';
}
?>

<div id="site-content" class="site-content">
	<div class="container">
		<div class="row">
			<?php 
				if ( $layout != 'none' && $layout=='left' ) {
					get_sidebar(); 
				} 
				?>

			<div class="col-md-<?php echo esc_attr( $col ); ?> col-sm-12 primary">
				
				<?php
				if ( have_posts() ) :
					
					/* Start the Loop */
					while ( have_posts() ) : the_post();
					
						/*
						 * Include the Post-Format-specific template for the content.
						 * If you want to override this in a child theme, then include a file
						 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
						 */
						get_template_part( 'template-parts/page/content', 'page' );
						
					endwhile;
					
					// If comments are open or we have at least one comment, load up the comment template.
						if ( comments_open() || get_comments_number() ) :
							comments_template();
						endif;
						
					the_posts_pagination( array(
							'prev_text' => '<i class="fa fa-angle-double-left"></i>',
							'next_text' => '<i class="fa fa-angle-double-right"></i>',
						) );
				
				else :
					
					get_template_part( 'template-parts/post/content', 'none' );
					
				endif;
				?>			
				
			</div>
			
			<?php 
				if ( $layout != 'none' && $layout=='right' ) {
					get_sidebar(); 
				} 
				?>
		</div>
	</div>
</div><!-- .site-content -->
	
<?php get_footer(); ?>