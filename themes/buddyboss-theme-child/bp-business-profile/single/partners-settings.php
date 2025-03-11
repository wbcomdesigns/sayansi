<?php

/**
 * Business Single page settings
 *
 * @package WordPress
 * @subpackage bp-business-profile
 */

if (!defined('ABSPATH')) {
	exit;
}
global $bP_business_settings;
$general_settings = isset( $bP_business_settings['general_settings'] ) ? $bP_business_settings['general_settings'] : array();
$singular_label   = ( isset( $general_settings['singular_label'] ) ) ? $general_settings['singular_label'] : 'Business';
$plural_label     = ( isset( $general_settings['plural_label'] ) ) ? $general_settings['plural_label'] : 'Businesses';
$integration = get_option('bp_business_integration');
$events      = (isset($integration['events'])) ? $integration['events'] : '';
$shop        = (isset($integration['shop'])) ? $integration['shop'] : '';
$settings_slug = bp_business_profile_get_business_slug(). '-settings';
?>
<div class="tabs bp-business-settings-content">
	<nav class="sub-navs no-ajax bp-navs single-screen-subnavs bp-business-settings-nav" id="bp-business-object-nav" role="navigation" aria-label="Settings menu">
		<ul class="subnav" id="bp-profile-settings-tabs-nav">
			<?php foreach (bp_business_profile_single_settings_tabs() as $slug => $name) :
				$active_class = (isset($_GET['active']) && $_GET['active'] == $slug) ? 'active' : ''; //phpcs:ignore
			?>
				<?php if (($slug == 'event-settings')) : ?>
					<?php if (!empty($events) &&  class_exists('Tribe__Events__Main') && class_exists('Tribe__Events__Community__Main')) : ?>
						<li data-id="<?php echo esc_attr($slug); ?>" id="bp-profile-setting-<?php echo esc_attr($slug); ?>-li" class="bp-profile-setting-sub-tab bp-business-sub-tab <?php echo esc_attr($active_class); ?>">
							<a href="#bp-profile-setting-<?php echo esc_attr($slug); ?>-content"><?php echo esc_html($name); ?></a>
						</li>
					<?php endif; ?>
				<?php elseif (($slug == 'shop-settings') && class_exists('WooCommerce')) : ?>
					<?php if (!empty($shop)) : ?>
						<li data-id="<?php echo esc_attr($slug); ?>" id="bp-profile-setting-<?php echo esc_attr($slug); ?>-li" class="bp-profile-setting-sub-tab bp-business-sub-tab <?php echo esc_attr($active_class); ?>">
							<a href="#bp-profile-setting-<?php echo esc_attr($slug); ?>-content"><?php echo esc_html($name); ?></a>
						</li>
					<?php endif; ?>
				<?php else : ?>
					<li data-id="<?php echo esc_attr($slug); ?>" id="bp-profile-setting-<?php echo esc_attr($slug); ?>-li" class="bp-profile-setting-sub-tab bp-business-sub-tab <?php echo esc_attr($active_class); ?>">
						<a href="#bp-profile-setting-<?php echo esc_attr($slug); ?>-content"><?php echo esc_html($name); ?></a>
					</li>
				<?php endif; ?>

			<?php endforeach; ?>
		</ul>
	</nav>
	<form action="" method="post" class="bp-profile-settings" id="bp-profile-settings">
		<div class="item-body-inner">
			<div class="bp-profile-setting-loader profile_loader"></div>
			<div id="tabs-content">
				<?php foreach (bp_business_profile_single_settings_tabs() as $slug => $name) : ?>
					<?php get_template_part(bp_business_profile_locate_template('single/settings/' . $slug . '.php', true), true); ?>
				<?php endforeach; ?>
			</div>
			<div class="bp-business-settings-action-wrapper">
				<button class="bp-business-settings-submit button primary" id="bp-business-settings-submit" type="submit">
					<span>
					<?php 
					/* translators: %s is the name of  business taxonomy name */
					printf( esc_html__( 'Save %s Settings', 'bp-business-profile' ), esc_attr( $singular_label ) ); ?>
					</span>
					<div class="bp-business-ajax-spinner" style="display: none;"></div>
				</button>
				<div class="bp-business-settings-result-box" id="bp-business-settings-result-box" style="display: none;"></div>
			</div>
			<input type="hidden" name="bp_business_id" value="<?php echo esc_attr(get_the_ID()); ?>" />
			<input type="hidden" name="action" value="business_profile_data_save" />
			<input type="hidden" name="page_url" id="business_setting_page_url" value="<?php echo esc_url(get_permalink()) . $settings_slug.'/'; ?>" />
			<?php wp_nonce_field('bp-profile-settings-save', 'bp-profile-settings-save-nonce'); ?>

		</div>
	</form>
</div>