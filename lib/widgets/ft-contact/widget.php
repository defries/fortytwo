<?php
/*
Description: FortyTwo Contact Widget
Author: Forsite Themes
Author URI: http://forsitethemes.com
Author Email: mail@forsitethemes.com
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Copyright 2013 mail@forsitethemes.com

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/**
 * Adds a Schema.org compliant contact widget.
 *
 * @package Genesis
 */

/**
 * ForSite Themes Contact widget class.
 *
 * @package Genesis
 * @subpackage Widgets
 * @since 0.1
 */
class FT_Contact_Widget extends WP_Widget {

	/*--------------------------------------------------*/
	/* Constructor
	/*--------------------------------------------------*/

	/**
	 * Specifies the classname and description, instantiates the widget,
	 * loads localization files, and includes necessary stylesheets and JavaScript.
	 */
	public function __construct() {

		parent::__construct(
			'widget-ft-contact',
			__( 'FortyTwo - Contact', 'fortytwo' ),
			array(
				'classname'  => 'ft-contact-widget',
				'description' => __( 'A Schema.org compliant Contact widget', 'fortytwo' )
			)
		);

	} // end constructor

	/**
	 * Echo the widget content.
	 *
	 * @param array   $args     Display arguments including before_title, after_title, before_widget, and after_widget.
	 * @param array   $instance The settings for the particular instance of the widget
	 */
	function widget( $args, $instance ) {
		extract( $args );

		$instance = wp_parse_args( (array) $instance, array(
				'name'		=> '',
				'phone'		=> '',
				'fax'		=> '',
				'email'		=> '',
				'address'	=> '',
				'pc'		=> '',
				'city'		=> ''

			) );

		echo $before_widget;
		include dirname( __FILE__ ) . '/views/widget.php';
		echo $after_widget;
	}

	 /** Update a particular instance.
	 *
	 * This function should check that $new_instance is set correctly.
	 * The newly calculated value of $instance should be returned.
	 * If "false" is returned, the instance won't be saved/updated.
	 *
	 * @param array   $new_instance New settings for this instance as input by the user via form()
	 * @param array   $old_instance Old settings for this instance
	 * @return array Settings to save or bool false to cancel saving
	 */
	function update( $new_instance, $old_instance ) {

		$new_instance['name']		= strip_tags( $new_instance['name'] );
		$new_instance['phone']		= strip_tags( $new_instance['phone'] );
		$new_instance['email']		= strip_tags( $new_instance['email'] );
		$new_instance['address']	= strip_tags( $new_instance['address'] );
		$new_instance['pc']			= strip_tags( $new_instance['pc'] );
		$new_instance['cityl']		= strip_tags( $new_instance['city'] );
		$new_instance['fax']		= strip_tags( $new_instance['fax'] );

		return $new_instance;
	}

	/** Echo the settings update form.
	 *
	 * @param array   $instance Current settings
	 */
	function form( $instance ) {

		$instance = wp_parse_args( (array) $instance, array(
				'name'		=> '',
				'phone'		=> '',
				'email'		=> '',
				'fax'		=> '',
				'address'	=> '',
				'pc'		=> '',
				'city'		=> '',
			) );

		include dirname( __FILE__ ) . '/views/form.php';

	}
}

add_action( 'widgets_init', create_function( '', 'register_widget("FT_Contact_Widget");' ) );