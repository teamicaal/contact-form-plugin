<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.icaal.co.uk
 * @since      1.0.0
 *
 * @package    Icaal_Contact_Form
 * @subpackage Icaal_Contact_Form/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Icaal_Contact_Form
 * @subpackage Icaal_Contact_Form/public
 * @author     ICAAL <info@icaal.co.uk>
 */
class Icaal_Contact_Form_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Icaal_Contact_Form_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Icaal_Contact_Form_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		// wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/icaal-contact-form-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Icaal_Contact_Form_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Icaal_Contact_Form_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		$variables = array(
			'ajax_url' => admin_url('admin-ajax.php'),
			'nonce'		 => wp_create_nonce('icaal-contact-form')
		);

		wp_register_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/icaal-contact-form-public.js', array( 'jquery' ), $this->version, true );
		wp_localize_script( $this->plugin_name, 'variables', $variables );
		wp_enqueue_script( $this->plugin_name );

	}

}
