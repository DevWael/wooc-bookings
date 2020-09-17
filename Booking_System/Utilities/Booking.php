<?php

namespace Booking_System\Utilities;

class Booking {
	protected $bookings_storage = 'wcb-bookings';

	private $order_id;
	private $booking_product_id;
	private $booking_date;
	private $booking_time;
	private $persons_count;

	public function __construct( $booking = '' ) {

	}

	public function set_order_id( $order_id ) {
		$this->order_id = $order_id;
	}

	public function set_booking_product_id( $booking_product_id ) {
		$this->booking_product_id = $booking_product_id;
	}

	public function set_booking_date( $booking_date ) {
		$this->booking_date = $booking_date;
	}

	public function set_booking_time( $booking_time ) {
		$this->booking_time = $booking_time;
	}

	public function set_persons_count( $persons_count ) {
		$this->persons_count = $persons_count;
	}

	function save() {
		$booking_data = array(
			'post_title'  => $this->booking_date . ' :: ' . $this->booking_time,
			'post_status' => 'publish',
			'post_type'   => $this->bookings_storage,
			'meta_input'  => array(),
		);

		return wp_insert_post( $booking_data, true );
	}

}