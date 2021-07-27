<?php
$option = wp_parse_args(  get_option( 'hotelone_option', array() ), hotelone_reset_data() );

$class = '';
if(empty($option['hotelone_gallery_bgimage'])){
	$class = 'noneimage-padding';
}else{
	$class = 'has_section_image';
}
				
if( !$option['hotelone_gallery_disable'] ): ?>
<div id="gallery" class="gallery_section section <?php echo esc_attr( $class ); ?>" style="background-color:<?php echo esc_attr($option['hotelone_gallery_bgcolor']); ?>;background-image:url(<?php echo esc_url($option['hotelone_gallery_bgimage']); ?>);">
	
	<?php if(!empty($option['hotelone_gallery_bgimage'])){ ?>
	<div class="sectionOverlay">
	<?php } ?>
	
	<div class="container">
		<div class="row">
			<div class="col-md-12 text-center">
				<?php if( !empty( $option['hotelone_gallerytitle'] ) ){ ?>
				<h2 class="section-title wow animated fadeInDown"><?php echo wp_kses_post( $option['hotelone_gallerytitle'] ); ?></h2>
				<?php } ?>
				<?php if( !empty( $option['hotelone_gallerysubtitle'] ) ){ ?>
				<div class="seprator wow animated slideInLeft"></div>
				<p class="section-desc wow animated fadeInUp"><?php echo wp_kses_post( $option['hotelone_gallerysubtitle'] ); ?></p>
				<?php } ?>
			</div>
		</div>
		
		<div class="row">
			<?php hotel_imperial_gallery_generate(); ?>
		</div>

	</div><!-- /.container -->
	
	<?php if(!empty($option['hotelone_gallery_bgimage'])){ ?>
	</div>
	<?php } ?>
	
</div><!-- /.gallery_section -->
<?php endif; ?>