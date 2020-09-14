<?php
/**
 * retrieve booking settings with id
 */

namespace Booking_System\Utilities;

class Booking_Settings {
	private $booking_id;

	public function __construct( $booking_id ) {
		$this->booking_id = $booking_id;
	}

	public function is_setting_available() {
		if ( false === get_post_status( $this->booking_id ) ) {
			return false;
		}

		return true;
	}

	public function get_min_persons() {
		return get_field( 'min_persons', $this->booking_id );
	}

	public function get_max_persons() {
		return get_field( 'max_persons', $this->booking_id );
	}

	public function is_allowed_extra_persons() {
		return get_field( 'allow_extra', $this->booking_id );
	}

	public function get_extra_person_price() {
		return get_field( 'extra_person_price', $this->booking_id );
	}

	public function get_available_dates() {
		return get_field( 'allowed_dates', $this->booking_id );
	}

	public function get_available_hours() {
		return get_field( 'allowed_hours', $this->booking_id );
	}
}