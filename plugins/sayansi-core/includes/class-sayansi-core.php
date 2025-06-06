<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://https://wbcomdesigns.com/
 * @since      1.0.0
 *
 * @package    Sayansi_Core
 * @subpackage Sayansi_Core/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Sayansi_Core
 * @subpackage Sayansi_Core/includes
 * @author     WBCOM Designs <admin@wbcomdesigns.com>
 */
class Sayansi_Core {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Sayansi_Core_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'SAYANSI_CORE_VERSION' ) ) {
			$this->version = SAYANSI_CORE_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'sayansi-core';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Sayansi_Core_Loader. Orchestrates the hooks of the plugin.
	 * - Sayansi_Core_i18n. Defines internationalization functionality.
	 * - Sayansi_Core_Admin. Defines all hooks for the admin area.
	 * - Sayansi_Core_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-sayansi-core-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-sayansi-core-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-sayansi-core-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-sayansi-core-public.php';

		$this->loader = new Sayansi_Core_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Sayansi_Core_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Sayansi_Core_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Sayansi_Core_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Sayansi_Core_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts', 999 );

		$this->loader->add_filter( 'bp_member_default_component', $plugin_public, 'wbcom_bp_member_default_component', );
		$this->loader->add_action( 'wp', $plugin_public, 'wbcom_cutomize_member_profile_tabs', 99 );
		$this->loader->add_action( 'wp_loaded', $plugin_public, 'wbcom_save_business_bibliography' );
		$this->loader->add_action( 'bp_business_profile_after_save_business', $plugin_public, 'wbcom_save_business_excerpt', 10 );
		$this->loader->add_filter( 'bprm_save_resume_resdirect_url', $plugin_public, 'wbcom_bprm_save_resume_resdirect_url' );
		$this->loader->add_action( 'business_profile_after_business_description', $plugin_public, 'wbcom_display_business_info_fields_in_contact');
		$this->loader->add_action( 'save_post', $plugin_public, 'wbcom_save_business_info_fields', 99, 1);
		$this->loader->add_filter( 'bprm_save_resume_resdirect_url', $plugin_public, 'wbcom_bprm_resume_redirect_url' );
		$this->loader->add_filter( 'bp_business_profile_single_menu_items', $plugin_public, 'wbcom_bp_business_profile_single_menu_items', 10, 2 );
		// $this->loader->add_filter( 'bb_user_can_create_document', $plugin_public, 'wbcom_bb_user_can_create_document' );
		$this->loader->add_action( 'groups_group_details_edited', $plugin_public, 'wbcom_group_detail' );
		$this->loader->add_action( 'bp_actions', $plugin_public, 'wbcom_bp_add_group_subnav_tab' );
	
		$this->loader->add_action('save_post', $plugin_public, 'wbcom_save_business_general_info_fields');
		$this->loader->add_filter( 'bp_after_has_document_parse_args', $plugin_public, 'wbcom_has_document_parse_args', 10, 1 );
		$this->loader->add_action('wp_ajax_load_forum_discussion', $plugin_public, 'wbcom_load_forum_discussion');
		$this->loader->add_action('get_template_part_form', $plugin_public, 'wbcom_add_back_btn_on_create_forum_page');
		$this->loader->add_action('bp_actions', $plugin_public, 'wbcom_bp_remove_mention_fvrt_groups_sub_tabs_profile');
		$this->loader->add_action( 'bp_setup_nav', $plugin_public, 'wbcom_reorder_activity_subnav_tab', 999 );
		$this->loader->add_filter( 'bcircles_parent_component_slug', $plugin_public, 'wbcom_change_connections_tab_slug' );		
		//$this->loader->add_action( 'bp_business_profile_after_save_general_settings_business', $plugin_public, 'wbcom_update_business_excerpt_value' );
		$this->loader->add_action( 'init', $plugin_public, 'wbcom_remove_home_url_filter' );				
		$this->loader->add_filter( 'home_url', $plugin_public, 'wbcom_change_home_url', 9999, 2 );
		$this->loader->add_action( 'buddypress_member_blog_post_submit', $plugin_public, 'wbcom_bp_blog_pro_save_group_setting', 99 ,1 );

		//for forums search, filter, layout 
		$this->loader->add_action( 'wp_ajax_forums_search', $plugin_public, 'wbcom_forums_search');
		$this->loader->add_action( 'wp_ajax_nopriv_forums_search', $plugin_public, 'wbcom_forums_search');

		// reorder bp blog pro group blog tab
		$this->loader->add_action('bp_blog_pro_group_blog_tab', $plugin_public, 'wbcom_bp_blog_pro_reorder_group_blog_tab_position', 999);

		// reorder bp stats tab
		// $this->loader->add_action('bp_stats_group_statistics_tab_filter', $plugin_public, 'wbcom_bp_stats_group_statistics_tabs_position', 999);

