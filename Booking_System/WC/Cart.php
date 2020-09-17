<?php


namespace Booking_System\WC;


use Booking_System\Utilities\Booking_Settings;

class Cart {
	/**
	 * add user selected option data to cart
	 *
	 * @param $cart_item_data
	 * @param $product_id
	 * @param $variation_id
	 *
	 * @return mixed
	 */
	public function add_cart_item_data( $cart_item_data, $product_id, $variation_id ) {

		if ( get_field( 'bookable', $product_id ) ) {
			$booking_settings_id = get_field( 'booking_option', $product_id );
			$booking_settings    = new Booking_Settings( $booking_settings_id );
			$max_persons         = $booking_settings->get_max_persons();
			$extra_person_price  = $booking_settings->get_extra_person_price();

		}

		return $cart_item_data;
	}

	/**
	 * Validate booking data
	 */
	function validate_cart_data( $passed, $product_id, $quantity, $variation_id = null ) {
		if ( get_field( 'bookable', $product_id ) ) {
			if ( ! isset( $_POST['wcd_persons_count'] ) || empty( $_POST['wcd_persons_count'] ) || ! is_numeric( $_POST['wcd_persons_count'] ) ) {
				$passed = false;
				wc_add_notice( __( 'Persons count is required', 'wcb' ), 'error' );
			}

			if ( ! isset( $_POST['wcd_date'] ) or empty( $_POST['wcd_date'] ) ) {
				$passed = false;
				wc_add_notice( __( 'Booking date is required', 'wcb' ), 'error' );
			}

			if ( ! isset( $_POST['wcb_time_select'] ) or empty( $_POST['wcb_time_select'] ) ) {
				$passed = false;
				wc_add_notice( __( 'Booking time is required', 'wcb' ), 'error' );
			}
		}

		return $passed;
	}
}