<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'CForms_Admin' ) ) {

	class CForms_Admin {

		/**
		 * @var CForms_Admin The single instance of the class
		 * @since 1.0.0
		 */
		protected static $_instance = null;

		/**
		 * Main CForms_Admin Instance
		 *
		 * Ensures only one instance of CForms_Admin is loaded or can be loaded.
		 *
		 * @since 1.0.0
		 * @static
		 * @return CForms_Admin - Main instance
		 */
		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}

			return self::$_instance;
		}

		/**
		 * Constructor
		 */
		public function __construct() {
			add_action( 'wp_ajax_cform_submit', array( $this, 'cform_submit' ) );
			add_action( 'wp_ajax_nopriv_cform_submit', array( $this, 'cform_submit' ) );
		}

		public function cform_submit() {

			if ( empty( $_POST['cform'] ) ) {
				return new WP_Error( 'error', __( 'invalid_request' ) );
			}

			$data = $_POST['cform'];

			if ( ! wp_verify_nonce( $data['nonce'],'cform_submit' ) ) {
				return new WP_Error( 'error', __( 'invalid_request' ) );
			}

			foreach( $data as $d ) {
				if ( empty( $d ) ) {
					wp_send_json( array( 'error' => 'please submit all details!' ) );
				}
			}

			$post_id = wp_insert_post( array(
				'post_type'      => 'customers',
				'post_title'     => $data['name'],
				'post_content'   => $data['message'],
				'post_status'    => 'publish',
				'comment_status' => 'closed',   // if you prefer
				'ping_status'    => 'closed',      // if you prefer
			) );

			if ( is_wp_error( $post_id ) ) {
				return new WP_Error( 'error', __( 'invalid_request' ) );
			}

			// insert post meta
			add_post_meta( $post_id, 'phone_number', $data['phone'] );
			add_post_meta( $post_id, 'email', $data['email'] );
			add_post_meta( $post_id, 'budget', $data['budget'] );
			add_post_meta( $post_id, 'date_time', $data['time'] );

			wp_send_json( array( 'success' => 1 ) );

		}

	}

	/**
	 * Returns the main instance of CForms_Admin to prevent the need to use globals.
	 *
	 * @since  1.0.0
	 * @return CForms_Admin
	 */
	function cforms_admin() {
		return CForms_Admin::instance();
	}

	cforms_admin();

}