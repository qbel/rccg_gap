<?php
/**
 * Custom testimonial shortcode.
 * 
 * @package    Theme_Junkie_Testimonials_Content
 * @since      0.1.0
 * @author     Theme Junkie
 * @copyright  Copyright (c) 2014, Theme Junkie
 * @license    http://www.gnu.org/licenses/gpl-2.0.html
 */

/* Testimonial shortcode. */
add_shortcode( 'tj-testimonial' , 'tjtsc_get_testimonials_shortcode' );

/**
 * Get the testimonials shortcode.
 *
 * @since  0.1.0
 * @access public
 * @param  array   $atts
 * @param  object  $content
 * @return string|array  The HTML for the testimonials.
 */
function tjtsc_get_testimonials_shortcode( $atts, $content ) {

	$args = shortcode_atts( tjtsc_get_default_args(), $atts );

	return tjtsc_get_testimonials( $args );

}