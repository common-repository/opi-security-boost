<?php
/**
 * OPI Security Boost
 *
 * @link https://opi.org.pl
 *
 * @package WordPress
 * @subpackage OPI Security Boost Users
 *
 * @since 1.0.0
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

require_once dirname( dirname( __FILE__ ) ) . '/class-opi-security-boost.php';

class OPI_Security_Boost_Users extends OPI_Security_Boost {

	/**
	 * Last login timestamp to user meta
	 *
	 * @since 1.0.3
	 */
	private $user_meta_last_login_timestamp_name = '_opi_last_login';

	private $type_name = 'users';
	/**
	 * Class _construct function.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		parent::__construct();
		/**
		 * Hooks
		 */
		add_action( 'parse_request', array( $this, 'action_init_fixers' ), PHP_INT_MAX );
		add_action( 'wp_error_added', array( $this, 'filter_shake_error_codes_fixers' ), PHP_INT_MAX, 4 );
		add_action( 'wp_login', array( $this, 'action_wp_login_set_last_login_timestamp' ), 10, 2 );
		add_filter( 'manage_' . $this->type_name . '_columns', array( $this, 'filter_add_columns' ) );
		add_filter( 'manage_' . $this->type_name . '_custom_column', array( $this, 'filter_add_custom_column_content' ), 10, 3 );
		add_filter( 'manage_' . $this->type_name . '_sortable_columns', array( $this, 'filter_add_sortable_columns' ) );
		add_filter( 'redirect_canonical', array( $this, 'check_enum' ), 10, 2 );
		/**
		 * create user with random users.ID
		 *
		 * @since 1.0.7
		 */
		add_filter( 'wp_pre_insert_user_data', array( $this, 'filter_wp_pre_insert_user_data_set_random_user_id' ), PHP_INT_MAX, 4 );
	}

	/**
	 * Add logged timestamp after the user has successfully logged in.
	 *
	 * @since 1.0.3
	 *
	 * @param string  $user_login Username.
	 * @param WP_User $user       WP_User object of the logged-in user.
	 */
	public function action_wp_login_set_last_login_timestamp( $user_login, $user ) {
		if ( update_user_meta( $user->ID, $this->user_meta_last_login_timestamp_name, time() ) ) {
			return;
		}
		add_user_meta( $user->ID, $this->user_meta_last_login_timestamp_name, time(), true );
	}

	/**
	 * Added columns to users table
	 *
	 * @since 1.0.3
	 */
	public function filter_add_custom_column_content( $content, $column_name, $user_id ) {
		switch ( $column_name ) {
			case 'user_registered':
				$udata = get_userdata( $user_id );
				return date( 'Y-m-d', strtotime( $udata->$column_name ) );
				break;
			case $this->user_meta_last_login_timestamp_name;
				$value = get_user_meta( $user_id, $column_name, true );
				if ( empty( $value ) ) {
					return '&mdash;';
				}
				return date( 'Y-m-d', $value );
				break;
		}
		return $content;
	}

	/**
	 * Added columns to users table
	 *
	 * @since 1.0.3
	 */
	public function filter_add_columns( $columns ) {
		$columns['user_registered']                            = esc_html__( 'Registration Date', 'opi-security-boost' );
		$columns[ $this->user_meta_last_login_timestamp_name ] = esc_html__( 'Last Login Date', 'opi-security-boost' );
		return $columns;
	}

	/**
	 * Added columns to users table
	 *
	 * @since 1.0.3
	 */
	public function filter_add_sortable_columns( $columns ) {
		$columns['user_registered']                            = array(
			'user_registered',
			false,
			esc_html__( 'Registration date', 'opi-security-boost' ),
			esc_html__( 'Table ordered by registration time.' ),
			'asc',
		);
		$columns[ $this->user_meta_last_login_timestamp_name ] = array(
			'meta_' . $this->user_meta_last_login_timestamp_name,
			false,
			esc_html__( 'Last login date', 'opi-security-boost' ),
			esc_html__( 'Table ordered by last time.' ),
			'asc',
		);
		return $columns;
	}
	/**
	 * error messages fixer
	 *
	 * @since 1.0.0
	 */
	public function filter_shake_error_codes_fixers( $code, $message, $data, $wp_error ) {
		switch ( $code ) {
			case 'incorrect_password':
			case 'invalidcombo':
			case 'invalid_email':
			case 'invalid_username':
			case 'username_exists':
				$new_message = sprintf(
					'<strong>%s</strong> %s',
					esc_html__( 'Error:', 'opi-security-boost' ),
					esc_html__( 'Wrong username, email or password.', 'opi-security-boost' )
				);
				if ( $message !== $new_message ) {
					$wp_error->remove( $code );
					$wp_error->add( $code, $new_message, $data );
				}
				break;
		}
	}

	/**
	 * URL Fixers
	 *
	 * @since 1.0.0
	 */
	public function action_init_fixers( $wp ) {
		if ( is_admin() ) {
			return;
		}
		if (
			isset( $wp->query_vars['author'] )
			&& preg_match( '/^\d+$/', $wp->query_vars['author'] )
		) {
			$wp->set_query_var( 'author', false );
			wp_redirect( get_option( 'home' ), 302 );
			exit;
		}
	}
	/**
	 * Check Enumerate helper
	 *
	 * @since 1.0.0
	 */
	public function check_enum( $redirect, $request ) {
		if ( preg_match( '/\?author=([0-9]*)(\/*)/i', $request ) ) {
			wp_redirect( get_option( 'home' ), 302 );
			exit;
		}
		return $redirect;
	}

	/**
	 * create user with random users.ID
	 *
	 * @since 1.0.7
	 *
	 * @param array    $data {
	 *     Values and keys for the user.
	 *
	 *     @type string $user_login      The user's login. Only included if $update == false
	 *     @type string $user_pass       The user's password.
	 *     @type string $user_email      The user's email.
	 *     @type string $user_url        The user's url.
	 *     @type string $user_nicename   The user's nice name. Defaults to a URL-safe version of user's login
	 *     @type string $display_name    The user's display name.
	 *     @type string $user_registered MySQL timestamp describing the moment when the user registered. Defaults to
	 *                                   the current UTC timestamp.
	 * }
	 * @param bool     $update   Whether the user is being updated rather than created.
	 * @param int|null $user_id  ID of the user to be updated, or NULL if the user is being created.
	 * @param array    $userdata The raw array of data passed to wp_insert_user().
	 */
	public function filter_wp_pre_insert_user_data_set_random_user_id( $data, $update, $user_id, $userdata ) {
		if ( $update ) {
			return $data;
		}
		if ( $user_id ) {
			return $data;
		}
		$data['ID'] = $this->get_random_user_ID();
		return $data;
	}

	/**
	 * get non-exists & random ID
	 *
	 * @since 1.0.7
	 */
	private function get_random_user_ID() {
		global $wpdb;
		$id  = 1;
		$sql = sprintf( 'select ID from %s where ID = %%d', $wpdb->users );
		do {
			$id = wp_rand();
		} while (
			$wpdb->get_var(
				$wpdb->prepare( $sql, $id )
			)
		);
		return $id;
	}

}
