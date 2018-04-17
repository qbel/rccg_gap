<?php
/**
 * Meta boxes functions for the plugin.
 *
 * @package    Theme_Junkie_Testimonials_Content
 * @since      0.1.0
 * @author     Theme Junkie
 * @copyright  Copyright (c) 2014, Theme Junkie
 * @license    http://www.gnu.org/licenses/gpl-2.0.html
 */

/* Register meta boxes. */
add_action( 'add_meta_boxes', 'tjtsc_add_meta_boxes' );

/* Save meta boxes. */
add_action( 'save_post', 'tjtsc_meta_boxes_save', 10, 2 );

/**
 * Registers new meta boxes for the 'testimonial_item' post editing screen in the admin.
 *
 * @since  0.1.0
 * @access public
 * @link   http://codex.wordpress.org/Function_Reference/add_meta_box
 */
function tjtsc_add_meta_boxes() {

	/* Check if current screen is testimonial page. */
	if ( 'testimonial' != get_current_screen()->post_type )
		return;

	add_meta_box( 
		'tjtsc-metaboxes-testimonial',
		__( 'Testimonial Settings', 'tjtsc' ),
		'tjtsc_metaboxes_display',
		'testimonial',
		'normal',
		'high'
	);

}

/**
 * Displays the content of the meta boxes.
 *
 * @param  object  $post
 * @since  0.1.0
 * @access public
 */
function tjtsc_metaboxes_display( $post ) {

	wp_nonce_field( basename( __FILE__ ), 'tjtsc-metaboxes-testimonial-nonce' ); ?>

	<div id="tjtsc-block">

		<div class="tjtsc-label">
			<label for="tjtsc-testimonial-name">
				<strong><?php _e( 'Name', 'tjtsc' ); ?></strong><br />
				<span class="description"><?php _e( 'The autor name.', 'tjtsc' ); ?></span>
			</label>
		</div>

		<div class="tjtsc-input">
			<input type="text" name="tjtsc-testimonial-name" id="tjtsc-testimonial-name" value="<?php echo sanitize_text_field( get_post_meta( $post->ID, 'tj_testimonial_author_name', true ) ); ?>" size="30" style="width: 99%;" placeholder="<?php esc_attr_e( 'John Doe', 'tjtsc' ); ?>" />
		</div>

	</div><!-- #tjtsc-block -->

	<div id="tjtsc-block">

		<div class="tjtsc-label">
			<label for="tjtsc-testimonial-avatar">
				<strong><?php _e( 'Avatar', 'tjtsc' ); ?></strong><br />
				<span class="description"><?php _e( 'Upload or insert author avatar.', 'tjtsc' ); ?></span>
			</label>
		</div>

		<div class="tjtsc-input">
			<input type="text" name="tjtsc-testimonial-avatar" id="tjtsc-testimonial-avatar" value="<?php echo esc_url( get_post_meta( $post->ID, 'tj_testimonial_author_avatar', true ) ); ?>" size="30" style="width: 83%;" placeholder="<?php echo esc_attr( 'http://' ) ?>" />
			<a href="#" class="tjtsc-open-media button" title="<?php esc_attr_e( 'Add Image', 'tjtsc' ); ?>"><?php _e( 'Add Image', 'tjtsc' ); ?></a>
		</div>

	</div><!-- #tjtsc-block -->

	<div id="tjtsc-block">

		<div class="tjtsc-label">
			<label for="tjtsc-testimonial-site-name">
				<strong><?php _e( 'Website Name', 'tjtsc' ); ?></strong><br />
				<span class="description"><?php _e( 'The autor website name.', 'tjtsc' ); ?></span>
			</label>
		</div>

		<div class="tjtsc-input">
			<input type="text" name="tjtsc-testimonial-site-name" id="tjtsc-testimonial-site-name" value="<?php echo sanitize_text_field( get_post_meta( $post->ID, 'tj_testimonial_site_name', true ) ); ?>" size="30" style="width: 99%;" placeholder="<?php esc_attr_e( 'Website Name', 'tjtsc' ); ?>" />
		</div>

	</div><!-- #tjtsc-block -->

	<div id="tjtsc-block">

		<div class="tjtsc-label">
			<label for="tjtsc-testimonial-site-url">
				<strong><?php _e( 'Website URL', 'tjtsc' ); ?></strong><br />
				<span class="description"><?php _e( 'The autor website url.', 'tjtsc' ); ?></span>
			</label>
		</div>

		<div class="tjtsc-input">
			<input type="text" name="tjtsc-testimonial-site-url" id="tjtsc-testimonial-site-url" value="<?php echo esc_url( get_post_meta( $post->ID, 'tj_testimonial_site_url', true ) ); ?>" size="30" style="width: 99%;" placeholder="<?php esc_attr_e( 'http://example.com/', 'tjtsc' ); ?>" />
		</div>

	</div><!-- #tjtsc-block -->

	<?php
}

/**
 * Saves the metadata for the testimonial item info meta box.
 *
 * @param  int     $post_id
 * @param  object  $post
 * @since  0.1.0
 * @access public
 */
function tjtsc_meta_boxes_save( $post_id, $post ) {

	if ( ! isset( $_POST['tjtsc-metaboxes-testimonial-nonce'] ) || ! wp_verify_nonce( $_POST['tjtsc-metaboxes-testimonial-nonce'], basename( __FILE__ ) ) )
		return;

	if ( ! current_user_can( 'edit_post', $post_id ) )
		return;

	$meta = array(
		'tj_testimonial_author_name'   => wp_filter_post_kses( $_POST['tjtsc-testimonial-name'] ),
		'tj_testimonial_author_avatar' => esc_url( $_POST['tjtsc-testimonial-avatar'] ),
		'tj_testimonial_site_name'     => wp_filter_post_kses( $_POST['tjtsc-testimonial-site-name'] ),
		'tj_testimonial_site_url'      => wp_filter_post_kses( $_POST['tjtsc-testimonial-site-url'] )
	);

	foreach ( $meta as $meta_key => $new_meta_value ) {

		/* Get the meta value of the custom field key. */
		$meta_value = get_post_meta( $post_id, $meta_key, true );

		/* If there is no new meta value but an old value exists, delete it. */
		if ( current_user_can( 'delete_post_meta', $post_id, $meta_key ) && '' == $new_meta_value && $meta_value )
			delete_post_meta( $post_id, $meta_key, $meta_value );

		/* If a new meta value was added and there was no previous value, add it. */
		elseif ( current_user_can( 'add_post_meta', $post_id, $meta_key ) && $new_meta_value && '' == $meta_value )
			add_post_meta( $post_id, $meta_key, $new_meta_value, true );

		/* If the new meta value does not match the old value, update it. */
		elseif ( current_user_can( 'edit_post_meta', $post_id, $meta_key ) && $new_meta_value && $new_meta_value != $meta_value )
			update_post_meta( $post_id, $meta_key, $new_meta_value );
	}

}