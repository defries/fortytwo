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
 * Tabs Widget.
 *
 * Thanks to Woothemes for the inspiration for out Tabs  Widget.
 * 
 * @package FortyTwo
 * @author  Forsite Themes
 */
class FT_Widget_Tabs_Widget extends FT_Widget {
	/**
	 * Widget slug / directory name.
	 *
	 * @since @@release
	 *
	 * @var string
	 */
	protected $slug = 'ft-tabs-widget';

	/**
	 * Hold the available tabs.
	 *
	 * @since @@release
	 *
	 * @var array
	 */
	public $available_tabs;

	/**
	 * Instantiate the widget class.
	 *
	 * @since @@release
	 */
	public function __construct() {
		$this->available_tabs = array( 'latest', 'popular', 'comments', 'tags' );
		$this->available_tabs = apply_filters( 'ft_tabs_widget_available', $this->available_tabs );

		$this->defaults = array(
			'title'           => __( 'Tabs', 'fortytwo' ),
			'tabs'            => array_slice( $this->available_tabs, 0, 3 ), /* default to selecting the first 3, to suggest that it is possible to omit having a tab */
			'limit'           => 5,
			'image_size'      => 45,
			'image_alignment' => 'left',
		);

		parent::__construct(
			$this->slug,
			__( '42 - Tabs', 'fortytwo' ),
			array(
				'classname'   => 'widget-' . $this->slug,
				'description' => __( 'Tabbed content widget for the FortyTwo Theme.', 'fortytwo' )
			),
			array(
				'width' => 505,
				'height' => 350,
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
		$instance = $old_instance;

		/* Strip tags for title and name to remove HTML (important for text inputs). */
		$instance['title'] = strip_tags( $new_instance['title'] );

		/* The select box is returning a text value, so we escape it. */
		$instance['image_alignment'] = esc_attr( $new_instance['image_alignment'] );

		/* Escape the text string and convert to an integer. */
		$instance['limit']           = intval( strip_tags( $new_instance['limit'] ) );
		$instance['image_size'] = intval( strip_tags( $new_instance['image_size'] ) );

		/* Convert multiple tab_$position fields into tabs array */
		$instance['tabs'] = array();
		for ( $i = 0; $i < count( $this->available_tabs ); $i++ ) {
			$tab_value = $new_instance[ 'tab_' . $i ];
			if ( $tab_value != 'none' ) {
				$instance['tabs'][] = $tab_value;
			}
		}

		// Allow child themes/plugins to act here.
		$instance = apply_filters( "{$this->slug}_widget_save", $instance, $new_instance, $this );

		return $instance;
	}

	/**
	 * Renders the latest content tab.
	 *
	 * @since @@release
	 *
	 * @param int    $limit           The maximum number of content items to show.
	 * @param int    $image_size      The image size.
	 * @param string $image_alignment The image alignment CSS class.
	 */
	public function tab_content_latest( $limit, $image_size, $image_alignment ) {
		global $post;
		$html = '';

		$html .= '<ul class="latest">' . "\n";
		$latest = get_posts( 'ignore_sticky_posts=1&numberposts=' . $limit . '&orderby=post_date&order=desc' );
		foreach ( $latest as $post ) {
			setup_postdata( $post );
			$html .= '<li>' . "\n";
			if ( $image_size > 0 ) {
				$html .= '<a title="' . the_title_attribute( array( 'echo' => false ) ) . '" href="' . esc_url( get_permalink( $post ) ) . '" class="pull-' . $image_alignment . '">' . $this->get_image( $image_size, $post ) . '</a>' . "\n";
			}
			$html .= '<h4 class="entry-title"><a title="' . the_title_attribute( array( 'echo' => false ) ) . '" href="' . esc_url( get_permalink( $post ) ) . '">' . get_the_title() . '</a></h4>' . "\n";
			$html .= get_the_excerpt() . ' <a title="' . the_title_attribute( array( 'echo' => false ) ) . '" href="' . esc_url( get_permalink( $post ) ) . '">Read more</a>' . "\n";
			$html .= '</li>' . "\n";
		}
		$html .= '</ul>' . "\n";
		wp_reset_query();

		return $html;
	}

	/**
	 * Renders the popular content tab.
	 *
	 * @since @@release
	 *
	 * @param int    $limit           The maximum number of content items to show.
	 * @param int    $image_size      The image size.
	 * @param string $image_alignment The image alignment CSS class.
	 */
	public function tab_content_popular( $limit, $image_size, $image_alignment ) {
		global $post;
		$html = '';

		$html .= '<ul class="popular">' . "\n";
		$popular = get_posts( 'ignore_sticky_posts=1&numberposts=' . $limit . '&orderby=comment_count&order=desc' );
		foreach ( $popular as $post ) {
			setup_postdata( $post );
			$html .= '<li>' . "\n";
			if ( $image_size > 0 ) {
				$html .= '<a title="' . the_title_attribute( array( 'echo' => false ) ) . '" href="' . esc_url( get_permalink( $post ) ) . '" class="pull-' . $image_alignment . '">' . $this->get_image( $image_size, $post ) . '</a>' . "\n";
			}
			$html .= '<h4 class="entry-title"><a title="' . the_title_attribute( array( 'echo' => false ) ) . '" href="' . esc_url( get_permalink( $post ) ) . '">' . get_the_title() . '</a></h4>' . "\n";
			$html .= get_the_excerpt() . ' <a title="' . the_title_attribute( array( 'echo' => false ) ) . '" href="' . esc_url( get_permalink( $post ) ) . '">Read more</a>' . "\n";
			$html .= '</li>' . "\n";
		}
		$html .= '</ul>' . "\n";
		wp_reset_query();

		return $html;
	}

	/**
	 * Renders the comments tab.
	 *
	 * @since @@release
	 *
	 * @param int    $limit           The maximum number of content items to show.
	 * @param int    $image_size      The image size.
	 * @param string $image_alignment The image alignment CSS class.
	 */
	public function tab_content_comments( $limit, $image_size, $image_alignment ) {
		$html = '';

		$comments = get_comments( array( 'number' => $limit, 'status' => 'approve' ) );
		if ( $comments ) {
			$html .= '<ul class="comments">' . "\n";
			foreach ( $comments as $c ) {
				$html .= '<li>' . "\n";
				$html .= '<span class="pull-' . $image_alignment . '">' . get_avatar( $c, 60 ) . '</span>';
				$html .= '<h4 class="entry-title"><a title="' . esc_attr( $c->comment_author . ' ' . __( 'on', 'fortytwo' ) . ' ' . get_the_title( $c->comment_post_ID ) ) . '" href="' . esc_url( get_comment_link( $c->comment_ID ) ) . '">' . esc_html( $c->comment_author ) . '</a></h4>' . "\n";
				$html .= '<span">' . stripslashes( substr( esc_html( $c->comment_content ), 0, 50 ) ) . '</span>' . "\n";
				$html .= '</li>' . "\n";
			}
			$html .= '</ul>' . "\n";
		}

		return $html;
	}

	/**
	 * Return an HTML fragment containing an <img> element for a post's image thumbnail.
	 *
	 * @since @@release
	 *
	 * @param int $image_size
	 * @param object $post  The post whose image thumbnail is being fetched
	 *
	 * @return string $html
	 */
	public function get_image( $size, $post ) {
		$html = '';
		// TODO: This could use post type icon if no post thumbnail is supported
		//$html = '<img data-src="holder.js/' . $size . 'x' . $size .'" class="no-thumbnail wp-post-image hide">';

		if ( current_theme_supports( 'post-thumbnails' ) && has_post_thumbnail( $post->ID ) ) {
			$html = get_the_post_thumbnail( $post->ID, array( 'width' => $size, 'height' => $size, 'crop' => true ), array( 'class' => 'has-thumbnail' ) );
		}

		return $html;
	}

	/**
	 * Render a tabs selection dropdown box.
	 *
	 * @since @@release
	 *
	 * @param array   $available_tabs An array of all the available tabs.
	 * @param array   $selected_tabs  An array of the tabs that are currently selected.
	 * @param int     $position       The position / order of the tab in the selected tabs.
	 */
	protected function render_tabs_dropdown( $available_tabs, $selected_tabs, $position ) {
		echo '<p><select' . $this->get_id_name( "tab_{$position}", false ) . ' class="widefat">';
		echo '<option value="none">' . __( ' - None selected - ', 'fortytwo' ) . '</option>';
		foreach ( $available_tabs as $available_tab ) {
			echo '<option value="' . esc_attr( $available_tab ) . '"' . selected( $available_tab, $selected_tabs[ $position ], false ) . '>' . __( $available_tab, 'fortytwo' ) . '</option>';
		}
		echo '</select></p>';
	}
}

add_action( 'widgets_init', 'ft_register_widget_tabs_widget' );
/**
 * Register the FT Tabs Widget widget.
 *
 * @since @@release
 */
function ft_register_widget_tabs_widget() {
	register_widget( 'FT_Widget_Tabs_Widget' );
}
