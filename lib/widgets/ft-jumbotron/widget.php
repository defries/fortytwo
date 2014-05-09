<?php
/**
 * FortyTwo Theme: Jumbotron Widget
 *
 * This file creates the Jumbotron Widget
 *
 * @package FortyTwo\Widgets
 * @author  Forsite Themes
 * @license GPL-2.0+
 * @link    http://forsitethemes/themes/fortytwo/
 */

class FT_Jumbotron extends FT_Widget {

	/**
	 * Specifies the classname and description, instantiates the widget,
	 * loads localization files, and includes necessary stylesheets and JavaScript.
	 */
	public function __construct() {

		parent::__construct(
			'widget-ft-jumbotron',
			__( '42&nbsp;&nbsp;- Jumbotron', 'fortytwo' ),
			array(
				'classname'   => 'ft-jumbotron',
				'description' => __( 'Jumbotron widget for the FortyTwo Theme.', 'fortytwo' )
			)
		);

		// Register admin styles and scripts
		add_action( 'admin_print_styles', array( $this, 'register_admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_scripts' ) );

	}

	/**
	 * Helper method to echo both the id= and name= attributes for a field input element
	 *
	 * @param string  field The field name
	 *
	 */
	public function echo_field_id( $field ) {
		echo ' id="' . $this->get_field_id( $field ). '" name="' . $this->get_field_name( $field ) . '" ';
	}

	/**
	 * Outputs the content of the widget.
	 *
	 * @param array   args  The array of form elements
	 * @param array   instance The current instance of the widget
	 */
	public function widget( $args, $instance ) {

		echo $args['before_widget'];

		foreach ( array( 'title', 'content', 'button_text', 'button_link', 'button_alignment' ) as $field_name ) {
			$instance[ $field_name ] = apply_filters( 'widget_$field_name', $instance[ $field_name ] );
		}
		$this->set_default( $instance['title'], __( 'Announcing the most important product feature', 'fortytwo' ) );
		$this->set_default( $instance['content'], __( 'And purely one near this hey therefore darn firefly had ducked overpaid wow!', 'fortytwo' ) );
		$this->set_default( $instance['button_text'], __( 'Purchase Today !', 'fortytwo' ) );
		$this->set_default( $instance['button_link'], '#' );
		$this->set_default( $instance['button_alignment'], 'right' );

		include dirname( __FILE__ ) . '/views/widget.php';

		echo $args['after_widget'];

	}

	/**
	 * Processes the widget's options to be saved.
	 *
	 * @param array   new_instance The previous instance of values before the update.
	 * @param array   old_instance The new instance of values to be generated via the update.
	 */
	public function update( $new_instance, $old_instance ) {

		$instance = $old_instance;

		foreach ( array(
			'title',
			'content',
			'button_text',
			'button_link',
			'button_alignment',
			) as $field_name ) {
			$instance[ $field_name ] = ( ! empty( $new_instance[ $field_name ] ) ) ? strip_tags( $new_instance[ $field_name ] ) : '';
		}

		return $instance;

	}

	/**
	 * Generates the administration form for the widget.
	 *
	 * @param array   instance The array of keys and values for the widget.
	 */
	public function form( $instance ) {
		$instance = wp_parse_args(
			(array) $instance,
			array(
				'title'            => '',
				'content'          => '',
				'button_alignment' => 'right',
				'button_text'      => '',
				'button_link'      => '',
			)
		);

		// Display the admin form
		include dirname( __FILE__ )  . '/views/form.php';

	}

	/**
	 * Registers and enqueues admin-specific styles.
	 */
	public function register_admin_styles() {

		wp_enqueue_style( 'ft-jumbotron-admin-styles', $this->url( 'css/admin.css' ) );

	}

	/**
	 * Registers and enqueues admin-specific JavaScript.
	 */
	public function register_admin_scripts() {
	}

	/**
	 * Registers and enqueues widget-specific styles.
	 */
	public function register_widget_styles() {

		wp_enqueue_style( 'ft-jumbotron-widget-styles', $this->url( 'css/widget.css' ) );

	}

	/**
	 * Registers and enqueues widget-specific scripts.
	 */
	public function register_widget_scripts() {

		wp_enqueue_script( 'ft-jumbotron-script', modules_url( 'ft-jumbotron/js/widget.js' ) );

	}

		/**
	 * Returns an absolute URL to a file releative to the widget's folder
	 *
	 * @param string  file The file path (relative to the widgets folder)
	 *
	 * @return string
	 */
	protected function url( $file ) {
		return trailingslashit( FORTYTWO_WIDGETS_URL ) . 'ft-jumbotron/' . $file;
	}

	/**
	 * Set a default value for an empty variable
	 *
	 * @param mixed   value The variable whoes default should be set.  NB!  This variable's value is set to default if empty()
	 * @param mixed   default The default value
	 */
	protected function set_default( &$value, $default ) {
		if ( empty ( $value ) ) {
			$value = $default;
		}
	}


}

add_action( 'widgets_init', create_function( '', 'register_widget("FT_Jumbotron");' ) );
