<?php
/**
 * Customizing the post type messages.
 * 
 * @package    Theme_Junkie_Testimonials_Content
 * @since      0.1.0
 * @author     Theme Junkie
 * @copyright  Copyright (c) 2014, Theme Junkie
 * @license    http://www.gnu.org/licenses/gpl-2.0.html
 */

/* Filter messages. */
add_filter( 'post_updated_messages', 'tjtsc_updated_messages' );

/**
 * Portfolio update messages.
 *
 * @param  array  $messages Existing post update messages.
 * @since  0.1.0
 * @access public
 * @return array  Amended post update messages with new CPT update messages.
 */
function tjtsc_updated_messages( $messages ) {
	global $post, $post_ID;

	$messages['testimonial'] = array(
		0 => '',
		1 => sprintf( __( 'Testimonial updated. <a href="%s">View Testimonial</a>', 'tjtsc' ), esc_url( get_permalink( $post_ID ) ) ),
		2 => __( 'Custom field updated.', 'tjtsc' ),
		3 => __( 'Custom field deleted.', 'tjtsc' ),
		4 => __( 'Testimonial updated.', 'tjtsc' ),
		5 => isset( $_GET['revision'] ) ? sprintf( __( 'Testimonial restored to revision from %s', 'tjtsc' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6 => sprintf( __( 'Testimonial published. <a href="%s">View It</a>', 'tjtsc' ), esc_url( get_permalink( $post_ID ) ) ),
		7 => __( 'Testimonial saved.', 'tjtsc' ),
		8 => sprintf( __( 'Testimonial submitted. <a target="_blank" href="%s">Preview Testimonial</a>', 'tjtsc' ), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
		9 => sprintf( __( 'Testimonial scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Testimonial</a>', 'tjtsc' ),
		  // translators: Publish box date format, see http://php.net/date
		  date_i18n( __( 'M j, Y @ G:i', 'tjtsc' ), strtotime( $post->post_date ) ), esc_url( get_permalink( $post_ID ) ) ),
		10 => sprintf( __( 'Testimonial draft updated. <a target="_blank" href="%s">Preview Testimonial</a>', 'tjtsc' ), esc_url( add_query_arg( 'preview', 'true', get_permalink( $post_ID ) ) ) ),
	);

	return $messages;
}