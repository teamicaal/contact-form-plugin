=== Plugin Name ===
Contributors: ashdotguru
Donate link: https://www.icaal.co.uk
Tags: contact, form, icaal
Requires at least: 4.7.0
Tested up to: 4.7.5
Stable tag: 1.3.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This is a simple plugin for creating and submitting contact forms via. AJAX.

== Description ==

This plugin will validate fields, send via. AJAX and optionally post the ICAAL Customer Dashboard.

All configuration is done using PHP constants in the `wp-config.php` file.

== Installation ==

Installing and setting up the plugin is easy, just follow these three steps:

1. Upload the plugin to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Open your `wp-config.php` file and add the line `define('ICAAL_TO_ADDRESS', 'YOUR_EMAIL');`, obviously replacing the value with your desired email

There are several options you can customise via. the `wp-config.php` file:

* Add `define('ICAAL_API_KEY', 'YOUR_API_KEY');` replacing the value with the customer's ICAAL Dashboard API Key
* Add `define('ICAAL_FROM_ADDRESS', 'YOUR_FROM_ADDRESS');`, replacing the value with the from email address, this will default to `no-reply@icaal.co.uk`
* Add `define('ICAAL_FROM_NAME', 'YOUR_FROM_NAME');`, replacing the value with the from name in the email header, this will default to the website name

The plugin will automatically load a `.js` script which will validate and submit the forms for you.

All you need to do is add `.icaal-contact-form` to the form. The only required field is `<input name="email">`.

You may also specify other required fields by adding a hidden field `<input type="hidden" name="required" value="first_name, last_name">`

== Changelog ==

= 1.3.3 =
= Improvements =
* **Phone number** - Automatically validate phone number fields

= 1.3.2 =
= Improvements =
* **To address** - Allow hidden field to specify to address

= 1.3.1 =
= Bugfixes =
* **Google Analytics** - Fix analytics events

= 1.3.0 =
= Improvements =
* **Google Analytics** - The contact form will now automatically send Google Analytics events with each successful enquiry. The event category is `Enquiry` and the action is `submit`

= 1.2.0 =
= Improvements =
* **JS** - There are now two callback functions you can use to do something on success or failure: `icaal_contact_form_success()`, `icaal_contact_form_failure(errors)`

= 1.1.2 =
= Bugfixes =
* **API** - Fix JSON send

= 1.1.1 =
= Bugfixes =
* **API** - Fix API URL

= 1.1.0 =
= Improvements =
* **API** - The plugin will now automatically post all form data from contact form 7 to the ICAAL Dashboard

= 1.0.1 =
= Bugfixes =
* **API** - Fix the ICAAL Dashboard API integration