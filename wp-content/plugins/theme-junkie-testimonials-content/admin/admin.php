<?php
/**
 * Admin functions for the plugin.
 *
 * @package    Theme_Junkie_Testimonials_Content
 * @since      0.1.0
 * @author     Theme Junkie
 * @copyright  Copyright (c) 2014, Theme Junkie
 * @license    http://www.gnu.org/licenses/gpl-2.0.html
 */

/* Set up the admin functionality. */
add_action( 'admin_menu', 'tjtsc_admin_setup' );

/**
 * Plugin's admin functionality.
 *
 * @since  0.1.0
 * @access public
 */
function tjtsc_admin_setup() {

	/* Filter the 'enter title here' placeholder. */
	add_filter( 'enter_title_here', 'tjtsc_title_placeholder', 10 );

	// /* Custom columns on the edit testimonial screen. */
	add_filter( 'manage_edit-testimonial_columns', 'tjtsc_edit_testimonial_columns' );
	add_action( 'manage_testimonial_posts_custom_column', 'tjtsc_manage_testimonial_columns', 10, 2 );
	add_filter( 'manage_edit-testimonial_sortable_columns', 'tjtsc_column_sortable' );

}

/**
 * Filter the 'enter title here' placeholder.
 *
 * @param  string  $title
 * @since  0.1.0
 * @access public
 * @return string
 */
function tjtsc_title_placeholder( $title ) {

	if ( 'testimonial' == get_current_screen()->post_type )
		$title = esc_attr__( 'Enter testimonial title here', 'tjtsc' );
	
	return $title;
}

/**
 * Sets up custom columns on the testimonial edit screen.
 *
 * @param  array  $columns
 * @since  0.1.0
 * @access public
 * @return array
 */
function tjtsc_edit_testimonial_columns( $columns ) {
	global $post;

	unset( $columns['title'] );

	$new_columns = array(
		'cb'    => '<input type="checkbox" />',
		'title' => __( 'Title', 'tjtsc' )
	);

	if ( get_post_meta( $post->ID, 'tj_testimonial_author_name', true ) )
		$new_columns['name'] = __( 'Name', 'tjtsc' );

	if ( get_post_meta( $post->ID, 'tj_testimonial_author_avatar', true ) )
		$new_columns['avatar'] = __( 'Image', 'tjtsc' );

	$new_columns['menu_order'] = __( 'Order', 'tjtsc' );

	return array_merge( $new_columns, $columns );
}

/**
 * Displays the content of custom testimonial columns on the edit screen.
 *
 * @param  string  $column
 * @param  int     $post_id
 * @since  0.1.0
 * @access public
 */
function tjtsc_manage_testimonial_columns( $column, $post_id ) {
	global $post;

	switch( $column ) {

		case 'name' :

			if ( get_post_meta( $post->ID, 'tj_testimonial_author_name', true ) )
				echo esc_attr( get_post_meta( $post->ID, 'tj_testimonial_author_name', true ) );

			break;

		case 'avatar' :

			if ( get_post_meta( $post->ID, 'tj_testimonial_author_avatar', true ) )
				echo '<img src="' . esc_url( get_post_meta( $post->ID, 'tj_testimonial_author_avatar', true ) ) . '" style="width: 75px; height: 75px;" />';

			break;

		case 'menu_order':

		    $order = $post->menu_order;
		    echo $order;

		    break;

		/* Just break out of the switch statement for everything else. */
		default :
			break;
	}
}

/**
 * Make Order column sortable.
 * 
 * @since  0.1.0
 * @access public
 * @return object
 */
function tjtsc_column_sortable( $columns ) {
	$columns['menu_order'] = 'menu_order';
	return $columns;
}