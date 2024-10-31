=== opi-security-boost ===
Contributors: mpietrzak, mruszczyk, iworks
Donate link: https://ko-fi.com/iworks?utm_source=opi-security-boost&utm_medium=readme-donate
Tags: security, hardness 
Requires at least: 6.0
Tested up to: 6.6
Stable tag: 1.0.7
Requires PHP: 8.0
License: GPLv3 or later
License URI: https://www.gnu.org/licenses/gpl-3.0.html

OPI Security Boost plugin adds basic hardness to your site.

== Description ==

OPI Security Boost plugin adds basic hardness to your site.

== Installation ==

There are 3 ways to install this plugin:

= 1. The super easy way =
1. In your Admin, go to menu Plugins > Add.
1. Search for `opi-security-boost`.
1. Click to install.
1. Activate the plugin.

= 2. The easy way =
1. Download the plugin (.zip file) on the right column of this page.
1. In your Admin, go to menu Plugins > Add.
1. Select button `Upload Plugin`.
1. Upload the .zip file you just downloaded.
1. Activate the plugin.

= 3. The old and reliable way (FTP) =
1. Upload `opi-security-boost` folder to the `/wp-content/plugins/` directory.
1. Activate the plugin through the 'Plugins' menu in WordPress.

== Frequently Asked Questions ==

== Screenshots ==

== Changelog ==

= 1.0.7 (2024-10-12) =
* Random ID generation for new user account has been added.

= 1.0.6 (2024-01-22) =
* The plugin has been published on WordPress.org.

= 1.0.5 (2023-10-19) =
* Implementation of comments submitted by WordPress Plugin Review Team.
* Users REST API for logged users has been restored.

= 1.0.4 (2023-08-01) =

* Directory indexes were been added.

= 1.0.3 (2023-07-31) =

* The last login date has been added to user login action.
* The last login date and the registration date were been added to users list table.

= 1.0.2 (2023-07-28) =

* The WordPress version has been removed from front-end.
* The `/readme.html` will be removed if there is proper files rights.

= 1.0.1 (2023-07-24) =

* Really Simple Discovery meta tag has been removed from front-end.
* Windows Live Writer meta tag has been removed from front-end.

= 1.0.0 (2023-07-21) =

* A prevent for enumerating users has been added:
** The `?author=\d+` query string has been redirected to the main page.
** The login form messages have been unified to remove information about account existence.
* Users related REST API endpoints have been removed.

== Upgrade Notice ==

