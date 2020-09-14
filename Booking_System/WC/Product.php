<?php

namespace Booking_System\WC;

use Booking_System\Utilities\Booking_Form;

class Product {

	public function __construct() {

	}

	public function booking_form() {
		$form = new Booking_Form( get_the_ID() );
		$form->generate_booking_form();
	}

	public function book_now( $product ){
		return 'Book Now';
	}

}