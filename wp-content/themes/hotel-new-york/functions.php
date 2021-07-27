<?php 
if ( ! defined( 'ABSPATH' ) ) {	exit; }

define( 'HotelNewYork_Version', '1.1.5' );

add_action('wp_enqueue_scripts', 'HotelNewYork_Scripts', 999 );

function HotelNewYork_Scripts() {

	$parent_style = 'hotelgalaxy_style';

	wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css' );	

	if(!defined('HG_PREMIUM_VERSION')){

		wp_enqueue_style( 'hotelnewyork-header', get_stylesheet_directory_uri() .'/assets/css/header.css' );

		wp_enqueue_style('hotelgalaxy-swiper');

		wp_enqueue_script('hotelgalaxy-swiper');

		wp_enqueue_style( 'hotelnewyork-slider', get_stylesheet_directory_uri() .'/assets/css/slider.css' );

		wp_enqueue_script('hotelnewyork-slider', get_stylesheet_directory_uri().'/assets/js/slider.js',array('jquery'), HotelNewYork_Version);
	}

	wp_enqueue_style( 'hotelnewyork-style', get_stylesheet_uri(), array( $parent_style ) );	
}

add_action( 'after_switch_theme', 'HotelNewYork_Migrate_Settings', 10 );

function HotelNewYork_Migrate_Settings() {

	if(!defined('HG_PREMIUM_VERSION') ){

		$option = get_option('hotel_galaxy_option');		
		$spacing = get_option('hg_spacing_settings');

		$new_option = array();

		$new_option['theme_color'] = '#8bc34a';	

		$new_option['seprator_color_before'] = '#8bc34a';		

		$new_option['header_layout'] = 'standard';			

		$new_option['slider_layout'] = 'two';

		$new_option['is_carousel_background_overlay'] = false;

		$new_option['infobar_background_color'] = '#04102d';

		$new_option['link_color'] = '#8bc34a';

		$new_option['link_color_visited'] = '#8bc34a';

		$new_option['entry_meta_link_color_hover'] = '#8bc34a';

		$new_option['blog_post_title_hover_color'] = '#8bc34a';

		$new_option['navigation_background_current_color'] = '#8bc34a';

		$new_option['navigation_background_hover_color'] = '#8bc34a';

		$new_option['sidebar_widget_top_border_color'] = '#8bc34a';

		$new_option['button_background_color'] = '#8bc34a';

		$new_option['footer_icon_bar_background_color'] = '#8bc34a';

		$new_option['footer_widget_title_underline_color'] = '#8bc34a';

		$new_option['back_to_top_background_color'] = '#8bc34a';

		if( !empty( $new_option ) ){

			$new_settings = wp_parse_args( $new_option, $option );				

			update_option( 'hotel_galaxy_option', $new_settings);

		}		

		// spacing		

		$new_spacing = array();

		$new_spacing['button_radius_top_left'] = '40';	
		$new_spacing['button_radius_top_right'] = '40';	
		$new_spacing['button_radius_bottom_left'] = '40';	
		$new_spacing['button_radius_bottom_right'] = '40';

		if( !empty( $new_spacing ) ){

			$new_spacing_settings = wp_parse_args( $new_spacing, $spacing );		

			update_option( 'hg_spacing_settings', $new_spacing_settings);

		}			

	}
}

add_action( 'hotelgalaxy_child_home_slider_two', 'hotelnewyork_home_slider');

function hotelnewyork_home_slider(){
	?>

	<div id="hg-main-slider" <?php hotelgalaxy_add_class( 'slider_layout' ) ?> >
		<div class="swiper-button-next"></div>
		<div class="swiper-button-prev"></div>
		<!-- !next / prev arrows -->

		<!-- pagination dots -->
		<div class="swiper-pagination"></div>
		<div class="swiper-wrapper">
			<?php hotelnewyork_get_home_slider() ?>
		</div>	
	</div>	

	<?php
}

function hotelnewyork_get_home_slider( ){

	if(!function_exists('hotelgalaxy_get_option')){
		return false;
	}	

	$arg = array('post_type'=>'hg_slider');

	$slider = new WP_Query($arg);

	if( $slider->have_posts() ){			

		while( $slider->have_posts() ) : $slider->the_post();

			$data = hotelgalaxy_get_metabox_settings( get_the_ID(), 'hg_slider_settings_' );

			hotelnewyork_get_slider_item( $data );			

		endwhile;
		
	}else{

		$demo_slider_one = array(
			'id' => '',
			'title' => esc_html__('Hotel New York', 'hotel-new-york'),
			'description' => esc_html__('Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','hotel-new-york'),
			'url' => esc_html__('#','hotel-new-york'),
			'rating' => 5,
			'is_rating' => true,
			'is_button' => true,
			'img' => 1,
		);	

		$demo_slider_two = array(
			'id' => '',
			'title' => esc_html__('Hotel New York', 'hotel-new-york'),
			'description' => esc_html__('Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','hotel-new-york'),
			'url' => esc_html__('#','hotel-new-york'),
			'rating' => 5,
			'is_rating' => true,
			'is_button' => true,
			'img' => 2,
		);	

		hotelnewyork_get_slider_demo_item( $demo_slider_one );
		hotelnewyork_get_slider_demo_item( $demo_slider_two );
	}

}

function hotelnewyork_get_slider_item( $data ){

	if(empty($data)){ 
		return false;
	}	

	$before = '<div class="swiper-slide">';
	$after = '</div>';

	if ( ! has_post_thumbnail() ) {
		return;
	}		

	echo $before;
	the_post_thumbnail('full', array(
		'itemprop' => 'image',

		'class'=>'img-responsive',
	));

	echo '<div class="container"> <div class="carousel-caption">';

	do_action('hotelgalaxy_add_rating', array( 'rating' => $data['rating'], 'is_rating'=> $data['is_rating'])); 

	echo '<h2>'.esc_html__( $data['title']).'</h2>';

	echo hotelgalaxy_trim_words( $data['id'], $data['description'], $data['url'], $data['is_button'] ); 

	echo '</div> </div>';
	echo $after;

}

function hotelnewyork_get_slider_demo_item( $data ){

	if(empty($data)){ 
		return false;
	}	

	$MediaId = get_option('hg_media_id');

	$before = '<div class="swiper-slide">';
	$after = '</div>';	

	echo $before;	

	$img_atts = wp_get_attachment_image_src($MediaId[$data['img']], 'full');
	?>

	<img class="img-responsive" src="<?php echo esc_url($img_atts[0]); ?>">
	<?php

	echo '<div class="container"> <div class="carousel-caption">';

	do_action('hotelgalaxy_add_rating', array( 'rating' => $data['rating'], 'is_rating'=> $data['is_rating'])); 

	echo '<h2>'.esc_html__( $data['title']).'</h2>';

	echo hotelgalaxy_trim_words( $data['id'], $data['description'], $data['url'], $data['is_button'] ); 

	echo '</div> </div>';
	echo $after;

}

?>