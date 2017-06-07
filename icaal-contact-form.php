<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.icaal.co.uk
 * @since             1.0.0
 * @package           Icaal_Contact_Form
 *
 * @wordpress-plugin
 * Plugin Name:       ICAAL Contact Form
 * Plugin URI:        https://www.icaal.co.uk/plugins/icaal-contact-form
 * Description:       Create simple contact forms, send via. AJAX and post to the ICAAL Dashboard
 * Version:           1.3.3
 * Author:            ICAAL
 * Author URI:        https://www.icaal.co.uk
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       icaal-contact-form
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-icaal-contact-form-activator.php
 */
function activate_icaal_contact_form() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-icaal-contact-form-activator.php';
	Icaal_Contact_Form_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-icaal-contact-form-deactivator.php
 */
function deactivate_icaal_contact_form() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-icaal-contact-form-deactivator.php';
	Icaal_Contact_Form_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_icaal_contact_form' );
register_deactivation_hook( __FILE__, 'deactivate_icaal_contact_form' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-icaal-contact-form.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_icaal_contact_form() {

	$plugin = new Icaal_Contact_Form();
	$plugin->run();

}
run_icaal_contact_form();
