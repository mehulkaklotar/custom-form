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
			add_action( 'init', array( $this, 'init' ), 0 );
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
			require_once( 'includes/admin/class-cforms-admin.php' );
			require_once( 'includes/class-cforms.php' );
		}

		/**
		 * Init when WordPress Initialises.
		 */
		public function init() {
			$this->customer_post_type();
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

		public function customer_post_type() {

			// Set UI labels for Custom Post Type
			$labels = array(
				'name'               => _x( 'Customers', 'Customers', 'cforms' ),
				'singular_name'      => _x( 'Customer', 'Customer', 'cforms' ),
				'menu_name'          => __( 'Customer', 'cforms' ),
				'all_items'          => __( 'All Customers', 'cforms' ),
				'view_item'          => __( 'View Customer', 'cforms' ),
				'add_new_item'       => __( 'Add New Customer', 'cforms' ),
				'add_new'            => __( 'Add New', 'cforms' ),
				'edit_item'          => __( 'Edit Customer', 'cforms' ),
				'update_item'        => __( 'Update Customer', 'cforms' ),
				'search_items'       => __( 'Search Customer', 'cforms' ),
				'not_found'          => __( 'Not Found', 'cforms' ),
				'not_found_in_trash' => __( 'Not found in Trash', 'cforms' ),
			);

			// Set other options for Custom Post Type

			$args = array(
				'label'               => __( 'customers', 'cforms' ),
				'description'         => __( 'Customer news and reviews', 'cforms' ),
				'labels'              => $labels,
				// Features this CPT supports in Post Editor
				'supports'            => array(
					'title',
					'editor',
					'excerpt',
					'author',
					'thumbnail',
					'comments',
					'revisions',
					'custom-fields',
				),
				'hierarchical'        => false,
				'public'              => false,
				'show_ui'             => true,
				'show_in_menu'        => true,
				'show_in_nav_menus'   => true,
				'show_in_admin_bar'   => true,
				'menu_position'       => 5,
				'can_export'          => true,
				'has_archive'         => true,
				'exclude_from_search' => true,
				'publicly_queryable'  => false,
				'rewrite' => array( 'slug' => 'customer' ),
			);

			// Registering your Customer Post Type
			register_post_type( 'customer', $args );

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



