=== Spacer ===
Contributors: clevelandwebdeveloper
Donate link: http://www.clevelandwebdeveloper.com/wordpress-plugins/donate.php
Tags: spacer, spacing, line space
Requires at least: 2.9
Tested up to: 4.4
Stable tag: 2.0
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Adds a spacer button to the WYSIWYG visual editor which allows you to add precise custom spacing between lines in your posts and pages.

== Description ==

This plugin adds a spacer button to the WYSIWYG visual editor which allows you to add precise custom spacing between lines in your posts and pages. Note that you can also enter negative spacing to shift the following content upwards.

<h3>New in Version 2.0</h3>

<ul>
<li>You can now hide spacer on mobile screens, or set a custom spacer height for mobile screens.</li>
<li>On individual spacers, you can manually edit the mobile height, custom classes, and inline style for the spacer element (see FAQ).</li>
<li>Added compatibility with Beaver Page Builder's wysiswyg UI.</li>
</ul>

<h4>For Premium Users</h4>
<b>Speed up your workflow by setting a default spacer height. You can also set defaults for your spacer element's custom classes and inline style.</b>

== Installation ==

1. From WP admin > Plugins > Add New
1. Search "Spacer" under search and hit Enter
1. Click "Install Now"
1. Click the Activate Plugin link

== Frequently asked questions ==

= Why shouldn't I just press enter for new lines? =

Every time you press Enter or Shift-Enter you will get line breaks that are a certain specific height. Some times you want control over the exact amount of space between lines.

= How do I hide spacer on mobile screens? =

Settings > Spacer > Default Spacer Height On Mobile > Set this to 0

= How do I add a spacer to a page/post? =

Press the spacer button in WYSIWYG visual editor (see screenshot). This will add a shortcode [spacer height="default"]. 

= How do I manually set the spacer height on individual spacers? =

Press the spacer button in WYSIWYG visual editor. This will add a shortcode [spacer height="default"]. Change default to whatever your desired line spacing is. For example, [spacer height="30px"] will give you 30 pixels of extra line spacing. If you use negative values the following content will be shifted upwards.

= How do I manually edit the height, mobile height, classes, and inline style on individual spacers? =
Here's an example of how you could apply this:
<pre>[spacer height="30px" mheight="0px" class="myspacer" style="background-color:red;"]</pre>

== Screenshots ==

1. Spacer Button
1. Setting the spacer height
1. Spacer height: 35px
1. Spacer height: -35px

== Changelog ==

= 2.0 =
* Feature added: Choose to hide spacer on mobile devices, or choose a different spacer height for mobile.
* Feature added: Manually edit mobile height, custom classes, and inline style for individual spacer elements.
* Feature added: Compatibility with Beaver Page Builder's wysiswyg UI.
* Premium users: Speed up your workflow by setting a default height for your spacer. You can also set defaults for your spacer's custom classes and inline style.

= 1.0 =
* Initial version

== Upgrade Notice ==

= 2.0 =
New: The new version allows you to hide spacer on mobile devices, or choose a different spacer height for mobile. Added compatibility with Beaver Page Builder's wysiswyg UI.