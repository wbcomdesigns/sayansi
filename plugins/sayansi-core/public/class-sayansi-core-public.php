<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://https://wbcomdesigns.com/
 * @since      1.0.0
 *
 * @package    Sayansi_Core
 * @subpackage Sayansi_Core/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Sayansi_Core
 * @subpackage Sayansi_Core/public
 * @author     WBCOM Designs <admin@wbcomdesigns.com>
 */
class Sayansi_Core_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Sayansi_Core_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Sayansi_Core_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name . '-chosen-style', plugin_dir_url( __FILE__ ) . 'css/vendor/chosen/chosen.min.css', array(), time(), 'all' );
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/sayansi-core-public.css', array(), time(), 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Sayansi_Core_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Sayansi_Core_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_script( $this->plugin_name . '-chosen-script', plugin_dir_url( __FILE__ ) . 'js/vendor/chosen/chosen.jquery.min.js', array( 'jquery' ), time(), true );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/sayansi-core-public.js', array( 'jquery' ), time(), true );

	}


	public function wbcom_bp_member_default_component( $default_tab ) {
		return 'home';
	}

	public function wbcom_bb_user_can_create_document() {
		if( ! bp_is_single_folder() ) {
			return false;
		}
	}


	public function wbcom_cutomize_member_profile_tabs() {
		if ( bp_displayed_user_domain() ) {
			$user_domain = bp_displayed_user_domain();
			$user_id     = bp_displayed_user_id();
		} elseif ( bp_loggedin_user_domain() ) {
			$user_domain = bp_loggedin_user_domain();
			$user_id     = bp_loggedin_user_id();
		} else {
			return;
		}

		bp_core_new_nav_item(
			array(
				'name'                => _x( 'My Home Page', 'Member Home page', 'sayansi-core' ),
				'slug'                => 'home',
				'position'            => 1,
				'screen_function'     => array( $this, 'wbcom_member_profile_home_tab_screen' ),
				'default_subnav_slug' => 'home',
			),
			'members'
		);

		/**
		 * Member Profile Library Tab Customizations
		 */
		$media_link = trailingslashit( $user_domain . 'library' );

		bp_core_remove_nav_item( bp_get_media_slug() );
		bp_core_remove_subnav_item( bp_get_media_slug(), 'my-media' );
		bp_core_remove_nav_item( bp_get_video_slug() );
		bp_core_remove_subnav_item( bp_get_media_slug(), 'my-video' );
		bp_core_remove_nav_item( bp_get_invites_slug() );
		bp_core_remove_subnav_item( bp_get_invites_slug(), 'invites' );
		bp_core_remove_subnav_item( bp_get_invites_slug(), 'sent-invites' );

		// bp_core_new_nav_item(
		// 	array(
		// 		'name'                => _x( 'My Library', 'Member Library page', 'sayansi-core' ),
		// 		'slug'                => 'library',
		// 		'position'            => 1,
		// 		'screen_function'     => array( $this, 'wbcom_member_profile_library_tab_screen' ),
		// 		'default_subnav_slug' => 'photos',
		// 	),
		// 	'members'
		// );

		// bp_core_new_subnav_item(
		// 	array(
		// 		'name'            => _x( 'Photos', 'Member Photos page', 'sayansi-core' ),
		// 		'slug'            => 'photos',
		// 		'parent_url'      => $media_link,
		// 		'parent_slug'     => 'library',
		// 		'screen_function' => array( $this, 'wbcom_member_profile_library_tab_screen' ),
		// 		'position'        => 10,
		// 	)
		// );

		// bp_core_new_subnav_item(
		// 	array(
		// 		'name'            => _x( 'Videos', 'Member Videos page', 'sayansi-core' ),
		// 		'slug'            => 'videos',
		// 		'parent_url'      => $media_link,
		// 		'parent_slug'     => 'library',
		// 		'screen_function' => array( $this, 'wbcom_member_profile_videos_tab_screen' ),
		// 		'position'        => 10,
		// 	)
		// );

		// bp_core_new_subnav_item(
		// 	array(
		// 		'name'            => _x( 'Documents', 'Member Documents page', 'sayansi-core' ),
		// 		'slug'            => 'documents',
		// 		'parent_url'      => $media_link,
		// 		'parent_slug'     => 'library',
		// 		'screen_function' => array( $this, 'wbcom_member_profile_documents_tab_screen' ),
		// 		'position'        => 10,
		// 	)
		// );
		/**
		 * Member Profile Library Tab Customizations Stop
		 */

		/**
		 * Member Profile Profile Tab Customizations
		 */
		$bprm_settings     = get_site_option( 'bprm_settings' );
		$profile_menu_slug = isset( $bprm_settings['tab_url'] ) && ! empty( $bprm_settings['tab_url'] ) ? $bprm_settings['tab_url'] : 'resume';
		$tab_resume_name   = isset( $bprm_settings['tab_resume_name'] ) && ! empty( $bprm_settings['tab_resume_name'] ) ? $bprm_settings['tab_resume_name'] : 'Resume';
		$resume_manager    = new Bp_Resume_Manager_Public( 'bp-resume-manager', BPRM_PLUGIN_VERSION );
		bp_core_remove_nav_item( $bprm_settings['tab_url'] );

		bp_core_new_subnav_item(
			array(
				'name'            => _x( 'Profile Settings', 'Member Profile Settings page', 'sayansi-core' ),
				'slug'            => 'public',
				'parent_url'      => trailingslashit( $user_domain . bp_get_profile_slug() ),
				'parent_slug'     => bp_get_profile_slug(),
				'screen_function' => 'bp_members_screen_display_profile',
				'position'        => 10,
			)
		);

		bp_core_new_subnav_item(
			array(
				'name'            => $tab_resume_name,
				'slug'            => $profile_menu_slug,
				'parent_url'      => trailingslashit( $user_domain . bp_get_profile_slug() ),
				'parent_slug'     => bp_get_profile_slug(),
				'screen_function' => array( $resume_manager, 'bprm_show_saved_resume_screen' ),
				'position'        => 10,
			)
		);

		if ( bp_is_my_profile() || current_user_can( 'administrator' ) ) {
			if ( bprm_check_user_resume_data( $user_id, 'bprm_resume_' ) ) {
				$tab_name = __( 'Edit ', 'sayansi-core' ) . $tab_resume_name;
			} else {
				$tab_name = __( 'Add ', 'sayansi-core' ) . $tab_resume_name;
			}
				// Add subnav add resume.
				bp_core_new_subnav_item(
					array(
						'name'            => $tab_name,
						'slug'            => 'add',
						'parent_url'      => trailingslashit( $user_domain . bp_get_profile_slug() ),
						'parent_slug'     => bp_get_profile_slug(),
						'screen_function' => array( $resume_manager, 'bprm_show_add_resume_screen' ),
						'position'        => 200,
						// 'link'            => site_url() . "/$member_slug/$name/$parent_slug/add/",
					)
				);
		}

		/**
		 * Add email invites subnav under connection tab.
		 */
		bp_core_new_subnav_item(
			array(
				'name'            => __( 'Send Invites', 'buddyboss' ),
				'slug'            => 'send-invites',
				'parent_url'      => trailingslashit( $user_domain . bp_get_friends_slug() ),
				'parent_slug'     => bp_get_friends_slug(),
				'screen_function' => array( $this, 'bprm_send_invites_screen' ),
				'position'        => 10,                        
			)
		);
		bp_core_new_subnav_item(
			array(
				'name'            => __( 'Sent Invites', 'buddyboss' ),
				'slug'            => 'sent-invites',
				'parent_url'      => trailingslashit( $user_domain . bp_get_friends_slug() ),
				'parent_slug'     => bp_get_friends_slug(),
				'screen_function' => array( $this, 'bprm_sent_invites_screen' ),
				'position'        => 20
			)
		);

	}

	public function wbcom_member_profile_home_tab_screen() {
		add_action( 'bp_template_content', array( $this, 'wbcom_member_profile_home_tab_screen_content' ) );
		bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
	}

	public function wbcom_member_profile_library_tab_screen() {
		add_action( 'bp_template_content', array( $this, 'wbcom_member_profile_library_tab_screen_content' ) );
		bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
	}

	public function wbcom_member_profile_videos_tab_screen() {
		add_action( 'bp_template_content', array( $this, 'wbcom_member_profile_videos_tab_screen_content' ) );
		bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
	}

	public function wbcom_member_profile_documents_tab_screen() {
		add_action( 'bp_template_content', array( $this, 'wbcom_member_profile_documents_tab_screen_content' ) );
		bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
	}

	public function bprm_send_invites_screen() {
		add_action( 'bp_template_content', array( $this,'wbcom_bprm_send_invites_screen' ) );
		bp_core_load_template( apply_filters( 'bp_invites_screen_send_invite', 'members/single/plugins' ) );
	}
	public function bprm_sent_invites_screen() {
		add_action( 'bp_template_content', array( $this, 'wbcom_bprm_sent_invites_screen' ) );
		bp_core_load_template( apply_filters( 'bp_invites_screen_send_invite', 'members/single/plugins' ) );
	}

	public function wbcom_bprm_send_invites_screen(){
		bp_get_template_part( 'members/single/invites' );		
	}

	public function wbcom_bprm_sent_invites_screen(){	
		bp_get_template_part( 'members/single/invites' );
	}

	public function wbcom_member_profile_home_tab_screen_content() {
		$user_id = bp_displayed_user_id() ? bp_displayed_user_id() : bp_loggedin_user_id();

		if( ! empty( xprofile_get_field_data( 'Feature Image', $user_id ) ) ) {
			?>
				<div class="profile-home-header">
					<?php echo xprofile_get_field_data( 'Feature Image', $user_id ); ?>
				</div>
			<?php
		}
		?>
		<div class="profile-home-column">
			<div class="profile-home-columns">
				<a href="<?php echo xprofile_get_field_data( 'Column One - Link', $user_id ); ?>">
					<div class="profile-home-columns-img">
						<?php echo xprofile_get_field_data( 'Column One - Logo', $user_id ); ?>
					</div>
					<h3 class="profile-home-columns-title"><?php echo xprofile_get_field_data( 'Column One - Title', $user_id ); ?></h3>
					<div class="profile-home-columns-content"><?php echo xprofile_get_field_data( 'Column One - Description', $user_id ); ?></div>
				</a>
			</div>

			<div class="profile-home-columns">
				<a href="<?php echo xprofile_get_field_data( 'Column Two - Link', $user_id ); ?>">
					<div class="profile-home-columns-img"><?php echo xprofile_get_field_data( 'Column Two - Logo', $user_id ); ?></div>
					<h3 class="profile-home-columns-title"><?php echo xprofile_get_field_data( 'Column Two - Title', $user_id ); ?></h3>
					<div class="profile-home-columns-content"><?php echo xprofile_get_field_data( 'Column Two - Description', $user_id ); ?></div>
				</a>
			</div>

			<div class="profile-home-columns">
				<a href="<?php echo xprofile_get_field_data( 'Column Three - Link', $user_id ); ?>">
					<div class="profile-home-columns-img"><?php echo xprofile_get_field_data( 'Column Three - Logo', $user_id ); ?></div>
					<h3 class="profile-home-columns-title"><?php echo xprofile_get_field_data( 'Column Three - Title', $user_id ); ?></h3>
					<div class="profile-home-columns-content"><?php echo xprofile_get_field_data( 'Column Three - Description', $user_id ); ?></div>
				</a>
			</div>

			<div class="profile-home-columns">
				<a href="<?php echo xprofile_get_field_data( 'Column Four - Link', $user_id ); ?>">
					<div class="profile-home-columns-img"><?php echo xprofile_get_field_data( 'Column Four - Logo', $user_id ); ?></div>
					<h3 class="profile-home-columns-title"><?php echo xprofile_get_field_data( 'Column Four - Title', $user_id ); ?></h3>
					<div class="profile-home-columns-content"><?php echo xprofile_get_field_data( 'Column Four - Description', $user_id ); ?></div>
				</a>
			</div>
		</div>
		<?php
	}


	public function wbcom_member_profile_library_tab_screen_content() {
		$is_send_ajax_request = bb_is_send_ajax_request();
		?>
		<div class="bb-media-container member-media">
			<?php
			// bp_get_template_part( 'members/single/parts/item-subnav' );
			bp_get_template_part( 'media/theatre' );
			if ( bp_is_profile_video_support_enabled() ) {
				bp_get_template_part( 'video/theatre' );
				bp_get_template_part( 'video/add-video-thumbnail' );
			}
			bp_get_template_part( 'document/theatre' );

			switch ( bp_current_action() ) :

				// Home/Media.
				case 'photos':
					bp_get_template_part( 'media/add-media' );
					bp_nouveau_member_hook( 'before', 'media_content' );
					bp_get_template_part( 'media/actions' );
					?>
					<div id="media-stream" class="media" data-bp-list="media" data-ajax="<?php echo esc_attr( $is_send_ajax_request ? 'true' : 'false' ); ?>">
						<?php
						if ( $is_send_ajax_request ) {
							echo '<div id="bp-ajax-loader">';
							bp_nouveau_user_feedback( 'member-media-loading' );
							echo '</div>';
						} else {
							bp_get_template_part( 'media/media-loop' );
						}
						?>
					</div><!-- .media -->
						<?php
						bp_nouveau_member_hook( 'after', 'media_content' );
					break;

				// Home/Media/Albums.
				case 'albums':
					if ( ! bp_is_single_album() ) {
						bp_get_template_part( 'media/albums' );
					} else {
						bp_get_template_part( 'media/single-album' );
					}
					break;

				// Any other.
				default:
					bp_get_template_part( 'members/single/plugins' );
					break;
			endswitch;
			?>
		</div>
		<?php
	}

	public function wbcom_member_profile_videos_tab_screen_content() {
		$is_send_ajax_request = bb_is_send_ajax_request();
		?>
		<div class="bb-video-container bb-media-container member-video">
			<?php
			bp_get_template_part( 'video/theatre' );
			bp_get_template_part( 'media/theatre' );
			bp_get_template_part( 'document/theatre' );

			switch ( bp_current_action() ) :

				// Home/Video.
				case 'videos':
					bp_get_template_part( 'video/add-video' );
					bp_nouveau_member_hook( 'before', 'video_content' );
					bp_get_template_part( 'video/actions' );
					?>
					<div id="video-stream" class="video" data-bp-list="video" data-ajax="<?php echo esc_attr( $is_send_ajax_request ? 'true' : 'false' ); ?>">
						<?php
						if ( $is_send_ajax_request ) {
							echo '<div id="bp-ajax-loader">';
							bp_nouveau_user_feedback( 'member-video-loading' );
							echo '</div>';
						} else {
							bp_get_template_part( 'video/video-loop' );
						}
						?>
					</div><!-- .video -->
					<?php
					bp_nouveau_member_hook( 'after', 'video_content' );

					break;

				// Home/Video/Albums.
				case 'albums':
					if ( ! bp_is_single_video_album() ) {
						bp_get_template_part( 'video/albums' );
					} else {
						bp_get_template_part( 'video/single-album' );
					}
					break;

				// Any other.
				default:
					bp_get_template_part( 'members/single/plugins' );
					break;
			endswitch;
			?>
		</div>
		<?php

	}

	public function wbcom_member_profile_documents_tab_screen_content() {
		$is_send_ajax_request = bb_is_send_ajax_request();
		?>

		<div class="bb-media-container member-media">
			<?php
			bp_get_template_part( 'document/theatre' );
			bp_get_template_part( 'video/theatre' );
			bp_get_template_part( 'media/theatre' );
			bp_get_template_part( 'video/add-video-thumbnail' );

			switch ( bp_current_action() ) :

				// Home/Media.
				case 'documents':
					?>
					<div class="bp-document-listing">
						<div class="bp-media-header-wrap">
							<h2 class="bb-title"><?php esc_html_e( 'Documents', 'buddyboss' ); ?></h2>
							<?php
							bp_get_template_part( 'document/add-folder' );
							bp_get_template_part( 'document/add-document' );
							?>
							<div id="search-documents-form" class="media-search-form" data-bp-search="document">
								<form action="" method="get" class="bp-dir-search-form search-form-has-reset" id="group-document-search-form" autocomplete="off">
									<button type="submit" id="group-document-search-submit" class="nouveau-search-submit search-form_submit" name="group_document_search_submit">
										<span class="dashicons dashicons-search" aria-hidden="true"></span>
										<span id="button-text" class="bp-screen-reader-text"><?php esc_html_e( 'Search', 'buddyboss' ); ?></span>
									</button>
									<label for="group-document-search" class="bp-screen-reader-text"><?php esc_html_e( 'Search Documents…', 'buddyboss' ); ?></label>
									<input id="group-document-search" name="document_search" type="search" placeholder="<?php esc_attr_e( 'Search Documents…', 'buddyboss' ); ?>">
									<button type="reset" class="search-form_reset">
										<span class="bb-icon-rf bb-icon-times" aria-hidden="true"></span>
										<span class="bp-screen-reader-text"><?php esc_html_e( 'Reset', 'buddyboss' ); ?></span>
									</button>
								</form>
							</div>

						</div>
					</div><!-- .bp-document-listing -->
					<?php bp_nouveau_member_hook( 'before', 'document_content' ); ?>

					<div id="media-stream" class="media" data-bp-list="document" data-ajax="<?php echo esc_attr( $is_send_ajax_request ? 'true' : 'false' ); ?>">
						<?php
						if ( $is_send_ajax_request ) {
							echo '<div id="bp-ajax-loader">';
							bp_nouveau_user_feedback( 'member-document-loading' );
							echo '</div>';
						} else {
							bp_get_template_part( 'document/document-loop' );
						}
						?>
					</div><!-- .media -->

					<?php
					bp_nouveau_member_hook( 'after', 'document_content' );

					break;

				// Home/Media/Albums.
				case 'folders':
					bp_get_template_part( 'document/single-folder' );
					break;

				// Any other.
				default:
					bp_get_template_part( 'members/single/plugins' );
					break;
			endswitch;
			?>
		</div>
		<?php

	}


	public function wbcom_save_business_bibliography() {
		if ( isset( $_POST['business-bibliography-nonce'] ) && wp_verify_nonce( $_POST['business-bibliography-nonce'], 'business-bibliography-action' )) {
			$bibliography = isset( $_POST['business-bibliography'] ) ? wp_kses_post( $_POST['business-bibliography'] ) : '';
			$business_id = isset( $_POST['business_id'] ) ? absint( $_POST['business_id'] ) : '';

			update_post_meta( $business_id, 'business_bibliography', wpautop( $bibliography ) );

			wp_redirect( $_POST['_wp_http_referer'] );
			exit();
		}
	}


	public function wbcom_save_business_excerpt( $business_id, $data ) {
		if ( empty( $business_id ) ) {
			return;
		}

		$beam_line_excerpt = isset( $data['beam_line_excerpt'] ) ? wp_kses_post( $data['beam_line_excerpt'] ) : '';

		update_field( 'beam_line_excerpt', wpautop( $beam_line_excerpt ), $business_id );

	}


	public function wbcom_bprm_save_resume_resdirect_url( $url ) {
		if ( bp_displayed_user_domain() ) {
			$user_domain = bp_displayed_user_domain();
		} elseif ( bp_loggedin_user_domain() ) {
			$user_domain = bp_loggedin_user_domain();
		}
		$bprm_settings     = get_option( 'bprm_settings' );
		$profile_menu_slug = isset( $bprm_settings['tab_url'] ) && ! empty( $bprm_settings['tab_url'] ) ? $bprm_settings['tab_url'] : 'resume';
		$url               = trailingslashit( $user_domain . bp_get_profile_slug() ) . $profile_menu_slug;
		return $url;
	}


	/**
	 * Get ACF Business Information group fields
	 *
	 * @return array|false
	 */
	public function wbcom_get_business_info_group_fields() {
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
	public function wbcom_display_business_info_fields_in_contact() {
	    if (function_exists('acf_form_head')) {
	        acf_form_head();    
	    }

	    $fields = $this->wbcom_get_business_info_group_fields();

	    if (!empty($fields)) {
	        acf_render_fields($fields, get_the_ID());
	    } else {
	        // Handle the case where no fields are found or an error occurred
	        echo '<p>No fields available to display.</p>';
	    }
	}


	/**
	 * Save ACF Business Information fields
	 *
	 * @param int $post_id
	 * @return void
	 */
	public function wbcom_save_business_info_fields($post_id) {
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


	/**
	 * Show ACF Business Information fields in the about section
	 *
	 * @return void
	 */
	public function wbcom_show_business_info_fields_in_about_section() {
	    $fields = $this->wbcom_get_business_info_group_fields();

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


	public function wbcom_bprm_resume_redirect_url( $url ) {
		$bprm_settings = get_option( 'bprm_settings' );
		$profile_menu_slug = isset( $bprm_settings['tab_url'] ) && ! empty( $bprm_settings['tab_url'] ) ? $bprm_settings['tab_url'] : 'resume';
		$url = trailingslashit( bp_displayed_user_domain() . bp_get_profile_slug() ) . $profile_menu_slug;

		return $url;
	}


	public function wbcom_bp_business_profile_single_menu_items( $items, $endpoints ) {
		$items = array(
			'home'               => esc_html__( 'Home', 'bp-business-profile' ),
			'about'              => esc_html__( 'About', 'bp-business-profile' ),
			'follower'           => esc_html__( 'Followers', 'bp-business-profile' ),
			'beam-line-activity' => esc_html__( 'Activity', 'bp-business-profile' ),
			'beam-line-blogs'    => esc_html__( 'Partner Blog', 'bp-business-profile' ),
			'medias'             => esc_html__( 'Media', 'bp-business-profile' ),
			'business-reviews'   => esc_html__( 'Reviews', 'bp-business-profile' ),			
			'inbox'             => esc_html__( 'Inbox', 'bp-business-profile' ),
			'business-settings' => esc_html__( 'Settings', 'bp-business-profile' ),
		);

		return $items;
	}

	public function wbcom_bp_add_group_subnav_tab() {
		if ( ! bp_is_group() ) {
				return;
			}
		bp_core_new_subnav_item( array(
		'name'                => _x( 'Group Description', 'Group Description', 'sayansi-core' ),
		'slug'            => 'new',
		'parent_slug'     => bp_get_current_group_slug(),
		'parent_url'      => bp_get_group_permalink( groups_get_current_group() ),
		'screen_function' => array( $this, 'wbcom_my_home_show_screen' ),
		'position'        => 1,
		), 'groups' );
	}

	public function wbcom_my_home_show_screen(){
		add_action( 'bp_template_content', array( $this,'wbcom_my_home_show_screen_display' ) );
		bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
	}

	public function wbcom_my_home_show_screen_display(){ 
		$group_id = bp_get_current_group_id(); 		
		?>
		<div class="profile-home-header">
			<?php $get_feature_img = groups_get_groupmeta( $group_id, 'group_feature_image' );
			if( $get_feature_img ){
			?>
			<img src="<?php echo esc_url( $get_feature_img ); ?>" alt="logo" width="500" height="600">
			<?php } else{
				echo '<p>No Feature Image Found</p>';
			}?>
		</div>
		<div class="profile-home-column">
			<div class="profile-home-columns">
				<a href="<?php echo esc_url( groups_get_groupmeta( $group_id, 'group_column_one_link' ) ); ?>">
					<div class="profile-home-columns-img">
						<?php
						$group_column_one_logo = groups_get_groupmeta( $group_id, 'group_column_one_logo' );
						if( $group_column_one_logo ){
						?>
						<img src="<?php echo esc_url( $group_column_one_logo ); ?>" alt="logo" width="500" height="600"> 
						<?php } else{ 
							echo '<p>No Column one Logo Found</p>';
						}?>
					</div>
					<?php
					$group_column_one_title = groups_get_groupmeta( $group_id, 'group_column_one_title' );
					if( $group_column_one_title ){					
					?>
					<h3 class="profile-home-columns-title"><?php echo groups_get_groupmeta( $group_id, 'group_column_one_title' ); ?></h3>
					<?php } else{
						echo '<p>No Column one Tiltle Found</p>';
					}
					$group_column_one_desc = groups_get_groupmeta( $group_id, 'group_column_one_desc' );
					if($group_column_one_desc){
					?>
					<div class="profile-home-columns-content"><?php echo $group_column_one_desc; ?></div>
					<?php } else{
						echo '<p>No Column one Content Found</p>';
					}?>
				</a>
			</div>

			<div class="profile-home-columns">
				<a href="<?php echo esc_url( groups_get_groupmeta( $group_id, 'group_column_two_link' ) ); ?>">
					<div class="profile-home-columns-img">
						<?php
						$group_column_two_logo = groups_get_groupmeta( $group_id, 'group_column_two_logo' );
						if( $group_column_two_logo ){
						?>
						<img src="<?php echo esc_url( $group_column_two_logo ); ?>" alt="logo" width="500" height="600"> 
						<?php } else{ 
							echo '<p>No Column two Logo Found</p>';
						}?>
					</div>
					<?php
					$group_column_two_title = groups_get_groupmeta( $group_id, 'group_column_two_title' );
					if( $group_column_two_title ){					
					?>
					<h3 class="profile-home-columns-title"><?php echo groups_get_groupmeta( $group_id, 'group_column_two_title' ); ?></h3>
					<?php } else{
						echo '<p>No Column two Tiltle Found</p>';
					}
					$group_column_two_desc = groups_get_groupmeta( $group_id, 'group_column_two_desc' );
					if($group_column_two_desc){
					?>
					<div class="profile-home-columns-content"><?php echo $group_column_two_desc; ?></div>
					<?php } else{
						echo '<p>No Column two Content Found</p>';
					}?>
				</a>
			</div>

			<div class="profile-home-columns">
				<a href="<?php echo esc_url( groups_get_groupmeta( $group_id, 'group_column_three_link' ) ); ?>">
					<div class="profile-home-columns-img">
						<?php
						$group_column_three_logo = groups_get_groupmeta( $group_id, 'group_column_three_logo' );
						if( $group_column_three_logo ){
						?>
						<img src="<?php echo esc_url( $group_column_three_logo ); ?>" alt="logo" width="500" height="600"> 
						<?php } else{ 
							echo '<p>No Column three Logo Found</p>';
						}?>
					</div>
					<?php
					$group_column_three_title = groups_get_groupmeta( $group_id, 'group_column_three_title' );
					if( $group_column_three_title ){					
					?>
					<h3 class="profile-home-columns-title"><?php echo groups_get_groupmeta( $group_id, 'group_column_three_title' ); ?></h3>
					<?php } else{
						echo '<p>No Column three Tiltle Found</p>';
					}
					$group_column_three_desc = groups_get_groupmeta( $group_id, 'group_column_three_desc' );
					if($group_column_three_desc){
					?>
					<div class="profile-home-columns-content"><?php echo $group_column_three_desc; ?></div>
					<?php } else{
						echo '<p>No Column three Content Found</p>';
					}?>
				</a>
			</div>

			<div class="profile-home-columns">
				<a href="<?php echo esc_url( groups_get_groupmeta( $group_id, 'group_column_four_link' ) ); ?>">
					<div class="profile-home-columns-img">
						<?php
						$group_column_four_logo = groups_get_groupmeta( $group_id, 'group_column_four_logo' );
						if( $group_column_four_logo ){
						?>
						<img src="<?php echo esc_url( $group_column_four_logo ); ?>" alt="logo" width="500" height="600"> 
						<?php } else{ 
							echo '<p>No Column four Logo Found</p>';
						}?>
					</div>
					<?php
					$group_column_four_title = groups_get_groupmeta( $group_id, 'group_column_four_title' );
					if( $group_column_four_title ){					
					?>
					<h3 class="profile-home-columns-title"><?php echo groups_get_groupmeta( $group_id, 'group_column_four_title' ); ?></h3>
					<?php } else{
						echo '<p>No Column four Tiltle Found</p>';
					}
					$group_column_four_desc = groups_get_groupmeta( $group_id, 'group_column_four_desc' );
					if($group_column_four_desc){
					?>
					<div class="profile-home-columns-content"><?php echo $group_column_four_desc; ?></div>
					<?php } else{
						echo '<p>No Column four Content Found</p>';
					}?>
				</a>
			</div>
		</div>
		<?php
	}
	

}
