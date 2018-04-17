/**
 * Media upload handler script.
 *
 * Props to Thomas Griffin for the following JS code!
 * 
 * @package    Theme_Junkie_Testimonials_Content
 * @since      0.1.0
 * @author     Theme Junkie
 * @copyright  Copyright (c) 2014, Theme Junkie
 * @license    http://www.gnu.org/licenses/gpl-2.0.html
 */
jQuery(document).ready(function($){
	
	var tjtsc_media_frame;
	
	// Bind to our click event in order to open up the new media experience.
	$(document.body).on('click.tjtscOpenMediaManager', '.tjtsc-open-media', function(e){

		// Prevent the default action from occuring.
		e.preventDefault();

		// If the frame already exists, re-open it.
		if ( tjtsc_media_frame ) {
			tjtsc_media_frame.open();
			return;
		}

		tjtsc_media_frame = wp.media.frames.tjtsc_media_frame = wp.media({

			className: 'media-frame tjtsc-media-frame',
			frame: 'select',
			multiple: false,
			title: tjtsc_media.title,
			library: {
				type: 'image'
			},
			button: {
				text:  tjtsc_media.button
			}

		});

		tjtsc_media_frame.on('select', function(){
			
			// Grab our attachment selection and construct a JSON representation of the model.
			var media_attachment = tjtsc_media_frame.state().get('selection').first().toJSON();

			// Send the attachment URL to our custom input field via jQuery.
			$('#tjtsc-testimonial-avatar').val(media_attachment.url);
			
		});

		// Now that everything has been set, let's open up the frame.
		tjtsc_media_frame.open();

	});

});