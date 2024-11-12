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
global $bP_business_settings;
$business_id = get_the_ID();

$selected_category = wp_get_post_terms( $business_id, 'business-category' );
$general_settings  = get_option( 'bp_business_profile_general_settings' );

$category_id = 0;
if ( ! empty( $selected_category ) ) {
	$category_id   = $selected_category[0]->term_id;
	$category_name = $selected_category[0]->name;
}
$email      = get_post_meta( $business_id, '_business_email', true );
$website    = get_post_meta( $business_id, '_business_website', true );
$phone      = get_post_meta( $business_id, '_business_phone', true );
$whatsapp   = get_post_meta( $business_id, '_business_whatsapp', true );
$works      = get_post_meta( $business_id, '_business_work', true );
$educations = get_post_meta( $business_id, '_business_education', true );
if ( ! empty( $bP_business_settings['map_settings']['map_api_key'] ) ) {
	$address = html_entity_decode(get_post_meta( $business_id, '_business_address', true ));
} else {
	$address_parts = array(
		get_post_meta( $business_id, '_business_address_1', true ),
		get_post_meta( $business_id, '_business_address_2', true ),
		get_post_meta( $business_id, '_business_city', true ),
		get_post_meta( $business_id, '_business_state', true ),
		get_post_meta( $business_id, '_business_zipcode', true ),
		get_post_meta( $business_id, '_business_country', true ),
	);

	// Remove empty parts to avoid extra commas in the address
	$address_parts = array_filter(
		$address_parts,
		function ( $value ) {
			return ! empty( $value );
		}
	);

	$address = html_entity_decode(implode( ', ', $address_parts ));
}

$works_empty      = true;
$educations_empty = true;

$show_setting_info = true;
if ( (int) get_current_user_id() != (int) get_post_field( 'post_author', $business_id ) ) {
	$show_setting_info = false;
}

if ( ! array_key_exists( 'visibility_control', $general_settings ) ) {
	$show_setting_info = false;
}

	

?>

