<?php

defined( 'ABSPATH' ) || exit;

function kwp_cf7_populate_programa_select( $tag ) {
	if ( 'programa' !== $tag['name'] || 'select' !== $tag['basetype'] ) {
		return $tag;
	}

	$placeholder = isset( $tag['labels'][0] )
		? $tag['labels'][0]
		: __( 'Programa de interés*', 'landing' );

	$options = array( $placeholder );
	$programas = get_posts(
		array(
			'post_type'      => 'programa',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'orderby'        => 'title',
			'order'          => 'ASC',
			'fields'         => 'ids',
		)
	);

	foreach ( $programas as $programa_id ) {
		$title = trim( get_the_title( $programa_id ) );

		if ( '' !== $title ) {
			$options[] = $title;
		}
	}

	$tag['raw_values'] = $options;
	$tag['values'] = $options;
	$tag['labels'] = $options;

	return $tag;
}

add_filter( 'wpcf7_form_tag', 'kwp_cf7_populate_programa_select', 10, 2 );