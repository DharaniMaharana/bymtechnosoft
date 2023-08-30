<?php
/**
 * Functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package travelvania
 * @since 1.0.0
 */


function travelvania_styles() {

	wp_enqueue_style(
		'travelvania-style',
		get_stylesheet_uri(),
		[],
		wp_get_theme()->get( 'Version' )
	);

	wp_enqueue_script( 
		'travelvania-custom-js', 
		get_template_directory_uri() . '/assets/custom.js', 
		array( 'jquery' ), 
		'20160412', 
		true 
	);

}
add_action( 'wp_enqueue_scripts', 'travelvania_styles' );


function travelvania_register_block_pattern_categories(){
    register_block_pattern_category(
        'travelvania-block-category',
        array( 'label' => __( 'Travelvania', 'travelvania' ) )
    );

}
add_action('init', 'travelvania_register_block_pattern_categories');

require get_template_directory() . '/tgm-plugin/tgm-hook.php';