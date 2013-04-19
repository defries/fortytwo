<?php
/**
 * Genesis Framework.
 *
 * @package Genesis\Admin
 * @author  StudioPress
 * @license http://www.opensource.org/licenses/gpl-license.php GPL-2.0+
 * @link    http://my.studiopress.com/themes/genesis/
 */

/**
 * Registers a new admin page, providing content and corresponding menu item for the CPT Archive Settings page.
 *
 * @package Genesis\Admin
 */
class Genesis_Admin_CPT_Archive_Settings extends Genesis_Admin_Boxes {
	/**
	 * Post type object.
	 *
	 * @var \stdClass
	 */
	protected $post_type;

	/**
	 * Create an archive settings admin menu item and settings page for relevant custom post types.
	 *
	 * @since 2.0.0
	 *
	 * @uses GENESIS_CPT_ARCHIVE_SETTINGS_FIELD_PREFIX Settings field key prefix.
	 * @uses \Genesis_Admin::create()                  Create admin menu and settings page.
	 *
	 * @param \stdClass $post_type Post Type object.
	 */
	public function __construct( stdClass $post_type ) {
		$this->post_type = $post_type;

		$page_id = 'genesis-cpt-archive-' . $this->post_type->name;

		$menu_ops = array(
			'submenu' => array (
				'parent_slug' => 'edit.php?post_type=' . $this->post_type->name,
				'page_title'  => apply_filters( 'genesis_cpt_archive_settings_page_label', __( 'Archive Settings', 'genesis' ) ),
				'menu_title'  => apply_filters( 'genesis_cpt_archive_settings_menu_label', __( 'Archive Settings', 'genesis' ) ),
				'capability'  => 'edit_theme_options',
			)
		);

		//* Handle non-top-level CPT menu items
		if ( is_string( $this->post_type->show_in_menu ) ) {
			$menu_ops['submenu']['parent_slug'] = $this->post_type->show_in_menu;
			$menu_ops['submenu']['menu_title']  = apply_filters( 'genesis_cpt_archive_settings_label', $this->post_type->labels->name . ' ' . __( 'Archive', 'genesis' ) );
			$menu_ops['submenu']['menu_position']  = $this->post_type->menu_position;
		}

		$page_ops = array(); //* use defaults

		$settings_field = GENESIS_CPT_ARCHIVE_SETTINGS_FIELD_PREFIX . $this->post_type->name;

		$default_settings = apply_filters(
			'genesis_cpt_archive_settings_defaults',
			array(
				'headline'    => '',
				'intro_text'  => '',
				'doctitle'    => '',
				'description' => '',
				'keywords'    => '',
				'layout'      => '',
				'body_class'  => '',
				'noindex'     => 0,
				'nofollow'    => 0,
				'noarchive'   => 0,
			)
		);

		$this->create( $page_id, $menu_ops, $page_ops, $settings_field, $default_settings );

		add_action( 'genesis_settings_sanitizer_init', array( $this, 'sanitizer_filters' ) );
	}

	/**
	 * Register each of the settings with a sanitization filter type.
	 *
	 * @since 2.0.0
	 *
	 * @uses genesis_add_option_filter() Assign filter to array of settings.
	 *
	 * @see \Genesis_Settings_Sanitizer::add_filter()
	 */
	public function sanitizer_filters() {

		genesis_add_option_filter(
			'no_html',
			$this->settings_field,
			array(
				'headline',
				'doctitle',
				'description',
				'keywords',
				'body_class',
			)
		);
		genesis_add_option_filter(
			'safe_html',
			$this->settings_field,
			array(
				'intro_text',
			)
		);
		genesis_add_option_filter(
			'one_zero',
			$this->settings_field,
			array(
				'noindex',
				'nofollow',
				'noarchive',
			)
		);
	}

	/**
 	 * Register meta boxes on the CPT Archive pages.
 	 *
 	 * Some of the meta box additions are dependent on certain theme support or user capabilities.
 	 *
 	 * The 'genesis_cpt_archives_settings_metaboxes' action hook is called at the end of this function.
 	 *
 	 * @since 2.0.0
 	 *
 	 * @see \Genesis_Admin_CPT_Archives_Settings::archive_box() Callback for Archive box.
 	 * @see \Genesis_Admin_CPT_Archives_Settings::seo_box()     Callback for SEO box.
 	 * @see \Genesis_Admin_CPT_Archives_Settings::layout_box()  Callback for Layout box.
	 */
	public function metaboxes() {
		add_meta_box( 'genesis-cpt-archives-settings', __( 'Archive Settings', 'genesis' ), array( $this, 'archive_box' ), $this->pagehook, 'main' );
		add_meta_box( 'genesis-cpt-archives-seo-settings', __( 'SEO Settings', 'genesis' ), array( $this, 'seo_box' ), $this->pagehook, 'main' );
		add_meta_box( 'genesis-cpt-archives-layout-settings', __( 'Layout Settings', 'genesis' ), array( $this, 'layout_box' ), $this->pagehook, 'main' );

		do_action( 'genesis_cpt_archives_settings_metaboxes', $this->pagehook );
	}

