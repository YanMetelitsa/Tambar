# Description

A fixed header is a common feature on many websites, but it can interfere with the WordPress admin bar. Tambar solves this problem!

## Features

* Move the admin bar to the bottom
* Set different admin bar positions for desktop and mobile
* Use switcher to hide the admin bar
* Hide the admin bar for selected roles

# Frequently Asked Questions

## I installed and activated the plugin, but it doesn't work

To use the plugin, you need to add the [`body_class()`](https://developer.wordpress.org/reference/functions/body_class/) function to your theme.

This function should be placed inside the `<body>` tag, typically found in the `header.php` file within your theme's directory.

# Changelog

## 2.3.3
* Fix: WordPress 6.7.0 `get_plugin_data()` 

## 2.3.1
* Fix: Show admin bar for role

## 2.3.0
* New: Ability to hide the admin bar for roles

## 2.2.1
* Fix: Show/hide cookie value

## 2.1.9
* Fix: Plugin's assets loading only when the admin bar is displayed

## 2.1.6
* New: Switcher options

## 2.1.0
* New: Admin bar state via PHP (no twinks)
* New: Options logic

## 2.0.6
* New: Admin bar position options for mobile

## 2.0
* New: Flexible plugin core

## 1.0.0
* New: Switched to SCSS
* New: Options page
* New: Show/hide admin bar functionality
* Fix: Smoother admin bar loading

## 0.9.0
* Initial release