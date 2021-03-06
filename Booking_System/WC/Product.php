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

	public function book_now_text( $default ) {
		global $post;
		$product = wc_get_product( $post->ID );
		if ( get_field( 'bookable', $product->get_id() ) ) {
			return __( 'Book Now', 'wcb' );
		}

		return $default;
	}

	function remove_quantity( $return, $product ) {
		$product_id = $product->get_id();
		if ( get_field( 'bookable', $product_id ) ) {
			return true;
		}

		return $return;
	}

	function cart_redirect_checkout( $url ) {
		if ( ! isset( $_REQUEST['add-to-cart'] ) || ! is_numeric( $_REQUEST['add-to-cart'] ) ) {
			return $url;
		}
		$product_id = apply_filters( 'woocommerce_add_to_cart_product_id', absint( $_REQUEST['add-to-cart'] ) );
		if ( get_field( 'bookable', $product_id ) ) {
			return wc_get_checkout_url();
		}

		return $url;
	}

	function redirect_to_checkout( $add_to_cart_url, $product ) { //todo check this
		if ( $product->get_sold_individually() // if individual product
		     && WC()->cart->find_product_in_cart( WC()->cart->generate_cart_id( $product->id ) ) // if in the cart
		     && $product->is_purchasable() // we also need these two conditions
		     && $product->is_in_stock() ) {
			$add_to_cart_url = wc_get_checkout_url();
		}

		return $add_to_cart_url;
	}

}