		// add group dropdown on the business creation
		$this->loader->add_action( 'bp_business_create_after_field', $plugin_public, 'wbcom_group_selection_business_creation' );	
		$this->loader->add_filter( 'bb_meprlms_courses_group_tab_name', $plugin_public, 'wbcom_rename_course_tab_on_group');

		// add course filter
		$this->loader->add_action( 'wp_ajax_filter_courses', $plugin_public, 'wbcom_filter_courses' );

		// mass messaging hook to send message from user profile connection tab		
		$this->loader->add_action('bp_before_member_friends_content', $plugin_public, 'wbcom_add_send_message_button_user_connection_tab');
		$this->loader->add_action('wp', $plugin_public, 'wbcom_handle_message_redirect');

		// create link group tab on partner setting
		$this->loader->add_filter( 'bp_business_profile_single_settings_tabs', $plugin_public, 'sayansi_create_link_groups_tab_partner_setting',999,1);

		//remove group from partne under link group tab in partner setting
		$this->loader->add_action( 'wp_ajax_remove_business_group', $plugin_public, 'sayansi_remove_business_group' );
		$this->loader->add_action( 'wp_ajax_update_partner_groups', $plugin_public, 'sayansi_update_partner_groups' );

		// search added on course for user profile
		$this->loader->add_action( 'wp_ajax_search_courses_user_profile', $plugin_public, 'sayansi_search_courses_user_profile' );

		// Reorder the business setting tab( photo, cover image )
		$this->loader->add_filter( 'bp_business_profile_single_settings_tabs', $plugin_public, 'sayansi_reorder_business_setting_tab');

		// Hide the business author from the team widget when more than one admin exist.
		$this->loader->add_action( 'bp_business_profile_team_widget_before_admins', $plugin_public, 'sayansi_business_team_widget_admin_user_filter', 10,2 );

		//for individual members under network tab search, filter, layout 
		$this->loader->add_action( 'wp_ajax_indiviual_members_search', $plugin_public, 'wbcom_indiviual_members_search');
		$this->loader->add_action( 'wp_ajax_nopriv_indiviual_members_search', $plugin_public, 'wbcom_indiviual_members_search');

		//for all partner under network tab search, filter, layout 	
		$this->loader->add_action( 'wp_ajax_network_all_partners', $plugin_public, 'sayansi_network_all_partners');
		$this->loader->add_action( 'wp_ajax_nopriv_network_all_partners', $plugin_public, 'sayansi_network_all_partners');

		//Add per page for member directory
		$this->loader->add_filter( 'bp_after_has_members_parse_args', $plugin_public, 'sayansi_bp_increase_members_per_page_on_directory');

		//Add per page for group directory
		$this->loader->add_filter( 'bp_after_has_groups_parse_args', $plugin_public, 'sayansi_bp_increase_groups_per_page_on_directory' );

		// Resume layout section add on edit resume template and save the value in the usermeta
		$this->loader->add_action( 'bprm_after_upload_resume_image_section', $plugin_public, 'sayansi_add_resume_layout_setting_on_edit_resume');

		// Save resume layout value in the usermeta
		$this->loader->add_action( 'wp_head', $plugin_public, 'sayansi_save_resume_layout_on_edit_resume' );

		// Add user_id class in body tag
		$this->loader->add_filter( 'body_class', $plugin_public, 'sayansi_custom_body_classes' );

		//Update resume admin menu link
		$this->loader->add_filter( 'bprm_override_resume_link_admin_menu', $plugin_public, 'sayansi_update_resume_admin_menu_link', 10, 3 );

		//Add reset resume button on edit resume template
		$this->loader->add_action( 'bprm_add_content_after_edit_form', $plugin_public, 'sayansi_add_reset_resume_button',10, 1 );

		//Allow business style on user
		$this->loader->add_filter( 'bp_business_add_condition_enq_style', $plugin_public, 'sayansi_allow_business_style_on_user' );

		$this->loader->add_filter( 'bprm_show_resume_layout_tab_on_account_setting', $plugin_public, 'sayansi_hide_resume_layout_tab_profile_setting' );

		$this->loader->add_action( 'wp_ajax_reset_resume', $plugin_public, 'bprm_reset_resume' );
		$this->loader->add_filter( 'bprm_override_share_resume_link', $plugin_public, 'sayansi_share_resume_link_override',10, 3 );
		$this->loader->add_filter( 'bp_blog_pro_remove_specific_group_id', $plugin_public, 'sayansi_remove_business_group_id', 10, 2 );

		$this->loader->add_action( 'bp_post_before_featured_image', $plugin_public, 'sayansi_add_partner_dropdown_on_blog');

		/*
		* Redirect Manage tab of partners on the partner setting
		*/
		$this->loader->add_action( 'wp_ajax_get_business_manage_url', $plugin_public, 'sayansi_get_business_manage_url');
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Sayansi_Core_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
