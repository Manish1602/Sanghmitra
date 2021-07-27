<?php 
/**
 * The header for the HotelOne theme.
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Hotelone
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>	
</head>
<body <?php body_class(); ?>>

<?php 
  if ( function_exists( 'wp_body_open' ) ) {
    wp_body_open();
  }else{
    do_action( 'wp_body_open' );
  }
?>

<?php do_action( 'hotelone_before_site_start' ); ?>

<a class="skip-link screen-reader-text" href="#wrapper"><?php esc_html_e( 'Skip to content', 'hotelone' ); ?></a>

<div id="wrapper">
	
	<?php 
	hotelone_header();
	?>