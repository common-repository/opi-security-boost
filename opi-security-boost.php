<?php
/*
Plugin Name: OPI Security Boost
Text Domain: opi-security-boost
Plugin URI: https://opi.org.pl/
Description: OPI Security Boost plugin adds basic hardness to your site.
Version: trunk
Author: Marcin Pietrzak
Author URI: http://iworks.pl/
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Copyright 2023-2024 Marcin Pietrzak (marcin@iworks.pl)

this program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * static options
 */
define( 'OPI_SECURITY_BOOST_VERSION', 'trunk' );
define( 'OPI_SECURITY_BOOST_PREFIX', 'opi_security_boost_' );
$base   = dirname( __FILE__ );
$vendor = $base . '/includes';

/**
 * Commons
 */

if ( ! class_exists( 'OPI_Security_Boost_WordPress' ) ) {
	require_once $vendor . '/opi/class-opi-security-boost-wordpress.php';
}
new OPI_Security_Boost_WordPress;

/**
 * EndPoints
 */
if ( ! class_exists( 'OPI_Security_Boost_REST_API' ) ) {
	require_once $vendor . '/opi/class-opi-security-boost-rest-api.php';
}
new OPI_Security_Boost_REST_API;

/**
 * users
 */
if ( ! class_exists( 'OPI_Security_Boost_Users' ) ) {
	require_once $vendor . '/opi/class-opi-security-boost-users.php';
}
new OPI_Security_Boost_Users;

/**
 * i18n
 */
load_plugin_textdomain( 'opi-security-boost', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );


function opi_security_boost_activate() {
}

function opi_security_boost_deactivate() {
}


/**
 * install & uninstall
 */
register_activation_hook( __FILE__, 'opi_security_boost_activate' );
register_deactivation_hook( __FILE__, 'opi_security_boost_deactivate' );
