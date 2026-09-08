<?php

defined( 'ABSPATH' ) || exit;

/**
 * Envía el formulario de admisión al punto final del CRM antes de que CF7 envíe su correo electrónico.
 *
 * Agregar KWP_CRM_WEBHOOK_URL en wp-config.php con la URL de Webhook.site utilizada para las pruebas.
 *
 * @param WPCF7_ContactForm $contact_form El formulario de contacto 7 enviado.
 * @param bool              $abort        Indica si se debe cancelar el envío del correo electrónico.
 * @param WPCF7_Submission  $submission   Instancia que contiene los datos del envío actual.
 * @return void
 */
function kwp_cf7_send_lead_to_crm( $contact_form, $abort, $submission ) {
	$admision_form_id = 118;

	if ( $admision_form_id !== (int) $contact_form->id() || ! $submission ) {
		return;
	}

	if ( ! defined( 'KWP_CRM_WEBHOOK_URL' ) || ! filter_var( KWP_CRM_WEBHOOK_URL, FILTER_VALIDATE_URL ) ) {
		error_log( 'KWP CRM: webhook URL is not configured.' );
		return;
	}

	$payload = array(
		'nombre_completo'		=> sanitize_text_field( $submission->get_posted_string( 'nombres' ) ),
		'correo_electronico'	=> sanitize_email( $submission->get_posted_string( 'email' ) ),
		'telefono_whatsapp'		=> sanitize_text_field( $submission->get_posted_string( 'telefono' ) ),
		'programa_interes'		=> sanitize_text_field( $submission->get_posted_string( 'programa' ) ),
		'utm_source'			=> sanitize_text_field( $submission->get_posted_string( 'utm_source' ) ),
		'utm_medium'			=> sanitize_text_field( $submission->get_posted_string( 'utm_medium' ) ),
		'utm_campaign'			=> sanitize_text_field( $submission->get_posted_string( 'utm_campaign' ) ),
		'fecha_registro'		=> current_time( 'c' ),
	);

	$request_args = array(
		'headers' => array(
			'Content-Type' => 'application/json; charset=utf-8',
		),
		'body'    => wp_json_encode( $payload ),
		'timeout' => 15,
	);

	// WAMP no tiene instalado el certificado del emisor en entorno local.
	if ( defined( 'KWP_CRM_SSLVERIFY' ) && false === KWP_CRM_SSLVERIFY ) {
		$request_args['sslverify'] = false;
	}

	$response = wp_remote_post( KWP_CRM_WEBHOOK_URL, $request_args );

	if ( is_wp_error( $response ) ) {
		error_log( 'KWP CRM: ' . $response->get_error_message() );
		return;
	}

	$status_code = wp_remote_retrieve_response_code( $response );

	if ( $status_code < 200 || $status_code >= 300 ) {
		error_log( 'KWP CRM: unexpected HTTP status ' . $status_code . '.' );
	}
}

add_action( 'wpcf7_before_send_mail', 'kwp_cf7_send_lead_to_crm', 10, 3 );
