# Description

A fixed header is a common feature on many websites, but it can interfere with the WordPress admin bar. Tambar solves this problem!

## Features

* Move the admin bar to the bottom
* Set different admin bar positions for desktop and mobile
* Use switcher to hide the admin bar
* Hide the admin bar for selected roles

# Installation

1. **Visit** Plugins > Add New
1. **Search** for "Tambar"
1. **Install and Activate** Tambar from the Plugins page
1. Use the [`body_class()`](https://developer.wordpress.org/reference/functions/body_class/) function in your template.

# Frequently Asked Questions

## I installed and activated the plugin, but it doesn't work

To use the plugin, you need to add the [`body_class()`](https://developer.wordpress.org/reference/functions/body_class/) function to your theme.

This function should be placed inside the `<body>` tag, typically found in the `header.php` file within your theme's directory.

# Changelog

## 2.3.3
* WordPress 6.7.0 `get_plugin_data() fix`

## 2.3.2
* Minor fixes

## 2.3.1
* Show admin bar for role setting bux fix

## 2.3.0
* Settings that allow hiding the admin bar for selected roles

## 2.2.1
* Show/hide cookie value read bug fix

## 2.1.9
* Now plugin's assets (JS and CSS) are loaded only when the admin bar is showed

## 2.1.6
* New switcher options

## 2.1.0
* Admin bar state via PHP (no twinks)
* New options logic

## 2.0.6
* Admin bar position options for mobile

## 2.0
* New flexible plugin core

## 1.0.0
* Switched to SCSS
* Show/hide admin bar functionality
* Added options page
* Smoother admin bar loading

## 0.9.0
* Initial release