<?php
/*
Plugin Name: Mozilla BrowserID
Plugin URI: http://wordpress.org/extend/plugins/browserid/
Description: BrowserID provides a safer and easier way to sign in
Version: 0.2
Author: Marcel Bokhorst
Author URI: http://blog.bokhorst.biz/about/
*/

/*
	Copyright (c) 2011 Marcel Bokhorst

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; either version 3 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

#error_reporting(E_ALL);

// Check PHP version
if (version_compare(PHP_VERSION, '5.0.0', '<'))
	die('BrowserID requires at least PHP 5, installed version is ' . PHP_VERSION);

// Define constants
define('c_bid_text_domain', 'browserid');
define('c_bid_option_version', 'bid_version');

// Define class
if (!class_exists('M66BrowserID')) {
	class M66BrowserID {
		// Class variables
		var $main_file = null;
		var $plugin_url = null;
		var $debug = null;

		// Constructor
		function __construct() {
			// Get main file name
			$this->main_file = str_replace('-class', '', __FILE__);

			// Get plugin url
			$this->plugin_url = WP_PLUGIN_URL . '/' . basename(dirname($this->main_file));
			if (strpos($this->plugin_url, 'http') === 0 && is_ssl())
				$this->plugin_url = str_replace('http://', 'https://', $this->plugin_url);

			// Debug mode
			$options = get_option('browserid_options');
			$this->debug = (isset($options['browserid_debug']) && $options['browserid_debug']);

			// Register de-activation
			register_deactivation_hook($this->main_file, array(&$this, 'Deactivate'));

			// Register actions
			add_action('init', array(&$this, 'Init'), 0);
			add_action('wp_head', array(&$this, 'WP_head'));
			add_action('login_head', array(&$this, 'WP_head'));
			add_action('login_form', array(&$this, 'Login_form'));
			add_action('widgets_init', create_function('', 'return register_widget("BrowserID_Widget");'));
			if (is_admin()) {
				add_action('admin_menu', array(&$this, 'Admin_menu'));
				add_action('admin_init', array(&$this, 'Admin_init'));
			}
		}

		// Handle plugin activation
		function Activate() {
			global $wpdb;
			$version = get_option(c_bid_option_version);
			if ($version < 1) {
			}
			if ($version < 1)
				update_option(c_bid_option_version, 1);
		}

		// Handle plugin deactivation
		function Deactivate() {
		}

		// Initialization
		function Init() {
			// I18n
			load_plugin_textdomain(c_bid_text_domain, false, dirname(plugin_basename(__FILE__)));

			// BrowserID assertion
			if (isset($_REQUEST['browserid_assertion'])) {
				// Build URL
				$url = 'https://browserid.org/verify?assertion=' . $_REQUEST['browserid_assertion'] . '&audience=' . $_SERVER['HTTP_HOST']; //urlencode(get_home_url());

				// Verify
				$response = wp_remote_get($url);

				// Check result
				if (is_wp_error($response)) {
					header('Content-type: text/plain');
					echo __($response->get_error_message()) . PHP_EOL;
				}
				else {
					// Decode result
					$result = json_decode($response['body']);
					if (empty($result) || empty($result->status)) {
						// No result or status
						header('Content-type: text/plain');
						echo __('Verification void', c_bid_text_domain) . PHP_EOL;
						if ($this->debug)
							print_r($response);
					}
					else if ($result->status == 'okay' && $result->audience == $_SERVER['HTTP_HOST']) {
						// Succeeded
						$user = self::Login_by_email($result->email);
						if ($user)
							wp_redirect(admin_url());
						else {
							// User not found?
							header('Content-type: text/plain');
							echo __('Login failed', c_bid_text_domain) . ' (' . $result->email . ')' . PHP_EOL;
						}
					}
					else {
						// Failed
						header('Content-type: text/plain');
						echo __('Verification failed', c_bid_text_domain) . PHP_EOL;
						if ($this->debug)
							print_r($result);
					}
				}
				exit();
			}

			// Enqueue BrowserID script
			wp_enqueue_script('browserid', 'https://browserid.org/include.js');
		}

		// Log WordPress user in using e-mail address
		function Login_by_email($email) {
			$user = get_user_by_email($email);
			if ($user) {
				wp_set_current_user($user->ID, $user->user_login);
				wp_set_auth_cookie($user->ID);
				do_action('wp_login', $user->user_login);
			}
			return $user;
		}

		// Define login JavaScript function
		function WP_head() {
?>
			<script type="text/javascript">
				function browserid_login() {
					navigator.id.getVerifiedEmail(function(assertion) {
						if (assertion)
							window.location='<?php echo get_home_url(); ?>?browserid_assertion=' + assertion;
						else
							alert("<?php _e('Verification failed', c_bid_text_domain); ?>");
					});
					return false;
				}
			</script>
<?php
		}

		// Add login button to login form
		function Login_form() {
			$options = get_option('browserid_options');
			if (empty($options['browserid_login_html']))
				$html = '<img src="https://browserid.org/i/sign_in_blue.png" style="border: 0;" />';
			else
				$html = $options['browserid_login_html'];
			echo '<p><a href="#" onclick="return browserid_login();">' . $html . '</a><br /><br /></p>';
		}

		// Register options page
		function Admin_menu() {
			if (function_exists('add_options_page'))
				add_options_page(
					__('BrowserID', c_bid_text_domain) . ' ' . __('Administration', c_bid_text_domain),
					__('BrowserID', c_bid_text_domain),
					'manage_options',
					$this->main_file,
					array(&$this, 'Administration'));
		}

		// Define options page
		function Admin_init() {
			register_setting('browserid_options', 'browserid_options', null);
			add_settings_section('plugin_main', null, array(&$this, 'Options_main'), 'browserid');
			add_settings_field('browserid_login_html', __('Custom login HTML:', c_bid_text_domain), array(&$this, 'Option_login_html'), 'browserid', 'plugin_main');
			add_settings_field('browserid_logout_html', __('Custom logout HTML:', c_bid_text_domain), array(&$this, 'Option_logout_html'), 'browserid', 'plugin_main');
			add_settings_field('browserid_nospsn', __('I don\'t want to support this plugin with the Sustainable Plugins Sponsorship Network:', c_bid_text_domain), array(&$this, 'Option_nospsn'), 'browserid', 'plugin_main');
			add_settings_field('browserid_debug', __('Debug mode:', c_bid_text_domain), array(&$this, 'Option_debug'), 'browserid', 'plugin_main');
  		}

		// Main options section
		function Options_main() {
		}

		// Login HTML option
		function Option_login_html() {
			$options = get_option('browserid_options');
			echo "<input id='browserid_login_html' name='browserid_options[browserid_login_html]' type='text' size='80' value='{$options['browserid_login_html']}' />";
		}

		// Logout HTML option
		function Option_logout_html() {
			$options = get_option('browserid_options');
			echo "<input id='browserid_logout_html' name='browserid_options[browserid_logout_html]' type='text' size='80' value='{$options['browserid_logout_html']}' />";
		}

		// SPSN option
		function Option_nospsn() {
			$options = get_option('browserid_options');
			$chk = (isset($options['browserid_nospsn']) && $options['browserid_nospsn'] ? " checked='checked'" : '');
			echo "<input id='browserid_nospsn' name='browserid_options[browserid_nospsn]' type='checkbox'" . $chk. "/>";
		}

		// Debug option
		function Option_debug() {
			$options = get_option('browserid_options');
			$chk = (isset($options['browserid_debug']) && $options['browserid_debug'] ? " checked='checked'" : '');
			echo "<input id='browserid_debug' name='browserid_options[browserid_debug]' type='checkbox'" . $chk. "/>";
		}

		// Render options page
		function Administration() {
			// Sustainable Plugins Sponsorship Network
			self::Render_SPSN();
?>
			<div class="wrap">
			<h2><?php _e('BrowserID', c_bid_text_domain); ?></h2>
			<form method="post" action="options.php">
			<?php settings_fields('browserid_options'); ?>
			<?php do_settings_sections('browserid'); ?>
			<p class="submit">
			<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
			</p>
			</form>
			<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
			<input type="hidden" name="cmd" value="_s-xclick">
			<input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----">
			<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
			</form>
			</div>
<?php
			if ($this->debug)
				echo '<br /><pre>' . print_r($_SERVER, true) . '</pre>';
		}

		// SPSN script
		function Render_SPSN() {
			$options = get_option('browserid_options');
			if (!(isset($options['browserid_nospsn']) && $options['browserid_nospsn'])) {
?>
				<script type="text/javascript">
				var psHost = (("https:" == document.location.protocol) ? "https://" : "http://");
				document.write(unescape("%3Cscript src='" + psHost + "pluginsponsors.com/direct/spsn/display.php?client=browserid&spot=' type='text/javascript'%3E%3C/script%3E"));
				</script>
				<a class="bid_spsn" href="http://pluginsponsors.com/privacy.html" target="_blank">
				<?php _e('Privacy in the Sustainable Plugins Sponsorship Network', c_bid_text_domain); ?></a>
<?php
			}
		}

		// Check environment
		function Check_prerequisites() {
			// Check WordPress version
			global $wp_version;
			if (version_compare($wp_version, '3.0') < 0)
				die('BrowserID requires at least WordPress 3.0');

			// Check basic prerequisities
			self::Check_function('add_action');
			self::Check_function('wp_enqueue_script');
			self::Check_function('json_decode');
		}

		function Check_function($name) {
			if (!function_exists($name))
				die('Required WordPress function "' . $name . '" does not exist');
		}

		// Change file extension
		function Change_extension($filename, $new_extension) {
			return preg_replace('/\..+$/', $new_extension, $filename);
		}
	}
}

class BrowserID_Widget extends WP_Widget {
	function BrowserID_Widget() {
		$widget_ops = array('classname' => 'widget_browserid', 'description' => '');
		$this->WP_Widget('BrowserID_Widget', 'BrowserID', $widget_ops);
	}

	// Widget contents
	function widget($args, $instance) {
		$options = get_option('browserid_options');

		if (is_user_logged_in()) {
			if (empty($options['browserid_logout_html']))
				$html = 'Logout';
			else
				$html = $options['browserid_logout_html'];
			echo '<a href="' . wp_logout_url() . '">' . $html . '</a>';
		}
		else {
			if (empty($options['browserid_login_html']))
				$html = '<img src="https://browserid.org/i/sign_in_blue.png" style="border: 0;" />';
			else
				$html = $options['browserid_login_html'];
			echo '<a href="#" onclick="return browserid_login();">' . $html . '</a>';
		}
	}

	// Update settings
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		return $instance;
	}

	// Render settings
	function form($instance) {
		if (empty($instance['title']))
			$instance['title'] = null;
?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($instance['title']); ?>" />
		</p>
<?php
	}
}

// Check pre-requisites
M66BrowserID::Check_prerequisites();

// Start plugin
global $m66browserid;
if (empty($m66browserid)) {
	$m66browserid = new M66BrowserID();
	register_activation_hook(__FILE__, array(&$m66browserid, 'Activate'));
}

?>
