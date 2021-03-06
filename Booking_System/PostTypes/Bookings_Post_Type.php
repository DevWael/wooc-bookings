<?php

namespace Booking_System\PostTypes;

class Bookings_Post_Type {

	protected $post_type_id = 'wcb-bookings';

	protected function labels() {
		return array(
			'name'          => __( 'Bookings', 'wcb' ),
			'singular_name' => __( 'Booking', 'wcb' ),
		);
	}

	protected function args() {
		return array(
			'labels'             => $this->labels(),
			'public'             => false,
			'publicly_queryable' => false,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'show_in_rest '      => false,
			'menu_position'      => null,
			'supports'           => array( 'title' ),
		);
	}

	public function register() {
		register_post_type( $this->post_type_id, $this->args() );
	}
}