<?php
/**
 * Plugin Name: Header Footer
 * Description: Build a global header and footer with Elementor, and choose which one is live from Settings.
 * Version: 1.0.0 | Developed by Din Mohammed
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'EHF_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

require_once EHF_PLUGIN_DIR . 'includes/class-cpt.php';
require_once EHF_PLUGIN_DIR . 'includes/class-settings.php';
require_once EHF_PLUGIN_DIR . 'includes/class-frontend.php';

class EHF_Activator {

	public static function activate() {
		EHF_CPT::register();

		$cpt_support = get_option( 'elementor_cpt_support', array( 'page', 'post' ) );
		if ( ! in_array( EHF_CPT::POST_TYPE, $cpt_support, true ) ) {
			$cpt_support[] = EHF_CPT::POST_TYPE;
			update_option( 'elementor_cpt_support', $cpt_support );
		}

		flush_rewrite_rules();
	}
}

register_activation_hook( __FILE__, array( 'EHF_Activator', 'activate' ) );

add_action( 'init', array( 'EHF_CPT', 'register' ) );
add_action( 'add_meta_boxes', array( 'EHF_CPT', 'register_meta_box' ) );
add_action( 'save_post_' . EHF_CPT::POST_TYPE, array( 'EHF_CPT', 'save_meta_box' ) );
add_action( 'template_redirect', array( 'EHF_CPT', 'redirect_single_template' ) );

add_action( 'admin_menu', array( 'EHF_Settings', 'register_menu' ) );
add_action( 'admin_init', array( 'EHF_Settings', 'register_settings' ) );

add_action( 'wp_body_open', array( 'EHF_Frontend', 'render_header' ) );
add_action( 'wp_footer', array( 'EHF_Frontend', 'render_footer' ), 5 );

add_action(
	'admin_notices',
	function () {
		if ( ! class_exists( '\Elementor\Plugin' ) ) {
			echo '<div class="notice notice-warning"><p>Elementor Header Footer requires the Elementor plugin to be installed and active.</p></div>';
		}
	}
);
