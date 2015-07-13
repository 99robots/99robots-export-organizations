<?php
/*
Plugin Name: Export Organizations
Plugin URI:
Description: Export Organizations by cateogry
version: 1.0.0
Author: 99 Robots
Author URI: https://99robots.com
License: GPL2
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/* Plugin Name */

if (!defined('NNROBOTS_EXPORT_ORGANIZATIONS_PLUGIN_NAME'))
    define('NNROBOTS_EXPORT_ORGANIZATIONS_PLUGIN_NAME', trim(dirname(plugin_basename(__FILE__)), '/'));

/* Plugin directory */

if (!defined('NNROBOTS_EXPORT_ORGANIZATIONS_PLUGIN_DIR'))
    define('NNROBOTS_EXPORT_ORGANIZATIONS_PLUGIN_DIR', plugin_dir_path(__FILE__) );

/* Plugin url */

if (!defined('NNROBOTS_EXPORT_ORGANIZATIONS_PLUGIN_URL'))
    define('NNROBOTS_EXPORT_ORGANIZATIONS_PLUGIN_URL', plugins_url() . '/' . NNROBOTS_EXPORT_ORGANIZATIONS_PLUGIN_NAME);

/* Plugin verison */

if (!defined('NNROBOTS_EXPORT_ORGANIZATIONS_VERSION_NUM'))
    define('NNROBOTS_EXPORT_ORGANIZATIONS_VERSION_NUM', '1.0.0');

// Activatation / Deactivation

register_activation_hook( __FILE__, array('NNRobots_Export_Organizations', 'register_activation'));

// Hooks / Filter

add_action('admin_menu', 					array('NNRobots_Export_Organizations', 'menu'));
add_action('init', 							array('NNRobots_Export_Organizations', 'load_textdoamin'));

add_filter("plugin_action_links_" . plugin_basename(__FILE__), 	array('NNRobots_Export_Organizations', 'settings_link'));


/**
 * NNRobots_Export_Organizations class.
 */
class NNRobots_Export_Organizations {

	/**
	 * prefix
	 *
	 * (default value: 'nnrobots_export_organizations_')
	 *
	 * @var string
	 * @access public
	 * @static
	 */
	static $prefix = 'nnrobots_export_organizations_';

	/**
	 * prefix_dash
	 *
	 * (default value: 'nnr-eo-')
	 *
	 * @var string
	 * @access public
	 * @static
	 */
	static $prefix_dash = 'nnr-eo-';

	/**
	 * settings_page
	 *
	 * (default value: 'nnrobotos-export-organizations-setings')
	 *
	 * @var string
	 * @access public
	 * @static
	 */
	static $settings_page = 'nnrobotos-export-organizations-setings';

	/**
	 * text_domain
	 *
	 * (default value: '99robots-export-organizations')
	 *
	 * @var string
	 * @access public
	 * @static
	 */
	static $text_domain = '99robots-export-organizations';

	/**
	 * Performs tasks needed upon activation
	 *
	 * @since 1.0.0
	 */
	static function register_activation() {

		/* Check if multisite, if so then save as site option */

		if (is_multisite()) {
			add_site_option(self::$prefix . 'version', NNROBOTS_EXPORT_ORGANIZATIONS_VERSION_NUM);
		} else {
			add_option(self::$prefix . 'version', NNROBOTS_EXPORT_ORGANIZATIONS_VERSION_NUM);
		}
	}

	/**
	 * Hooks to 'plugin_action_links_' filter
	 *
	 * @since 1.0.0
	 */
	static function settings_link($links) {
		$settings_link = '<a href="admin.php?page=' . self::$settings_page . '">Settings</a>';
		array_unshift($links, $settings_link);
		return $links;
	}

	/**
	 * Load the text domain
	 *
	 * @since 1.0.0
	 */
	static function load_textdoamin() {
		load_plugin_textdomain(self::$text_domain, false, basename( dirname( __FILE__ ) ) . '/languages' );
	}

