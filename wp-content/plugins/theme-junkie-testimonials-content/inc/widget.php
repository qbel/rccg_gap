<?php
/**
 * Custom testimonial widget.
 * 
 * @package    Theme_Junkie_Testimonials_Content
 * @since      0.1.0
 * @author     Theme Junkie
 * @copyright  Copyright (c) 2014, Theme Junkie
 * @license    http://www.gnu.org/licenses/gpl-2.0.html
 */
class Tj_Testimonials_Widget extends WP_Widget {

	/**
	 * Sets up the widgets.
	 *
	 * @since 0.1.0
	 */
	function __construct() {

		/* Set up the widget options. */
		$widget_options = array(
			'classname'   => 'tjtsc-widget testimonial-widget',
			'description' => __( 'Display custom testimonials content.', 'spt' )
		);

		/* Create the widget. */
		$this->WP_Widget(
			'tjtsc-widget',               // $this->id_base
			__( 'Testimonial', 'spt' ), // $this->name
			$widget_options             // $this->widget_options
		);

	}

	/**
	 * Outputs the widget based on the arguments input through the widget controls.
	 *
	 * @since 0.1.0
	 */
	function widget( $args, $instance ) {
		extract( $args );

		/* Output the theme's $before_widget wrapper. */
		echo $before_widget;

		/* If both title and title url is not empty, display it. */
		if ( ! empty( $instance['title_url'] ) && ! empty( $instance['title'] ) ) {
			echo $before_title . '<a href="' . esc_url( $instance['title_url'] ) . '" title="' . esc_attr( $instance['title'] ) . '">' . apply_filters( 'widget_title',  $instance['title'], $instance, $this->id_base ) . '</a>' . $after_title;

		/* If the title not empty, display it. */
		} elseif ( ! empty( $instance['title'] ) ) {
			echo $before_title . apply_filters( 'widget_title',  $instance['title'], $instance, $this->id_base ) . $after_title;
		}

		/* Get the related post query. */
		echo tjtsc_get_testimonials( $instance );

		/* Close the theme's widget wrapper. */
		echo $after_widget;

	}

	/**
	 * Updates the widget control options for the particular instance of the widget.
	 *
	 * @since 0.1.0
	 */
	function update( $new_instance, $old_instance ) {

		$instance = $new_instance;

		$instance['title']     = strip_tags( $new_instance['title'] );
		$instance['title_url'] = esc_url( $new_instance['title_url'] );
		$instance['limit']     = (int) $new_instance['limit'];
		$instance['column']    = (int) $new_instance['column'];
		$instance['css_class'] = sanitize_html_class( $new_instance['css_class'] );
		$instance['before']    = wp_filter_post_kses( $new_instance['before'] );
		$instance['after']     = wp_filter_post_kses( $new_instance['after'] );

		return $instance;
	}

	/**
	 * Displays the widget control options in the Widgets admin screen.
	 *
	 * @since 0.1.0
	 */
	function form( $instance ) {

		/* Merge the user-selected arguments with the defaults. */
		$instance = wp_parse_args( (array) $instance, tjtsc_get_default_args() );

		/* Extract the array to allow easy use of variables. */
		extract( $instance );
		?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>">
				<?php _e( 'Title:', 'tjtsc' ); ?>
			</label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'title_url' ); ?>">
				<?php _e( 'Title URL:', 'tjtsc' ); ?>
			</label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title_url' ); ?>" name="<?php echo $this->get_field_name( 'title_url' ); ?>" value="<?php echo esc_url( $instance['title_url'] ); ?>" placeholder="<?php echo esc_attr( 'http://' ); ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'limit' ); ?>">
				<?php _e( 'Number of testimonials to show: ', 'tjtsc' ); ?>
			</label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'limit' ); ?>" name="<?php echo $this->get_field_name( 'limit' ); ?>" type="text" value="<?php echo (int) $instance['limit']; ?>" />
			<small>-1 <?php _e( 'to show all testimonials.', 'tjtsc' ); ?></small>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'column' ); ?>">
				<?php _e( 'Grid Columns: ', 'tjtsc' ); ?>
			</label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'column' ); ?>" name="<?php echo $this->get_field_name( 'column' ); ?>" type="text" value="<?php echo (int) $instance['column']; ?>" />
			<small><?php _e( 'Display testimonials per row.', 'tjtsc' ); ?></small>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'css_class' ); ?>">
				<?php _e( 'CSS Class:', 'tjtsc' ); ?>
			</label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'css_class' ); ?>" name="<?php echo $this->get_field_name( 'css_class' ); ?>" value="<?php echo sanitize_html_class( $instance['css_class'] ); ?>"  placeholder="<?php echo esc_attr( 'unique-class' ); ?>" />
			<small><?php _e( 'Unique class for the widget.', 'tjtsc' ); ?></small>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'before' ); ?>">
				<?php _e( 'HTML before the testimonials: ', 'tjtsc' );?>
			</label>
			<textarea class="widefat" id="<?php echo $this->get_field_id( 'before' ); ?>" name="<?php echo $this->get_field_name( 'before' ); ?>" rows="5"><?php echo htmlspecialchars( stripslashes( $instance['before'] ) ); ?></textarea>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'after' ); ?>">
				<?php _e( 'HTML after the testimonials: ', 'tjtsc' );?>
			</label>
			<textarea class="widefat" id="<?php echo $this->get_field_id( 'after' ); ?>" name="<?php echo $this->get_field_name( 'after' ); ?>" rows="5"><?php echo htmlspecialchars( stripslashes( $instance['after'] ) ); ?></textarea>
		</p>

		<?php

	}

}