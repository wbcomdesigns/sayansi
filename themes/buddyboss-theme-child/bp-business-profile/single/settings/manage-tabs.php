<?php
/**
 * Business Single page settings
 *
 * @package WordPress
 * @subpackage bp-business-profile
 */

/**
* This template is override because client wants to by default disable the review tab.
*/


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$business_id = get_the_ID();
$tabs_items  = get_post_meta( $business_id, '_business_tabs_items', true );
if ( $tabs_items === '' ) {
	$tabs_items = array();
}

$integration = get_option( 'bp_business_integration' );
$events      = ( isset( $integration['events'] ) ) ? $integration['events'] : '';
$job         = ( isset( $integration['my-jobs'] ) ) ? $integration['my-jobs'] : '';
$shop        = ( isset( $integration['shop'] ) ) ? $integration['shop'] : '';
?>

<div id="bp-profile-setting-manage-tabs-content" class="tab-content settings-tab-content">
	<h3 class="bp-business-screen-title"><?php esc_html_e( 'Manage Tabs', 'bp-business-profile' ); ?></h3>
	<p class="description"><?php esc_html_e( 'You can disable Business Main Navigation for website members. You\'ll still have access to all the tabs.', 'bp-business-profile' ); ?></p>
	
	<fieldset class="bp-business-profile-info">
		<div class="business-toggle-wrapper bp-business-profile-info-wrapper">
			<label for="business-show_team-address"><?php esc_html_e( 'Home', 'bp-business-profile' ); ?></label>			
			<input type="hidden" name="business_info[tabs_items][]" id="business-show_team-address" value="home"/>
		</div>
	</fieldset>
	
	<fieldset class="bp-business-profile-info">
		<div class="business-toggle-wrapper bp-business-profile-info-wrapper">
			<label for="business-show_team-address"><?php esc_html_e( 'About', 'bp-business-profile' ); ?></label>
			<label class="business-slider-switch">
				<input type="checkbox" name="business_info[tabs_items][]" id="business-show_team-address" value="about"  
				<?php
				if ( in_array( 'about', $tabs_items ) || empty( $tabs_items ) ) :
					?>
  checked <?php endif; ?>/>
				<div class="business-slider business-round"></div>
			</label>
		</div>
	</fieldset>
	
	<fieldset class="bp-business-profile-info">
		<div class="business-toggle-wrapper bp-business-profile-info-wrapper">
			<label for="business-show_team-address"><?php esc_html_e( 'Media', 'bp-business-profile' ); ?></label>
			<label class="business-slider-switch">
				<input type="checkbox" name="business_info[tabs_items][]" id="business-show_team-address" value="medias"  
				<?php
				if ( in_array( 'medias', $tabs_items ) || empty( $tabs_items ) ) :
					?>
  checked <?php endif; ?>/>
				<div class="business-slider business-round"></div>
			</label>
		</div>
	</fieldset>
	
	<fieldset class="bp-business-profile-info">
		<div class="business-toggle-wrapper bp-business-profile-info-wrapper">
			<label for="business-show_team-address"><?php esc_html_e( 'Reviews', 'bp-business-profile' ); ?></label>
			<label class="business-slider-switch">
				<input type="checkbox" name="business_info[tabs_items][]" id="business-show_team-address" value="partners-reviews"  
				<?php
				if ( in_array( 'partners-reviews', $tabs_items ) ) :
					?>
  checked <?php endif; ?>/>
				<div class="business-slider business-round"></div>
			</label>
		</div>
	</fieldset>
	
	<fieldset class="bp-business-profile-info">
		<div class="business-toggle-wrapper bp-business-profile-info-wrapper">
			<label for="business-show_team-address"><?php esc_html_e( 'Follower', 'bp-business-profile' ); ?></label>
			<label class="business-slider-switch">
				<input type="checkbox" name="business_info[tabs_items][]" id="business-show_team-address" value="follower"  
				<?php
				if ( in_array( 'follower', $tabs_items ) || empty( $tabs_items ) ) :
					?>
  checked <?php endif; ?>/>
				<div class="business-slider business-round"></div>
			</label>
		</div>
	</fieldset>
	
	<fieldset class="bp-business-profile-info">
		<div class="business-toggle-wrapper bp-business-profile-info-wrapper">
			<label for="business-show_team-address"><?php esc_html_e( 'Inbox', 'bp-business-profile' ); ?></label>
			<input type="hidden" name="business_info[tabs_items][]" id="business-show_team-address" value="inbox"/>
		</div>
	</fieldset>
	
	<?php if ( class_exists( 'Tribe__Events__Main' ) && class_exists( 'Tribe__Events__Community__Main' ) && ! empty( $events ) ) { ?>
		<fieldset class="bp-business-profile-info">
			<div class="business-toggle-wrapper bp-business-profile-info-wrapper">
				<label for="business-show_team-address"><?php esc_html_e( 'Events', 'bp-business-profile' ); ?></label>			
				<label class="business-slider-switch">
				<input type="checkbox" name="business_info[tabs_items][]" id="business-show_team-address" value="events"  
				<?php
				if ( in_array( 'events', $tabs_items ) || empty( $tabs_items ) ) :
					?>
  checked <?php endif; ?>/>
				<div class="business-slider business-round"></div>
			</label>
			</div>
		</fieldset>
	
	<?php } ?>


	<?php if ( class_exists( 'WP_Job_Manager' ) && ! empty( $job ) ) { ?>
		<fieldset class="bp-business-profile-info">
			<div class="business-toggle-wrapper bp-business-profile-info-wrapper">
				<label for="business-show_team-address"><?php esc_html_e( 'Jobs', 'bp-business-profile' ); ?></label>			
				<label class="business-slider-switch">
				<input type="checkbox" name="business_info[tabs_items][]" id="business-show_team-address" value="my-jobs"  
				<?php
				if ( in_array( 'my-jobs', $tabs_items ) || empty( $tabs_items ) ) :
					?>
  checked <?php endif; ?>/>
				<div class="business-slider business-round"></div>
			</label>
			</div>
		</fieldset>
	
	<?php } ?>
	
	
	<?php if ( class_exists( 'WooCommerce' ) && ! empty( $shop ) ) { ?>
		<fieldset class="bp-business-profile-info">
			<div class="business-toggle-wrapper bp-business-profile-info-wrapper">
				<label for="business-show_team-address"><?php esc_html_e( 'Shop', 'bp-business-profile' ); ?></label>			
				<label class="business-slider-switch">
				<input type="checkbox" name="business_info[tabs_items][]" id="business-show_team-address" value="product"  
				<?php
				if ( in_array( 'product', $tabs_items ) || empty( $tabs_items ) ) :
					?>
  checked <?php endif; ?>/>
				<div class="business-slider business-round"></div>
			</label>
			</div>
		</fieldset>
	
	<?php } ?>
	
	<?php do_action( 'bp_business_before_settings', $business_id, $tabs_items ); ?>
	
	<fieldset class="bp-business-profile-info">
		<div class="business-toggle-wrapper bp-business-profile-info-wrapper">
			<label for="business-show_team-address"><?php esc_html_e( 'Settings', 'bp-business-profile' ); ?></label>
			<input type="hidden" name="business_info[tabs_items][]" id="business-show_team-address" value="business-settings"/>
		</div>
	</fieldset>
	
	
	
</div>
