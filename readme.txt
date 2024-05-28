=== Tambar – Bottom Admin Bar ===
Contributors: yanmetelitsa
Tags: adminbar, admin, bar, position, bottom
Tested up to: 6.5.3
Requires at least: 6.4
Stable tag: 2.1.9
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Fixed header is a common feature of many websites, but it can interfere with the WordPress admin bar. Tambar is a solution for this problem!

== Description ==

**Features**

* Move admin bar to the bottom
* Different admin bar positions for desktop and mobile
* Use switcher to hide admin bar

== Installation ==

1. **Visit** Plugins > Add New
1. **Search** for "Tambar"
1. **Install and Activate** Tambar from the Plugins page
1. Use the [`body_class()`](https://developer.wordpress.org/reference/functions/body_class/) function in your template.

== Frequently Asked Questions ==

= I installed and activated the plugin, but it doesn't work =

To use the plugin, you need to add the [`body_class()`](https://developer.wordpress.org/reference/functions/body_class/) function to your theme.

This function should go inside the `body` tag, which is usually located in the `header.php` file in your theme's directory.

== Changelog ==

= 2.1.9 =
* Now plugin's assets (JS and CSS) are loaded only when the admin bar is showed

= 2.1.6 =
* Switcher position can be changed

= 2.1.5 =
* New switcher options

= 2.1.0 =
* Admin bar state via PHP (not twinks)
* New options logic

= 2.0.6 =
* Admin bar position options for mobile

= 2.0 =
* New, more flexible plugin core

= 1.0.3 =
* Show/hide admin bar animation

= 1.0.0 =
* Switched to SCSS
* Show/hide admin bar functionality
* Added options page
* Smoother admin bar loading

= 0.9.0 =
* Initial release