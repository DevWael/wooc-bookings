<?php

namespace Booking_System\WC;

class Order {

	/**
	 * display booking data on order details
	 */
	public function order_data( $item, $cart_item_key, $values, $order ) {
		if ( isset( $values['booking_date'] ) ) {
			$item->add_meta_data(
				__( 'Booking Date', 'wcb' ),
				$values['booking_date'],
				true
			);
		}

		if ( isset( $values['booking_time'] ) ) {
			$item->add_meta_data(
				__( 'Booking Time', 'wcb' ),
				$values['booking_time'],
				true
			);
		}

		if ( isset( $values['persons_count'] ) ) {
			$item->add_meta_data(
				__( 'Persons Count', 'wcb' ),
				$values['persons_count'],
				true
			);
		}

		if ( isset( $values['person_price'] ) ) {
			$max_persons   = $values['max_persons_count'];
			$persons_count = $values['persons_count'];
			$person_price  = $values['person_price'];
			if ( $persons_count > $max_persons ) {
				$extra_persons = $persons_count - $max_persons;
				$extra_price   = $person_price * $extra_persons;
				$item->add_meta_data(
					__( 'Extra Persons Price', 'wcb' ),
					$extra_price . ' ' . get_woocommerce_currency_symbol(),
					true
				);
			}
		}
	}

	/**
	 * display booking data on order email
	 */
	public function order_email_data( $product_name, $values ) {
		if ( isset( $item['booking_date'] ) ) {
			$product_name .= sprintf(
				'<ul><li>%s: %s</li></ul>',
				__( 'Booking Date', 'wcb' ),
				esc_html( $values['booking_date'] )
			);
		}

		if ( isset( $item['booking_time'] ) ) {
			$product_name .= sprintf(
				'<ul><li>%s: %s</li></ul>',
				__( 'Booking Time', 'wcb' ),
				esc_html( $values['booking_time'] )
			);
		}

		if ( isset( $item['persons_count'] ) ) {
			$product_name .= sprintf(
				'<ul><li>%s: %s</li></ul>',
				__( 'Persons Count', 'wcb' ),
				esc_html( $values['persons_count'] )
			);
		}

		if ( isset( $item['person_price'] ) ) {
			$max_persons   = $values['max_persons_count'];
			$persons_count = $values['persons_count'];
			$person_price  = $values['person_price'];
			if ( $persons_count > $max_persons ) {
				$extra_persons = $persons_count - $max_persons;
				$extra_price   = $person_price * $extra_persons;
				$product_name  .= sprintf(
					'<ul><li>%s: %s</li></ul>',
					__( 'Extra Persons Price', 'wcb' ),
					$extra_price . ' ' . get_woocommerce_currency_symbol()
				);
			}
		}

		return $product_name;
	}
}