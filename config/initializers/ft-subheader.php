<?php
/**
 * FortyTwo Insert Page Title: Adds the page title to all pages
 * @todo  $ft_site_subheader to be translated and possibly filterable
 * @todo  page title and breadcrumbs should have our own do action
 * @todo  This code needs better documentation
 */


/** Remove the default location of breadcrumbs as well call it when adding our subheader area */
remove_action( 'genesis_before_loop', 'genesis_do_breadcrumbs' );

add_filter( 'genesis_breadcrumb_args', 'fortytwo_breadcrumb_args' );
/**
 * Modifying the default breadcrumb
 * @todo  This code needs better documentation
 */
function fortytwo_breadcrumb_args( $args ) {

	$args['sep'] = ' / ';
	$args['list_sep'] = ', '; // Genesis 1.5 and later
	$args['heirarchial_attachments'] = true; // Genesis 1.5 and later
	$args['heirarchial_categories'] = true; // Genesis 1.5 and later
	$args['display'] = true;
	$args['labels']['prefix'] = '';
	$args['labels']['author'] = '';
	$args['labels']['category'] = ''; // Genesis 1.6 and later
	$args['labels']['tag'] = '';
	$args['labels']['date'] = '';
	$args['labels']['search'] = '';
	$args['labels']['tax'] = '';
	$args['labels']['post_type'] = '';

	return $args;
}

add_action( 'genesis_after_header', 'fortytwo_insert_site_subheader' );
/**
 * Insert the site-subheader section
 * @todo  This code needs better documentation
 *
 */
function fortytwo_insert_site_subheader() {

	/** do nothing when we're not on the front-page */
	if ( !is_front_page() ) {

		global $post;

		$ft_subheader_attr = apply_filters( 'fortytwo_site_subheader_attr', array(
			'title'       => $post->post_title,
			'breadcrumbs' => true,
			'widget'      => false
		));
?>

		<div class="site-subheader">
			<div class="wrap">
				<div class="inner-wrap">
					<div class="subheader-area">
						<h1 class="subheader"><?php esc_attr_e( $ft_subheader_attr['title'], 'fortytwo' ); ?></h1>
						<?php if ( $ft_subheader_attr['breadcrumbs'] ) genesis_do_breadcrumbs(); ?>
					</div>
				</div>
			</div>
		</div>

<?php
	}
}

add_filter( 'fortytwo_site_subheader_attr', 'fortytwo_custom_site_subheader_title' );
/**
 * We are altering the title attribute of the site subheader using the fortytwo_site_subheader_attr filter
 *
 * We alter this based on the type of page being viewed
 *
 * @todo  This code needs better documentation
 *
 */
function fortytwo_custom_site_subheader_title( $ft_subheader_attr ) {

	global $post;

	$subheader_title = single_term_title("", false);

	if ( is_single() ) {
		$ft_subheader_attr['title'] = esc_attr( 'Article: ', 'fortytwo' ) . $ft_subheader_attr['title'];
		return $ft_subheader_attr;
	}

	if ( is_category() ) {
		$ft_subheader_attr['title'] = esc_attr( 'Category: ', 'fortytwo' ) . single_term_title( '', false );
		return $ft_subheader_attr;
	}

	if ( is_tag() ) {
		$ft_subheader_attr['title'] = esc_attr( 'Tag: ', 'fortytwo' ) . single_term_title( '', false );
		return $ft_subheader_attr;
	}

	if ( is_author() ) {
		$ft_subheader_attr['title'] = esc_attr( 'Articles by ', 'fortytwo' ) . get_the_author_meta( 'display_name', $post->post_author );
		return $ft_subheader_attr;
	}

	if ( is_date() ) {
		$ft_subheader_attr['title'] = esc_attr( 'Articles for ', 'fortytwo' ) . single_month_title( ' ', false );
		return $ft_subheader_attr;
	}

	if ( is_archive() ) {
		$ft_subheader_attr['title'] = esc_attr( 'Archive: ', 'fortytwo' ) . single_term_title( '', false );
		return $ft_subheader_attr;
	}

	return $ft_subheader_attr;
}