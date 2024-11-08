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

/**
 * Get ACF Business Information group fields
 *
 * @return array|false
 */
function wbcom_get_business_info_group_fields() {
    if (function_exists('acf_get_fields')) {
        $fields = acf_get_fields('group_6729bef606dca');
        return is_array($fields) ? $fields : false;
    }
    return false; // Return false if ACF function does not exist
}

/**
 * Display ACF Business Information fields in the contact tab
 *
 * @return void
 */
function wbcom_display_business_info_fields_in_contact() {
    if (function_exists('acf_form_head')) {
        acf_form_head();    
    }

    $fields = wbcom_get_business_info_group_fields();

    if (!empty($fields)) {
        acf_render_fields($fields, get_the_ID());
    } else {
        // Handle the case where no fields are found or an error occurred
        echo '<p>No fields available to display.</p>';
    }
}
add_action('business_profile_after_contact_info', 'wbcom_display_business_info_fields_in_contact');


/**
 * Save ACF Business Information fields
 *
 * @param int $post_id
 * @return void
 */
function wbcom_save_business_info_fields($post_id) {
    if (isset($_POST['acf']) && !empty($_POST['acf'])) {
        foreach ($_POST['acf'] as $field_key => $field_value) {
            $field = function_exists('get_field_object') ? get_field_object($field_key) : null;

            if ($field) {
                switch ($field['type']) {
                    case 'text':
                        update_field($field_key, sanitize_text_field($field_value), $post_id);
                        break;
                     case 'wysiwyg':
                        update_field($field_key, $field_value, $post_id);
                        break;
                    default:
                        update_field($field_key, $field_value, $post_id);
                        break;
                }
            }
        }
    }
}
add_action('save_post', 'wbcom_save_business_info_fields', 99, 1);


/**
 * Show ACF Business Information fields in the about section
 *
 * @return void
 */
function wbcom_show_business_info_fields_in_about_section() {
    $fields = wbcom_get_business_info_group_fields();

    if (!empty($fields)) {
        foreach ($fields as $field) {
            $value = get_field($field['name'], get_the_ID());
            if (!empty($value)) {
                echo '<h2>' . esc_html($field['label']) . '</h2>';

                switch ($field['type']) {
                    case 'text':
                        echo '<p>' . esc_html($value) . '</p>';
                        break;
                    case 'wysiwyg':
                        echo $value;
                        break;
                }
            }
        }
    }
}
add_action('bp_business_profile_after_render_contact_info', 'wbcom_show_business_info_fields_in_about_section');


apply_filters('bp_business_profile_contact_info', function() { 
  return true;
} );


add_filter( 'bp_business_profile_single_menu_items', 'wbcom_custom_business_tabs', 10, 2);
function wbcom_custom_business_tabs($items, $endpoints) { 
  $items['beam-line-activity'] = esc_html( 'Activity' );
  // $items['beam-line-blogs'] = esc_html( 'Blogs' );

  return $items;
}


add_filter( 'bp_member_change_blog_label', function() {
    return 'My Blog';
} );
?>