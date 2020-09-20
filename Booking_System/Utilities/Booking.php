<?php

namespace Booking_System\Utilities;

class Booking {
	protected $bookings_storage = 'wcb-bookings';

	private $order_id;
	private $booking_product_id;
	private $booking_id;
	private $booking_date;
	private $booking_time;
	private $persons_count;

	public function __construct( $booking_id = '' ) {
		$this->booking_id = $booking_id;
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

	private function save_booking_to_order( $order_id, $booking_id ) {
		update_post_meta( $order_id, 'wcb_booking_id', $booking_id );
	}

	private function delete_booking_from_order( $order_id ) {
		delete_post_meta( $order_id, 'wcb_booking_id' );
	}

	public function save() {
		$booking_data = array(
			'post_title'  => '#' . $this->order_id,
			'post_status' => 'publish',
			'post_type'   => $this->bookings_storage,
		);

		$booking_id = wp_insert_post( $booking_data, true );

		if ( is_wp_error( $booking_id ) ) {
			return $booking_id;
		}

		update_field( 'booking_date', $this->booking_date, $booking_id );
		update_field( 'booking_time', $this->booking_time, $booking_id );
		update_field( 'persons_count', $this->persons_count, $booking_id );
		update_field( 'booking_product_id', $this->booking_product_id, $booking_id );

		$this->save_booking_to_order( $this->order_id, $booking_id );

		return $booking_id;
	}

	public function delete_booking() {
		wp_delete_post( $this->booking_id, true );
		$this->delete_booking_from_order( $this->order_id );
	}

}