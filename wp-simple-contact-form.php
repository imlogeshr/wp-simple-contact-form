<?php
/*
Plugin Name: WP Simple Contact Form
Plugin URI:  https://github.com/qriouslad/wp-simple-contact-form
Description: Simple contact form plugin. Form display is done via a shortcode.
Version:     1.0
Author:      Bowo
Author URI:  https://bowo.io
Text Domain: wpscf
Domain Path: /languages
License:     GPL2
 
WP Simple URL Shortener is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.
 
WP Simple URL Shortener is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
along with {Plugin Name}. If not, see {License URI}.
*/

/**
 * Create contact form HTML
 *
 * @return string $html The raw HTML of contact form
 */
function wpscf_html_form_code() {
	$html = '<form action="' . esc_url( $_SERVER['REQUEST_URI'] ) . '" method="post">';
	$html .= '<p>';
	$html .= 'Your Name (required)<br />';
	$html .= '<input type="text" name="wpscf-name" pattern="[a-zA-Z0-9]+" value="' . ( isset( $_POST["wpscf-name"]) ? esc_attr( $_POST["wpscf-name"] ) : '') . '" size="40" />';
	$html .= '</p>';
	$html .= '<p>';
	$html .= 'Your Email (required) <br />';
	$html .= '<input type="email" name="wpscf-email" value="' . ( isset( $_POST["wpscf-email"] ) ? esc_attr( $_POST["wpscf-email"] ) : '') . '" size="40" />';
	$html .= '</p>';
	$html .= '<p>';
	$html .= 'Subject (required) <br />';
	$html .= '<input type="text" name="wpscf-subject" pattern="[a-zA-Z ]+" value="' . ( isset( $_POST["wpscf-subject"] ) ? esc_attr( $_POST["wpscf-subject"] ) : '' ) . '" size="40" />';
	$html .= '</p>';
	$html .= '<p>';
	$html .= 'Your Message (required) <br />';
	$html .= '<textarea rows="10" cols="35" name="wpscf-message">' . ( isset( $_POST["wpscf-message"] ) ? esc_attr( $_POST["wpscf-message"] ) : '' ) . '</textarea>';
	$html .= '</p>';
	$html .= '<p><input type="submit" name="wpscf-submitted" value="Send" /></p>';
	$html .= '</form>';

	echo $html;
}


/**
 * Deliver email containing message data
 * 
 */
function wpscf_deliver_mail() {

	// If the submit button is clicked, send the email
	if ( isset( $_POST['wpscf-submitted'] ) ) {

		// Sanitize form values
		$name = sanitize_text_field( $_POST['wpscf-name'] );
		$email = sanitize_email( $_POST['wpscf-email'] );
		$subject = sanitize_text_field( $_POST['wpscf-subject'] );
		$message = esc_textarea( $_POST['wpscf-message'] );

		// Get the site administrator's email address
		$to = get_option( 'admin_email' );

		$headers = 'From: $name <$email>' . '\r\n';

		// If email has been processed for sending, display a success message
		if ( wp_mail($to, $subject, $message, $headers ) ) {
			echo '<div';
			echo '<p>Thanks for contacting. We will respond soon.</p>';
			echo '</div>';
		} else {
			echo 'An unexpected error occured.';
		}

	}

}