	/**
	 * Callback for Archive Settings meta box.
	 *
	 * @since 2.0.0
	 *
	 * @uses \Genesis_Admin::get_field_id()    Construct full field id.
	 * @uses \Genesis_Admin::get_field_name()  Construct full field name.
	 * @uses \Genesis_Admin::get_field_value() Retrieve value of key under $this->settings_field.
	 *
	 * @see \Genesis_Admin_Settings::metaboxes() Register meta boxes.
	 */
	public function archive_box() {
		?>
		<p><?php printf( __( 'View the <a href="%s">%s archive</a>.', 'genesis' ), get_post_type_archive_link( $this->post_type->name ), $this->post_type->name ); ?></p>

		<p><label for="<?php echo $this->get_field_id( 'headline' ); ?>"><b><?php _e( 'Archive Headline', 'genesis' ); ?></b></label></p>
		<p><input class="large-text" type="text" name="<?php echo $this->get_field_name( 'headline' ); ?>" id="<?php echo $this->get_field_id( 'headline' ); ?>" value="<?php echo esc_attr( $this->get_field_value( 'headline' ) ); ?>" /></p>
		<p class="description"><?php _e( 'Leave empty if you do not want to display a headline.', 'genesis' ); ?></p>

		<p><label for="<?php echo $this->get_field_id( 'intro_text' ); ?>"><b><?php _e( 'Archive Intro Text', 'genesis' ); ?></b></label></p>
		<p><textarea class="widefat" rows="5" cols="30" name="<?php echo $this->get_field_name( 'intro_text' ); ?>" id="<?php echo $this->get_field_id( 'intro_text' ); ?>"><?php echo esc_textarea( $this->get_field_value( 'intro_text' ) ); ?></textarea></p>
		<p class="description"><?php _e( 'Leave empty if you do not want to display any intro text.', 'genesis' ); ?></p>
		<?php
	}

	/**
	 * Callback for SEO Settings meta box.
	 *
	 * @since 2.0.0
	 *
	 * @uses \Genesis_Admin::get_field_id()    Construct full field id.
	 * @uses \Genesis_Admin::get_field_name()  Construct full field name.
	 * @uses \Genesis_Admin::get_field_value() Retrieve value of key under $this->settings_field.
	 *
	 * @see \Genesis_Admin_Settings::metaboxes() Register meta boxes.
	 */
	public function seo_box() {
		?>
		<p><label for="<?php echo $this->get_field_id( 'doctitle' ); ?>"><b><?php printf( __( 'Custom Document %s', 'genesis' ), '<code>&lt;title&gt;</code>' ); ?></b></label></p>
		<p><input class="large-text" type="text" name="<?php echo $this->get_field_name( 'doctitle' ); ?>" id="<?php echo $this->get_field_id( 'doctitle' ); ?>" value="<?php echo esc_attr( $this->get_field_value( 'doctitle' ) ); ?>" /></p>

		<p><label for="<?php echo $this->get_field_id( 'doctitle' ); ?>"><b><?php _e( 'Meta Description', 'genesis' ); ?></b></label></p>
		<p><input class="large-text" type="text" name="<?php echo $this->get_field_name( 'description' ); ?>" id="<?php echo $this->get_field_id( 'description' ); ?>" value="<?php echo esc_attr( $this->get_field_value( 'description' ) ); ?>" /></p>

		<p><label for="<?php echo $this->get_field_id( 'doctitle' ); ?>"><b><?php _e( 'Meta Keywords', 'genesis' ); ?></b></label></p>
		<p><input class="large-text" type="text" name="<?php echo $this->get_field_name( 'keywords' ); ?>" id="<?php echo $this->get_field_id( 'keywords' ); ?>" value="<?php echo esc_attr( $this->get_field_value( 'keywords' ) ); ?>" /></p>
		<p class="description"><?php _e( 'Comma separated list', 'genesis' ); ?></p>

		<h4><?php _e( 'Robots Meta Tags:', 'genesis' ); ?></h4>
		<p>
			<label for="<?php echo $this->get_field_id( 'noindex' ); ?>"><input type="checkbox" name="<?php echo $this->get_field_name( 'noindex' ); ?>" id="<?php echo $this->get_field_id( 'noindex' ); ?>" value="1" <?php checked( $this->get_field_value( 'noindex' ) ); ?> />
			<?php printf( __( 'Apply %s to this archive', 'genesis' ), '<code>noindex</code>' ); ?> <a href="http://yoast.com/articles/robots-meta-tags/" target="_blank">[?]</a></label><br />

			<label for="<?php echo $this->get_field_id( 'nofollow' ); ?>"><input type="checkbox" name="<?php echo $this->get_field_name( 'nofollow' ); ?>" id="<?php echo $this->get_field_id( 'nofollow' ); ?>" value="1" <?php checked( $this->get_field_value( 'nofollow' ) ); ?> />
			<?php printf( __( 'Apply %s to this archive', 'genesis' ), '<code>nofollow</code>' ); ?> <a href="http://yoast.com/articles/robots-meta-tags/" target="_blank">[?]</a></label><br />

			<label for="<?php echo $this->get_field_id( 'noarchive' ); ?>"><input type="checkbox" name="<?php echo $this->get_field_name( 'noarchive' ); ?>" id="<?php echo $this->get_field_id( 'noarchive' ); ?>" value="1" <?php checked( $this->get_field_value( 'noarchive' ) ); ?> />
			<?php printf( __( 'Apply %s to this archive', 'genesis' ), '<code>noarchive</code>' ); ?> <a href="http://yoast.com/articles/robots-meta-tags/" target="_blank">[?]</a></label>
		</p>
		<?php
	}

