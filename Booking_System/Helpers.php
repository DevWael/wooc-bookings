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

}