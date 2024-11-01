<?php
/*
  Plugin Name: Vialala
  Description: Intégration de vos offres vialala dans votre blog
  Author: Vialala.com
  Version: 1.1.3
  Author URI:  https://www.vialala.fr
  Domain Path: /languages
 */

define( 'VIALALA_WP_INTERNAL_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'VIALALA_WP_HTML_PLUGIN_PATH', plugin_dir_url(__FILE__) );

define( 'VIALALA_DEFAULT_API_URL', 'https://api.vialala.fr/api/' );

$GLOBALS['vialala_data'] = null;

function vialala_wp_load_plugin_textdomain() {
    load_plugin_textdomain( 'vialala', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
}
add_action( 'plugins_loaded', 'vialala_wp_load_plugin_textdomain' );

function vialala_load_plugin_css() {
    wp_enqueue_style( 'vialala_css', plugins_url( 'css/vialala.css' , __FILE__ ));
}
add_action( 'wp_enqueue_scripts', 'vialala_load_plugin_css' );


include_once( VIALALA_WP_INTERNAL_PLUGIN_PATH . 'include/service/ApiService.php');
include_once( VIALALA_WP_INTERNAL_PLUGIN_PATH . 'include/service/OfferService.php');
include_once( VIALALA_WP_INTERNAL_PLUGIN_PATH . 'include/service/ServiceService.php');
include_once( VIALALA_WP_INTERNAL_PLUGIN_PATH . 'include/service/TravelPlannerService.php');

include_once( VIALALA_WP_INTERNAL_PLUGIN_PATH . 'include/shortcode/OfferShortcode.php');
include_once( VIALALA_WP_INTERNAL_PLUGIN_PATH . 'include/shortcode/TravelPlannerShortcode.php');

