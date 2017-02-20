# ICAAL Contact Form

## Table of contents

* [Description](#description)
* [Installation](#installation)
* [Changelog](#changelog)
* [Contributors](#contributors)

## Description

This plugin will validate fields, send via. AJAX and optionally post the ICAAL Customer Dashboard.

All configuration is done using PHP constants in the `wp-config.php` file.

## Installation

Installing and setting up the plugin is easy, just follow these three steps:

1. Upload the plugin to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Open your `wp-config.php` file and add the line `define('ICAAL_TO_ADDRESS', 'YOUR_EMAIL');`, obviously replacing the value with your desired email

There are several options you can customise via. the `wp-config.php` file:

* Add `define('ICAAL_API_KEY', 'YOUR_API_KEY');` replacing the value with the customer's ICAAL Dashboard API Key
* Add `define('ICAAL_FROM_ADDRESS', 'YOUR_FROM_ADDRESS');`, replacing the value with the from email address, this will default to `no-reply@icaal.co.uk`
* Add `define('ICAAL_FROM_NAME', 'YOUR_FROM_NAME');`, replacing the value with the from name in the email header, this will default to the website name

## Changelog

## Contributors

* [ashdotguru](https://github.com/ashdotguru)