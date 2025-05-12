<?php
/**
 * @package BuddyBoss Child
 * The parent theme functions are located at /buddyboss-theme/inc/theme/functions.php
 * Add your own functions at the bottom of this file.
 */


/****************************** THEME SETUP ******************************/

/**
 * Sets up theme for translation
 *
 * @since BuddyBoss Child 1.0.0
 */
function buddyboss_theme_child_languages()
{
  /**
   * Makes child theme available for translation.
   * Translations can be added into the /languages/ directory.
   */

  // Translate text from the PARENT theme.
  load_theme_textdomain( 'buddyboss-theme', get_stylesheet_directory() . '/languages' );

  // Translate text from the CHILD theme only.
  // Change 'buddyboss-theme' instances in all child theme files to 'buddyboss-theme-child'.
  // load_theme_textdomain( 'buddyboss-theme-child', get_stylesheet_directory() . '/languages' );

}
add_action( 'after_setup_theme', 'buddyboss_theme_child_languages' );

/**
 * Enqueues scripts and styles for child theme front-end.
 *
 * @since Boss Child Theme  1.0.0
 */
function buddyboss_theme_child_scripts_styles()
{
  /**
   * Scripts and Styles loaded by the parent theme can be unloaded if needed
   * using wp_deregister_script or wp_deregister_style.
   *
   * See the WordPress Codex for more information about those functions:
   * http://codex.wordpress.org/Function_Reference/wp_deregister_script
   * http://codex.wordpress.org/Function_Reference/wp_deregister_style
   **/

  // Styles
  wp_enqueue_style( 'buddyboss-child-css', get_stylesheet_directory_uri().'/assets/css/custom.css' );

  // Javascript
  wp_enqueue_script( 'buddyboss-child-js', get_stylesheet_directory_uri().'/assets/js/custom.js' );
}
add_action( 'wp_enqueue_scripts', 'buddyboss_theme_child_scripts_styles', 9999 );


/****************************** CUSTOM FUNCTIONS ******************************/

// Add your own custom functions here

add_filter( 'body_class', function( $classes ) {
  $user_member_type = bp_get_member_type( get_current_user_id() );
  $can_create_business = ( user_can( get_current_user_id(), 'administrator' ) || in_array( $user_member_type, array( 'developer' ) ) );

  if( isset( $can_create_business ) && $can_create_business ) {
    $classes[] = 'can-create-business';
  }

  return $classes;

} );


apply_filters('bp_business_profile_contact_info', function() { 
  return true;
} );


add_filter( 'bp_member_change_blog_label', function() {
    return 'My Blog';
} );

/*
add_filter('register_business_post_type_slug_rewrite', function($slug, $type = '' ) {
	if( $type == 'rewrite_slug' ) {
		return '/';
	} 
	return '';
}, 10 ,2);
*/

/**
 * Add menu title for Blog network subnav
 *
 * @param  mixed $content
 * @return void
 */
function wbcom_display_blog_network_content_before_main( $content ) {
  $current_url = home_url( $_SERVER['REQUEST_URI'] ); // Get the full URL
  $current_page_path = parse_url( $current_url, PHP_URL_PATH ); // Get the path part of the URL

  // Check if the path is exactly '/blog/'
  if( '/blog/' == $current_page_path ){
    echo '<div class="container">'; 
    echo '<h2>Blog Network</h2>';
    echo '</div>'; 
  } else {
    echo '';
  }
}
add_action( 'buddyboss_theme_begin_content', 'wbcom_display_blog_network_content_before_main' );

// Update connection tab slug on user profile
define ( 'BP_FRIENDS_SLUG', 'connections' );

add_filter( 'classic_editor_enabled_editors_for_post_type', function ( $editors, $post_type ) {
  if ( $post_type == 'mpcs-course' || $post_type == 'mpcs-lesson' || $post_type = 'mpcs-quiz' ) {
    $editors['classic_editor'] = false;
  }
  return $editors;
}, 10, 2 );