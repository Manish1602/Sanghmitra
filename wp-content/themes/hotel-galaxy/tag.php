<?php 

if ( ! defined( 'ABSPATH' ) ) { exit; }

get_header();
 
?>
<div class="container">

	<main class="content-area">

		<div class="row">
			
			<div class="col-md-8">	

				<?php 

				if ( have_posts()): 
					
					while ( have_posts() ): the_post();						
						get_template_part( 'content',get_post_format());

					endwhile;
					hotelgalaxy_posts_nav('footer-nav');

				else :
					get_template_part( 'no-results', 'search' );

				endif; ?>

			</div>			

			<div class="col-md-4">

				<?php get_sidebar(); ?>
				
			</div>	

		</div>

	</main>

</div>
<?php get_footer(); ?>