<?php

namespace Booking_System\WC;

use Booking_System\Helpers;
use Booking_System\Utilities\Booking;

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

	private function save_booking( $order_id, $booking_date, $booking_time, $persons_count, $product_id ) {
		$booking = new Booking();
		$booking->set_booking_date( $booking_date );
		$booking->set_booking_time( $booking_time );
		$booking->set_persons_count( $persons_count );
		$booking->set_order_id( $order_id );
		$booking->set_booking_product_id( $product_id );
		$booking->save();
	}

	private function remove_booking( $order_id ) {
		$booking_id = get_post_meta( $order_id, 'wcb_booking_id', true );
		$booking    = new Booking( $booking_id );
		$booking->set_order_id( $order_id );
		$booking->delete_booking();
	}

	public function create_booking( $order_id, $old_status, $new_status ) {
		if ( $new_status == 'completed' ) {
			$order = wc_get_order( $order_id );
			foreach ( $order->get_items() as $item_id => $item ) {
				$product_id = $item->get_product_id();
				if ( Helpers::is_bookable( $product_id ) ) {
					$booking_date  = $item->get_meta( __( 'Booking Date', 'wcb' ), true );
					$booking_time  = $item->get_meta( __( 'Booking Time', 'wcb' ), true );
					$persons_count = $item->get_meta( __( 'Persons Count', 'wcb' ), true );
					$this->save_booking( $order_id, $booking_date, $booking_time, $persons_count, $product_id );
				}
			}
		}
	}

	public function delete_booking( $order_id, $old_status, $new_status ) {
		if ( $new_status != 'completed' ) {
			$order = wc_get_order( $order_id );
			foreach ( $order->get_items() as $item_id => $item ) {
				$product_id = $item->get_product_id();
				if ( Helpers::is_bookable( $product_id ) ) {
					$this->remove_booking( $order_id );
				}
			}
		}
	}
}