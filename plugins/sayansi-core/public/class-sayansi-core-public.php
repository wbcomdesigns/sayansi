<?php
use DMS\Includes\Data_Objects\Setting;
use DMS\Includes\Utils\Helper;
use DMS\Includes\Services\Request_Params;
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
		$this->sayansi_include_activity_connections_core_file();

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
		if( function_exists( 'bbp_get_forum_post_type' ) && 'forum' == bbp_get_forum_post_type() ){	
			$forums_page = get_post( get_the_ID() );
			$forum_desc = $forums_page->post_content;
		}
		
		wp_enqueue_script('jquery-ui-dialog');
        wp_enqueue_style('wp-jquery-ui-dialog');
        wp_enqueue_script('jquery');

		wp_localize_script(
			$this->plugin_name,
			'sayansi_ajax_object',
			array(
				'ajax_url'   => admin_url( 'admin-ajax.php' ),
				'ajax_nonce' => wp_create_nonce( 'sayansi_ajax_security' ),
				'forum_desc' => $forum_desc,
				'check_group_component' => bp_is_groups_component(),				
			)
		);
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

		// remove the membership tab from user profile
		$main_slug = MeprHooks::apply_filters('mepr-bp-info-main-nav-slug', 'mp-membership');
		bp_core_remove_nav_item( $main_slug );

		//Add category wise tab in user profile under partner tab
		bp_core_remove_subnav_item( bp_business_profile_get_business_slug(), 'partner' );
		if ( function_exists( 'buddypress' ) && isset( buddypress()->buddyboss ) ) {
			$parent_url = trailingslashit( bp_displayed_user_domain() . bp_business_profile_get_business_slug() );
		} else {
			$parent_url = trailingslashit( bp_displayed_user_domain() );
		}
		$taxonomy_terms = get_terms( array(
			'taxonomy'   => 'business-category',
			'hide_empty' => false,
			'parent'     => 0,
		) );
		foreach ($taxonomy_terms as $index => $term) {									
			// Determine the slug based on the index
			// if ($index == 0) {				
			// 	$slug = bp_business_profile_get_business_slug(); // First term slug
			// } else {
			// 	$slug = $term->slug; // Other terms slug
			// }
			bp_core_new_subnav_item(
				array(
					'name'            => $term->name,
					// 'slug'            => $slug,
					'slug'            => $term->slug,
					'parent_url'      => $parent_url,
					'parent_slug'     => bp_business_profile_get_business_slug(),
					'screen_function' => array($this, 'bprm_display_category_partners'),
					// 'position'        => 15
				)
			);			
		}
		//End add category wise tab in user profile under partner tab


		/**
		 * Member Profile Profile Tab Customizations
		 */
		$bprm_settings     = get_site_option( 'bprm_settings' );
		$profile_menu_slug = isset( $bprm_settings['tab_url'] ) && ! empty( $bprm_settings['tab_url'] ) ? $bprm_settings['tab_url'] : 'resume';
		$tab_resume_name   = isset( $bprm_settings['tab_resume_name'] ) && ! empty( $bprm_settings['tab_resume_name'] ) ? $bprm_settings['tab_resume_name'] : 'Resume';
		$resume_manager    = new Bp_Resume_Manager_Public( 'bp-resume-manager', BPRM_PLUGIN_VERSION );
		$business_profile  = new Bp_Business_Profile_Public( 'bp-business-profile' , BP_BUSINESS_PROFILE_VERSION );
		$mpbuddypress      = new MpBuddyPress( 'memberpress-buddypress' , '1.1.20' );
		bp_core_remove_nav_item( $bprm_settings['tab_url'] );

		//remove all partner subtab under the partner tab and added all partner subtab		
		bp_core_new_subnav_item(
			array(
				/* translators: %s is the business label */
				'name'            => sprintf( esc_html__( 'All %1$s', 'bp-business-profile' ), bp_business_profile_get_plural_label() ),
				'slug'            => bp_business_profile_get_business_slug(),					
				'parent_url'      => $parent_url,
				'parent_slug'     => bp_business_profile_get_business_slug(),
				'screen_function' => array( $this, 'wbcom_all_partners_screen' ),
				'position'        => 30,
			)
		);
		//remove all partner subtab under the partner tab and added all partner subtab	
		
		bp_core_new_subnav_item(
			array(
				'name'            => _x( 'Settings', 'Member Profile Settings page', 'sayansi-core' ),
				'slug'            => 'public',
				'parent_url'      => trailingslashit( $user_domain . bp_get_profile_slug() ),
				'parent_slug'     => bp_get_profile_slug(),
				'screen_function' => 'bp_members_screen_display_profile',
				'position'        => 10,
			)
		);		

		if ( bp_is_my_profile() || current_user_can( 'administrator' ) ) {
			//Info Sub Menu
			bp_core_new_subnav_item(
			array(
				'name' => _x('Info', 'ui', 'memberpress-buddypress'),
				'slug' => 'mp-info',
				'parent_url' => trailingslashit( $user_domain . bp_get_profile_slug() ),
				'parent_slug' => bp_get_profile_slug(),
				'screen_function' => array($mpbuddypress, 'membership_info'),
				'position' => 10,
				'user_has_access' => bp_is_my_profile(),
				'site_admin_only' => false,
				'item_css_id' => 'mepr-bp-info'
			)
			);

			//Subscriptions Sub Menu
			bp_core_new_subnav_item(
				array(
					'name' => _x('Subscriptions', 'ui', 'memberpress-buddypress'),
					'slug' => MeprHooks::apply_filters('mepr-bp-subscriptions-slug', 'mp-subscriptions'),
					'parent_url' => trailingslashit( $user_domain . bp_get_profile_slug() ),
					'parent_slug' => bp_get_profile_slug(),
					'screen_function' => array($mpbuddypress, 'membership_subscriptions'),
					'position' => 10,
					'user_has_access' => bp_is_my_profile(),
					'site_admin_only' => false,
					'item_css_id' => 'mepr-bp-subscriptions'
				)
			);

			//Payments Sub Menu
			bp_core_new_subnav_item(
				array(
					'name' => _x('Payments', 'ui', 'memberpress-buddypress'),
					'slug' => MeprHooks::apply_filters('mepr-bp-payments-slug', 'mp-payments'),
					'parent_url' => trailingslashit( $user_domain . bp_get_profile_slug() ),
					'parent_slug' => bp_get_profile_slug(),
					'screen_function' => array($mpbuddypress, 'membership_payments'),
					'position' => 20,
					'user_has_access' => bp_is_my_profile(),
					'site_admin_only' => false,
					'item_css_id' => 'mepr-bp-payments'
				)
			);

			//Courses Sub Menu
			if(is_plugin_active('memberpress-courses/main.php')) {
				bp_core_new_subnav_item(
					array(
					'name'            => _x( 'My Courses', 'ui', 'memberpress-buddypress' ),
					'slug'            => MeprHooks::apply_filters('mepr-bp-courses-slug', 'mp-courses'),
					'parent_url'      => trailingslashit( $user_domain . bp_get_profile_slug() ),
					'parent_slug'     => bp_get_profile_slug(),
					'screen_function' => array( $mpbuddypress, 'membership_courses' ),
					'position'        => 20,
					'user_has_access' => bp_is_my_profile(),
					'site_admin_only' => false,
					'item_css_id'     => 'mepr-bp-courses'
					)
				);
			}

			bp_core_new_subnav_item(
				array(
					'name'            => $tab_resume_name,
					'slug'            => $profile_menu_slug,
					'parent_url'      => trailingslashit( $user_domain . bp_get_profile_slug() ),
					'parent_slug'     => bp_get_profile_slug(),
					'screen_function' => array( $resume_manager, 'bprm_show_saved_resume_screen' ),
					'position'        => 20,
				)
			);

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

		// Add custom subtab 'my-partner' under the connection tab in user profile
		bp_core_new_subnav_item(
			array(
				'name'            => __( 'My Partners', 'buddyboss' ),
				'slug'            => 'my-partners',
				'parent_url'      => trailingslashit( $user_domain . bp_get_friends_slug() ),
				'parent_slug'     => bp_get_friends_slug(),
				// 'screen_function' => array( $this, 'wbcom_my_partner_tab_screen' ),
				'screen_function' => array( $business_profile, 'bp_business_profile_followed_business' ),
				'position'        => 10,                        
			)
		);

		//Add custom subtab 'my-partner' under the connection tab in user profile
		bp_core_new_subnav_item(
			array(
				'name'            => __( 'All Individuals', 'buddyboss' ),
				'slug'            => 'members',
				'parent_url'      => trailingslashit( $user_domain . bp_get_friends_slug() ),
				'parent_slug'     => bp_get_friends_slug(),
				'screen_function' => array( $this, 'wbcom_all_individual_screen' ),
				'position'        => 1
			)
		);
		bp_core_new_subnav_item(
			array(
				'name'            => __( 'All Partners', 'buddyboss' ),
				'slug'            => 'all-partners',
				'parent_url'      => trailingslashit( $user_domain . bp_get_friends_slug() ),
				'parent_slug'     => bp_get_friends_slug(),
				'screen_function' => array( $this, 'wbcom_all_partners_screen' ),
				'position'        => 2
			)
		);
		bp_core_new_subnav_item(
			array(
				'name'            => __( 'Send Invites', 'buddyboss' ),
				'slug'            => 'send-invites',
				'parent_url'      => trailingslashit( $user_domain . bp_get_friends_slug() ),
				'parent_slug'     => bp_get_friends_slug(),
				'screen_function' => array( $this, 'bprm_send_invites_screen' ),
				'position'        => 35,                        
			)
		);
		bp_core_new_subnav_item(
			array(
				'name'            => __( 'Sent Invites', 'buddyboss' ),
				'slug'            => 'sent-invites',
				'parent_url'      => trailingslashit( $user_domain . bp_get_friends_slug() ),
				'parent_slug'     => bp_get_friends_slug(),
				'screen_function' => array( $this, 'bprm_sent_invites_screen' ),
				'position'        => 40
			)
		);
		//End add custom subtab 'my-partner' under the connection tab in user profile

		// Add sub nav on member profile activity
		bp_core_new_subnav_item(
			array(
				'name'            => __( 'All Activity', 'buddypress-hashtags' ),
				'slug'            => 'all-activity',
				'parent_url'      => trailingslashit( bp_displayed_user_domain() . bp_get_activity_slug() ),
				'parent_slug'     => bp_get_activity_slug(),
				'screen_function' => array( $this, 'wbcom_render_member_all_activity_callback' ),
				'position'        => 1,
			)
		);
		// End Add sub nav on member profile activity

		//Add all group sub tab unser the group tab in user profile 
		bp_core_new_subnav_item(
			array(
				'name'            => __( 'All Groups', 'sayansi-core' ),
				'slug'            => 'all-group',
				'parent_url'      => trailingslashit( bp_displayed_user_domain() . 'groups/' ),
				'parent_slug'     => 'groups',
				'screen_function' => array( $this, 'wbcom_render_member_all_group_callback' ),
				'position'        => 1,
			)
		);
		//End add all group sub tab unser the group tab in user profile

		//Add all forum sub tab under the forum tab in user profile 
		bp_core_new_subnav_item(
			array(
				'name'            => __( 'All Forums', 'sayansi-core' ),
				'slug'            => 'all-forum',
				'parent_url'      => trailingslashit( bp_displayed_user_domain() . 'forums/' ),
				'parent_slug'     => 'forums',
				'screen_function' => array( $this, 'wbcom_render_member_all_forum_callback' ),
				'position'        => 1,
			)
		);
		//End add all-forum sub tab under the forum tab in user profile

		//Add all forum sub tab under the forum tab in user profile 
		bp_core_new_subnav_item(
			array(
				'name'            => __( 'All Courses', 'sayansi-core' ),
				'slug'            => 'all-courses',
				'parent_url'      => trailingslashit( bp_displayed_user_domain() . 'courses/' ),
				'parent_slug'     => 'courses',
				'screen_function' => array( $this, 'wbcom_render_member_all_courses_callback' ),
				'position'        => 1,
			)
		);
		//End add all-forum sub tab under the forum tab in user profile

	}

	/**
	 * All Course tab callback
	 */
	public function wbcom_render_member_all_courses_callback(){
		add_action('bp_template_content', array( $this, 'wbcom_render_member_all_courses_content' ));
    	bp_core_load_template('members/single/plugins'); // Load the BuddyPress template
	}

	/**
	 * All Course tab content
	 */
	public function wbcom_render_member_all_courses_content(){		
		// Pagination parameters
		$paged = ( isset( $_GET['page'] ) ) ? absint( $_GET['page'] ) : 1;
		$per_page = 15;  // Number of courses per page
		$offset = ( $paged - 1 ) * $per_page;

		// Query to fetch all courses
		$args = array(
			'post_type'      => 'mpcs-course',       // Post type for forums in bbPress
			'posts_per_page' => $per_page,     // Number of forums per page
			'post_status'    => 'publish',     // Only published forums
			'orderby'        => 'title',       // Order forums alphabetically
			'order'          => 'ASC',         // Ascending order
			'paged'          => $paged,        // Set the page number for pagination
		);
		
		?>

		<div class="profile-all-courses-tab container grid-xl">
		<div class="columns mpcs-cards">
		<?php
		$courses = new WP_Query( $args );
		if ( $courses->have_posts() ) {
			while ( $courses->have_posts() ) {
				 $courses->the_post();				
				 ?>
				<div class="column col-3 col-md-6 col-xs-12">
					<div class="card s-rounded">
						<div class="card-image">
							<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
								<?php
								if ( has_post_thumbnail() ) :
									the_post_thumbnail( apply_filters( 'mpcs_course_thumbnail_size', 'mpcs-course-thumbnail' ), array( 'class' => 'img-responsive' ) );
								else :
									?>
									<img src="<?php echo esc_url( bb_meprlms_integration_url( '/assets/images/course-placeholder.jpg' ) ); ?>"
										class="img-responsive" alt="">
								<?php endif; ?>
							</a>
						</div>
						<div class="card-header">
							<div class="card-title">
								<h2 class="h5"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
							</div>
						</div>
						<div class="card-body"><?php the_excerpt(); ?></div>
						<div class="card-footer">
							<span class="course-author">
								<?php
								$user_id    = get_the_author_meta( 'ID' );
								$author_url = bp_core_get_user_domain( $user_id );
								?>
								<a href="<?php echo esc_url( $author_url ); ?>">
									<?php
									echo bp_core_fetch_avatar(
										array(
											'item_id' => $user_id,
											'html' => true,
										)
									) . bp_core_get_user_displayname( $user_id );
									?>
								</a>
							</span>
						</div>
					</div>
				</div>				
				 <?php
			}
			// <div class="pagination"></div>
		} ?>
		
		</div>
		</div>
		<?php
	}

	/**
	 * All Forum tab callback
	 */
	public function wbcom_render_member_all_forum_callback(){
		add_action('bp_template_content', array( $this, 'wbcom_render_member_all_forum_content' ));
    	bp_core_load_template('members/single/plugins'); // Load the BuddyPress template
	}

	/**
	 * All Forum tab content
	 */
	public function wbcom_render_member_all_forum_content(){		
        ?>
        <div class="all-forums-tab-content">                        
			<?php
            // Pagination parameters
            $paged = ( isset( $_GET['page'] ) ) ? absint( $_GET['page'] ) : 1;
            $per_page = 15;  // Number of forums per page
            $offset = ( $paged - 1 ) * $per_page;

            // Query to fetch all forums
            $args = array(
                'post_type'      => 'forum',       // Post type for forums in bbPress
                'posts_per_page' => $per_page,     // Number of forums per page
                'post_status'    => 'publish',     // Only published forums
                'orderby'        => 'title',       // Order forums alphabetically
                'order'          => 'ASC',         // Ascending order
                'paged'          => $paged,        // Set the page number for pagination
            );
            
            $forums = new WP_Query( $args );            
			?>
			<div id="bbpress-forums"> 
			<?php
            if ( $forums->have_posts() ) {
                echo '<ul class="grid-view bb-grid bb-forums-list bs-card-list">';
                while ( $forums->have_posts() ) {
                    $forums->the_post();
					//echo '<br> title : ' . bbp_forum_title() . '<br>';
                    echo '<li class="sm-grid-1-2 md-grid-1-2 lg-grid-1-3 lg-grid-1-3"> ';
					?>
					
					<div class="bb-cover-list-item">
						<a href="<?php echo esc_attr( get_the_permalink() ); ?>" class="bb-cover-wrap" title="<?php bbp_forum_title(); ?>">
                            </a>
						<div class="bs-card-forum-details ">
							<div class="bs-sec-header">
								<h3><a class="bbp-forum-title" href="<?php echo esc_attr( get_the_permalink() ); ?>"><?php bbp_forum_title(); ?></a></h3>
							</div>

							<div class="bb-forum-content-wrap">
								<div class="bb-forum-content"><?php echo bbp_get_forum_content_excerpt_view_more( bbp_get_forum_id(), 150, '&hellip;' ); ?></div>
							</div>

							<div class="forums-meta bb-forums-meta">
								<?php
									$r = array(
									'before'            => '',
									'after'             => '',
									'link_before'       => '<span>',
									'link_after'        => '</span>',
									'count_before'      => ' (',
									'count_after'       => ')',
									'count_sep'         => ', ',
									'separator'         => ' ',
									'forum_id'          => '',
									'show_topic_count'  => false,
									'show_reply_count'  => false,
								);

								bbp_list_forums($r);
								?>

							</div>
								<div class="bs-timestamp"><?php bbp_forum_freshness_link(); ?></div>
							</div>
						</div>			
					
					<?php                   
                    echo '</li>';
                }
                echo '</ul></div>';
                ?>
				<div class="bbp-pagination">
					<div class="bbp-pagination-count">						
						<?php
						// Get the total number of forums
						$total_forums = $forums->found_posts;

						// Calculate the range of forums on the current page
						$from = ( $paged - 1 ) * $per_page + 1;
						$to = min( $paged * $per_page, $total_forums );
						// Display the "Viewing X - Y of Z forums" text
						echo '<p>Viewing ' . $from . ' - ' . $to . ' of ' . $total_forums . ' forums</p>';
						?>
					</div>
					<div class="bbp-pagination-links">
					<?php
                // Display pagination
                echo paginate_links( array(
                    'total'   => $forums->max_num_pages, // Total number of pages
                    'current' => $paged,                 // Current page number
                    'format'  => '?page=%#%',            // Pagination format
                    'prev_text' => '&laquo; Previous',   // Previous page text
                    'next_text' => 'Next &raquo;',       // Next page text
                ) );
                
                wp_reset_postdata();  // Reset the post data after custom query
            } else {
                echo '<p>No forums found.</p>';
            }
            ?>
			</div>
			</div>		
        </div>
        <?php
	}

	/**
	 * All group sub tab callback
	 */
	public function wbcom_render_member_all_group_callback(){
		add_action('bp_template_content', array( $this, 'wbcom_render_member_all_group_content' ));
    	bp_core_load_template('members/single/plugins'); // Load the BuddyPress template
	}

	/**
	 * All group sub tab content
	 */
	public function wbcom_render_member_all_group_content(){		
            // Query all groups
            $args = array(
                'per_page' => 20, // Number of groups per page
            );            
            $groups = groups_get_groups( $args );            
            if ( !empty( $groups['groups'] ) ) {
                echo '<ul id="groups-list" class="item-list groups-list bp-list grid bb-cover-enabled left	groups-dir-list">';
                foreach ( $groups['groups'] as $group ) {                    
					$bp_group_id =  $group->id;
					$group_cover_image_url = bp_attachments_get_attachment(
							'url',
							array(
								'object_dir' => 'groups',
								'item_id'    => $bp_group_id,
							)
						);
					?>
					<li class="item-entry odd public group-type-professional-fields-disciplines is-admin is-member group-has-avatar" data-bp-item-id="24" data-bp-item-component="groups">
						<div class="list-wrap">
							<div class="bs-group-cover only-grid-view  has-default cover-small">
								<a href="<?php echo esc_url($group->permalink ); ?>">
									<img src="<?php echo esc_url( $group_cover_image_url ); ?>" alt="Group cover image">
								</a>
							</div>
							<div class="item-avatar"><a href="<?php echo esc_url($group->permalink ); ?>" class="group-avatar-wrap"><img src="<?php echo esc_url( $group_cover_image_url ); ?>" class="avatar group-24-avatar avatar-300 photo" width="300" height="300" alt="Group logo of Oceanography"></a></div>
								<div class="item  ">
									<div class="group-item-wrap">
										<div class="item-block">
											<h2 class="list-title groups-title"><a href="<?php echo esc_url($group->permalink ); ?>" class="bp-group-home-link oceanography-home-link"><?php echo $group->name; ?></a></h2>											
										</div>
										<div class="item-desc group-item-desc only-list-view"></div>
									</div>						
									<div class="group-footer-wrap  ">
										<div class="group-members-wrap">
											<span class="bs-group-members">
												<span class="bs-group-member" data-bp-tooltip-pos="up-left" data-bp-tooltip="LAWRENCE">
													<a href="http://sayansinewbu.local/members/admin/">
														<img src="http://sayansinewbu.local/wp-content/plugins/buddyboss-platform/bp-core/images/profile-avatar-buddyboss-50.png" alt="LAWRENCE" class="round">
													</a>
												</span>
											</span>
										</div>
										<div class="groups-loop-buttons footer-button-wrap">
											<div class="bp-generic-meta groups-meta action">
												<div id="groupbutton-24" class="generic-button">
													<button class="group-button join-group button" data-bp-nonce="http://sayansinewbu.local/groups/oceanography/join/?_wpnonce=3ecac012a6" data-bp-btn-action="join_group">
														Join Group
													</button>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</li>
					<?php
                }
                echo '</ul>';
            } else {
                echo '<p>No groups found.</p>';
            }
		?>
		
	<?php
	}

	/**
	 * Individual tab callback
	 *
	 * @return void
	 */
	public function wbcom_all_individual_screen(){
		add_action('bp_template_content', array( $this, 'wbcom_all_individual_screen_content' ));
    	bp_core_load_template('members/single/plugins'); // Load the BuddyPress template
	}
	/**
	 * Individual tab content
	 *
	 * @return void
	 */
	public function wbcom_all_individual_screen_content(){		
		?>
		<div id="members-dir-list" class="members dir-list" data-bp-list="" data-ajax="true" style="">
        <h3>All Members</h3>        
        
        <?php
        // Fetch members (you can modify this to suit your needs, like adding pagination)
        $args = array(
            'per_page' => 20, // Limit the number of members per page
            'page'     => 1,   // Page number
            'type'     => 'active', // Active members only
        );

        $members = new WP_User_Query( $args );

        if ( ! empty( $members->results ) ) :
            echo '<ul id="members-list" class="item-list members-list bp-list grid">';
            foreach ( $members->results as $member ) :
				$member_joined_date = bb_get_member_joined_date( $member->ID );
				$member_last_activity = bp_get_last_activity( $member->ID );
				$profile_actions = bb_member_directories_get_profile_actions( $member->ID );
                ?>
                <li class="item-entry odd is-online is-current-user" data-bp-item-id="35" data-bp-item-component="members">
                    <a href="<?php echo bp_core_get_user_domain( $member->ID ); ?>">                            
                    
					<div class="list-wrap footer-buttons-on no-secondary-buttons no-primary-buttons">
						<div class="list-wrap-inner">
							<div class="item-avatar">
									<?php echo get_avatar( $member->ID, 32 ); ?>
							</div>
							<div class="item">
								<div class="item-block">								
									<h2 class="list-title member-name">
										<?php echo esc_html( $member->display_name ); ?>
									</h2>
									<p class="item-meta last-activity"><?php echo wp_kses_post( $member_joined_date );?><span class="separator">&bull;</span><?php echo wp_kses_post( $member_last_activity ); ?></p>							
								</div>
								<div class="flex align-items-center follow-container justify-center"></div>

							</div><!-- // .item -->
							<div class="member-buttons-wrap">

							<?php if ( ! empty( $profile_actions['secondary'] ) ) { ?>
								<div class="flex only-grid-view button-wrap member-button-wrap footer-button-wrap">
									<?php echo wp_kses_post( $profile_actions['secondary'] ); ?>
								</div>
								<?php
							}

							if ( ! empty( $profile_actions['primary'] ) ) {
								?>
								<div class="flex only-list-view align-items-center primary-action justify-center">
									<?php echo wp_kses_post( $profile_actions['primary'] ); ?>
								</div>
							<?php } ?>

						</div><!-- .member-buttons-wrap -->
						</div>
						<div class="bp-members-list-hook">
							<div class="bp-members-list-hook-inner"></div>
						</div>

					</div>
					</a>
                </li>
                <?php
            endforeach;
            echo '</ul>';
        else :
            echo '<p>No members found.</p>';
        endif;
        ?>
    </div>
	<style>
		</style>
		<?php
	}

	/**
	 * All Partner tab callback
	 *
	 * @return void
	 */
	public function wbcom_all_partners_screen(){
		add_action('bp_template_content', array( $this, 'wbcom_all_partners_screen_content' ));
    	bp_core_load_template('members/single/plugins'); // Load the BuddyPress template
	}
	/**
	 * All Partner tab content
	 *
	 * @return void
	 */
	public function wbcom_all_partners_screen_content(){		
		bp_business_profile_locate_template('business-loop.php');
		?>
		<style>
			article.business.type-business{
				display:none!important;
			}
			.partners .bp-feedback.bp-messages.info{
				display:none!important;
			}
			.member-business{
				display:none!important;
			}
		</style>
		<?php
	}

	/**
	 * all activity tab callback
	 *
	 * @return void
	 */
	public function wbcom_render_member_all_activity_callback(){
		add_action('bp_template_content', 'wbcom_render_member_all_activity_content_callback');
    	bp_core_load_template('members/single/plugins'); // Load the BuddyPress template
	}

	/**
	 * all activity tab content
	 *
	 * @return void
	 */
	public function wbcom_render_member_all_activity_content_callback(){
		 // Fetch activities
		$args = array(
			'per_page' => 10, // Number of activities to display
			'page' => 1, // Current page			
			'user_id' => 0,
		);

		$activities = bp_activity_get($args);

		if (!empty($activities['activities'])) {
			foreach ($activities['activities'] as $activity) {
				// Display activity content
				echo '<div class="activity-item">';
				echo '<p>' . bp_core_get_userlink($activity->user_id) . ' ' . $activity->content . '</p>';
				echo '<span>' . bp_core_time_since($activity->date_recorded) . ' ago</span>';
				echo '</div>';
			}
		} else {
			echo '<p>No activities found.</p>';
		}
	}

	public function bprm_display_category_partners(){
		add_action( 'bp_template_content', array( $this, 'wbcom_bprm_display_category_partners' ) );
		bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
	}

	public function wbcom_bprm_display_category_partners(){
		include 'sayansi-display-business-user-profile.php';		
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


	public function wbcom_save_business_excerpt( $business_id ) {
		if ( empty( $business_id ) ) {
			return;
		}

		$beam_line_excerpt = isset( $_REQUEST['beam_line_excerpt'] ) ? wp_kses_post( $_REQUEST['beam_line_excerpt'] ) : '';

		update_field( 'beam_line_excerpt', wpautop( $beam_line_excerpt ), $business_id );

		// add groups in the partner from group selection on partner creation
		$group_id = isset( $_POST[ 'business-group' ] ) ? $_POST[ 'business-group' ] : '';		
		groups_update_groupmeta( $group_id, 'bp-group-add-business', $business_id );

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
		$reviews_slug  = bp_business_profile_get_business_slug() . '-reviews';
		$settings_slug = bp_business_profile_get_business_slug() . '-settings';
		$items = array(
			'home'               => esc_html__( 'My Home Page', 'bp-business-profile' ),
			'about'              => esc_html__( 'About', 'bp-business-profile' ),
			'follower'           => esc_html__( 'Followers', 'bp-business-profile' ),
			'beam-line-activity' => esc_html__( 'Activity', 'bp-business-profile' ),
			'beam-line-blogs'    => esc_html__( 'Partner Blog', 'bp-business-profile' ),
			'medias'             => esc_html__( 'Media', 'bp-business-profile' ),
			$reviews_slug        => esc_html__( 'Reviews', 'bp-business-profile' ),			
			'inbox'              => esc_html__( 'Inbox', 'bp-business-profile' ),
			$settings_slug       => esc_html__( 'Settings', 'bp-business-profile' ),
		);
		
		
		if ( function_exists( 'is_user_logged_in' ) && ! is_user_logged_in() ) {
			unset( $items[$settings_slug] );
			unset( $items['inbox'] );
			//unset( $items['medias'] );
		}

		if ( is_single() && get_post_type() === 'business' ) {
			$business_id  = get_the_ID();
			$group_id     = get_post_meta( $business_id, 'bp-business-group', true );
			$group        = groups_get_group( $group_id );
			$creator_id   = $group->creator_id;
			$user_id      = get_current_user_id();
			$group_member = new BP_Groups_Member( $user_id, $group_id );
			if ( (int) get_current_user_id() != (int) get_post_field( 'post_author', get_the_ID() ) && (int) $group_member->is_admin !== 1 ) {
				unset( $items[$settings_slug] );
			}
		}
		return $items;
	}
	
	/**
	 * This is for display feature img, logo, title and description custom fields.
	 *
	 * @param  int $group_id
	 * @return void
	 */
	public function wbcom_group_detail( $group_id ){	
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

		// add group-partner tab
		bp_core_new_subnav_item( array(
			'name'                => _x( 'Group Partners', 'Group partners', 'sayansi-core' ),
			'slug'            => 'group-partner',
			'parent_slug'     => bp_get_current_group_slug(),
			'parent_url'      => bp_get_group_permalink( groups_get_current_group() ),
			'screen_function' => array( $this, 'wbcom_display_group_partner_screen' ),
			'position'        => 30,
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

	public function wbcom_display_group_partner_screen(){
		add_action( 'bp_template_content', array( $this,'wbcom_group_partner_screen_display' ) );
		bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
	}

	// Display partner under the group
	public function wbcom_group_partner_screen_display(){
		$group_id = bp_get_current_group_id(); 
		$business_id = groups_get_groupmeta( $group_id, 'bp-group-add-business', true );		
		$business = get_post( $business_id );
		$_business_cover_image  = get_post_meta( $business_id, '_business_cover_image', true );
		$_business_avatar_image = get_post_meta( $business_id, '_business_avatar_image', true );
		$average_rating         = bp_business_profile_get_average_rating_for_business( $business_id );
		$category               = get_the_terms( $business_id, 'business-category' );
		$category_name          = '';
		
		if( $business_id ){
		?>
			<div id="business-dir-list" class="business dir-list" data-bp-list="business">	<div id="business-list-container" class="business-list-container">
			<div id="business-list-container" class="business-list-container">
				<ul id="business-list" class="item-list business-list row grid">
					<li class="col col-md-4 col-sm-6 col-xs-12">
						<div class="bp-business-list-wrap">
							<a href="<?php echo esc_url( get_permalink() ); ?>" class="bp-business-list-inner-wrap">
								<div class="bp-business-cover-img">					
								<?php if ( $_business_cover_image != '' ) : ?>
									<?php echo wp_get_attachment_image( esc_attr($_business_cover_image), 'full' ); ?>
								<?php else : ?>
									<?php bp_business_profile_defaut_cover_image(); ?>
								<?php if ( $category_name != '' ) : ?>
									<span class="bp-business-category"><?php echo esc_html( $category_name ); ?></span>
								<?php endif; ?>
								<?php endif ?>
									<span class="bp-business-category"><?php echo esc_html( $business->post_title ); ?></span>				
								</div>
							</a>
							<div class="item-avatar bp-business-avatar">				
								<?php 
									if ( $_business_avatar_image != '' ) :
										echo wp_get_attachment_image( $_business_avatar_image );
									else :
										bp_business_profile_defaut_avatar();
									endif; 
								?>
							</div>
							<?php do_action( 'bp_business_before_content_wrap' ); ?>

							<div class="bp-business-content-wrap">
								<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
								<?php if ( $average_rating > 0 ) : ?>
									<div class="bp-business-rating">
										<span class="bp-business-rating-wrap">
											<?php bp_business_profile_reviews_html( esc_html($average_rating), esc_html($business_id) ); ?>
										</span>
									</div>
								<?php endif; ?>

								<?php do_action( 'bp_business_before_profile_excerpt' ); ?>

								<?php if ( get_the_excerpt() != '' ) : ?>
									<div class="bp-business-profile-excerpt">
										<?php echo esc_html( wp_trim_words( get_the_excerpt(), 20 ) ); ?>
									</div>
								<?php endif; ?>
								
								<?php do_action( 'bp_business_after_profile_excerpt' ); ?>			
							</div>

							<div class="bp-business-follow-button-container-wrapper">
								<div class="bp-business-item-actions">
									<div id="bp-business-follow-button-<?php echo esc_attr( $business_id ); ?>" class="bp-business-header-nav-button bp-business-follow-button-container bp-business-listing-follow-button">

										<?php echo bp_business_get_follow_button( $business_id, $group_id ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

									</div>
								</div>
							</div>					
						</div>
					</li>
				</ul>
			</div>
		</div>
			<?php
		} else{
			esc_html_e( 'Not Found Any Partner !!', 'sayansi-core' );
		}
	}

	// Display the fields dynamically
	public function wbcom_display_business_info_fields_in_general() {		
		$post_id = get_the_ID();
		$fields = wbcom_get_business_info_fields();

		echo '<form method="post" enctype="multipart/form-data">';
		wp_nonce_field('wbcom_save_business_info', 'wbcom_business_info_nonce');

		foreach ($fields as $field_key => $field) {
			$value = get_post_meta($post_id, $field_key, true);

			echo '<p>';
			echo '<label for="' . esc_attr($field_key) . '">' . esc_html($field['label']) . ':</label><br>';

			// Generate the field based on its type
			switch ($field['type']) {
				case 'textarea':
					echo '<textarea name="' . esc_attr($field_key) . '" id="' . esc_attr($field_key) . '">' . esc_textarea($value) . '</textarea>';
					break;
				case 'text':                                   
						echo '<input type="text" name="' . esc_attr($field_key) .'" value="' . esc_attr($value) . '"> ' .'<br>';               
					break;
				case 'file':
				//   foreach ($field['options'] as $option_value => $option_label) {                    
						echo '<input type="file" name="' . esc_attr($field_key) .'" value="' . esc_attr($value) . '"> ' . '<br>';
						echo '<img src="' . esc_url( $value ) .'" alt="logo" width="500" height="600">';
					// }
					break;
				default: // Default to text input
					echo '<input type="' . esc_attr($field['type']) . '" name="' . esc_attr($field_key) . '" id="' . esc_attr($field_key) . '" value="' . esc_attr($value) . '">';
					break;
			}

			echo '</p>';
		}

		echo '</form>';
	}

	public function wbcom_save_business_general_info_fields( $post_id ){
		if (!isset($_POST['wbcom_business_info_nonce']) || !wp_verify_nonce($_POST['wbcom_business_info_nonce'], 'wbcom_save_business_info')) {
			return;
		}
		$fields = wbcom_get_business_info_fields();   
		foreach ($fields as $field_key => $field) {        
			if (isset($_POST[$field_key])) {
				$value = $_POST[$field_key];
				update_post_meta($post_id, $field_key, $value);
			}
			if( isset( $_FILES[$field_key] )  ){
				$file = $_FILES[$field_key];
				require_once ABSPATH . 'wp-admin/includes/file.php';
					$upload = wp_handle_upload($file, ['test_form' => false]);
					if (isset($upload['error'])) {
					wp_die('Upload error: ' . $upload['error']);
				}
				$file_url = $upload['url'];
				update_post_meta($post_id, $field_key, $file_url);
			}
		}
	}


	/**
	 * add filter to display the only docs on user and group : library->document
	 *
	 * @param  array $r to filter the documents.
	 * @return array $r.
	 */
	public function wbcom_has_document_parse_args( $r ) {

		if ( ! isset( $r['folder_id'] ) && empty( $r['folder_id'] ) ) {
			return $r;
		}

		if ( bp_is_user() && 'folders' == bp_current_action() ) {

			if ( strpos( $_SERVER['HTTP_REFERER'], 'tab=audio' ) ) {
				$r['meta_query_document'] = array(
					array(
						'key'     => 'extension',
						'value'   => '.mp3',
						'compare' => '=',
					),

				);
			} elseif ( strpos( $_SERVER['HTTP_REFERER'], 'tab=videos' ) ) {
				$r['meta_query_document'] = array(
					array(
						'key'     => 'extension',
						'value'   => '.mp4',
						'compare' => '=',
					),

				);
			} elseif ( strpos( $_SERVER['HTTP_REFERER'], 'tab=photos' ) ) {
				$r['meta_query_document'] = array(
					array(
						'key'     => 'extension',
						'value'   => '.jpg',
						'compare' => '=',
					),

				);
			} elseif ( strpos( $_SERVER['HTTP_REFERER'], 'tab=document' ) ) {
				$r['meta_query_document'] = array(
					array(
						'key'     => 'extension',
						'value'   => '.pdf',
						'compare' => '=',
					)
				);
			}
		}	
		return $r;
	}
	
	/**
	 * Load forum and discussion tab content at all forums menu
	 *
	 * @return void
	 */
	public function wbcom_load_forum_discussion() {
		$nonce = $_POST['nonce'];

		if ( ! wp_verify_nonce( $nonce, 'sayansi_ajax_security' ) ) {
			wp_send_json_error('Invalid nonce');
			return;
		}

		$endpoint = isset( $_POST['check_tab'] ) ? $_POST['check_tab'] : '';
		ob_start(); // Start output buffering

		if( 'sayansi-forum' == $endpoint ) {
			$forum_desc = $_POST['forum_desc'];			
			include get_stylesheet_directory() . '/tab-forums.php';
		} else {
			include get_stylesheet_directory() . '/tab-discussion.php';
		}

		$content = ob_get_clean(); // Get the buffered content
		wp_send_json_success($content); // Send the content back as a JSON response
		die();
	}
	
	/**
	 * Add back btn on the create forum page
	 *
	 * @return void
	 */
	public function wbcom_add_back_btn_on_create_forum_page() {		
		echo "<a class='sayansi-back-forum-page' href='" . esc_attr('https://sayansi.africanlightsource.org/forums/') . "'>";
		esc_html_e( 'Back To Forums', 'sayansi-core' );
		echo "</a>";
	}
	
	/**
	 * Remove favourite subtab from the user profile timelines tab
	 *
	 * @return void
	 */
	public function wbcom_bp_remove_mention_fvrt_groups_sub_tabs_profile() {
		if ( ! bp_is_user() ) {
			return;
		}
		bp_core_remove_subnav_item( 'activity', 'favorites' );
	}

	/**
	 * Reorder activity, connections subnav items
	 *
	 * @return void
	 */
	public function wbcom_reorder_activity_subnav_tab() {
		if ( ! bp_is_user() ) {
			return;
		}
		global $bp;		
		$bp->bp_options_nav['activity']['mentions']['position'] = 35; 
		$bp->bp_options_nav['activity']['hashtags']['position'] = 40;
		$bp->bp_options_nav['activity']['groups']['position'] = 100;
		$bp->bp_nav['courses']['position'] = 80;
		// reorder connection subnav items
		if ( defined('BP_FRIENDS_SLUG') ) {		   
			$bp->bp_options_nav['connections']['teams']['position'] = 11;				   
		} else {
			$bp->bp_options_nav['friends']['teams']['position'] = 11;				   
		}
	}

	/**
	 * Update connection tab slug on user profile
	 *
	 * @return void
	 */
	public function wbcom_change_connections_tab_slug( $slug ){
		if ( defined( 'BP_FRIENDS_SLUG' ) ) {
			$slug = BP_FRIENDS_SLUG;
		} else {
			return $slug;	
		}
		return $slug;
	}
	
	/**
	 * wbcom_update_business_excerpt_value
	 *
	 * @param  mixed $business_id
	 * @return void
	 */
	// public function wbcom_update_business_excerpt_value($business_id){	
	// 	$excerpt_val = isset( $_POST['acf']['field_6729c0127e757'] ) ? $_POST['acf']['field_6729c0127e757'] : '';
	// 	// Update the post excerpt for the custom post type.
	// 	$post_args = array(
	// 		'ID'           => $business_id,
	// 		'post_excerpt' => $excerpt_val,
	// 	);					
	// 	update_field( 'beam_line_excerpt', wpautop( $excerpt_val ), $business_id );
	// 	wp_update_post( $post_args );
	// }

	/**
	 * Remove filter for redirect single business to home
	 *
	 * @return void
	 */
	public function wbcom_remove_home_url_filter() {    	
		remove_filter( 'home_url', array( DMS\Includes\Integrations\BuddyBoss\BuddyBoss_Platform::get_instance(), 'change_home_url' ), 9999 );
	}

	/**
	 * Add filter for exclude single business to redirect home
	 *
	 * @return void
	 */
	public function wbcom_change_home_url( $url, $path ){
		if(is_user_logged_in()){
			$request_params = new Request_Params();
			$is_global_mapping_active = Setting::find( 'dms_global_mapping' )->get_value();
			if(empty($is_global_mapping_active)){
				return $url;
			}

			$main_mapping_ids = Setting::find('dms_main_mapping');
			$current_mapping = Helper::matching_mapping_from_db($request_params->get_domain(), $request_params->get_path());

			if(empty($current_mapping) || empty($main_mapping_ids)){
				return $url;
			}

			if(!empty($main_mapping_ids->value) && !in_array($current_mapping->get_id(), $main_mapping_ids->value)){
				return $url;
			}


			$current_domain = $request_params->get_domain();
			return trim( Helper::generate_url( $current_domain, trim($current_mapping->get_path().'/'.$path, '/')), '/' );
		}

		return $url;
	}
	
	/**
	 * Add file on connections sub-tab under activity tab on user profile
	 */
	public function sayansi_include_activity_connections_core_file() {
        
        $url = isset( $_SERVER['REQUEST_URI'] ) ? $_SERVER['REQUEST_URI'] : '';
        
        if( ! empty($url) && strpos($url,'/activity/connections') ){
            
            $friend_file = WP_PLUGIN_DIR.'/buddyboss-platform/bp-activity/screens/friends.php';
            
            if( file_exists( $friend_file ) ){
                
                require $friend_file;
            }
           
        }
    }

    /**
	 * Function to save group setting value.
	 *
	 * @param  int $post_id Post ID.
	 * @return void
	 */
	public function wbcom_bp_blog_pro_save_group_setting( $post_id ) {
		global $wpdb;
		$table_name = $wpdb->prefix . 'bp_activity';
		$selected_business_group = isset( $_POST['bp_blog_pro_business_group_links'] ) ? sanitize_text_field( wp_unslash( $_POST['bp_blog_pro_business_group_links'] ) ) : '';
		if ( isset( $selected_business_group ) && ! empty( $selected_business_group ) ) {
			update_post_meta( $post_id, 'bp_blog_pro_business_group_links', $selected_business_group );
			$act_id = get_post_meta( $post_id, 'bp_member_blog_pro_activity_id', true );
			$wpdb->query( $wpdb->prepare( "UPDATE ".$wpdb->prefix . "bp_activity' SET item_id='%d' WHERE id=%d", $selected_business_group, $act_id ) );

			bp_activity_update_meta( $act_id, 'bp_member_blog_pro_group_posts', $selected_business_group );
			groups_update_groupmeta( $selected_business_group, 'bp_blog_pro_business_group_post', $post_id );
		}
	}


	 /**
	 * Function to search, filter forums.
	 *
	 * @return void
	 */
	public function wbcom_forums_search(){

		$query 			= sanitize_text_field($_POST['query']);
		$order 			= sanitize_text_field($_POST['order']);
		$forum_order 	= sanitize_text_field($_POST['forum_order']);
		$layout 		= sanitize_text_field($_POST['layout']);
		$paged 			= isset($_POST['paged']) ? intval($_POST['paged']) : 1; // Get the current page number
		$posts_per_page = apply_filters( 'wbcom_forum_paginations_count' ,15 );
		
		// Set up the arguments for the query
		$args = array(
			'post_type' => 'forum',
			'posts_per_page' => $posts_per_page, // Get all forums if no query is provided
			'paged' => $paged,
		);
	
		// If there is a search query, add it to the arguments
		if (!empty($query)) {
			$args['s'] = $query; // Search for the query
		}
		// Set the order based on the parameter
		if ($order === 'alphabetical') {
			$args['orderby'] = 'title'; // Order by title
			$args['order'] = 'ASC'; // Ascending order
		}

		if ($forum_order === 'alphabetical') {
			$args['orderby'] = 'title'; // Order by title
			$args['order'] = 'ASC'; // Ascending order
		} elseif ($forum_order === 'recent') {
			$args['orderby'] = 'date'; // Order by date
			$args['order'] = 'DESC'; // Descending order (newest first)
		}
			
		$forum_query = new WP_Query($args);

		$total_forums = $forum_query->found_posts; // Get the total number of forums
		
		// Calculate the range
	    $current_page = max(1, $paged);
	    $start = ($current_page - 1) * $posts_per_page + 1; // Starting forum number
	    $end = min($start + $posts_per_page - 1, $total_forums); // Ending forum number

		echo '<div id="bbpress-forums">';		
		echo '<ul class="' . esc_attr($layout) . ' bb-grid bb-forums-list bs-card-list">';	
		if ($forum_query->have_posts()) {
			while ($forum_query->have_posts()) {
				$forum_query->the_post();
				$forum_id = get_the_ID();
				$forum_desc = get_post_field('post_content', $forum_id); // Get the forum description
				?>
				<li class="sm-grid-1-2 md-grid-1-2 lg-grid-1-3 lg-grid-1-3">
					<div class="bb-cover-list-item">
						<a href="<?php echo esc_attr( get_permalink() ); ?>" class="bb-cover-wrap" title="<?php echo esc_attr( get_the_title() ); ?>">
						<?php echo get_the_post_thumbnail(); ?>
						</a>
						<div class="bs-card-forum-details ">
							<div class="bs-sec-header">
								<h3>
									<a class="bbp-forum-title" href="<?php echo esc_attr( get_permalink() ); ?>">
										<?php echo esc_html( get_the_title() ); ?>
									</a>
								</h3>
							</div>
							<div class="bb-forum-content-wrap">
								<div class="bb-forum-content"><?php echo esc_html($forum_desc); ?></div>
							</div>
							<div class="forums-meta bb-forums-meta"></div>
							<div class="bs-timestamp">
								<?php esc_html__('No Discussions','sayansi-core');?>
							</div>
						</div>
					</div>
				</li>
				<?php
			}
		} else {
			echo '<p>' . esc_html__('No forums found.', 'sayansi-core') . '</p>';
		}
		echo '</ul>';
		
	    // add pagination for forum
		$pagination_args = array(
		    'base' => esc_url( home_url( '/forums/?paged=%#%' ) ),
		    'format' => '?paged=%#%',
		    'current' => $paged,
		    'total' => $forum_query->max_num_pages,
		    'prev_text' => __('&laquo; Previous', 'sayansi-core'),
		    'next_text' => __('Next &raquo;', 'sayansi-core'),		    
		);
	    echo '<div class="bbp-pagination">';
	    echo '<div class="bbp-pagination-count">Viewing '. esc_html($start) . ' - ' . esc_html($end) . ' of ' . esc_html($total_forums) . ' forums </div>';
	    echo '<div class="bbp-pagination-links">' . paginate_links($pagination_args) . '</div>';
	    echo '</div>';
		
		echo '</div>';
	   	
		echo '</div>';	
		wp_die(); 
	}

	public function wbcom_bp_blog_pro_reorder_group_blog_tab_position( $nav ){
		 global $bp;	
	    // Check if we’re in a group context
	    if (bp_is_group() && isset($bp->groups->nav)) {		
	        // Access the group navigation
			$nav['name'] = esc_html__( 'Group Blog', 'sayansi-core' );
			$nav['position'] = 9;
	    }	
		return $nav;
	}

	public function wbcom_bp_stats_group_statistics_tabs_position( $pos ){
		 global $bp;	
	    // Check if we’re in a group context
	    if (bp_is_group() && isset($bp->groups->nav)) {		
	        // Access the group navigation
			$pos = 90;
	    }	
		return $pos;
	}

	// add group dropdown on business creation
	public function wbcom_group_selection_business_creation(){
		$groups = groups_get_groups(
			array(									
				'type'        => 'active',				
				'per_page' 	  => -1,
			)
		);		
		echo '<label for="business-group">Select Group</label>';
		echo '<p class="bp-business-description">Select group to associated partner</p>';
		echo '<select name="business-group" id="business-group" aria-required="true" class="business-group">';
		echo '<option>' . esc_html( 'Select Group' ) . '</option>';
		foreach ( $groups['groups'] as $group ) {
			$business_meta = groups_get_groupmeta( $group->id, 'bp-group-business');
			if( empty( $business_meta ) ){				 				
				echo '<option value=" ' . $group->id . ' " > ' . $group->name . ' </option>';				 
			}
		}
		echo '<select>';
	 }

	 // rename course tab on single group
	 public function wbcom_rename_course_tab_on_group( $coursename ){
		$coursename = __( 'Group Courses', 'sayansi-core' );
		return $coursename;
	}

	public function wbcom_filter_courses() {
		// Check if order_by is set; if not, default to 'alphabetical'
		 $order_by = isset($_POST['order_by']) ? sanitize_text_field($_POST['order_by']) : 'alphabetical';
		 $tabname = isset($_POST['tabname']) ? sanitize_text_field($_POST['tabname']) : '';
	 
		 // Set up the arguments based on the selected value
		 $args = array(			
			 'post_type' => 'mpcs-course',
			 'post_status' => array('publish', 'private'),
			 'orderby' => 'title', // Default to alphabetical
			 'order' => 'ASC',
			 'posts_per_page' => 10,
		 );
	 
		 // Modify the order by based on the selected value
		 if ($order_by === 'recent') {
			 $args['orderby'] = 'date';
			 $args['order'] = 'DESC';
		 }
	 
		 // If the tabname is 'instructor-courses', filter by the current user's ID
		 if ($tabname === 'instructor-courses') {
			 $args['author'] = get_current_user_id(); // Filter by the current user
		 }
		 
		 // Fetch the courses
		 $courses = new WP_Query($args);
	 
		 // Check if there are posts and output them
		 if ($courses->have_posts()) {
			 while ($courses->have_posts()) {
				 $courses->the_post(); ?>
				 <div class="column col-4 col-md-6 col-xs-12">
					 <div class="card s-rounded">
						 <div class="card-image">
							 <a href="<?php the_permalink(); ?>" alt="<?php the_title_attribute(); ?>">
								 <?php 
								 if (has_post_thumbnail()) {
									 the_post_thumbnail('mpcs-course-thumbnail', array('class' => 'img-responsive'));
								 } else {
									 // Default image if no thumbnail is found
									 $default_image_url = 'https://sayansi.africanlightsource.org/wp-content/plugins/buddyboss-platform-pro/includes/integrations/meprlms/assets/images/course-placeholder.jpg';
									 echo '<img src="' . esc_url($default_image_url) . '" class="img-responsive" alt="' . esc_attr__('Default Course Image', 'sayansi-core') . '">';
								 }
								 ?>
							 </a>
						 </div>
						 <div class="card-header">
							 <div class="card-title">
								 <h2 class="h5"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
							 </div>
						 </div>
						 <div class="card-body">
							 <?php the_excerpt(); ?>
						 </div>
						 <div class="card-footer">
							 <span class="course-author">
								 <?php
								 $user_id = get_the_author_meta('ID');
								 $author_url = bp_core_get_user_domain($user_id);
								 ?>
								 <a href="<?php echo esc_url($author_url); ?>">
									 <?php
									 echo bp_core_fetch_avatar(array(
										 'item_id' => $user_id,
										 'html' => true,
									 )) . bp_core_get_user_displayname($user_id);
									 ?>
								 </a>
							 </span>
						 </div>
					 </div>
				 </div>
				 <?php
			 } 
			 wp_reset_postdata();
		 } else {
			 echo '<p>' . esc_html__('No courses found.', 'sayansi-core') . '</p>';
		 }
		 
		 wp_die();
	}

	// add button to send message to the connection - mass messaging on user profile
	public function wbcom_add_send_message_button_user_connection_tab() {
		if (bp_is_my_profile() ) {
			$compose_url = bp_loggedin_user_domain() . bp_get_messages_slug() . '/compose/?all_connections=1';
		?>
		<div class="bp-connections-send-message">
			<a href="<?php echo esc_url($compose_url); ?>" class="button send-message-to-connections">
				<?php _e('Send Message', 'buddyboss'); ?>
			</a>
		</div>
		<?php
		}
	}

	// manage rediretion on click the send button on use profile under connection tab - mass messaging on user profile
	public function wbcom_handle_message_redirect() {
		if (isset($_GET['all_connections']) && $_GET['all_connections'] == 1 && bp_is_messages_component() && bp_is_current_action('compose')) {
			add_action('wp_footer', array($this, 'wbcom_pre_select_connections_on_compose'));
		}
	}

	// manage auto select the connection on the compose - mass messaging on user profile
	public function wbcom_pre_select_connections_on_compose() {
		// Get the current user's connections (friend IDs)
		$connections = friends_get_friend_user_ids(bp_loggedin_user_id());

		// If no connections, exit early
		if (empty($connections)) {
			return;
		}

		// Prepare an array of connection data with BuddyBoss-style values and first names
		$connection_data = array_map(function($user_id) {
			// Get the first name from user meta
			$first_name = get_user_meta($user_id, 'first_name', true);

			// Fallback to nicename if first name is empty
			$display_name = !empty($first_name) ? $first_name : bp_core_get_username($user_id);

			// Get the raw username (nicename) without any prefixes
			$user = get_userdata($user_id);
			$username = $user->user_login; // Use user_login to ensure raw username            
			return [
				'id' => '@' . $username,    // Apply @bb- prefix once (e.g., @bb-nicolina)
				'text' => $display_name        // First name (or nicename as fallback) for display
			];
		}, $connections);

		// Encode the connection data as JSON
		$connections_json = json_encode($connection_data);

	?>
	<script type="text/javascript">
	jQuery(document).ready(function($) {
		var connections = <?php echo $connections_json; ?>;

		// Wait for Select2 to initialize
		var checkSelect2 = setInterval(function() {
			if ($('#send-to-input').hasClass('select2-hidden-accessible')) {
				clearInterval(checkSelect2);

				// Pre-select connections by BuddyBoss-style values
				var selectedIds = connections.map(function(item) {
					return item.id; // e.g., @bb-nicolina
				});

				// Set the selected values in the Select2 field
				$('#send-to-input').val(selectedIds);

				// Add the options to Select2 with first names as the visible text
				connections.forEach(function(item) {
					var option = new Option(item.text, item.id, true, true);
					$('#send-to-input').append(option);
				});

				// Trigger change to update the Select2 display
				$('#send-to-input').trigger('change');
			}
		}, 100);
	});
	</script>
	<?php
	}

	// Display confirmation popup on the user profile send message - mass messaging on user profile
	public function wbcom_add_confirmation_popup_to_send_message(){
	?>
		<script type="text/javascript">
			jQuery(document).ready(function($) {
				if ($('#bp-messages-send').length) {
					// Store reference to the button
					var $sendButton = $('#bp-messages-send');
					var $form = $('#send_message_form');

					// Handle click on the Send button
					$sendButton.on('click.messageConfirm', function(e) {
						e.preventDefault();
						
						// Check if dialog already exists
						if ($('.ui-dialog').length) {
							return false;
						}

						// Create and show confirmation dialog
						$('<div class="msg-bulk-dialog"></div>').appendTo('body')
							.html('<div><h6>Are you sure you want to send this message?</h6></div>')
							.dialog({
								modal: true,
								title: 'Confirm Message Send',
								zIndex: 10000,
								autoOpen: true,
								width: '350px',
								resizable: false,
								buttons: {
									"Yes": function() {
										// Remove our custom handler temporarily
										$sendButton.off('click.messageConfirm');
										
										// Trigger the original BuddyBoss send action
										if (typeof bp !== 'undefined' && bp.Messages && bp.Messages.Views) {
											// Trigger BuddyBoss's internal send mechanism if available
											$sendButton.trigger('click');
										} else {
											// Fallback: simulate the original button click
											$sendButton[0].click();
										}
										
										// Close dialog
										$(this).dialog("close");
									},
									"No": function() {
										$(this).dialog("close");
									}
								},
								close: function() {
									$(this).remove();
									// Reattach our handler after dialog closes
									setTimeout(function() {
										$sendButton.off('click.messageConfirm').on('click.messageConfirm', arguments.callee.caller);
									}, 100);
								}
							});
						
						return false;
					});
				}
			});

		</script>
	<?php
	}
}
