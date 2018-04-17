<?php
/**
 * File for registering post type.
 *
 * @package    Theme_Junkie_Testimonials_Content
 * @since      0.1.0
 * @author     Theme Junkie
 * @copyright  Copyright (c) 2014, Theme Junkie
 * @link       http://codex.wordpress.org/Function_Reference/register_post_type
 * @license    http://www.gnu.org/licenses/gpl-2.0.html
 */

/* Register custom post type on the 'init' hook. */
add_action( 'init', 'tjtsc_register_post_type' );

/**
 * Registers post type needed by the plugin.
 *
 * @since  0.1.0
 * @access public
 */
function tjtsc_register_post_type() {

	$labels = array(
	    'name'               => __( 'Testimonials', 'tjtsc' ),
	    'singular_name'      => __( 'Testimonial', 'tjtsc' ),
    	'menu_name'          => __( 'Testimonials', 'tjtsc' ),
    	'name_admin_bar'     => __( 'Testimonial', 'tjtsc' ),
		'all_items'          => __( 'Testimonials', 'tjtsc' ),
	    'add_new'            => __( 'Add New', 'tjtsc' ),
		'add_new_item'       => __( 'Add New Testimonial', 'tjtsc' ),
		'edit_item'          => __( 'Edit Testimonial', 'tjtsc' ),
		'new_item'           => __( 'New Testimonial', 'tjtsc' ),
		'view_item'          => __( 'View Testimonial', 'tjtsc' ),
		'search_items'       => __( 'Search Testimonials', 'tjtsc' ),
		'not_found'          => __( 'No Testimonials found', 'tjtsc' ),
		'not_found_in_trash' => __( 'No Testimonials found in trash', 'tjtsc' ),
		'parent_item_colon'  => '',
	);

	$defaults = array(	
		'labels'              => apply_filters( 'tjtsc_testimonials_labels', $labels ),
		'public'              => true,
		'exclude_from_search' => true,
		'menu_position'       => 57,
		'menu_icon'           => 'dashicons-format-chat',
		'supports'            => array( 'title', 'editor', 'revisions', 'page-attributes' ),
		'rewrite'             => array( 'slug' => 'testimonial', 'with_front' => false ),
		'has_archive'         => true
	);

	$args = apply_filters( 'tjtsc_testimonials_args', $defaults );

	register_post_type( 'testimonial', $args );

}