<div id="bp-profile-about-content" class="tabs bp-business-settings-content">
	<div class="bp-business-section-container">
		<?php if ( $category_id != 0 && $category_id != '' ) : ?>
			<div class="bp-business-info-widget-section">
				<h3 class="bp-business-screen-title"><?php esc_html_e( 'Category', 'bp-business-profile' ); ?></h3>
				<p><i class="fas fa-rectangle-list"></i><?php echo esc_html( $category_name ); ?></p>
			</div>
		<?php endif; ?>
		
		<?php if ( $website || $phone || $email || $whatsapp || $address || apply_filters( 'bp_business_profile_contact_info', false ) ) : ?>
		<div class="bp-business-info-widget-section">
			<h3 class="bp-business-screen-title"><?php esc_html_e( 'Contact Information', 'bp-business-profile' ); ?></h3>				
			
			<ul>
				<?php do_action( 'bp_business_profile_before_render_contact_info' ); ?>

				<?php if ( $email != '' ) : ?>
					<li class="bp-business-contact">
						<a href="mailto:<?php echo esc_attr( $email ); ?>"><i class="fas fa-envelope"></i><span><?php echo esc_html( $email ); ?></span></a>
					</li>
				<?php endif; ?>	
				<?php if ( $website != '' ) : ?>
					<li class="bp-business-contact">
						<a href="<?php echo esc_url( $website ); ?>"><i class="fas fa-globe"></i><span><?php echo esc_html( $website ); ?></span></a>
					</li>
				<?php endif; ?>	
				<?php if ( $phone != '' ) : ?>
					<li class="bp-business-contact">
						<a href="tel:<?php echo esc_attr( $phone ); ?>"><i class="fas fa-phone-alt"></i><span><?php echo esc_html( $phone ); ?></span></a>
					</li>
				<?php endif; ?>	
				<?php if ( $whatsapp != '' ) : ?>
					<li class="bp-business-contact">
						<a href="https://wa.me/<?php echo esc_attr( $whatsapp ); ?>"><i class="fab fa-whatsapp"></i><span><?php echo esc_html( $whatsapp ); ?></span></a>
					</li>
				<?php endif; ?>	
				<?php if ( $address != '' ) : ?>
					<li class="bp-business-contact">
						<a target="_blank" class="bp-tooltip" data-bp-tooltip-pos="up" data-bp-tooltip="Get Directions" href="https://www.google.com/maps/dir/?api=1&destination=<?php echo urlencode($address); ?>"><i class="fas fa-map-marker-alt"></i><span><?php echo esc_html( $address ); ?></span></a>
					</li>
				<?php endif; ?>	

				<?php do_action( 'bp_business_profile_after_render_contact_info' ); ?>

			</ul>
			
		</div>
		
		<?php elseif ( is_user_logged_in() && $show_setting_info == true ) : ?>				
			<div class="bp-business-info-widget-section">
				<h3 class="bp-business-screen-title"><?php esc_html_e( 'Contact Information', 'bp-business-profile' ); ?></h3>
				<p><?php esc_html_e( 'To add contact information:', 'bp-business-profile' ); ?> <a href='<?php echo esc_url( get_post_permalink() ); ?>business-settings/#contact-info'><?php esc_html_e( 'Contact Info', 'bp-business-profile' ); ?></a></p>
			</div>
		<?php endif; ?>

		<?php if ( ! empty( $works ) && isset( $works[0]['company_name'] ) && $works[0]['company_name'] != '' ) : ?>
			<div class="bp-bussiness-work-info-section">
				<h3 class="bp-business-screen-title"><?php esc_html_e( 'Work', 'bp-business-profile' ); ?></h3>
				<?php
				foreach ( $works as $work ) :
					$former_text = ( isset( $work['to_year'] ) && $work['to_year'] != '' ) ? esc_html__( 'Former', 'bp-business-profile' ) : '';
					?>
					<div class="bp-bussiness-work-info">
						<?php /* translators: %s: */ ?>
						<p class="bp-business-work-title"><i class="fas fa-briefcase"></i><b><?php echo sprintf( esc_html__( '%1$s %2$s at %3$s', 'bp-business-profile' ), esc_html( $former_text ), esc_html( $work['position'] ), esc_html( $work['company_name'] ) ); ?></b></p>
						<p class="bp-business-work-year-place">

							<?php
							if ( isset( $work['from_year'] ) && $work['from_year'] != '' && isset( $work['to_year'] ) && $work['to_year'] != '' ) {
								?>
								<span class="work-year"><i class="fas fa-calendar-days"></i><?php echo sprintf( '%s - %s ', esc_html( $work['from_year'] ), esc_html( $work['to_year'] ) ); ?></span>
								<span class="work-place"><i class="fas fa-location-dot"></i><?php echo esc_html( $work['place'] ); ?></span>
							<?php } else { ?>
								<span class="work-year"><i class="fas fa-calendar-days"></i><?php echo sprintf( '%s - %s ', esc_html( $work['from_year'] ), esc_html__( 'Present', 'bp-business-profile' ) ); ?></span>
								<span class="work-place"><i class="fas fa-location-dot"></i><?php echo esc_html( $work['place'] ); ?></span>
							<?php } ?>
						</p>
						<?php if ( isset( $work['description'] ) && $work['description'] != '' ) : ?>
							<p class="bp-business-work-description"><i class="fas fa-file-text"></i><?php echo esc_html( $work['description'] ); ?></p>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
			</div>
			<?php
			$works_empty = false;
		endif;
		?>

		<?php if ( ! empty( $educations ) && isset( $educations[0]['institute'] ) && $educations[0]['institute'] != '' ) : ?>

			<div class="bp-bussiness-education-info-section">
				<h3 class="bp-business-screen-title"><?php esc_html_e( 'Education', 'bp-business-profile' ); ?></h3>
				<?php foreach ( $educations as $education ) : ?>
					<div class="bp-bussiness-education-info">
						<?php /* translators: %s: */ ?>
						<p class="bp-business-education-title"><i class="fas fa-graduation-cap"></i><b><?php echo sprintf( esc_html__( 'Studied  %1$s at %2$s, %3$s', 'bp-business-profile' ), esc_html( $education['gegree'] ), esc_html( $education['institute'] ), esc_html( $education['place'] ) ); ?></b></p>
						<p class="bp-business-education-year-place">

							<?php
							if ( isset( $education['from_year'] ) && $education['from_year'] != '' && isset( $education['to_year'] ) && $education['to_year'] != '' ) {
								?>
								<span class="education-year"><i class="fas fa-calendar-days"></i><?php echo sprintf( '%s - %s ', esc_html( $education['from_year'] ), esc_html( $education['to_year'] ) ); ?></span>
								<span class="education-place"><i class="fas fa-location-dot"></i><?php echo esc_html( $education['place'] ); ?></span>
							<?php } else { ?>
								<span class="education-year"><i class="fas fa-calendar-days"></i><?php echo sprintf( '%s - %s ', esc_html( $education['from_year'] ), esc_html__( 'Present', 'bp-business-profile' ) ); ?></span>
								<span class="education-place"><i class="fas fa-location-dot"></i><?php echo esc_html( $education['place'] ); ?></span>
							<?php } ?>
						</p>
						<?php if ( isset( $education['description'] ) && $education['description'] != '' ) : ?>
							<p class="bp-business-education-description"><i class="fas fa-file-text"></i><?php echo esc_html( $education['description'] ); ?></p>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
			</div>
			<?php
			$educations_empty = false;
		endif;
		?>

		<?php if ( $educations_empty == true && $works_empty == true && is_user_logged_in() && $show_setting_info == true ) : ?>
			<div class="bp-business-info-widget-section">
				<h3 class="bp-business-screen-title"><?php esc_html_e( 'Work & Education', 'bp-business-profile' ); ?></h3>
				<p><?php esc_html_e( 'To add work and education:', 'bp-business-profile' ); ?> <a href='<?php echo esc_url( get_post_permalink() ); ?>business-settings/#work-education'><?php esc_html_e( 'Work & Education', 'bp-business-profile' ); ?></a></p>
			</div>
		<?php endif; ?>
		<div class="bp-business-bibliography-widget-section">
			<?php $bibliography = get_post_meta( $business_id, 'business_bibliography', true); ?>
			<?php if( (int) get_current_user_id() === (int) get_post_field( 'post_author', $business_id ) ) : ?>
				<form id="business-bibliography-form" method="post">
					<div class="business-bibliography-editor"> 
						<?php wp_editor( $bibliography, 'business-bibliography' ); ?>
					</div>
					<div calss="business-bibliography-save">
						<input type="submit" name="submit" id="business-bibliography-save" class="button button-primary" value="Save"/>
					</div>
					<input type="hidden" name="business_id" value="<?php echo esc_attr( $business_id ); ?>">
					<?php wp_nonce_field( 'business-bibliography-action', 'business-bibliography-nonce' ); ?>
				</form>
			<?php else: ?>
				<div class="business-bibliography-content">
					<?php echo $bibliography; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>

	<?php bp_business_profile_locate_template( 'single/business-right-sidebar.php', true ); ?>	

</div>
<?php
