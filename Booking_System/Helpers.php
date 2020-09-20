<?php

namespace Booking_System;

class Helpers {

	public static function build_attributes( $array ) {
		$atts = '';
		foreach ( $array as $key => $value ) {
			$atts .= $key . '="' . htmlspecialchars( $value ) . '" ';
		}

		return $atts;
	}

	public static function log( $log_data ) {
		$log_filename = WCB_DIR . "log";
		if ( ! file_exists( $log_filename ) ) {
			// create directory/folder uploads.
			mkdir( $log_filename, 0777, true );
		}
		$log_file_data = $log_filename . '/log_' . date( 'd-M-Y' ) . '.log';
		// if you don't add `FILE_APPEND`, the file will be erased each time you add a log
		file_put_contents( $log_file_data, 'Info : ' . date( "d/m/Y h:i:sa" ) . ' ' . print_r( $log_data, true ) . "\n", FILE_APPEND );
	}

	public static function is_bookable( $product_id ) {
		return get_field( 'bookable', $product_id );
	}

}