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
$user_full_name           = $bp->loggedin_user->fullname;
$is_my_profile            = bp_is_my_profile();
$current_group_id         = get_post_meta( get_the_ID(), 'bp-business-group', true );


if ( bp_is_active( 'groups' ) && is_user_logged_in() ) {
	$bpmb_pro_group_stngs     = get_option( 'group_blog_integration' );
	$bpmp_allow_group_linking = isset( $bpmb_pro_group_stngs['allow_group_linking'] ) ? $bpmb_pro_group_stngs['allow_group_linking'] : '';
	$group_ids                = groups_get_user_groups( $user_id );
	$is_groups_member         = groups_is_user_member( $user_id, $current_group_id );
	if ( ! $bpmp_allow_group_linking || ! $is_groups_member ) {
		return false;
	}
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
	if ( ! $bpmb_pro_allow_group_links || 'yes' !== $bpmp_allow_group_linking ) {
		return false;
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

$query_args = array(
	'author'      => $post_authors,
	'post_type'   => 'post',
	'post_status' => $post_status,
	'paged'       => intval( $pagination_page ),
	'meta_query'  => array(
		array(
			'key'     => 'bp_blog_pro_group_links',
			'value'   => $current_group_id,
			'compare' => '==',
		),
	),
);
// do the query.
// $post_loop = new WP_Query( $query_args );
query_posts( $query_args );
?>
<div class="bp-member-blog-container bpmb-blog-posts">
	<?php if ( have_posts() ) : ?>
		<?php
		while ( have_posts() ) :
			the_post();
			global $post;
			$selected_group = get_post_meta( $post->ID, 'bp_blog_pro_group_links', true );
			if ( $selected_group == $current_group_id ) {
				?>
			<div id="post-<?php the_ID(); ?>" class="<?php echo esc_attr( 'bpmb-blog-post' ); ?>">				
					<div class="post-featured-image">
						<a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php esc_html_e( 'Permanent Link to', 'buddypress-member-blog-pro' ); ?> <?php the_title_attribute(); ?>">
							<?php if ( function_exists( 'has_post_thumbnail' ) && has_post_thumbnail( get_the_ID() ) ) : ?>
								<?php the_post_thumbnail(); ?>
							<?php else : ?>
								<img src="<?php echo esc_url( BUDDYPRESS_MEMBER_BLOG_PLUGIN_URL ) . 'public/images/no-post-image.jpg'; ?>" class="attachment-thumbnail size-thumbnail wp-post-image" alt="<?php the_title(); ?>" width="150" height="150">
							<?php endif; ?>
						</a>
					</div>
				<div class="post-content">

					<h3 class="entry-title">
						<a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php esc_html_e( 'Permanent Link to', 'buddypress-member-blog-pro' ); ?> <?php the_title_attribute(); ?>"><?php the_title(); ?></a>
						<?php
						if ( $user_id == $post->post_author ) :
							?>
							<span class="bp-edit-post"><?php echo wp_kses_post( bp_member_blog_get_edit_link() ); ?></span>
						<?php endif; ?>
					</h3>
					<div class="post-date">
						<?php
						/* translators: %s: Category List*/
						printf( esc_html__( '%1$s', 'buddypress-member-blog-pro' ), get_the_date(), wp_kses_post( get_the_category_list( ', ' ) ) );
						?>
					</div>
					<div class="entry-content">
						<?php the_excerpt( __( 'Read the rest of this entry &rarr;', 'buddypress-member-blog-pro' ) ); ?>
						<?php
						wp_link_pages(
							array(
								'before'         => '<div class="page-link"><p>' . __( 'Pages: ', 'buddypress-member-blog-pro' ),
								'after'          => '</p></div>',
								'next_or_number' => 'number',
							)
						);
						?>
					</div>
					<?php
					if ( $user_id == $post->post_author ) :
						$bpmb_pro_groups_instance = Buddypress_Member_Blog_Pro_Groups::instance();
						$bpmb_pro_groups_instance->bp_member_blog_pro_action_links( $post );
					endif;
					?>
				</div>
			</div>
		<?php } endwhile; ?>
			<div class="navigation pagination">
				<?php bp_member_blog_paginate(); ?>
			</div>
		<?php
		else :
			if ( is_user_logged_in() ) {
				$bp_template_option = bp_get_option( '_bp_theme_package_id' );
				$current_group_name = $bp->groups->current_group->name;
				if ( 'nouveau' === $bp_template_option ) {
					echo '<div id="message" class="info bp-feedback bp-messages bp-template-notice">';
					echo '<span class="bp-icon" aria-hidden="true"></span>';
				} else {
					echo '<div id="message" class="info">';
				}
				/* translators: %1$s is replaced with login user full name, %2$1s is replaced with Current Group Name */
				echo '<p>' . sprintf( esc_html__( '%1$s has not posted anything in the group %2$1s.', 'buddypress-member-blog-pro' ), esc_html( $user_full_name ), esc_html( $current_group_name ) ) . '</p>';
				echo '</div>';
			} else {
				$current_group_name = $bp->groups->current_group->name;
				$bp_template_option = bp_get_option( '_bp_theme_package_id' );
				if ( 'nouveau' === $bp_template_option ) {
					echo '<div id="message" class="info bp-feedback bp-messages bp-template-notice">';
					echo '<span class="bp-icon" aria-hidden="true"></span>';
				} else {
					echo '<div id="message" class="info">';
				}
				/* translators: %s is replaced with Current Group Name */
				echo '<p>' . sprintf( esc_html__( "%s doesn't have any posts.", 'buddypress-member-blog-pro' ), esc_html( $current_group_name ) ) . '</p>';
				echo '</div>';
			}
			?>
	<?php endif; ?>
	<?php
	wp_reset_postdata();
	wp_reset_query();
	?>
</div>