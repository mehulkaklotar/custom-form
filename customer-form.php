<?php

/**
 * Plugin Name: Customer Form
 * Plugin URI: https://github.com/mehulkaklotar/customer-form
 * Description: A add on to create customer leads
 * Version: 1.0.0
 * Author: mehulkaklotar
 * Author URI: http://kaklo.me
 * Requires at least: 4.1
 * Tested up to: 4.8
 *
 * Text Domain: customer-form
 *
 * @package Customer_Form
 * @category Core
 * @author mehulkaklotar
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'Customer_Form' ) ) {

	/**
	 * Main Customer Form Class
	 *
	 * @class Customer_Form
	 * @version	1.0.0
	 */
	final class Customer_Form {

		/**
		 * @var string
		 */
		public $version = '1.0.0';
		/**
		 * @var Customer_Form The single instance of the class
		 * @since 1.0.0
		 */
		protected static $_instance = null;

		/**
		 * Main Customer_Form Instance
		 *
		 * Ensures only one instance of Customer_Form is loaded or can be loaded.
		 *
		 * @since 1.0.0
		 * @static
		 * @see customer_form()
		 * @return Customer_Form - Main instance
		 */
		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}


		/**
		 * Customer_Form Constructor.
		 */
		public function __construct() {
			$this->define_constants();
			$this->init_hooks();
		}

		/**
		 * Hook into actions and filters
		 * @since  0.1
		 */
		private function init_hooks() {
			add_action( 'init', array( $this, 'init' ) );
		}

		/**
		 * Define WCE Constants
		 */
		private function define_constants() {
			$this->define( 'CFORM_PLUGIN_FILE', __FILE__ );
			$this->define( 'CFORM_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
			$this->define( 'CFORM_VERSION', $this->version );
			$this->define( 'CFORM_TEXT_DOMAIN', 'cforms' );
			$this->define( 'CFORM_PLUGIN_PATH', $this->plugin_path() );
			$this->define( 'CFORM_PLUGIN_URL', $this->plugin_url() );
		}

		/**
		 * Define constant if not already set
		 * @param  string $name
		 * @param  string|bool $value
		 */
		private function define( $name, $value ) {
			if ( ! defined( $name ) ) {
				define( $name, $value );
			}
		}

		/**
		 * Include required core files used in admin and on the frontend.
		 */
		public function includes() {
			include_once( 'admin/class-cforms-admin.php' );
		}

		/**
		 * Init WooCommerce when WordPress Initialises.
		 */
		public function init() {
			$this->includes();
		}

		/**
		 * Get the plugin url.
		 * @return string
		 */
		public function plugin_url() {
			return untrailingslashit( plugins_url( '/', __FILE__ ) );
		}

		/**
		 * Get the plugin path.
		 * @return string
		 */
		public function plugin_path() {
			return untrailingslashit( plugin_dir_path( __FILE__ ) );
		}

	}

	/**
	 * Returns the main instance of Customer_Form to prevent the need to use globals.
	 *
	 * @since  1.0.0
	 * @return Customer_Form
	 */
	function customer_form() {
		return Customer_Form::instance();
	}

	customer_form();

}



