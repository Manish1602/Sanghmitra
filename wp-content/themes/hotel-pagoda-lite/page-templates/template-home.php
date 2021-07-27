<?php
/**
 *
 * Template Name: Frontpage
 * Description: A page template that displays the Homepage or a Front page as in theme main page with slider and some other contents of the
 * post.
 *
 * @package Hotel Pagoda
 */

get_header();

get_template_part('template-parts/homepage/about', 'section');
get_template_part('template-parts/homepage/room', 'section');
get_template_part('template-parts/homepage/cta', 'section');

get_template_part('template-parts/homepage/blog', 'section');

get_footer();
