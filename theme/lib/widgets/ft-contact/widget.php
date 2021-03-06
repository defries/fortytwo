<?php
/**
 * FortyTwo Theme
 *
 * @package FortyTwo
 * @author  Forsite Themes
 * @license GPL-2.0+
 * @link    http://forsitethemes/themes/fortytwo/
 */

/**
 * Contact Widget.
 *
 * @package FortyTwo
 * @author  Forsite Themes
 */
class FT_Widget_Contact extends FT_Widget {
	/**
	 * Widget slug / directory name.
	 *
	 * @since @@release
	 *
	 * @var string
	 */
	protected $slug = 'ft-contact';

	/**
	 * Instantiate the widget class.
	 *
	 * @since @@release
	 */
	public function __construct() {
		$this->defaults = array(
			'title'   => '',
			'name'    => '',
			'phone'   => '',
			'email'   => '',
			'fax'     => '',
			'address' => '',
			'zip'     => '',
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
	 * Update a particular instance.
	 * 
	 * This function should check that $new_instance is set correctly.
	 * The newly calculated value of $instance should be returned.
	 * If "false" is returned, the instance won't be saved/updated.
	 *
	 * @since @@release
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
}

add_action( 'widgets_init', 'ft_register_widget_contact' );
/**
 * Register the FT Contact widget.
 *
 * @since @@release
 */
function ft_register_widget_contact() {
	register_widget( 'FT_Widget_Contact' );
}
