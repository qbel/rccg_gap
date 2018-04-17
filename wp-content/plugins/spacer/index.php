<?php

/*
Plugin Name: Spacer
Description: Adds a spacer button to the WYSIWYG visual editor which allows you to add precise custom spacing between lines in your posts and pages.
Version: 2.0
Author: Justin Saad
Author URI: http://www.clevelandwebdeveloper.com
License: GPL2
*/


//begin wysiwyg visual editor custom button plugin

$plugin_label = "Spacer";
$plugin_slug = "motech_spacer";

class motech_spacer {

	public function __construct() {
		
		global $plugin_label, $plugin_slug;
		$this->plugin_slug = $plugin_slug;
		$this->plugin_label = $plugin_label;
		$this->plugin_dir = plugins_url( '' , __FILE__ );
		
		//do when class is instantiated	
		add_shortcode('spacer', array($this, 'addShortcodeHandler'));
		add_filter( 'tiny_mce_version', array($this, 'my_refresh_mce'));
		
		//plugin row links
		add_filter( 'plugin_row_meta', array($this,'plugin_row_links'), 10, 2 );
		
        if(is_admin()){
			add_action('admin_init', array($this, 'page_init'));
			add_action('admin_menu', array($this, 'add_plugin_page'));
			//custom image picker css for admin page
			add_action('admin_head', array($this,'motech_imagepicker_admin_css'));
			//custom image picker jquery for admin page
			add_action('admin_footer', array($this,'motech_imagepicker_admin_jquery'));			
			//add Settings link to plugin page
			add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array($this, 'add_plugin_action_links') );
			//image upload script
			add_action('admin_enqueue_scripts', array($this,'spacer_imageupload_script'));
			
			add_action( 'admin_enqueue_scripts', array($this, 'enqueue_color_picker') ); //enqueue color picker
			
			//admin messages
			add_action('admin_notices', array($this,'admin_show_message'));
			add_action('admin_init', array($this,'adminmessage_init'));			
		}
	}
	

	function admin_show_message()
	{
		$user_id = get_current_user_id();
		//there is no default spacer height set, and nag message not ignored...
		$checkdefault = get_option($this->plugin_slug . '_default_height_mobile','');
		if ( ( ! get_user_meta($user_id, 'spacer3001_nag_ignore') ) && ($checkdefault=='') ) {
			echo '<div id="message" class="updated fade notice"><p>';
			echo ("<b>You can now <a href=\"".get_bloginfo( 'wpurl' ) . "/wp-admin/options-general.php?page=".$this->plugin_slug."-setting-admin\">hide spacer on mobile screens</a>, or set a custom spacer height for mobile screens.</b>");
			echo "</p>";
			echo "<p><strong><a href=\"".get_bloginfo( 'wpurl' ) . "/wp-admin/options-general.php?page=".$this->plugin_slug."-setting-admin\" target=\"_parent\">Set spacer height for mobile devices</a> | <a class=\"dismiss-notice\" href=\"".get_bloginfo( 'wpurl' ) . "/wp-admin/options-general.php?page=".$this->plugin_slug."-setting-admin&spacer3001_nag_ignore=0\" target=\"_parent\">Dismiss this notice</a></strong></p></div>";
		}
	}
	 
	function adminmessage_init()
	{
		if ( isset($_GET['spacer3001_nag_ignore']) && '0' == $_GET['spacer3001_nag_ignore'] ) {
			$user_id = get_current_user_id();
			add_user_meta($user_id, 'spacer3001_nag_ignore', 'true', true);
			if (wp_get_referer()) {
				/* Redirects user to where they were before */
				wp_safe_redirect(wp_get_referer());
			} else {
				/* if there is no referrer you redirect to home */
				wp_safe_redirect(home_url());
			}
		}
	}	
	
	function enqueue_color_picker( $hook_suffix ) {
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( $this->plugin_slug.'-script-handle', plugins_url('js/motech-color-picker.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
	}	
	
	function spacer_imageupload_script() {
		if (isset($_GET['page']) && $_GET['page'] == $this->plugin_slug.'-setting-admin') {
			wp_enqueue_media();
			wp_register_script('spacer_imageupload-js', plugins_url( 'js/spacer_imageupload.js' , __FILE__ ), array('jquery'));
			wp_enqueue_script('spacer_imageupload-js');
		}
	}	

	// add the shortcode handler 
	function addShortcodeHandler($atts, $content = null) {
			extract(shortcode_atts(array( "height" => '', "mheight" => '', "class" => '', "style" => '' ), $atts));
			
			//prep variables
			$spacer_css = "";
			$classes = "";
			
			//prep mobile height, if it's empty we will use desktop height. if set to 0 we will hide the spacer on mobile devices.
			$mobile_height = "";
			$mobile_height_inline = "";
			$mobile_height_default = get_option($this->plugin_slug . '_default_height_mobile','');
			
			//first check for inline height, then check default mobile height
			if(isset($mheight) && $mheight != ""){
				$mobile_height = $mheight;
				$mobile_height_inline = $mheight;
			}elseif(isset($mobile_height_default) && $mobile_height_default != ""){
				$mobile_height = get_option($this->plugin_slug . '_default_height_mobile','');
				$mobile_height_default = $mobile_height;
			}
			
			
			//determine the height to use for the spacer. if it's a mobile device and there is a mobile height set, use that. otherwise use desktop height
			if( function_exists('wp_is_mobile') && wp_is_mobile() && (isset($mobile_height) && $mobile_height != "")) {
				$mobileunit = get_option($this->plugin_slug . '_default_height_mobile_unit','px');
				
				
				if(isset($mobile_height_inline) && $mobile_height_inline != "") {
					if ($mobile_height_inline > 0 ) {
						$spacer_css .= "padding-top: " . $mobile_height_inline . ";";
					} elseif($mobile_height_inline < 0) {
						$spacer_css .= "margin-top: " . $mobile_height_inline . ";";
					} elseif($mobile_height_inline == 0){
						$spacer_css .= "display:none;";
					}
				} elseif(isset($mobile_height_default) && $mobile_height_default != ""){
					if($mobile_height_default > 0){
						$spacer_css .= "padding-top: " . $mobile_height_default . $mobileunit.";";
					}elseif($mobile_height_default < 0){
						$spacer_css .= "margin-top: " . $mobile_height_default . $mobileunit. ";";
					}elseif($mobile_height_default == 0){;
						$spacer_css .= "display:none;";
					}
				}
				
			} elseif($height=="default"){ //there is no mobile height set. use the desktop default height
				
				//for now assume positive. in a sec add logic for if negative
				$checkheight = get_option($this->plugin_slug . '_default_height','20');
				$checkunit = get_option($this->plugin_slug . '_default_height_unit','px');
				
				if($checkheight > 0){
					$spacer_css .= "padding-top: " . $checkheight . $checkunit.";";
				}elseif($checkheight < 0){
					$spacer_css .= "margin-top: " . $checkheight . $checkunit. ";";
				}
			} elseif ($height > 0 ) { #no default for desktop, use positive inline height
				$spacer_css .= "padding-top: " . $height . ";";
			} elseif($height < 0) { #no positive inline for desktop, use negative inline height
				$spacer_css .= "margin-top: " . $height . ";";
			}
			
			
			//custom background image
			$bg = get_option($this->plugin_slug.'_custom_background_image_upload');
			if(!empty($bg)) {
				$spacer_css .= "background: url(".$bg.");";
			}
			
			//custom background image position
			$spacer_css .= $this->background_position();
			
			//background color
			$bgcolor = get_option($this->plugin_slug.'_background_color');
			if(!empty($bgcolor)) {
				$spacer_css .= "background-color:".$bgcolor.";";
			}
			
			//classes
			$defaultclasses = get_option($this->plugin_slug.'_spacer_class','');
			$classes .= $defaultclasses;
			if(!empty($class)){
				$classes .= " ".$class;
			}
			
			//styles
			$defaultstyle = get_option($this->plugin_slug.'_spacer_style','');
			$spacer_css .= $defaultstyle;
			if(!empty($style)){
				$spacer_css .= " ".$style;
			}
			
			
			//create the spacer after all settings have been loaded
			return '<span class="'.$classes.'" style="display:block;clear:both;height: 0px;'.$spacer_css.'"></span>';
	}
	
	function background_position(){
		$bgposition = get_option($this->plugin_slug.'_custom_background_image_position','repeat');
		if($bgposition=="repeat"){
			return "background-repeat:repeat;";
		} elseif($bgposition=="croptofit"){
			return "background-size:cover;background-position:center;";
		} elseif($bgposition=="stretch"){
			return "background-size: 100% 100%;background-repeat: no-repeat;background-position: center;";
		} elseif($bgposition=="propstretch"){
			return "background-size: contain;background-repeat: no-repeat;background-position: center;";
		}
	}
	
	
	function add_custom_button() {
	   if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') )
		 return;
	   if ( get_user_option('rich_editing') == 'true') {
		 add_filter('mce_external_plugins', array($this, 'add_custom_tinymce_plugin'),99999999);
		 add_filter('mce_buttons', array($this, 'register_custom_button'),99999999);
	   }
	}
	
	function register_custom_button($buttons) {
	   array_push($buttons, "|", get_class($this));
	   return $buttons;
	}
	
	function add_custom_tinymce_plugin($plugin_array) {
	   //use this in a plugin
	   $plugin_array[get_class($this)] = plugins_url( 'editor_plugin.js' , __FILE__ );
	   //use this in a theme
	   //$plugin_array[get_class($this)] = get_bloginfo('template_url').'/editor_plugin.js';
	   return $plugin_array;
	}
	
	function my_refresh_mce($ver) {
	  $ver += 5;
	  return $ver;
	}
	
	function plugin_row_links($links, $file) {
		$plugin = plugin_basename(__FILE__); 
		if ($file == $plugin) // only for this plugin
				return array_merge( $links,
			array( '<a target="_blank" href="http://www.linkedin.com/in/ClevelandWebDeveloper/">' . __('Find me on LinkedIn' ) . '</a>' ),
			array( '<a target="_blank" href="http://twitter.com/ClevelandWebDev">' . __('Follow me on Twitter') . '</a>' )
		);
		return $links;
	}

    public function create_admin_page(){
        ?>
		<div class="wrap" style="position:relative">
		    <?php screen_icon(); ?>
		    <h2 class="aplabel"><?php echo $this->plugin_label ?></h2>
            
            
            <div id="green_ribbon">
            
            	<div id="green_ribbon_top">
                	<div id="green_ribbon_left">
                    </div>
                    <div id="green_ribbon_base">
                    	<span id="hms_get_premium">NEW! Get Premium &raquo;</span>
                        <span class="hms_get_premium_meta">Spacer Premium is now available for as low as $20!</span>
                    </div>
                    <div id="green_ribbon_right">
                    </div>
                </div>
                
                <div class="motech_premium_box">
                	<div class="motech_premium_box_wrap">
                        <h2>Get Premium</h2>
                        <div class="updated below-h2" style="margin-bottom: -20px !important;"><p><strong>Purchase will be processed via PayPal.</strong></p></div>
                        <div class="updated below-h2"><p><strong>Every license is valid for the lifetime of the website where it's installed.</strong></p></div>
                        
                        <div class="motech_purchase_buttons">
                        
                            <div class="motech_purchase_button one_use">
                                <form action="https://www.paypal.com/cgi-bin/webscr" method="post"><input name="cmd" value="_s-xclick" type="hidden"><input name="hosted_button_id" value="W5UNGBADQSZ9A" type="hidden"><input type="hidden" name="page_style" value="motech_spacer_premium">
                                    <button name="submit">
                                        <div class="purchase_graphic">Buy 1 Use</div>
                                        <div class="purchase_bubble">
                                            <div class="purchase_price">$20</div>
                                            <div class="purchase_meta">1 site license</div>
                                        </div>
                                    </button>
                                    <img src="https://www.paypal.com/en_US/i/scr/pixel.gif" alt="" border="0" height="1" width="1">
                                </form>
                            </div>
                            
                            
                            <div class="motech_purchase_button unlimited_use">
                                <form action="https://www.paypal.com/cgi-bin/webscr" method="post"><input name="cmd" value="_s-xclick" type="hidden"><input name="hosted_button_id" value="P7E4QQM6Q25MN" type="hidden"><input type="hidden" name="page_style" value="motech_spacer_premium">
                                    <button name="submit">
                                        <div class="purchase_graphic">Buy <span>Unlimited</span></div>
                                        <div class="purchase_bubble">
                                            <div class="purchase_price">$50</div>
                                            <div class="purchase_meta">Unlimited sites forever!</div>
                                        </div>
                                    </button>
                                    <img src="https://www.paypal.com/en_US/i/scr/pixel.gif" alt="" border="0" height="1" width="1">
                                </form>
                            </div>
                            
                    	</div>
                        
                        <div class="motech_premium_cancel"><span>Cancel</span></div>
                        
                    </div>
                </div>
            </div>
            
            
		    <form method="post" action="options.php" class="<?php echo $this->plugin_slug ?>_form">
		        <?php
	            // This prints out all hidden setting fields
			    settings_fields($this->plugin_slug.'_option_group');	
			    do_settings_sections($this->plugin_slug.'-setting-admin');
			?>
		        <?php submit_button(); ?>
		    </form>
		</div>
	<?php
    }
	
    public function page_init(){
		
        add_settings_section(
	    $this->plugin_slug.'_setting_section',
	    'Configuration',
	    array($this, 'print_section_info'),
	    $this->plugin_slug.'-setting-admin'
		);
		
		//add text input field
		$field_slug = "default_height";
		$field_label = "Default Spacer Height" . $this->get_premium_warning();
		$field_id = $this->plugin_slug.'_'.$field_slug;
		register_setting($this->plugin_slug.'_option_group', $field_id, array($this, 'po_20'));
		add_settings_field(
		    $field_id,
		    $field_label, 
		    array($this, 'create_a_text_input'), //callback function for text input
		    $this->plugin_slug.'-setting-admin',
		    $this->plugin_slug.'_setting_section',
		    array(								// The array of arguments to pass to the callback.
				"id" => $field_id, //sends field id to callback
				"desc" => 'Speed up your workfolow by setting a default height to apply to your spacers. Note that you can also enter negative spacing to shift the following content upwards.', //description of the field (optional)
				"placeholder" => 'eg: 20',
				"default" => '20' //sets the default field value (optional), when grabbing this option value later on remember to use get_option(option_name, default_value) so it will return default value if no value exists yet
			)			
		);
		
		//add a select input field
		$field_slug = "default_height_unit";
		$field_label = "Spacer Height Unit" . $this->get_premium_warning();
		$field_id = $this->plugin_slug.'_'.$field_slug;
		$this->unit_options = array(
								array("label" => "px", "value" => "px"),
								array("label" => "em", "value" => "em"),
								array("label" => "rem", "value" => "rem"),
								array("label" => "%", "value" => "%"),
		);
		register_setting($this->plugin_slug.'_option_group', $field_id, array($this, 'po_px'));
		add_settings_field(	
			$field_id,						
			$field_label,							
			array($this, 'create_a_select_input'), //callback function for select input
		    $this->plugin_slug.'-setting-admin',
		    $this->plugin_slug.'_setting_section',
		    array(								// The array of arguments to pass to the callback.
				"id" => $field_id, //sends select field id to callback
				"default" => 'px', //sets the default field value (optional), when grabbing this field value later on remember to use get_option(option_name, default_value) so it will return default value if no value exists yet
				"desc" => 'Select a unit of measurement to use with your default spacer height.', //description of the field (optional)
				"meta" => 'style="max-width:450px;"',
				"select_options" => $this->unit_options //sets select option data
			)				
		);
		
		//add text input field
		$field_slug = "default_height_mobile";
		$field_label = "Default Spacer Height On Mobile (Optional)";
		$field_id = $this->plugin_slug.'_'.$field_slug;
		register_setting($this->plugin_slug.'_option_group', $field_id);
		add_settings_field(
		    $field_id,
		    $field_label, 
		    array($this, 'create_a_text_input'), //callback function for text input
		    $this->plugin_slug.'-setting-admin',
		    $this->plugin_slug.'_setting_section',
		    array(								// The array of arguments to pass to the callback.
				"id" => $field_id, //sends field id to callback
				"desc" => 'Set the default spacer height on mobile devices. If left empty, the spacer mobile height will be the same as the spacer desktop height. If set to 0, the spacer will be hidden on mobile.', //description of the field (optional)
				"placeholder" => 'eg: 10',
				"default" => '' //sets the default field value (optional), when grabbing this option value later on remember to use get_option(option_name, default_value) so it will return default value if no value exists yet
			)			
		);
		
		//add a select input field
		$field_slug = "default_height_mobile_unit";
		$field_label = "Spacer Height Unit On Mobile";
		$field_id = $this->plugin_slug.'_'.$field_slug;
		$this->unit_options = array(
								array("label" => "px", "value" => "px"),
								array("label" => "em", "value" => "em"),
								array("label" => "rem", "value" => "rem"),
								array("label" => "%", "value" => "%"),
		);
		register_setting($this->plugin_slug.'_option_group', $field_id);
		add_settings_field(	
			$field_id,						
			$field_label,							
			array($this, 'create_a_select_input'), //callback function for select input
		    $this->plugin_slug.'-setting-admin',
		    $this->plugin_slug.'_setting_section',
		    array(								// The array of arguments to pass to the callback.
				"id" => $field_id, //sends select field id to callback
				"default" => 'px', //sets the default field value (optional), when grabbing this field value later on remember to use get_option(option_name, default_value) so it will return default value if no value exists yet
				"desc" => 'Select a unit of measurement to use with your default spacer height on mobile devices. This only applies if you have a default spacer height set for mobile.', //description of the field (optional)
				"meta" => 'style="max-width:450px;"',
				"select_options" => $this->unit_options //sets select option data
			)				
		);
		
		//add text input field
		$field_slug = "spacer_class";
		$field_label = "Default Spacer Class (Optional)" . $this->get_premium_warning();
		$field_id = $this->plugin_slug.'_'.$field_slug;
		register_setting($this->plugin_slug.'_option_group', $field_id, array($this, 'po'));
		add_settings_field(
		    $field_id,
		    $field_label, 
		    array($this, 'create_a_text_input'), //callback function for text input
		    $this->plugin_slug.'-setting-admin',
		    $this->plugin_slug.'_setting_section',
		    array(								// The array of arguments to pass to the callback.
				"id" => $field_id, //sends field id to callback
				"desc" => 'Enter a custom css class to apply to all of your spacer elements. Multiple classes can be added by putting a blank space between each class name', //description of the field (optional)
				"placeholder" => 'eg: MyClass1 Class2'
			)			
		);		
		
		//add textarea input field
		$field_slug = "spacer_style";
		$field_label = "Spacer Style (Optional)" . $this->get_premium_warning();
		$field_id = $this->plugin_slug.'_'.$field_slug;
		register_setting($this->plugin_slug.'_option_group', $field_id, array($this, 'po'));
		add_settings_field(	
			$field_id,						
			$field_label,							
			array($this, 'create_a_textarea_input'), //callback function for textarea input
		    $this->plugin_slug.'-setting-admin',
		    $this->plugin_slug.'_setting_section',
		    array(								// The array of arguments to pass to the callback.
				"id" => $field_id, //sends field id to callback
				"desc" => 'Enter custom css to apply to all of your spacer elements. This is for advanced users. Just leave this empty if you\'re not sure what this means or if you don\'t have a use for it.', //description of the field (optional)
				"placeholder" => '(for example) border-top: solid 2px black; border-bottom: solid 2px black; margin-bottom: 25px;' //sets the field placeholder which appears when the field is empty (optional)
			)				
		);		
		

	
	//add radio option
	//$option_id = "status";
	//add_settings_field($option_id, 'Status', array($this, 'create_radio_field'), 'wordpresshidesite-setting-admin', 'setting_section_id', array("option_id" => $option_id));
			
    }  //end page_init	


	/**
	 * This following set of functions handle all input field creation
	 * 
	 */
	function create_image_upload($args) {
		?>
			<?php
			//set default value if applicable
            if(isset($args["default"])) {
                $default = $args["default"];
            } else {
                $default = false;
            }
            ?>
            <input class="motech_upload_image" type="text" size="36" name="<?php echo $args["id"] ?>" value="<?php echo get_option($args["id"], $default) ?>" /> 
            <input class="motech_upload_image_button" class="button" type="button" value="Upload Image" />
        	<br />
			<?php
			if(isset($args["desc"])) {
				echo "<span class='description'>".$args["desc"]."</span>";
			} else {
				echo "<span class='description'>Enter a URL or upload an image.</span>";	
			}
			?>
            <?php
				$current_image = get_option($args["id"],$default);
				if(!empty($current_image)) {
					echo "<br><strong>Preview</strong><br><img style='padding-left:20px; max-width: 50%; max-height: 400px;' src='".$current_image."'>";	
				}
			?>
        <?php
	} // end create_image_upload

	function create_a_checkbox($args) {
		$html = '<input type="checkbox" id="'  . $args["id"] . '" name="'  . $args["id"] . '" value="1" ' . checked(1, get_option($args["id"], $args["default"]), false) . '/>'; 
		
		// Here, we will take the desc argument of the array and add it to a label next to the checkbox
		$html .= '<label for="'  . $args["id"] . '">&nbsp;'  . $args["desc"] . '</label>';
		
		echo $html;
		
	} // end create_a_checkbox
	
	function create_a_text_input($args) {
		//grab placeholder if there is one
		if(isset($args["placeholder"])) {
			$placeholder_html = "placeholder=\"".$args["placeholder"]."\"";
		}	else {
			$placeholder_html = "";
		}
		//grab maxlength if there is one
		if(isset($args["maxlength"])) {
			$max_length_html = "maxlength=\"".$args["maxlength"]."\"";
		}	else {
			$max_length_html = "";
		}
		if(isset($args["default"])) {
			$default = $args["default"];
		} else {
			$default = false;
		}
		if(!isset($args["class"])){
			$args["class"] = "";
		}
		// Render the output
		echo '<input type="text" '  . $placeholder_html . $max_length_html . ' id="'  . $args["id"] . '" class="' . $args["class"]. '" name="'  . $args["id"] . '" value="' . get_option($args["id"], $default) . '" />';
		if($args["desc"]) {
			echo "<p class='description'>".$args["desc"]."</p>";
		}
		

	} // end create_a_text_input
	
	function create_a_textarea_input($args) {
		//grab placeholder if there is one
		if($args["placeholder"]) {
			$placeholder_html = "placeholder=\"".$args["placeholder"]."\"";
		}	else {
			$placeholder_html = "";
		}
		//get default value if there is one
		if(isset($args["default"])) {
			$default = $args["default"];
		} else {
			$default = false;
		}
		// Render the output
		echo '<textarea '  . $placeholder_html . ' id="'  . $args["id"] . '"  name="'  . $args["id"] . '" rows="5" cols="50">' . get_option($args["id"], $default) . '</textarea>';
		if($args["desc"]) {
			echo "<p class='description'>".$args["desc"]."</p>";
		}		
	}
	
	function create_a_radio_input($args) {
	
		$radio_options = $args["radio_options"];
		$html = "";
		if($args["desc"]) {
			$html .= $args["desc"] . "<br>";
		}
		//get default value if there is one
		if(isset($args["default"])) {
			$default = $args["default"];
		} else {
			$default = false;
		}
		foreach($radio_options as $radio_option) {
			$html .= '<input type="radio" id="'  . $args["id"] . '_' . $radio_option["value"] . '" name="'  . $args["id"] . '" value="'.$radio_option["value"].'" ' . checked($radio_option["value"], get_option($args['id'], $default), false) . '/>';
			$html .= '<label for="'  . $args["id"] . '_' . $radio_option["value"] . '"> '.$radio_option["label"].'</label><br>';
		}
		
		echo $html;
	
	} // end create_a_radio_input callback

	function create_a_select_input($args) {
	
		$select_options = $args["select_options"];
		$html = "";
		//get default value if there is one
		if(isset($args["default"])) {
			$default = $args["default"];
		} else {
			$default = false;
		}
		if(isset($args["meta"])) {
			$meta = $args["meta"];
		} else {
			$meta = "";
		}
		$html .= '<select id="'  . $args["id"] . '" name="'  . $args["id"] . '" ' . $meta . '" >';
			foreach($select_options as $select_option) {
				$html .= '<option value="'.$select_option["value"].'" ' . selected( $select_option["value"], get_option($args["id"], $default), false) . '>'.$select_option["label"].'</option>';
			}
		$html .= '</select>';
		if($args["desc"]) {
			$html .= "<p class='description'>".$args["desc"]."</p>";
		}		
		echo $html;
	
	} // end create_a_select_input callback
	
    public function print_section_info(){ //section summary info goes here
		//print 'This is the where you set the password for your site.';
    }	

    public function add_plugin_page(){
        // This page will be under "Settings"
		add_options_page('Settings Admin', $this->plugin_label, 'manage_options', $this->plugin_slug.'-setting-admin', array($this, 'create_admin_page'));
    }
	
	//add plugin action links logic
	function add_plugin_action_links( $links ) {
	 
		return array_merge(
			array(
				'settings' => '<a href="' . get_bloginfo( 'wpurl' ) . '/wp-admin/options-general.php?page='.$this->plugin_slug.'-setting-admin">Settings</a>'
			),
			$links
		);
	 
	}
	
	function motech_imagepicker_admin_css() {
		if (isset($_GET['page']) && $_GET['page'] == $this->plugin_slug.'-setting-admin') { //if we are on our admin page
			?>
            <style>
				.hmshidden {display:none;}
				#wpbody h3 {font-size:20px;}
				#hide_my_site_current_theme {display:none;}
				div.updated.success {background-color: rgb(169, 252, 169);border-color: rgb(85, 151, 85);}
				.mvalid {background-color: rgb(169, 252, 169);border-color: rgb(85, 151, 85);width: 127px;font-weight: bold;padding-left: 10px;border: solid 1px rgb(85, 151, 85);border-radius: 3px;}
				.motech_premium_only {color:red;}
				#green_ribbon_top {position:relative;z-index:2;}
				#green_ribbon_left {background:url(<?php echo $this->plugin_dir ?>/images/green_ribbon_left.png) no-repeat -11px 0px;width: 80px;height: 60px;float: left;}
				#green_ribbon_right {background:url(<?php echo $this->plugin_dir ?>/images/green_ribbon_right.png) no-repeat;width: 80px;height: 60px;position: absolute;top: 0px;right: -10px;}
				#green_ribbon_base {background:url(<?php echo $this->plugin_dir ?>/images/green_ribbon_base.png) repeat-x;height: 60px;margin-left: 49px;margin-right: 70px;}
				#green_ribbon_base span {display: inline-block;color: white;position: relative;top: 11px;height: 35px; line-height:33px;font-size: 17px;font-weight: bold;font-style: italic;text-shadow: 1px 3px 2px #597c2a;}
				#hms_get_premium {background: rgb(58, 80, 27);background: rgba(58, 80, 27, 0.73);cursor:pointer;padding: 0px 12px;margin-left: -17px;font-style: normal !important;margin-right: 12px;text-shadow: 1px 3px 2px #364C18 !important;}
				#hms_get_premium:hover {background:rgb(30, 43, 12);background:rgba(30, 43, 12, 0.73);text-shadow: 1px 3px 2px #21310B !important;}
				.motech_premium_box {background:url(<?php echo $this->plugin_dir ?>/images/premium_back.png); margin-left: 49px;padding-top: 29px;padding-bottom:36px;margin-right: 70px;position:relative;top:-16px;display:none;}
				.motech_premium_box_wrap {margin-left:20px; margin-right:20px;}
				.motech_premium_box h2 {text-align: center;color: #585858;font-size: 36px;text-shadow: 1px 3px 2px #acabab;}
				.motech_premium_box .updated {margin-bottom: 20px !important;margin-top: 29px !important;}
				.motech_premium_box button {background: none;border: none; position:relative;cursor: pointer;overflow: visible;}
				.motech_purchase_button .purchase_graphic {background:url(<?php echo $this->plugin_dir ?>/images/buy_sprite.png) no-repeat;height: 100px;width: 101px;background-position: -17px -24px;color: white;font-size: 22px;padding: 20px 42px;padding-top: 57px;text-shadow: 1px 1px 7px black;position: absolute;top: -80px;left: -80px;line-height:normal;font-family: 'Open Sans', sans-serif;}
				.redeem_info{margin-top:20px;display:none;}
				.motech_purchase_button.unlimited_use .purchase_graphic {width: 115px;padding: 21px 36px;padding-top: 57px;}
				.motech_purchase_button.unlimited_use .purchase_graphic span {font-weight:bold;}
				.motech_purchase_button .purchase_bubble {background: white;border-radius: 9px;width: 350px;height: 123px;margin-bottom: 5px;-webkit-transition: all .2s ease-out;  -moz-transition: all .2s ease-out;-o-transition: all .2s ease-out;transition: all .2s ease-out;}
				.motech_purchase_button:hover .purchase_bubble {  background-color: #99dcf8;box-shadow:2px 3px 2px rgba(0, 0, 0, 0.31);}
				.motech_purchase_button.three_use:hover .purchase_bubble {  background-color: #96f5e4;}
				.motech_purchase_button.unlimited_use:hover .purchase_bubble {  background-color: #f8c4c6;}
				.motech_purchase_buttons {padding-top:90px;text-align:center;}
				.motech_purchase_button {display:inline-block;margin-right: 100px;vertical-align:top;}
				.motech_purchase_button .purchase_price {font-size: 60px;color: #585858;line-height:normal;}
				.motech_purchase_button:last-child {margin-right:0px;}
				.motech_purchase_button.three_use .purchase_graphic {background-position: -208px -24px;}
				.motech_purchase_button.unlimited_use .purchase_graphic {background-position: -397px -24px;}
				.motech_premium_cancel {color:#626262;text-align:center;font-size:22px;margin-top:43px;}
				.motech_premium_cancel span:hover {cursor:pointer;text-decoration:underline;}
				.<?php echo $this->plugin_slug ?>_form > .form-table {max-width:770px;}
				

				/*css for the image picker*/
				.motech_image_picker img {border-radius: 14px;box-shadow: 0px 0px 0px 2px rgba(0, 0, 255, 0.3);}
				.motech_image_picker_wrap:hover img, .motech_image_picker_wrap:focus img {box-shadow: 0px 0px 0px 2px rgba(0, 0, 255, 0.56);}
				.motech_image_picker_wrap.current img, .motech_image_picker_wrap:active img {box-shadow: 0px 0px 0px 4px rgba(0, 0, 255, 0.9);}
				.motech_image_picker_wrap {display:inline-block;cursor: pointer;margin-right:20px;margin-bottom: 30px;}
				.motech_image_picker_wrap div {font-weight:bold;font-size:16px;margin-top:10px;color:rgba(0, 0, 0, 0.47);}

				/* Begin Responsive
				====================================================================== */
				@media only screen and (max-width: 1700px) {
					.motech_purchase_button .purchase_price {font-size: 42px;padding-top: 18px;}
					.motech_purchase_button .purchase_bubble {width: 252px;}
				}
				@media only screen and (max-width: 1535px) {
					.motech_purchase_button .purchase_bubble {width: 131px;padding-top: 69px;}
					.motech_purchase_button .purchase_graphic {left: -23px;}
					.motech_purchase_button {margin-right:70px;}
				}
				@media only screen and (max-width: 1255px) {
					.motechdonate {height: 55px;}
				}
				@media only screen and (max-width: 1025px) {
					.hms_get_premium_meta {display:none !important;}
				}
				@media only screen and (max-width: 980px) {
					.motech_purchase_button {display:block;margin-bottom: 80px;margin-right:0px;}
				}
				@media only screen and (max-width: 445px) {
					.motech_premium_box h2 {font-size:22px;}
				}
				@media only screen and (max-width: 380px) {
					#green_ribbon_base span {font-size: 12px;}
					#hms_get_premium {margin-right:0px;}
				}
				@media only screen and (max-width: 330px) {
					.motech_purchase_button {
						margin-left: -9px;
					}
			</style>
            
            <!--[if lt IE 9]>
                <style>
                    .motech_image_picker_wrap.current img, .motech_image_picker_wrap:active img {
                    	border: 4px solid rgb(0, 0, 255);
                        margin:-4px;
                    }
                    .motech_purchase_button {
                        display: block;
                        padding-bottom: 70px;
                        margin-right: 0px;
                    }
                    .motech_purchase_button.unlimited_use {
                    	padding-bottom: 0px;
                    }
                    .hms_get_premium_meta {display:none !important;}
                </style>
            <![endif]-->            
            <?php
		}
	}

	function po($input) {
		if (get_option($this->plugin_slug . '_ihmsa','') == 'hmsia') {
			return $input;		
		}
		if (!empty($input)) {
			add_settings_error('plk_error_id81',esc_attr('settings_updated_81'),__('A premium option was not saved. You must first enter your license key to unlock this premium feature.'),'error');		
		}
	}
	
	function po_px($input) {
		if (get_option($this->plugin_slug . '_ihmsa','') == 'hmsia') {
			return $input;		
		}
		if ($input != "px") {
			add_settings_error('plk_error_id82',esc_attr('settings_updated_82'),__('A premium option was not saved. You must first enter your license key to unlock this premium feature.'),'error');		
		}
	}
	
	function po_20($input) {
		if (get_option($this->plugin_slug . '_ihmsa','') == 'hmsia') {
			return $input;		
		}
		if ($input != "20") {
			add_settings_error('plk_error_id83',esc_attr('settings_updated_83'),__('A premium option was not saved. You must first enter your license key to unlock this premium feature.'),'error');
		}
		return "20";
	}
	
	function get_premium_warning() {
		if (get_option($this->plugin_slug . '_ihmsa','') == 'hmsia') {
			return '';
		} else {
			return '<span class="motech_premium_only"> (Premium Only)</span>';
		}
	}
	
	function motech_imagepicker_admin_jquery() {
		if (isset($_GET['page']) && $_GET['page'] == $this->plugin_slug.'-setting-admin') { //if we are on our admin page
			?>
				<script>
					jQuery(function() {

						//jquery for color picker
						jQuery('tr.motech-color-field').removeClass('motech-color-field');
						
						//jquery for image picker
						jQuery(".motech_image_picker_wrap").click(function(){
							jQuery(this).closest(".motech_image_picker").find(".motech_image_picker_wrap").removeClass("current");
							jQuery(this).addClass("current");
							selectedvalue = jQuery(this).find("img").attr("alt");
							jQuery("#<?php echo $this->plugin_slug ?>_current_theme").val(selectedvalue);
						});
						jQuery("#<?php echo $this->plugin_slug ?>_current_theme").parent().parent().hide();
						<?php if (get_option($this->plugin_slug . '_ihmsa','') == 'hmsia') : ?>
							<?php
								if(get_option('hide_my_site_premium_expansion_plk','') != '') {
									$useval = get_option('hide_my_site_premium_expansion_plk','');
								} elseif(get_option($this->plugin_slug . '_plk','') != '') {
									$useval = get_option('hide_my_site_premium_expansion_plk','');
								}
							?>
							useval = '<?php echo $useval ?>';
							jQuery("#hide_my_site_plk").replaceWith("<div>"+useval+"</div>");
						<?php else : ?>
							jQuery("#hide_my_site_plk").replaceWith("<div></div>");
						<?php endif ?>
						
						jQuery("#hms_get_premium, .motech_premium_cancel span").click(function(){
							jQuery(".motech_premium_box").slideToggle(200);
						});
						jQuery(".how_to_redeem").click(function(){
							jQuery(".redeem_info").slideToggle(200);
						});
						jQuery(".hms_get_premium").click(function(){
							jQuery("html, body").animate({ scrollTop: 0 }, 300, function() {
    							// Animation complete.
								jQuery(".motech_premium_box").slideDown(200);
  							});
						});


					});			
				</script>
            <?php
		}
	}	
	
	

} //end class

$class = new motech_spacer();

add_action('init', array($class, 'add_custom_button')); 