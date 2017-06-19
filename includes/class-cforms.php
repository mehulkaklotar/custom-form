<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'CForms' ) ) {

	class CForms {

		/**
		 * @var CForms The single instance of the class
		 * @since 1.0.0
		 */
		protected static $_instance = null;

		/**
		 * Main CForms Instance
		 *
		 * Ensures only one instance of CForms is loaded or can be loaded.
		 *
		 * @since 1.0.0
		 * @static
		 * @return CForms - Main instance
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
			add_shortcode( 'customer_form', array( $this, 'customer_form' ) );
			add_shortcode( 'cform_name', array( $this, 'cform_name' ) );
			add_shortcode( 'cform_phone', array( $this, 'cform_phone' ) );
			add_shortcode( 'cform_email', array( $this, 'cform_email' ) );
			add_shortcode( 'cform_budget', array( $this, 'cform_budget' ) );
			add_shortcode( 'cform_message', array( $this, 'cform_message' ) );
		}

		/**
		 * customer form on front end
		 *
		 * @param $atts
		 *
		 * @return string
		 */
		public static function customer_form( $atts, $content = '' ) {

			$atts = shortcode_atts( array(
				'title' => 'Customer Form',
			), $atts, 'customer_form' );

			wp_enqueue_script( 'cform_script', CFORM_PLUGIN_URL . '/js/scripts.js' );
			wp_enqueue_style( 'cform_style', CFORM_PLUGIN_URL . '/css/style.css' );

			wp_localize_script( 'cform_script',
				'cform_vars',
				array(
					'ajaxURL' => admin_url( 'admin-ajax.php' )
				)
			);

			ob_start();
			?>
			<form method="post" id="cform">

				<h1><?php _e( $atts['title'], 'cform' ); ?></h1>

				<fieldset>
					<?php echo do_shortcode( $content ); ?>
				</fieldset>

				<input type="hidden" name="action" value="cform_submit" />
				<input type="hidden" name="cform[time]" id="cform_time" value="" />
				<input type="hidden" name="cform[nonce]" value="<?php echo wp_create_nonce( 'cform_submit' ); ?>" />

				<button name="cform_submit" id="cform_submit" type="submit"><?php _e('Submit', 'cform' ); ?></button>
			</form>
			<?php

			return ob_get_clean();

		}

		/**
		 * customer form name field
		 *
		 * @param $atts
		 *
		 * @return string
		 */
		public static function cform_name( $atts ) {

			$atts = shortcode_atts( array(
				'title' => 'Name',
				'maxlength' => '50',
				'required' => '1'
			), $atts, 'cform_name' );

			ob_start();
			?>
			<label for="cform_name"><?php echo $atts['title']; ?>:
			<input type="text" <?php echo '1' == $atts['required'] ? 'required="required"' : ''; ?> name="cform[name]" maxlength="<?php echo $atts['maxlength']; ?>" />
			</label>
			<?php

			return ob_get_clean();

		}

		/**
		 * customer form phone field
		 *
		 * @param $atts
		 *
		 * @return string
		 */
		public static function cform_phone( $atts ) {

			$atts = shortcode_atts( array(
				'title' => 'Phone Number',
				'maxlength' => '10',
				'required' => '1'
			), $atts, 'cform_phone' );

			ob_start();
			?>
			<label for="cform_phone"><?php echo $atts['title']; ?>:
				<input type="number" <?php echo '1' == $atts['required'] ? 'required="required"' : ''; ?> name="cform[phone]" maxlength="<?php echo $atts['maxlength']; ?>" />
			</label>
			<?php

			return ob_get_clean();

		}

		/**
		 * customer form email field
		 *
		 * @param $atts
		 *
		 * @return string
		 */
		public static function cform_email( $atts ) {

			$atts = shortcode_atts( array(
				'title' => 'Email Address',
				'maxlength' => '50',
				'required' => '1'
			), $atts, 'cform_email' );

			ob_start();
			?>
			<label for="cform_email"><?php echo $atts['title']; ?>:
				<input type="email" <?php echo '1' == $atts['required'] ? 'required="required"' : ''; ?> name="cform[email]" maxlength="<?php echo $atts['maxlength']; ?>" />
			</label>
			<?php

			return ob_get_clean();

		}

		/**
		 * customer form email field
		 *
		 * @param $atts
		 *
		 * @return string
		 */
		public static function cform_budget( $atts ) {

			$atts = shortcode_atts( array(
				'title' => 'Desired Budget',
				'maxlength' => '50',
				'required' => '1'
			), $atts, 'cform_budget' );

			ob_start();
			?>
			<label for="cform_budget"><?php echo $atts['title']; ?>:
				<input type="number" <?php echo '1' == $atts['required'] ? 'required="required"' : ''; ?> name="cform[budget]" maxlength="<?php echo $atts['maxlength']; ?>" />
			</label>
			<?php

			return ob_get_clean();

		}


		/**
		 * customer form message field
		 *
		 * @param $atts
		 *
		 * @return string
		 */
		public static function cform_message( $atts ) {

			$atts = shortcode_atts( array(
				'title' => 'Message',
				'rows' => '10',
				'cols' => '100',
				'required' => '1'
			), $atts, 'cform_message' );

			ob_start();
			?>
			<label for="cform_message"><?php echo $atts['title']; ?>:
			<textarea name="cform[message]" <?php echo '1' == $atts['required'] ? 'required="required"' : ''; ?> rows="<?php echo $atts['rows']; ?>" cols="<?php echo $atts['cols']; ?>"></textarea>
			</label>
			<?php

			return ob_get_clean();

		}
	}

	/**
	 * Returns the main instance of CForms to prevent the need to use globals.
	 *
	 * @since  1.0.0
	 * @return CForms
	 */
	function cforms() {
		return CForms::instance();
	}
	cforms();

}