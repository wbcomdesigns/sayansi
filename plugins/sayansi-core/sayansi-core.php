<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://https://wbcomdesigns.com/
 * @since             1.0.0
 * @package           Sayansi_Core
 *
 * @wordpress-plugin
 * Plugin Name:       Sayansi Core
 * Plugin URI:        https://https://wbcomdesigns.com/
 * Description:       The core plugin for all customizations
 * Version:           1.0.0
 * Author:            Wbcom Designs
 * Author URI:        https://https://wbcomdesigns.com//
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       sayansi-core
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'SAYANSI_CORE_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-sayansi-core-activator.php
 */
function activate_sayansi_core() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-sayansi-core-activator.php';
	Sayansi_Core_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-sayansi-core-deactivator.php
 */
function deactivate_sayansi_core() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-sayansi-core-deactivator.php';
	Sayansi_Core_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_sayansi_core' );
register_deactivation_hook( __FILE__, 'deactivate_sayansi_core' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-sayansi-core.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_sayansi_core() {

	$plugin = new Sayansi_Core();
	$plugin->run();

}

function sensei_core_document_tab_labels() {
  global $bp;
  if( ! empty( $bp ) && ( bp_is_user() )  ){
	$bp->bp_nav['documents']['name'] = esc_html__( 'My Library', 'sayansi-core' );
	$bp->bp_nav['beamline']['name'] = esc_html__( 'My Beam Lines', 'sayansi-core' );
  }
}
add_action( 'bp_setup_nav', 'sensei_core_document_tab_labels', 999 );

add_action( 'groups_group_details_edited', 'sayansi_group_detail' );
function sayansi_group_detail( $group_id ){	
	if( isset( $_FILES['group_feature_image'] )  ){
		 $file = $_FILES['group_feature_image'];
		  require_once ABSPATH . 'wp-admin/includes/file.php';
		    $upload = wp_handle_upload($file, ['test_form' => false]);
			 if (isset($upload['error'])) {
            wp_die('Upload error: ' . $upload['error']);
        }
		$file_url = $upload['url'];
		groups_update_groupmeta( $group_id, 'group_feature_image', $file_url );
	}
	if( isset( $_POST['group_column_one_title'] )  ){
		groups_update_groupmeta( $group_id, 'group_column_one_title', $_POST['group_column_one_title'] );
	}
	if( isset( $_POST['group_column_one_desc'] )  ){
		groups_update_groupmeta( $group_id, 'group_column_one_desc', $_POST['group_column_one_desc'] );
	}
	if( isset( $_POST['group_column_one_link'] )  ){
		groups_update_groupmeta( $group_id, 'group_column_one_link', $_POST['group_column_one_link'] );
	}
	if( isset( $_FILES['group_column_one_logo'] )  ){
		 $file = $_FILES['group_column_one_logo'];
		  require_once ABSPATH . 'wp-admin/includes/file.php';
		    $upload = wp_handle_upload($file, ['test_form' => false]);
			 if (isset($upload['error'])) {
            wp_die('Upload error: ' . $upload['error']);
        }
		$file_url = $upload['url'];
		groups_update_groupmeta( $group_id, 'group_column_one_logo', $file_url);
	}
	if( isset( $_POST['group_column_two_title'] )  ){
		groups_update_groupmeta( $group_id, 'group_column_two_title', $_POST['group_column_two_title'] );
	}
	if( isset( $_POST['group_column_two_desc'] )  ){
		groups_update_groupmeta( $group_id, 'group_column_two_desc', $_POST['group_column_two_desc'] );
	}
	if( isset( $_POST['group_column_two_link'] )  ){
		groups_update_groupmeta( $group_id, 'group_column_two_link', $_POST['group_column_two_link'] );
	}
	if( isset( $_FILES['group_column_two_logo'] )  ){
		$file = $_FILES['group_column_two_logo'];
		  require_once ABSPATH . 'wp-admin/includes/file.php';
		    $upload = wp_handle_upload($file, ['test_form' => false]);
			 if (isset($upload['error'])) {
            wp_die('Upload error: ' . $upload['error']);
        }
		$file_url = $upload['url'];
		groups_update_groupmeta( $group_id, 'group_column_two_logo', $file_url );
	}
	if( isset( $_POST['group_column_three_title'] )  ){
		groups_update_groupmeta( $group_id, 'group_column_three_title', $_POST['group_column_three_title'] );
	}
	if( isset( $_POST['group_column_three_desc'] )  ){
		groups_update_groupmeta( $group_id, 'group_column_three_desc', $_POST['group_column_three_desc'] );
	}
	if( isset( $_POST['group_column_three_link'] )  ){
		groups_update_groupmeta( $group_id, 'group_column_three_link', $_POST['group_column_three_link'] );
	}
	if( isset( $_FILES['group_column_three_logo'] )  ){
		$file = $_FILES['group_column_three_logo'];
		  require_once ABSPATH . 'wp-admin/includes/file.php';
		    $upload = wp_handle_upload($file, ['test_form' => false]);
			 if (isset($upload['error'])) {
            wp_die('Upload error: ' . $upload['error']);
        }
		$file_url = $upload['url'];
		groups_update_groupmeta( $group_id, 'group_column_three_logo', $file_url );
	}
	if( isset( $_POST['group_column_four_title'] )  ){
		groups_update_groupmeta( $group_id, 'group_column_four_title', $_POST['group_column_four_title'] );
	}
	if( isset( $_POST['group_column_four_desc'] )  ){
		groups_update_groupmeta( $group_id, 'group_column_four_desc', $_POST['group_column_four_desc'] );
	}
	if( isset( $_POST['group_column_four_link'] )  ){
		groups_update_groupmeta( $group_id, 'group_column_four_link', $_POST['group_column_four_link'] );
	}
	if( isset( $_FILES['group_column_four_logo'] )  ){
		$file = $_FILES['group_column_four_logo'];
		  require_once ABSPATH . 'wp-admin/includes/file.php';
		    $upload = wp_handle_upload($file, ['test_form' => false]);
			 if (isset($upload['error'])) {
            wp_die('Upload error: ' . $upload['error']);
        }
		$file_url = $upload['url'];
		groups_update_groupmeta( $group_id, 'group_column_four_logo', $file_url );
	}
}

run_sayansi_core();
