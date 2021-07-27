<?php
/*
 *  Template Name: AboutUs Page
 */
 
 get_header(); the_post(); ?>
<div id="aboutPage_section" class="aboutPage_section section wow animated fadeInDown">
	<div class="container">
		<div class="row">
			
			<?php if( has_post_thumbnail() ): ?>
			<div class="col-md-6 col-sm-6">
				<div class="about_thumb">
					<?php the_post_thumbnail(); ?>
				</div>
			</div>
			<?php endif; ?>
			
			<div class="col-md-<?php if( get_the_post_thumbnail( $post, 'full' ) ){ echo '6'; }else{ echo '12'; } ?> col-sm-<?php if( get_the_post_thumbnail( $post, 'full' ) ){ echo '6'; }else{ echo '12'; } ?>">
				<?php 
					the_title('<h3>','</h3>'); 
				
					the_content();
				?>
			</div>
		</div>
	</div><!-- .container -->
</div><!-- .aboutPage_section -->
<?php 
hotelone_load_section( 'callout' );
get_footer(); ?>