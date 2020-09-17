<?php


namespace Booking_System\WC;


class Checkout {
	/**
	 * disable cash on delivery (cod) payment gateway if user has booking product in his cart
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
}