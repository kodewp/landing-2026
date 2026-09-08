<?php

defined( 'ABSPATH' ) || exit;

function kwp_register_cpt_team() {
	register_post_type( 'programa',
		array(
			'labels' => array(
				'name' => __( 'Programas' ),
				'singular_name' => __( 'Programa' ),
			),
			'public' => true,
			'has_archive' => true,
			'rewrite' => array('slug' => 'programa'),
			'show_in_rest' => true,
			'menu_position'	=> 20,
			'menu_icon'	=> 'dashicons-book',
			'supports'	=> array('title', 'thumbnail', 'editor'),
		)
	);

	register_taxonomy(
		'tipo-de-programa',
		'programa',
		array(
			'hierarchical' => true,
			'label' => 'Tipo de Programa',
			'query_var' => true,
			'show_admin_column' => true,
			'show_in_nav_menus' => false,
			'rewrite' => array(
				'slug' => 'tipo-de-programa',
				'with_front' => false,
			)
		)
	);

}	
add_action( 'init', 'kwp_register_cpt_team' );