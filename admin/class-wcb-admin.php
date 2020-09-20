<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://github.com/DevWael
 * @since      1.0.0
 *
 * @package    Wcb
 * @subpackage Wcb/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wcb
 * @subpackage Wcb/admin
 * @author     Ahmad Wael <dev.ahmedwael@gmail.com>
 */
class Wcb_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $plugin_name The name of this plugin.
	 * @param string $version The version of this plugin.
	 *
	 * @since    1.0.0
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wcb_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wcb_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_style( 'wcb_fullcalendar_css', 'https://cdn.jsdelivr.net/npm/fullcalendar@5.3.2/main.min.css' );
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wcb-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wcb_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wcb_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( 'wcb_fullcalendar_js', 'https://cdn.jsdelivr.net/npm/fullcalendar@5.3.2/main.min.js', array( 'jquery' ) );
		wp_enqueue_script( 'wcb_fullcalendar_locals_js', 'https://cdn.jsdelivr.net/npm/fullcalendar@5.3.2/locales-all.min.js', array( 'jquery' ) );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wcb-admin.js', array( 'jquery' ), $this->version, false );

	}

	public function bookings_calendar_page() {
		add_submenu_page( 'edit.php?post_type=wcb-bookings', __( 'Booking Calendar', 'wcb' ), __( 'Booking Calendar', 'wcb' ), 'manage_options', 'wcb-bookings-calendar', array(
			$this,
			'bookings_calendar_page_content'
		) );
	}

	public function bookings_calendar_page_content() {
		$events = array();
		$args   = array(
			'post_type'      => 'wcb-bookings',
			'posts_per_page' => - 1,
			'date_query'     => array(
				array(
					'after' => strtotime( " -2 months" )
				),
			),
		);
		$query  = new WP_Query( $args );
		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				$events[] = array(
					'title' => get_the_title(),
					'start' => get_field( 'booking_date' ) . ' ' . ( get_field( 'booking_time' ) + 12 ) . ':00',
					'url'   => add_query_arg( array(
						'post'   => get_post_meta( get_the_ID(), 'wcb_booking_order_id', true ),
						'action' => 'edit',
					), admin_url( 'post.php' ) )
				);
			}
		}
		wp_reset_postdata();
		?>
        <div class="wrap">
            <div id="calendar"></div>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var calendarEl = document.getElementById('calendar');
                var calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',
                    locale: '<?php echo get_locale();?>',
                    firstDay: 0,
                    events: <?php echo json_encode( $events )?>
                });
                calendar.render();
            });
        </script>
		<?php
	}
}
