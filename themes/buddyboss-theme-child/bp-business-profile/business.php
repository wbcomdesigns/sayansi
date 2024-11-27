<?php
/**
 * Business category page
 *
 * @package WordPress
 * @subpackage bp-business-profile
 */

global $general_settings;

$business_id            = intval(get_the_ID());
$category               = get_the_terms( $business_id, 'business-category' );
$category_name          = '';
$_business_avatar_image = get_post_meta( $business_id, '_business_avatar_image', true );
$_business_cover_image  = get_post_meta( $business_id, '_business_cover_image', true );
$average_rating         = bp_business_profile_get_average_rating_for_business( $business_id );
$review_count           = array_sum( bp_business_profile_get_rating_counts_for_business( $business_id ) );
$group_id               = (int) get_post_meta( $business_id, 'bp-business-group', true );

if ( ! empty( $category ) ) {
	$category_name = esc_html($category['0']->name);
}

$business_hours_on_listing = ( isset( $general_settings['business_hours_on_listing'] ) ) ? $general_settings['business_hours_on_listing'] : '';

$column = 4;
if ( isset( $_REQUEST['args'] ) ) {
	if ( isset( $_REQUEST['args']['column'] ) && $_REQUEST['args']['column'] != '' ) {
		$column = intval(sanitize_text_field( $_REQUEST['args']['column'] ));
		$column = 12 / $column;
	}
}
?>
<li class="col col-md-<?php echo esc_attr( $column ); ?> col-sm-6 col-xs-12">
	<div class="bp-business-list-wrap">
		<a href="<?php echo esc_url( the_guid() ); ?>" class="bp-business-list-inner-wrap">
			<div class="bp-business-cover-img">

				<?php if ( $_business_cover_image != '' ) : ?>

					<?php echo wp_get_attachment_image( esc_attr($_business_cover_image), 'full' ); ?>

				<?php else : ?>

					<?php bp_business_profile_defaut_cover_image(); ?>

				<?php endif ?>

				<?php if ( $category_name != '' ) : ?>

					<span class="bp-business-category"><?php echo esc_html( $category_name ); ?></span>

				<?php endif; ?>

			</div>
		</a>
		<div class="item-avatar bp-business-avatar">
			<?php if ( $_business_avatar_image != '' ) : ?>

				<?php echo wp_get_attachment_image( $_business_avatar_image ); ?>

			<?php else : ?>

				<?php bp_business_profile_defaut_avatar(); ?>

			<?php endif; ?>
		</div>

		<?php do_action( 'bp_business_before_content_wrap' ); ?>

		<div class="bp-business-content-wrap">
			<h3><a href="<?php the_guid(); ?>"><?php the_title(); ?></a></h3>
			<?php if ( $average_rating > 0 ) : ?>
				<div class="bp-business-rating">
					<span class="bp-business-rating-wrap">
						<?php bp_business_profile_reviews_html( esc_html($average_rating), esc_html($business_id) ); ?>
					</span>
				</div>
			<?php endif; ?>

			<?php do_action( 'bp_business_before_profile_excerpt' ); ?>

			<?php if ( ! empty( get_field( "beam_line_excerpt" ) ) ) : ?>
				<div class="bp-business-profile-excerpt">
					<?php echo get_field( "beam_line_excerpt" ); ?>
				</div>
			<?php endif; ?>

			<?php do_action( 'bp_business_after_profile_excerpt' ); ?>
			
		</div>

		<?php do_action( 'bp_business_after_content_wrap' ); ?>

		<div class="bp-business-follow-button-container-wrapper">
			<div class="bp-business-item-actions">
				<div id="bp-business-follow-button-<?php echo esc_attr( $business_id ); ?>" class="bp-business-header-nav-button bp-business-follow-button-container bp-business-listing-follow-button">

					<?php echo bp_business_get_follow_button( $business_id, $group_id ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

				</div>
			</div>
		</div>
		
		<?php
		if ( $business_hours_on_listing != '' ) :
			$business_hours = get_post_meta( $business_id, '_business_hours', true );
			if ( ! empty( $business_hours ) ) {
				$day        = strtolower( gmdate( 'l' ) );
				$type       = ( isset( $business_hours['type'] ) ) ? $business_hours['type'] : '';
				$timezone   = ( isset( $business_hours['timezone'] ) ) ? $business_hours['timezone'] : '';
				$hours_form = ( isset( $business_hours[ $day ]['from'] ) ) ? $business_hours[ $day ]['from'] : '';
				$hours_to   = ( isset( $business_hours[ $day ]['to'] ) ) ? $business_hours[ $day ]['to'] : '';

				$hide = false;
				foreach ( bp_business_profile_work_days() as $day_key => $day_info ) {
					$hoursform = ( isset( $business_hours[ $day_key ]['from'] ) ) ? $business_hours[ $day_key ]['from'] : '';
					if ( count( $hoursform ) == 1 && isset( $hoursform[0] ) && $hoursform[0] != '' ) {
						$hide = true;
						break;
					}
				}
				?>
				<div class="bp-business-hours-container-section">
					<div class="bp-business-today-timing">
						<?php
						if ( $type === 'permanetly_closed' ) {
							?>
							
							<label class="bp-business-permanently-closed"><?php esc_html_e( 'Permanently closed', 'bp-business-profile' ); ?></label>
							
						<?php } elseif ( $type === 'temporarily_closed' ) { ?>
							
							<label class="bp-business-temporarily-closed"><?php esc_html_e( 'Temporarily closed', 'bp-business-profile' ); ?></label>
							
						<?php } elseif ( $type === 'always_open' ) { ?>
						
							<label class="bp-business-open-24h"><?php esc_html_e( 'Open 24h', 'bp-business-profile' ); ?></label>
							
							<?php
						} else {

							echo bp_business_profile_open_close_label( $timezone, $hours_form, $hours_to );// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

							if ( ! empty( $hours_form ) && $hide == true ) {
								$count = count( $hours_form );
								echo "<ul class=''>";
								for ( $i = 0; $i < $count; $i++ ) {
									if ( isset( $hours_form[ $i ] ) && $hours_form[ $i ] == '' ) {
										continue;
									}
									?>
									<li><?php echo esc_html( bp_business_profile_work_selected_hours( $hours_form[ $i ] ) ) . ' - ' . esc_html( bp_business_profile_work_selected_hours( $hours_to[ $i ] ) ); ?></li>
									<?php
								}
								echo '</ul>';

								?>
							<span class="fa-solid fa-chevron-down bp_business_hours_expand_more" data-toggle="tooltip" data-placement="top" title="<?php esc_attr_e( 'Toggle weekly schedule', 'bp-business-profile' ); ?>"></span>
								<?php
							}
						}
						?>
						
					</div>
					<?php if ( $type === 'selected_hours' ) { ?>
						<div class="bp-business-open-hours-wrapper" style="display:none;">
							<ul class="extra-details">
								<?php
								foreach ( bp_business_profile_work_days() as $day_key => $day_info ) :
									$day_name   = $day_info['label'];
									$hours_form = ( isset( $business_hours[ $day_key ]['from'] ) ) ? $business_hours[ $day_key ]['from'] : '';
									$hours_to   = ( isset( $business_hours[ $day_key ]['to'] ) ) ? $business_hours[ $day_key ]['to'] : '';
									?>
									<li>
										<span class="bp-business-day"><?php echo esc_html( $day_name ); ?></span>
										<span class="bp-business-day-info">
											<?php
											if ( $type === 'permanetly_closed' ) {
												esc_html_e( 'Permanently closed', 'bp-business-profile' );
											} elseif ( $type === 'temporarily_closed' ) {
												esc_html_e( 'Temporarily closed', 'bp-business-profile' );
											} elseif ( $type === 'always_open' ) {
												esc_html_e( 'Open 24h', 'bp-business-profile' );
											} elseif ( ! empty( $hours_form ) ) {
												$count = count( $hours_form );
												echo "<ul class=''>";
												for ( $i = 0; $i < $count; $i++ ) {
													?>
														<li><?php echo esc_html( bp_business_profile_work_selected_hours( $hours_form[ $i ] ) ) . ' - ' . esc_html( bp_business_profile_work_selected_hours( $hours_to[ $i ] ) ); ?></li>
														<?php
												}
												echo '</ul>';
											}
											?>
										</span>
									</li>
								<?php endforeach; ?>
							</ul>					
						</div>
					<?php } ?>
				</div>
				<?php
			}
		endif;
		?>
		
	</div>
</li>
