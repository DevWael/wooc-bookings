<?php


namespace Booking_System\WC;


class Checkout {
	/**
	 * disable cash on delivery (cod) payment gateway if user has booking product in his cart
	 *
	 * @param $available_gateways
	 *
	 * @return mixed
	 */
	function disable_cod_on_booking( $available_gateways ) {
		global $woocommerce;
		$unset = false;
		foreach ( $woocommerce->cart->cart_contents as $key => $values ) {
			$product_id = $values['product_id'];
			if ( get_field( 'bookable', $product_id ) ) {
				$unset = true;
			}
		}
		if ( $unset == true ) {
			unset( $available_gateways['cod'] );
		}

		return $available_gateways;
	}

	/**
	 * disable shipping fields on checkout
	 * @param $needs_shipping_address
	 *
	 * @return false
	 */
	function disable_shipping_fields( $needs_shipping_address ) {
		// Loop through cart items
		foreach ( WC()->cart->get_cart() as $item ) {
			if ( get_field( 'bookable', $item['data']->get_id() ) ) {
				$needs_shipping_address = false;
				break; // Stop the loop
			}
		}

		return $needs_shipping_address;
	}
}