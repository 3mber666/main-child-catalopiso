<?php
/**
 * 
 * Plugin Name: WP Store [Catalopiso Child Stores]
 * Plugin URI: https://tugasvirtualsolutions.com/
 * Author: Tugas Virtual Solutions
 * Author URI: https://tugasvirtualsolutions.com/
 * Version: 1.2.9
 * Description: A plug-in that can add stores, generate QR and QR page router.
 * Text-Domain: tugasvirtualsolution.com
 * 
 */

if( ! defined( 'ABSPATH' ) ) : exit(); endif; // No direct access allowed.
/**
* Define Plugins Contants
*/

define ( 'WPRK_PATH', trailingslashit( plugin_dir_path( __FILE__ ) ) );
define ( 'WPRK_URL', trailingslashit( plugins_url( '/', __FILE__ ) ) );

/**
 * Loading Necessary Scripts
 * 
 */

add_action( 'admin_enqueue_scripts', 'load_scripts' );
function load_scripts() {
    wp_enqueue_script( 'wp-react-kickoff', WPRK_URL . 'dist/bundle.js', [ 'jquery', 'wp-element' ], wp_rand(), true );
    wp_localize_script( 'wp-react-kickoff', 'appLocalizer', [
        'apiUrl' => home_url( '/wp-json' ),
        'nonce' => wp_create_nonce( 'wp_rest' ),
    ] );
}

add_filter('plugin_action_links_'.plugin_basename(__FILE__), 'add_link_x');
function add_link_x( $links ) {
	$links[] = '<a href="' .admin_url( 'admin.php?page=store-code-settings' ) .'">' . __('Settings') . '</a>';
	return $links;
}


require 'plugin-update-checker/plugin-update-checker.php';
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
	'https://github.com/strayd0g/main-child-catalopiso/',
	__FILE__,
	'store-code-settings'
);

//Set the branch that contains the stable release.
$myUpdateChecker->setBranch('main');

// Optional: If you're using a private repository, specify the access token like this:
$myUpdateChecker->setAuthentication('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');


// import necessary files
require_once WPRK_PATH . 'classes/class-create-admin-menu.php';
require_once WPRK_PATH . 'classes/class-create-settings-routes.php';
require_once WPRK_PATH . 'loaders/receiver.php';
require_once WPRK_PATH . 'loaders/mailer.php';
require_once WPRK_PATH . 'loaders/install.php';
require_once WPRK_PATH . 'lib/helpers.php';