	/**
	 * Callback for Layout Settings meta box.
	 *
	 * @since 2.0.0
	 *
	 * @uses \Genesis_Admin::get_field_id()    Construct full field id.
	 * @uses \Genesis_Admin::get_field_name()  Construct full field name.
	 * @uses \Genesis_Admin::get_field_value() Retrieve value of key under $this->settings_field.
	 * @uses genesis_layout_selector()         Display layout selector.
	 *
	 * @see \Genesis_Admin_Settings::metaboxes() Register meta boxes.
	 */
	public function layout_box() {
		$layout = $this->get_field_value( 'layout' );

		?>
		<div class="genesis-layout-selector">
			<p><input type="radio" class="default-layout" name="<?php echo $this->get_field_name( 'layout' ); ?>" id="default-layout" value="" <?php checked( $layout, '' ); ?> /> <label class="default" for="default-layout"><?php printf( __( 'Default Layout set in <a href="%s">Theme Settings</a>', 'genesis' ), menu_page_url( 'genesis', 0 ) ); ?></label></p>

			<p><?php genesis_layout_selector( array( 'name' => $this->get_field_name( 'layout' ), 'selected' => $layout, 'type' => 'site' ) ); ?></p>
		</div>

		<br class="clear" />

		<p><label for="<?php echo $this->get_field_id( 'body_class' ); ?>"><b><?php _e( 'Custom Body Class', 'genesis' ); ?></b></label></p>
		<p><input class="large-text" type="text" name="<?php echo $this->get_field_name( 'body_class' ); ?>" id="<?php echo $this->get_field_id( 'body_class' ); ?>" value="<?php echo esc_attr( $this->get_field_value( 'body_class' ) ); ?>" /></p>
		<?php
	}

	/**
	 * Add contextual help content for the archive settings page.
	 *
	 * @since 2.0.0
	 *
	 * @todo Populate this contextual help method.
	 */
	public function help() {
		$screen = get_current_screen();
		$archive_help =
			'<h3>' . __( 'Archive Settings', 'genesis' ) . '</h3>' .
			'<p>' . __( 'Some help content here', 'genesis' ) . '</p>';

		$screen->add_help_tab(
			array(
				'id'      => $this->pagehook . '-archive',
				'title'   => __( 'Archive Settings', 'genesis' ),
				'content' => $archive_help,
			)
		);

		$seo_help =
			'<h3>' . __( 'SEO Settings', 'genesis' ) . '</h3>' .
			'<p>' . __( 'Some help content here', 'genesis' ) . '</p>';

		$screen->add_help_tab(
			array(
				'id'      => $this->pagehook . '-seo',
				'title'   => __( 'SEO Settings', 'genesis' ),
				'content' => $seo_help,
			)
		);

		$layout_help =
			'<h3>' . __( 'Layout Settings', 'genesis' ) . '</h3>' .
			'<p>' . __( 'Some help content here', 'genesis' ) . '</p>';

		$screen->add_help_tab(
			array(
				'id'      => $this->pagehook . '-layout',
				'title'   => __( 'Layout Settings', 'genesis' ),
				'content' => $layout_help,
			)
		);
	}
}
