<?php
/**
 * FortyTwo Theme: Adds a Schema.org compliant contact widget.
 *
 * @package FortyTwo\Widgets
 * @author  Forsite Themes
 * @license GPL-2.0+
 * @link    http://forsitethemes/themes/fortytwo/
 */

/**
 * ForSite Themes Contact widget class.
 *
 * @package FortyTwo\Widgets
 * @author  Forsite Themes
 */
class FT_Widget_Contact extends FT_Widget {

	/**
	 * Widget slug / directory name.
	 *
	 * @var string
	 */
	protected $slug = 'ft-contact';

	/**
	 * Instantiate the widget class.
	 */
	public function __construct() {
		$this->defaults = array(
			'name'    => '',
			'phone'   => '',
			'email'   => '',
			'fax'     => '',
			'address' => '',
			'pc'      => '',
			'city'    => '',
		);

		parent::__construct(
			$this->slug,
			__( '42 - Contact Information', 'fortytwo' ),
			array(
				'classname'   => 'widget-' . $this->slug,
				'description' => __( 'A Schema.org compliant Contact Widget', 'fortytwo' )
			)
		);
	}

	/**
	 * Echo the settings update form.
	 *
	 * @param array $instance Current settings.
	 */
	public function form( $instance ) {
		$instance = wp_parse_args( $instance, $this->defaults );

		include dirname( __FILE__ ) . '/views/form.php';
	}

	/**
	 * Update a particular instance.
	 * 
	 * This function should check that $new_instance is set correctly.
	 * The newly calculated value of $instance should be returned.
	 * If "false" is returned, the instance won't be saved/updated.
	 *
	 * @param array $new_instance New settings for this instance as input by the user via form().
	 * @param array $old_instance Old settings for this instance.
	 * 
	 * @return array Settings to save or bool false to cancel saving.
	 */
	public function update( $new_instance, $old_instance ) {
		foreach ( $this->get_fields() as $field ) {
			$new_instance[ $field ] = strip_tags( $new_instance[ $field ] );
		}

		return $new_instance;
	}

	/**
	 * Echo the widget content.
	 *
	 * @param array   $args     Display arguments including before_title, after_title, before_widget, and after_widget.
	 * @param array   $instance The settings for the particular instance of the widget
	 */
	public function widget( $args, $instance ) {
		$instance = wp_parse_args( $instance, $this->defaults );

		echo $args['before_widget'];
		include dirname( __FILE__ ) . '/views/widget.php';
		echo $args['after_widget'];
	}

	/**
	 * Registers and enqueues admin-specific styles.
	 */
	public function admin_styles() {
		wp_enqueue_style( $this->slug . '-admin', $this->url( 'css/admin.css' ) );
	}
}

add_action( 'widgets_init', create_function( '', 'register_widget("FT_Widget_Contact");' ) );
