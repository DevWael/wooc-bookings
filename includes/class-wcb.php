<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://github.com/DevWael
 * @since      1.0.0
 *
 * @package    Wcb
 * @subpackage Wcb/includes
 */

use Booking_System\PostTypes\Booking_Items_Post_Type;
use Booking_System\PostTypes\Bookings_Post_Type;

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Wcb
 * @subpackage Wcb/includes
 * @author     Ahmad Wael <dev.ahmedwael@gmail.com>
 */
class Wcb {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Wcb_Loader $loader Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $plugin_name The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $version The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'WCB_VERSION' ) ) {
			$this->version = WCB_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'wcb';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		$this->load_acf();
		$this->booking_items();
		$this->bookings();
		$this->product_booking_form();
		$this->cart_process();
		$this->checkout_customizations();
		$this->order_data();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Wcb_Loader. Orchestrates the hooks of the plugin.
	 * - Wcb_i18n. Defines internationalization functionality.
	 * - Wcb_Admin. Defines all hooks for the admin area.
	 * - Wcb_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wcb-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wcb-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wcb-admin.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/ACF_Integrate.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-wcb-public.php';

		$this->loader = new Wcb_Loader();

	}

	private function load_acf() {
		new ACF_Integrate( $this->get_plugin_name(), $this->get_version() );
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Wcb_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Wcb_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	private function booking_items() {
		$booking = new Booking_Items_Post_Type();

		$this->loader->add_action( 'init', $booking, 'register' );
	}

	private function bookings() {
		$booking = new Bookings_Post_Type();

		$this->loader->add_action( 'init', $booking, 'register' );
	}

	private function product_booking_form() {
		$product = new \Booking_System\WC\Product();

		$this->loader->add_action( 'woocommerce_before_add_to_cart_button', $product, 'booking_form' );
		$this->loader->add_filter( 'woocommerce_is_sold_individually', $product, 'remove_quantity', 10, 2 );
		$this->loader->add_filter( 'woocommerce_product_add_to_cart_url', $product, 'redirect_to_checkout', 10, 2 );
		$this->loader->add_filter( 'woocommerce_product_single_add_to_cart_text', $product, 'book_now_text', 10, 1 );
		$this->loader->add_filter( 'woocommerce_add_to_cart_redirect', $product, 'cart_redirect_checkout', 10, 1 );
	}

	private function cart_process() {
		$cart = new \Booking_System\WC\Cart();
		$this->loader->add_filter( 'woocommerce_add_cart_item_data', $cart, 'add_cart_item_data', 10, 3 );
		$this->loader->add_filter( 'woocommerce_add_to_cart_validation', $cart, 'validate_cart_data', 10, 4 );
		$this->loader->add_filter( 'woocommerce_before_calculate_totals', $cart, 'calculate_cart_total', 25, 3 );
		$this->loader->add_filter( 'woocommerce_get_item_data', $cart, 'display_cart_data', 10, 2 );
	}

	private function checkout_customizations() {
		$checkout = new \Booking_System\WC\Checkout();

		$this->loader->add_filter( 'woocommerce_available_payment_gateways', $checkout, 'disable_cod_on_booking', 10, 1 );
		$this->loader->add_filter( 'woocommerce_cart_needs_shipping_address', $checkout, 'disable_shipping_fields', 10, 1 );
	}

	private function order_data() {
		$order_data = new \Booking_System\WC\Order();
		$this->loader->add_action( 'woocommerce_checkout_create_order_line_item', $order_data, 'order_data', 10, 4 );
		$this->loader->add_action( 'woocommerce_order_status_changed', $order_data, 'create_booking', 10, 3 );
		$this->loader->add_action( 'woocommerce_order_status_changed', $order_data, 'delete_booking', 10, 3 );
		$this->loader->add_filter( 'woocommerce_order_item_name', $order_data, 'order_email_data', 10, 2 );
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Wcb_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'bookings_calendar_page' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Wcb_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @return    string    The name of the plugin.
	 * @since     1.0.0
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @return    Wcb_Loader    Orchestrates the hooks of the plugin.
	 * @since     1.0.0
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @return    string    The version number of the plugin.
	 * @since     1.0.0
	 */
	public function get_version() {
		return $this->version;
	}

}
