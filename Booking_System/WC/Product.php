<?php

namespace Booking_System\WC;

use Booking_System\Utilities\Booking_Form;

class Product {

	private $product_id;

	public function __construct() {
		$this->product_id = get_the_ID();
	}

	public function booking_form() {
		if ( $this->product_id ) {
			$form = new Booking_Form( $this->product_id );
		}
	}

}