<?php
global $javo_this_single_page_type;
$javo_this_single_page_type = get_post_meta( get_the_ID(), 'single_post_type', true );

/* Single Item Type Fixed */
$javo_this_single_page_type = 'item-tab';

add_filter('body_class', 'javo_this_single_type_callback');
function javo_this_single_type_callback($classes) {
	global $post, $javo_this_single_page_type;

	$classes[] = 'javo-'.$javo_this_single_page_type;
	return $classes;
}
if( true ){
	/* Single Item Template file Fixed */
	get_template_part('templates/parts/single', 'item-tab');
}else{
	if( !empty( $javo_this_single_page_type ) ){
		get_template_part('templates/parts/single', $javo_this_single_page_type);
	}else{
		get_template_part('templates/parts/single', 'item-one-page');
	}
};