	/**
	 * Hook for the admin menu
	 *
	 * @since 1.0.0
	 *
	 * @param 	N/A
	 * @return 	N/A
	 */
	static function menu() {

		// Settings page

		$settings_page_load = add_submenu_page(
	 		'options-general.php',
	 		__("Export Organizations", self::$text_domain),
	 		__("Export Organizations", self::$text_domain),
	 		'manage_options',
	 		self::$settings_page,
	 		array('NNRobots_Export_Organizations','settings'));

	 	add_action("admin_print_scripts-$settings_page_load", array('NNRobots_Export_Organizations', 'admin_include_scripts'));
	}

	/**
	 * include_scripts function.
	 *
	 * Include css on admin
	 * @access public
	 * @static
	 * @return void
	 */
	static function admin_include_scripts() {

		// Styles

		wp_enqueue_style(self::$prefix . 'settings_css', 	NNROBOTS_EXPORT_ORGANIZATIONS_PLUGIN_URL . '/admin/css/settings.css');
		wp_enqueue_style(self::$prefix . 'bootstrap_css', 	NNROBOTS_EXPORT_ORGANIZATIONS_PLUGIN_URL . '/admin/css/nnr-bootstrap.min.css');
		wp_enqueue_style(self::$prefix . 'fontawesome_css',	NNROBOTS_EXPORT_ORGANIZATIONS_PLUGIN_URL . '/admin/include/fontawesome/css/font-awesome.min.css');

		// Scripts

		wp_enqueue_script(self::$prefix . 'bootstrap_js', 	NNROBOTS_EXPORT_ORGANIZATIONS_PLUGIN_URL . '/admin/js/bootstrap.min.js', array('jquery'));
	}

	/**
	 * Displays Options admin page
	 *
	 * @since 1.0.0
	 *
	 * @param 	N/A
	 * @return 	N/A
	 */

	static function settings() {

		// Force Download

		if ( isset($_GET['file']) && $_GET['file'] != '' ) {

			// Get all organizations

			$organizations = get_posts(array(
				'post_type' 		=> 'organization',
				'posts_per_page' 	=> -1,
				'post_status' 		=> 'publish',
				'tax_query' => array(
					array(
						'taxonomy' 	=> 'org_category',
						'field' 	=> 'id',
						'terms' 	=> $_GET[self::$prefix_dash . 'organization-category']
					)
				)
			));

			// Create file with organization data

			$file = NNROBOTS_EXPORT_ORGANIZATIONS_PLUGIN_DIR . 'organizations.csv';

			$export_file = fopen($file, "w");
			fputcsv($export_file, array(
				'Post ID',
				'Published Date',
				'Modified Date',
				'Title',
				'Acronym',
				'Description',
				'Notes',
				'Programs',
				'Annual Events',
				'Postal Address',
				'Street',
				'City',
				'Province/State',
				'Zip Code',
				'Phone',
				'Fax',
				'Email',
				'Website',
				'Open Hours',
				'Meeting space',
				'Contact Name',
				'Contact Phone',
				'Contact Email',
				'Contact Position',
				'Organization Type',
				'Organization Tags',
				'Parent Categories',
				'Child Categories',
			));

			foreach ($organizations as $organization) {
			    //fputcsv($export_file, $organization);
			}

			fclose($export_file);

			if (file_exists($file)) {
				header('Content-Description: File Transfer');
				header('Content-Type: application/octet-stream');
				header('Content-Disposition: attachment; filename='.basename($file));
				header('Expires: 0');
				header('Cache-Control: must-revalidate');
				header('Pragma: public');
				header('Content-Length: ' . filesize($file));
				ob_clean();
				flush();
				readfile($file);
				exit(0);
			}

		}

		$organization_categories = get_terms('org_category');

		require('admin/views/settings.php');
	}

}