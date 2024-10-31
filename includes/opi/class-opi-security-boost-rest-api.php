<?php
/**
 * opi-remove-endpoints
 *
 * @link https://opi.org.pl
 *
 * @package WordPress
 * @subpackage OPI Security Boost REST API
 *
 * @since 1.0.0
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

require_once dirname( dirname( __FILE__ ) ) . '/class-opi-security-boost.php';

class OPI_Security_Boost_REST_API extends OPI_Security_Boost {

	private $endpoints_to_remove = array(
		'users',
		'users/(?P<id>[\d]+)',
	);

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
		add_filter( 'rest_endpoints', array( $this, 'filter_rest_endpoints_remove' ) );
	}

	public function filter_rest_endpoints_remove( $endpoints ) {
		if ( is_admin() ) {
			return $endpoints;
		}
		if ( is_user_logged_in() ) {
			return $endpoints;
		}
		foreach ( $this->endpoints_to_remove as $one ) {
			$endpoint = '/wp/v2/' . $one;
			if ( isset( $endpoints[ $endpoint ] ) ) {
				unset( $endpoints[ $endpoint ] );
			}
		}
		return $endpoints;
	}
}
