<?php
/**
 * This file is used for listing the posts on profile
 *
 * @package Buddypress_Member_Blog
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;
global $bp,$current_user,$wpdb;
$bp_member_blog_gen_stngs = get_option( 'bp_member_blog_gen_stngs' );
$user_id                  = bp_loggedin_user_id();
$is_my_profile            = bp_is_my_profile();
$current_group_id         = get_post_meta( get_the_ID(), 'bp-business-group', true );


if ( bp_is_active( 'groups' ) && is_user_logged_in() ) {
	$bpmb_pro_group_stngs     = get_option( 'group_blog_integration' );
	$bpmp_allow_group_linking = isset( $bpmb_pro_group_stngs['allow_group_linking'] ) ? $bpmb_pro_group_stngs['allow_group_linking'] : '';
	$group_ids                = groups_get_user_groups( $user_id );
	$is_groups_member         = groups_is_user_member( $user_id, $current_group_id );
	
	$bpmp_allow_group_role      = isset( $bpmb_pro_group_stngs['who_can_link'] ) ? $bpmb_pro_group_stngs['who_can_link'] : '';
	$bpmb_pro_group_roles       = array( 'member', 'admin', 'mod' );
	$bpmb_pro_allow_group_roles = array();
	if ( isset( $bpmp_allow_group_role ) && ! empty( $bpmp_allow_group_role ) ) {
		$bpmb_pro_allow_group_roles = array_intersect_key( $bpmp_allow_group_role, $bpmb_pro_group_roles );
	}
	$bpmb_pro_allow_group_links = false;
	foreach ( $group_ids['groups'] as $group_id ) {
		$bp_groups_members = groups_get_group_members(
			array(
				'group_id'   => $group_id,
				'group_role' => $bpmb_pro_allow_group_roles,
				'type'       => 'active',
			)
		);
		foreach ( $bp_groups_members['members'] as $key => $bp_groups_member ) {
			if ( ( ! empty( $bp_groups_member->is_admin ) && $bp_groups_member->user_id === $user_id ) || ( ! empty( $bp_groups_member->is_mod ) && ( $bp_groups_member->user_id === $user_id ) ) ) {
				$bpmb_pro_allow_group_links = true;
			} elseif ( ( ! empty( $bp_groups_member->is_admin ) && $bp_groups_member->user_id !== $user_id ) || ( ! empty( $bp_groups_member->is_mod ) && ( $bp_groups_member->user_id !== $user_id ) ) || $bp_groups_member->user_id === $user_id ) {
				$bpmb_pro_allow_group_links = true;
			}
			$ids[] = $bp_groups_member->user_id;
		}
	}

	$author_id   = (int) get_post_field( 'post_author', get_the_ID() );
	$user_full_name = get_the_author_meta( 'display_name', $author_id );
	$current_user_id = get_current_user_id();
	$is_post_owner = ( $author_id === $current_user_id );
	if( !$is_post_owner ){ ?>
		<style>
			.business-subnavs ul{
				display:none;
			}
		</style>
		<?php
	}	
}
/*
* Check current user role to allowed create post or not.
*/
$action_button         = false;
$member_types          = bp_get_member_type( get_current_user_id(), false );
$bpmb_pro_create_posts = ( isset( $bp_member_blog_gen_stngs['bp_create_post'] ) ) ? ! empty( $bp_member_blog_gen_stngs['bp_create_post'] ) : '';
$bpmb_pro_member_types = ( isset( $bp_member_blog_gen_stngs['member_types'] ) ) ? ! empty( $bp_member_blog_gen_stngs['member_types'] ) : '';
if ( $bpmb_pro_create_posts || $bpmb_pro_member_types ) {
	$bp_member_blog_gen_stngs['bp_create_post'] = ( isset( $bp_member_blog_gen_stngs['bp_create_post'] ) ) ? $bp_member_blog_gen_stngs['bp_create_post'] : array();
	$bp_member_blog_gen_stngs['member_types']   = ( isset( $bp_member_blog_gen_stngs['member_types'] ) ) ? $bp_member_blog_gen_stngs['member_types'] : array();
	$user_roles                                 = array_intersect( (array) $current_user->roles, $bp_member_blog_gen_stngs['bp_create_post'] );
	$user_types                                 = array_intersect( (array) $member_types, $bp_member_blog_gen_stngs['member_types'] );
	if ( empty( $user_roles ) && empty( $user_types ) ) {
		$action_button = true;
	} elseif ( ! is_user_logged_in() || get_current_user_id() != bp_displayed_user_id() ) {

		$action_button = true;
	}
}

$posts_id       = groups_get_groupmeta( $current_group_id, 'bp_blog_pro_group_post', true );
$selected_group = get_post_meta( $posts_id, 'bp_blog_pro_group_links', true );
// let us build the post query.
if ( $is_my_profile || is_super_admin() ) {
	$post_status = 'any';
} else {
	$post_status = 'publish';
}

