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
				'name'                => _x( 'Home', 'Member Home page', 'sayansi-core' ),
				'slug'                => 'home',
				'position'            => 1,
				'screen_function'     => array( $this, 'wbcom_member_profile_home_tab_screen' ),
				'default_subnav_slug' => 'home',
			),
			'members'
		);
	}

	public function wbcom_member_profile_home_tab_screen() {
		add_action( 'bp_template_content', array( $this, 'wbcom_member_profile_home_tab_screen_content' ) );
		bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
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

}
