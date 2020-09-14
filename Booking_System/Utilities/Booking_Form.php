<?php

namespace Booking_System\Utilities;

class Booking_Form {

	private $product_id;
	private $is_bookable;
	private $booking_setting_id;
	private $booking_settings;

	public function __construct( $product_id ) {
		$this->product_id = $product_id;
		$this->is_bookable();
		$this->booking_setting_id();
		$this->booking_settings = new Booking_Settings( $this->booking_setting_id );
	}

	public function is_bookable() {
		$this->is_bookable = get_field( 'bookable', $this->product_id );
	}

	public function booking_setting_id() {
		$this->booking_setting_id = get_field( 'booking_option', $this->product_id );
	}

	private function persons_count_field() {

	}

	public function generate_booking_form() {
		if ( $this->is_bookable ) {

		}
	}
}