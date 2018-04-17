=== Theme Junkie Testimonials Content ===
Contributors: themejunkie
Tags: testimonial, testimonials, post type, custom post type, widget, shortcode
Requires at least: 3.6
Tested up to: 3.9
Stable tag: 0.1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Adds a Testimonial section to your WordPress website.

== Description ==

This plugin adds a testimonials custom post type to your WordPress website to manage your customers testimonial. It should work out-of-the-box with any WordPress themes because it support widget and shortcode to display the testimonials.

Please read [usage instructions](http://wordpress.org/extend/plugins/theme-junkie-testimonials-content/other_notes/) page to read more on how to use it.

It has built-in meta boxes to add more data to the testimonials:  

* Author name
* Author avatar uploader
* Author website name
* Author website url

= Note =
We **DO NOT** provide any css style for the plugin, so it give you more flexibility to styling the testimonials style. We only provide the css selectors to guide you, please [read it here](http://wordpress.org/extend/plugins/theme-junkie-testimonials-content/other_notes/).

= Plugin Info =
* Developed by [Theme Junkie](http://www.theme-junkie.com/)
* Check out the [Github](https://github.com/themejunkie/theme-junkie-testimonials-content) repo to contribute.

== Installation ==

**Through Dashboard**

1. Log in to your WordPress admin panel and go to Plugins -> Add New
2. Type **theme junkie testimonials content** in the search box and click on search button.
3. Find Theme Junkie Testimonials Content plugin.
4. Then click on Install Now after that activate the plugin.
5. [Usage instructions](http://wordpress.org/extend/plugins/theme-junkie-testimonials-content/other_notes/)

**Installing Via FTP**

1. Download the plugin to your hardisk.
2. Unzip.
3. Upload the **theme-junkie-testimonials-content** folder into your plugins directory.
4. Log in to your WordPress admin panel and click the Plugins menu.
5. Then activate the plugin.
6. [Usage instructions](http://wordpress.org/extend/plugins/theme-junkie-testimonials-content/other_notes/)

== Frequently Asked Questions ==

= Can I use this plugin without purchasing Theme Junkie themes? =
Yes, this plugin was developed to support all themes.

= Why was this plugin created? =
Because we will move all custom post types in our themes to a plugins.

== Screenshots ==

1. Testimonials screen
2. Meta boxes
3. Widget

== Shortcode Usage ==

To display the testimonials in your content you can use the following shortcode:

`
[tj-testimonial]
`

To add arguments to this, please use any of the following arguments:

* `limit`     = "1" (limit the testimonials to show) 
* `column`    = "1" (testimonials per row, it adds unique css class to allow you styling it) 
* `css_class` = "" (custom uniqeu class) 
* `before`    = "" (html or text before the testimonials) 
* `after`     = "" (html or text after the testimonials)

== Shortcode Usage Examples ==

Display 4 testimonials with 4 columns
`
[tj-testimonial limit="4" column="4"]
`

Display custom HTML before the testimonials
`
[tj-testimonial before="<h1>Testimonials</h1>"]
`

== Widget Usage ==

Please go to Appearance &rarr; Widgets and you should see Testimonial widget. Drag it and drop to the sidebar.

== CSS Selectors ==

`
.tjtsc-testimonial {
	// testimonials wrapper
}

.testimonial-column-... {
	// testimonial column, the dot(...) automatically generated from the column arguments in the shortcode
}

.tjtsc-testimonial ul {
	// testimonial list style
}

.tjtsc-testimonial li {
	// testimonial list style
}

.tjtsc-testimonial p {
	// testimonial content
}

.tjtsc-testimonial img {
	// testimonial avatar
}

.tjtsc-testimonial cite {
	// testimonial name, website name and website url
}
`

== Changelog ==

= 0.1 - 4/30/2014 =
* Initial Release