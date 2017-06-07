<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.icaal.co.uk
 * @since      1.0.0
 *
 * @package    Icaal_Contact_Form
 * @subpackage Icaal_Contact_Form/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Icaal_Contact_Form
 * @subpackage Icaal_Contact_Form/admin
 * @author     ICAAL <info@icaal.co.uk>
 */
class Icaal_Contact_Form_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/icaal-contact-form-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/icaal-contact-form-admin.js', array( 'jquery' ), $this->version, false );

	}

  public function set_content_type( $content_type ) {
    return 'text/html';
  }

  public function set_mail_from() {
  	if( defined('ICAAL_FROM_ADDRESS' ) ) {
  		return ICAAL_FROM_ADDRESS;
  	}
    return 'no-reply@icaal.co.uk';
  }

  public function set_mail_from_name() {
  	if( defined('ICAAL_FROM_NAME' ) ) {
  		return ICAAL_FROM_NAME;
  	}
    return get_bloginfo('name');
  }

	public function contact_form_submission() {

		$nonce = $_POST['nonce'];

		if( wp_verify_nonce( $nonce, 'icaal-contact-form') ) {

			$data = $_POST['data'];
			$required = $data['required'];
			$required_fields = explode(', ', $required);
			$email = $data['email'];
			$errors = array();

			if( !is_email($email) ) {
				$errors['email'] = 'Please enter a valid email';
			}

			if( !empty($required) && !empty($required_fields) ) {
				foreach( $required_fields as $field ) {
					if( empty($data[$field]) ) {
						$errors[$field] = ucwords( str_replace('_', ' ', $field) ) . ' is empty';
					} elseif( $field == 'phone' && !$this->is_phone($data[$field]) ) {
						$errors[$field] = 'Phone number is not valid';
					}
				}
			}

			if( !empty($errors) ) {
				$response = array(
					'errors' => $errors
				);
				wp_send_json($response);
			} else {

				$from = $email;
				$to = ICAAL_TO_ADDRESS;
				$website = home_url();
				$ip = $this->get_ip_address();

				unset($data['required']);
				
				if( $this->send_submission( $data ) ) {
					if( defined('ICAAL_API_KEY') ) {
						unset($data['email']);
						$this->save_submission( $from, $to, $website, $ip, $data );
					}
					wp_send_json_success();
				} else {
					$response = array(
						'error' => 'There was an error sending your message, please try again.'
					);
					wp_send_json($response);
				}

			}

		}

		$response = array(
			'error' => 'Your request has timed out, please refresh the page.'
		);
		wp_send_json($response);

	}

	public function contact_form_7_sent( $contact_form ) {

    $submission = WPCF7_Submission::get_instance();
    $data = $submission->get_posted_data();

    if( array_key_exists('email', $data) ) {
    	$from = $data['email'];
    } elseif( array_key_exists('mc4wp-EMAIL', $data) ) {
    	$from = $data['mc4wp-EMAIL'];
    } elseif( array_key_exists('your-email', $data) ) {
    	$from = $data['your-email'];
    }

    $fields = array();
    foreach( $data as $key => $value ) {
    	if( substr($key, 0, 6) != '_wpcf7' ) {
    		$fields[$key] = $value;
    	}
    }

    $this->save_submission( $from, ICAAL_TO_ADDRESS, home_url(), $this->get_ip_address(), $fields );

	}

	private function send_submission( $data ) {

		$to = ICAAL_TO_ADDRESS;
		if( !empty($data['to']) ) {
			$to = $data['to'];
			unset($data['to']);
		}
		$subject = 'New Website Enquiry';

		$message = '<p>You have received a new enquiry from your website.</p>';
		foreach( $data as $key => $value ) {
			$message .= ucwords( str_replace('_', ' ', $key) ) . ': ' . $value . '<br>';
		}
		$message .= '<hr>';
		$message .= 'This email was auto-generated from ' . home_url();

		return wp_mail($to, $subject, $message);

	}

	private function save_submission( $from, $to, $website, $ip, $data ) {
	
		if( defined('ICAAL_API_KEY') ) {

			$params = (object) array(
				'key' 		=> ICAAL_API_KEY,
				'from' 	  => $from,
				'to'		  => $to,
				'website' => $website,
				'ip'			=> $ip,
				'fields'	=> $data
			);
			$url = 'https://api.icaal.co.uk/customers/enquiries';
			if( WP_DEBUG === true ) {
				$url = 'http://api.icaal.dev/customers/enquiries';
			}
			$args = array(
				'headers' => array(
					'Content-Type' => 'application/json'
				),
				'body' => json_encode($params)
			);

			$response = wp_remote_post( $url, $args );

			return true;

		}

		return false;

	}

	private function get_ip_address() {
	  if (!empty($_SERVER['HTTP_CLIENT_IP']) && $this->validate_ip($_SERVER['HTTP_CLIENT_IP'])) {
	    return $_SERVER['HTTP_CLIENT_IP'];
	  }

	  if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
	    if (strpos($_SERVER['HTTP_X_FORWARDED_FOR'], ',') !== false) {
	      $iplist = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
	      foreach ($iplist as $ip) {
	        if ($this->validate_ip($ip))
	          return $ip;
	      }
	    } else {
	      if ($this->validate_ip($_SERVER['HTTP_X_FORWARDED_FOR']))
	        return $_SERVER['HTTP_X_FORWARDED_FOR'];
	    }
	  }
	  if (!empty($_SERVER['HTTP_X_FORWARDED']) && $this->validate_ip($_SERVER['HTTP_X_FORWARDED']))
	    return $_SERVER['HTTP_X_FORWARDED'];
	  if (!empty($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']) && $this->validate_ip($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']))
	    return $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
	  if (!empty($_SERVER['HTTP_FORWARDED_FOR']) && $this->validate_ip($_SERVER['HTTP_FORWARDED_FOR']))
	    return $_SERVER['HTTP_FORWARDED_FOR'];
	  if (!empty($_SERVER['HTTP_FORWARDED']) && $this->validate_ip($_SERVER['HTTP_FORWARDED']))
	    return $_SERVER['HTTP_FORWARDED'];

	  return $_SERVER['REMOTE_ADDR'];
	}

	private function validate_ip( $ip ) {
	  if (strtolower($ip) === 'unknown')
	     return false;

	  $ip = ip2long($ip);

	  if ($ip !== false && $ip !== -1) {
	    $ip = sprintf('%u', $ip);
	    if ($ip >= 0 && $ip <= 50331647) return false;
	    if ($ip >= 167772160 && $ip <= 184549375) return false;
	    if ($ip >= 2130706432 && $ip <= 2147483647) return false;
	    if ($ip >= 2851995648 && $ip <= 2852061183) return false;
	    if ($ip >= 2886729728 && $ip <= 2887778303) return false;
	    if ($ip >= 3221225984 && $ip <= 3221226239) return false;
	    if ($ip >= 3232235520 && $ip <= 3232301055) return false;
	    if ($ip >= 4294967040) return false;
	  }
	  return true;
	}

	private function is_phone( $phone ) {
		$phone = str_replace('+44', '0', $phone);
		$pattern = "/^(\+44\s?7\d{3}|\(?07\d{3}\)?)\s?\d{3}\s?\d{3}$/";

		if( preg_match($pattern, $phone) ) {
			return true;
		} elseif( in_array(strlen($phone), array(9,10,11)) ) {
			return true;
		}

		return false;
	}

}
