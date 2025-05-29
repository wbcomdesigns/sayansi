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
define ( 'BP_FRIENDS_SLUG', 'connections' );
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
 * The file responsible for defining function.
 */
require plugin_dir_path( __FILE__ ) . 'includes/sayansi-core-functions.php';

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

//add function to get upload doc ids for group and user profile
function wbcom_get_documents( $component_name, $doc_component, $id, $folder_id ){
	global $wpdb;
	if( 'members' == $component_name ){
		if( 'document' == $doc_component ){
			$results = $wpdb->get_results(
				$wpdb->prepare(
					"
					SELECT d.id, d.user_id, d.attachment_id, d.blog_id, d.title, d.description, d.date_modified, d.status,d.activity_id, d.group_id,d.folder_id, dm.meta_value AS file_extension
					FROM {$wpdb->prefix}bp_document d
					JOIN {$wpdb->prefix}bp_document_meta dm ON d.id = dm.document_id
					WHERE d.user_id = %d 
					AND d.folder_id = %d
					AND dm.meta_key = 'extension'        
					AND (dm.meta_value LIKE '.doc' OR dm.meta_value LIKE '.docx' OR dm.meta_value LIKE '.pdf' OR dm.meta_value LIKE '.csv' OR dm.meta_value LIKE '.xls')
					",
					$id,
					$folder_id
				)
			);
			$ids = array();
			foreach( $results as $result ){
				$ids[] = $result->id;
			}
		} elseif( 'audio' == $doc_component ){	
			$results = $wpdb->get_results(
						$wpdb->prepare(
							"
							SELECT d.id, d.title, d.description, d.date_modified, d.status, dm.meta_value AS file_extension
							FROM {$wpdb->prefix}bp_document d
							JOIN {$wpdb->prefix}bp_document_meta dm ON d.id = dm.document_id
							WHERE d.user_id = %d 
							AND d.folder_id = %d
							AND dm.meta_key = 'extension'
							AND (dm.meta_value LIKE '.mp3')
							",
							$id,
							$folder_id
						)
					);		
					$ids = array();
					foreach( $results as $result ){
						$ids[] = $result->id;
					}
		}
	} elseif( 'groups' == $component_name ){	
		if( 'document' == $doc_component ){	
			$results = $wpdb->get_results(
				$wpdb->prepare(
					"
					SELECT d.id, d.user_id, d.attachment_id, d.blog_id, d.title, d.description, d.date_modified, d.status,d.activity_id, d.group_id,d.folder_id, dm.meta_value AS file_extension
					FROM {$wpdb->prefix}bp_document d
					JOIN {$wpdb->prefix}bp_document_meta dm ON d.id = dm.document_id
					WHERE d.group_id = %d 
					AND d.folder_id = %d
					AND dm.meta_key = 'extension'        
					AND (dm.meta_value LIKE '.doc')
					",
					$id,
					$folder_id
				)
			);		
			$ids = array();
			foreach( $results as $result ){
				$ids[] = $result->id;
			}
		} elseif( 'audio' == $doc_component ){			
				$results = $wpdb->get_results(
					$wpdb->prepare(
						"
						SELECT d.id, d.title, d.description, d.date_modified, d.status, dm.meta_value AS file_extension
						FROM {$wpdb->prefix}bp_document d
						JOIN {$wpdb->prefix}bp_document_meta dm ON d.id = dm.document_id
						WHERE d.group_id = %d 
						AND d.folder_id = %d
						AND dm.meta_key = 'extension'
						AND (dm.meta_value LIKE '.mp3')
						",
						$id,
						$folder_id
					)
				);		
				$ids = array();
				foreach( $results as $result ){
					$ids[] = $result->id;
				}
		}
	}
	return $ids;
}

/**
 * wbcom_update_business_excerpt_value
 *
 * @return void
 */
function wbcom_update_business_excerpt_value(){	
	global $wpdb;
	$excerpt_val = isset( $_POST['acf']['field_6729c0127e757'] ) ? sanitize_text_field( wp_unslash( $_POST['acf']['field_6729c0127e757'] ) ) : ''; //phpcs:ignore
	$bp_business_profile_id = isset( $_POST['bp_business_profile_id'] ) ? sanitize_text_field( wp_unslash( $_POST['bp_business_profile_id'] ) ) : ''; //phpcs:ignore
	// Update the post excerpt for the custom post type.						
	update_field( 'beam_line_excerpt', wpautop( $excerpt_val ), $bp_business_profile_id );
	$wpdb->update(
		'wp_posts',
		array(
			'post_excerpt' => $excerpt_val,				
		),
		array(
			'ID' => $bp_business_profile_id,
		)
	);
}
add_action('save_post', 'wbcom_update_business_excerpt_value');

add_action('wp', 'wbcom_document');
function wbcom_document(){
	if ( ! current_user_can( 'manage_options' ) && 'document' == bp_current_component() ) {
	?>
	<style>
		.document-options #bp-add-document,
		.document-options #bb-create-folder{
			display : none!important;	
		}
		.document-type-navs .component-navigation.document-nav{
			display : none!important;	
		}
	</style>
	<?php
	}
}

run_sayansi_core();