$pagination_page = ( get_query_var( 'paged' ) ) ? absint( get_query_var( 'paged' ) ) : 1;
$bpmb_pro_table  = $wpdb->prefix . 'bp_groups_members';
$group_users     = $wpdb->get_results( "SELECT * FROM $bpmb_pro_table WHERE `group_id` = $current_group_id" );
foreach ( $group_users as $group_user ) {
	$user_ids[] = $group_user->user_id;
}
$post_authors = implode( ',', $user_ids );
if( isset( $_GET['tab'] ) && 'publish' == $_GET['tab'] ){
	$query_args = array(
		'author'      => $post_authors,
		'post_type'   => 'post',
		'post_status' => $post_status,
		'paged'       => intval( $pagination_page ),
		'meta_query'  => array(
			'relation' => 'OR',
			array(
				'key'     => 'bp_blog_pro_group_links',
				'value'   => $current_group_id,
				'compare' => '==',
			),
			array(
				'key'     => 'bp_blog_pro_business_group_links',
				'value'   => $current_group_id, // Replace $some_value with the value you want to compare
				'compare' => '==', // Change the comparison operator if needed
			),
		),
	);
} elseif( isset( $_GET['tab'] ) && 'pending' == $_GET['tab'] ){
	$query_args = array(
		'author'      => $user_id,
		'post_type'   => 'post',
		'post_status' => 'pending',
		'paged'       => intval( $paged ),
	);
} elseif( isset( $_GET['tab'] ) && 'draft' == $_GET['tab'] ){
	$query_args = array(
		'author'      => $user_id,
		'post_type'   => 'post',
		'post_status' => 'draft',
		'paged'       => intval( $paged ),
	);
} else {
	$query_args = array(
		'author'      => $post_authors,
		'post_type'   => 'post',
		'post_status' => $post_status,
		'paged'       => intval( $pagination_page ),
		'meta_query'  => array(
			'relation' => 'OR',
			array(
				'key'     => 'bp_blog_pro_group_links',
				'value'   => $current_group_id,
				'compare' => '==',
			),
			array(
				'key'     => 'bp_blog_pro_business_group_links',
				'value'   => $current_group_id, // Replace $some_value with the value you want to compare
				'compare' => '==', // Change the comparison operator if needed
			),
		),
	);
}
// do the query.
// $post_loop = new WP_Query( $query_args );
query_posts( $query_args );
$post_query = new WP_Query( $query_args );
$total_posts = $post_query->found_posts;
//Add blog related tab in beam
global $wp;
$beam_link = site_url() . '/' . $wp->request;
$endpoint = isset( $_GET['tab'] ) ? $_GET['tab'] : '';
$link                     = '';
$bp_member_blog_gen_stngs = get_option( 'bp_member_blog_gen_stngs' );
if ( isset( $bp_member_blog_gen_stngs['bp_post_page'] ) && $bp_member_blog_gen_stngs['bp_post_page'] != 0 ) {
	$link = get_permalink( $bp_member_blog_gen_stngs['bp_post_page'] );
}
?>
<nav class="bp-navs business-subnavs no-ajax business-subnav business-subnav-plain" id="subnav" role="navigation" aria-label="<?php esc_html_e( 'Business submenu', 'bp-business-profile' ); ?>">
	<ul class="subnav">		
			<li class="bp-business-tab bp-business-navigation-link bp-business-navigation-link--publish <?php echo 'publish' === $endpoint ? 'current selected' : ''; ?>">
				<a href="<?php echo esc_url( $beam_link . '?tab=publish' ); ?>" id="beam-publish"><?php esc_html_e( 'Published', 'bp-business-profile' ); ?>
					<?php if ( $total_posts > 0 ) : ?>
						<span class="post-count">(<?php echo esc_html( $total_posts ); ?>)</span>
					<?php endif; ?>
				</a>
			</li>		
		
			<li class="bp-business-tab bp-business-navigation-link bp-business-navigation-link--pending <?php echo 'pending' === $endpoint ? 'current selected' : ''; ?>">
				<a href="<?php echo esc_url( $beam_link . '?tab=pending' ); ?>" id="beam-pending"><?php esc_html_e( 'Pending', 'bp-business-profile' ); ?>
					<?php if ( $total_posts > 0 ) : ?>
						<span class="post-count">(<?php echo esc_html( $total_posts ); ?>)</span>
					<?php endif; ?>
				</a>
			</li>		
		
			<li class="bp-business-tab bp-business-navigation-link bp-business-navigation-link--draft <?php echo 'draft' === $endpoint ? 'current selected' : ''; ?>">
				<a href="<?php echo esc_url( $beam_link . '?tab=draft' ); ?>" id="beam-draft"><?php esc_html_e( 'Draft', 'bp-business-profile' ); ?>
					<?php if ( $total_posts > 0 ) : ?>
						<span class="post-count">(<?php echo esc_html( $total_posts ); ?>)</span>
					<?php endif; ?>
				</a>
			</li>		
		
		
			<li class="bp-business-tab bp-business-navigation-link bp-business-navigation-link--documents <?php echo 'new-post' === $endpoint ? 'current selected' : ''; ?>">
				<a href="<?php echo esc_url( $link ); ?>" id="beam-new-post"><?php esc_html_e( 'New Post', 'bp-business-profile' ); ?></a>
			</li>	

		<?php do_action( 'bp_business_profile_blog_subnav' ); ?>
	</ul>
</nav>
	
	<!-- End Add blog related tab in beam -->

<div class="bp-member-blog-container bpmb-blog-posts">
	<?php
		switch ( $endpoint ) {
			case 'publish':					
				include 'beam-publish.php';
				break;
			case 'pending':
				include 'beam-pending.php';
				break;
			case 'draft':
				include 'beam-draft.php';
				break;
			default:
				include 'beam-publish.php';
				break;
		}
	?>
	<?php
	wp_reset_postdata();
	wp_reset_query();
	?>
</div>