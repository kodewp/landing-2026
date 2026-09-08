<?php

if (!function_exists('kwp_theme_setup')) :

	function kwp_theme_setup(){

		// Language
		load_theme_textdomain('landing', get_template_directory() . '/languages');

		// Title
		add_theme_support('title-tag');

		// Disable Gutemberg and Widgets blocks
		add_filter('use_block_editor_for_post', '__return_false', 10);
		add_filter('use_widgets_block_editor', '__return_false');

		// Disable  Extendify library
		add_filter('extendifysdk_load_library', '__return_false');

		// Remove emoji
		remove_action('wp_head', 'print_emoji_detection_script', 7);
		remove_action('wp_print_styles', 'print_emoji_styles');

		// Add filter for disable srcset on frontend (img)
		add_filter('wp_calculate_image_srcset', '__return_false');
		add_filter('wp_calculate_image_srcset_meta', '__return_false');

		// Remove <p> tags
		add_filter('wpcf7_autop_or_not', '__return_false');

		// Add CPT
		require_once get_stylesheet_directory() . '/inc/cpt/programa.php';

		// Contact Form 7
		require_once get_stylesheet_directory() . '/inc/cf7/programa.php';
		require_once get_stylesheet_directory() . '/inc/cf7/crm.php';
		require_once get_stylesheet_directory() . '/inc/cf7/utm.php';

	};

endif;

add_action('after_setup_theme', 'kwp_theme_setup');

function kwp_enqueue_custom_styles() {
	$custom_style_path = get_stylesheet_directory() . '/assets/css/custom_style.css';

	wp_enqueue_style(
		'visiva-custom-style',
		get_stylesheet_directory_uri() . '/assets/css/custom_style.css',
		array(),
		file_exists($custom_style_path) ? (string) filemtime($custom_style_path) : '1.0.0'
	);
}

add_action('wp_enqueue_scripts', 'kwp_enqueue_custom_styles', 20);
