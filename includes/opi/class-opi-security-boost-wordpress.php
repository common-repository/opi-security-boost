<?php
/**
 * opi-remove-endpoints
 *
 * @link https://opi.org.pl
 *
 * @package WordPress
 * @subpackage OPI Security Boost WordPress
 *
 * @since 1.0.1
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

require_once dirname( dirname( __FILE__ ) ) . '/class-opi-security-boost.php';

class OPI_Security_Boost_WordPress extends OPI_Security_Boost {

	/**
	 * check last installed wp version name
	 *
	 * @since 1.0.3
	 */
	private $last_installed_wp_version_name = 'opi_security_boost_wp_version';

	/**
	 * check last installed plugin version name
	 *
	 * @since 1.0.4
	 */
	private $last_installed_plugin_version_name = 'opi_security_boost_plugin_version';

	/**
	 * messages
	 *
	 * @since 1.0.6
	 */
	private $messages_name = 'opi_security_boost_plugin_messages';

	/**
	 * Class consttruct function.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		parent::__construct();
		/**
		 * Hooks
		 */
		add_filter( 'the_generator', '__return_empty_string' );
		add_action( 'init', array( $this, 'action_init_remove' ) );
		add_action( 'shutdown', array( $this, 'action_shutdown' ) );
	}

	public function action_init_remove() {
		remove_action( 'wp_head', 'rsd_link' );
		remove_action( 'wp_head', 'wlwmanifest_link' );
		remove_action( 'wp_head', 'wp_generator' );
	}

	public function action_shutdown() {
		global $wp_version;
		/**
		 * try to remove readme.html
		 */
		if ( get_option( $this->last_installed_wp_version_name, '' ) !== $wp_version ) {
			$file = ABSPATH . 'readme.html';
			if ( is_file( $file ) && is_writable( $file ) ) {
				unlink( $file );
			}
			update_option( $this->last_installed_wp_version_name, $wp_version );
			/**
			 * force to refresh index.php with new version of WordPress
			 *
			 * @since 1.0.5
			 */
			delete_option( $this->last_installed_plugin_version_name );
		}
		/**
		 * check indexes files
		 */
		if ( get_option( $this->last_installed_plugin_version_name, '' ) !== '1.0.7' ) {
			$dirs = $this->get_dirs_to_check();
			foreach ( $dirs as $key => $full_path_dir ) {
				if ( ! is_dir( $full_path_dir ) ) {
					continue;
				}
				$file = $full_path_dir . '/index.php';
				if ( ! is_file( $file ) ) {
					$value = get_option( $this->messages_name );
					if ( ! is_array( $value ) ) {
						$value = array();
					}
					$value[] = sprintf( 'missing %s', $file );
				}
			}
			update_option( $this->last_installed_plugin_version_name, '1.0.7' );
		}
	}

	private function get_dirs_to_check() {
		$dirs = array(
			'themes' => get_theme_root(),
		);
		if ( defined( 'WP_CONTENT_DIR' ) && WP_CONTENT_DIR ) {
			$dirs['content'] = WP_CONTENT_DIR;
			$dirs['upgrade'] = WP_CONTENT_DIR . '/upgrade';
		}
		if ( defined( 'WP_PLUGIN_DIR' ) && WP_PLUGIN_DIR ) {
			$dirs['plugins'] = WP_PLUGIN_DIR;
		}
		$wp_upload_dir = wp_get_upload_dir();
		if ( $wp_upload_dir ) {
			$dirs['uploads'] = $wp_upload_dir['basedir'];
		}
		return $dirs;
	}
}
