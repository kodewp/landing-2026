<?php

defined( 'ABSPATH' ) || exit;

/**
 * Establecer los valores UTM a partir de la URL de destino y mantiene una reserva de origen.
 *
 * @param array $tag La etiqueta del formulario analizada..
 * @return array
 */
function kwp_cf7_populate_utm_fields( $tag ) {
	$utm_defaults = array(
		'utm_source'   => 'landing-page',
		'utm_medium'   => 'organic',
		'utm_campaign' => 'admision-programas',
	);

	if ( 'hidden' !== $tag['basetype'] || ! isset( $utm_defaults[ $tag['name'] ] ) ) {
		return $tag;
	}

	$value = $utm_defaults[ $tag['name'] ];

	if ( isset( $_GET[ $tag['name'] ] ) && is_scalar( $_GET[ $tag['name'] ] ) ) {
		$query_value = sanitize_text_field( wp_unslash( $_GET[ $tag['name'] ] ) );

		if ( '' !== $query_value ) {
			$value = $query_value;
		}
	}

	$tag['raw_values'] = array( $value );
	$tag['values'] = array( $value );
	$tag['labels'] = array( $value );

	return $tag;
}

add_filter( 'wpcf7_form_tag', 'kwp_cf7_populate_utm_fields', 10, 2 );
