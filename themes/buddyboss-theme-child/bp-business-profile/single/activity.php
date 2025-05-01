<?php
/**
 * Business Single page settings
 *
 * @package WordPress
 * @subpackage bp-business-profile
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$business_id = get_the_ID();
?>

<div id="bp-profile-beam-line-activity-content" class="tabs bp-business-settings-content">
	

	<?php bp_business_profile_locate_template( 'single/business-left-sidebar.php', true ); ?>
	
	<div id="bp-business-buddypress" class="buddypress-wrap">
	
		<?php if ( is_user_logged_in() && ( bp_business_profile_is_admin() || bp_business_profile_is_moderator() ) ) : ?>		
		
			<div class="bp-business-profile-post-form-wrapper">
				
				<?php bp_get_template_part( 'activity/post-form' ); ?>
				
			</div>
		<?php endif; ?>
		
		<div class="screen-content">

			<?php bp_nouveau_activity_hook( 'before_directory', 'list' ); ?>
			
			<div id="activity-stream" class="activity" data-bp-list="activity" >

				<div id="bp-ajax-loader"><?php bp_nouveau_user_feedback( 'directory-activity-loading' ); ?></div>

			</div><!-- .activity -->
			
			<?php bp_nouveau_after_activity_directory_content(); ?>
		</div><!-- // .screen-content -->
	</div>
	
	<?php bp_business_profile_locate_template( 'single/business-right-sidebar.php', true ); ?>
	

</div>
