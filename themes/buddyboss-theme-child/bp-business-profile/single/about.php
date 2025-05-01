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
$category_name = '';
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
    $address_parts = array_filter(
        [
            get_post_meta( $business_id, '_business_address_1', true ),
            get_post_meta( $business_id, '_business_address_2', true ),
            get_post_meta( $business_id, '_business_city', true ),
            get_post_meta( $business_id, '_business_state', true ),
            get_post_meta( $business_id, '_business_zipcode', true ),
            get_post_meta( $business_id, '_business_country', true ),
        ],
        function ( $value ) {
            return ! empty( $value );
        }
    );

    $address = html_entity_decode(implode( ', ', $address_parts ));
}

$works_empty      = empty( $works ) || ! isset( $works[0]['company_name'] ) || $works[0]['company_name'] === '';
$educations_empty = empty( $educations ) || ! isset( $educations[0]['institute'] ) || $educations[0]['institute'] === '';

$show_setting_info = true;
if ( (int) get_current_user_id() !== (int) get_post_field( 'post_author', $business_id ) ) {
    $show_setting_info = false;
}

if ( ! array_key_exists( 'visibility_control', $general_settings ) ) {
    $show_setting_info = false;
}
?>

<div id="bp-profile-about-content" class="tabs bp-business-settings-content">
    <div class="bp-business-section-container">

        <!-- About Section -->
        <?php do_action( 'bp_business_profile_before_about_section', $business_id ); ?>
        <div class="bp-business-about-section">
            <h3 class="bp-business-screen-title"><?php esc_html_e( 'About', 'bp-business-profile' ); ?></h3>
            <div class="bp-business-content">
                <?php echo apply_filters( 'the_content', get_post_field( 'post_content', $business_id ) ); ?>
            </div>
        </div>
        <?php do_action( 'bp_business_profile_after_about_section', $business_id ); ?>

        <!-- Category Section -->
        <?php if ( $category_name ) : ?>
            <?php do_action( 'bp_business_profile_before_category_section', $business_id ); ?>
            <div class="bp-business-info-widget-section">
                <h3 class="bp-business-screen-title"><?php esc_html_e( 'Category', 'bp-business-profile' ); ?></h3>
                <p><i class="fas fa-rectangle-list"></i><?php echo esc_html( $category_name ); ?></p>
            </div>
            <?php do_action( 'bp_business_profile_after_category_section', $business_id ); ?>
        <?php endif; ?>

        <!-- Contact Information -->
        <?php if ( $website || $phone || $email || $whatsapp || $address || apply_filters( 'bp_business_profile_contact_info', false ) ) : ?>
            <div class="bp-business-info-widget-section">
                <h3 class="bp-business-screen-title"><?php esc_html_e( 'Contact Information', 'bp-business-profile' ); ?></h3>
                <ul>
                    <?php do_action( 'bp_business_profile_before_render_contact_info', $business_id ); ?>
                    <?php if ( $email ) : ?>
                        <li class="bp-business-contact">
                            <a href="mailto:<?php echo esc_attr( $email ); ?>"><i class="fas fa-envelope"></i><?php echo esc_html( $email ); ?></a>
                        </li>
                    <?php endif; ?>
                    <?php if ( $website ) : ?>
                        <li class="bp-business-contact">
                            <a href="<?php echo esc_url( $website ); ?>"><i class="fas fa-globe"></i><?php echo esc_html( $website ); ?></a>
                        </li>
                    <?php endif; ?>
                    <?php if ( $phone ) : ?>
                        <li class="bp-business-contact">
                            <a href="tel:<?php echo esc_attr( $phone ); ?>"><i class="fas fa-phone-alt"></i><?php echo esc_html( $phone ); ?></a>
                        </li>
                    <?php endif; ?>
                    <?php if ( $whatsapp ) : ?>
                        <li class="bp-business-contact">
                            <a href="https://wa.me/<?php echo esc_attr( $whatsapp ); ?>"><i class="fab fa-whatsapp"></i><?php echo esc_html( $whatsapp ); ?></a>
                        </li>
                    <?php endif; ?>
                    <?php if ( $address ) : ?>
                        <li class="bp-business-contact">
                            <a target="_blank" href="https://www.google.com/maps/dir/?api=1&destination=<?php echo urlencode( $address ); ?>">
                                <i class="fas fa-map-marker-alt"></i><?php echo esc_html( $address ); ?>
                            </a>
                        </li>
                    <?php endif; ?>
                    <?php do_action( 'bp_business_profile_after_render_contact_info', $business_id ); ?>
                </ul>
            </div>
        <?php endif; ?>

       <!-- Work Section -->
		<?php if ( ! $works_empty ) : ?>
			<div class="bp-business-info-widget-section bp-business-work-section">
				<h3 class="bp-business-screen-title"><?php esc_html_e( 'Work', 'bp-business-profile' ); ?></h3>
				<?php foreach ( $works as $work ) : ?>
					<div class="bp-business-work-info">
						<p class="bp-business-work-title">
							<i class="fas fa-briefcase"></i>
							<strong class="bp-business-work-position"><?php echo esc_html( $work['position'] . ' at ' . $work['company_name'] ); ?></strong>
						</p>
						<p class="bp-business-work-details">
							<?php if ( ! empty( $work['from_year'] ) ) : ?>
								<span class="bp-business-work-year">
									<i class="fas fa-calendar-alt"></i>
									<?php echo esc_html( $work['from_year'] . ' - ' . ( $work['to_year'] ?? esc_html__( 'Present', 'bp-business-profile' ) ) ); ?>
								</span>
							<?php endif; ?>
							<?php if ( ! empty( $work['place'] ) ) : ?>
								<span class="bp-business-work-place">
									<i class="fas fa-map-marker-alt"></i>
									<?php echo esc_html( $work['place'] ); ?>
								</span>
							<?php endif; ?>
						</p>
						<?php if ( ! empty( $work['description'] ) ) : ?>
							<p class="bp-business-work-description"><?php echo esc_html( $work['description'] ); ?></p>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>

		<!-- Education Section -->
		<?php if ( ! $educations_empty ) : ?>
			<div class="bp-business-info-widget-section bp-business-education-section">
				<h3 class="bp-business-screen-title"><?php esc_html_e( 'Education', 'bp-business-profile' ); ?></h3>
				<?php foreach ( $educations as $education ) : ?>
					<div class="bp-business-education-info">
						<p class="bp-business-education-title">
							<i class="fas fa-graduation-cap"></i>
							<strong class="bp-business-education-degree"><?php echo esc_html( 'Studied ' . $education['gegree'] . ' at ' . $education['institute'] ); ?></strong>
						</p>
						<p class="bp-business-education-details">
							<?php if ( ! empty( $education['from_year'] ) ) : ?>
								<span class="bp-business-education-year">
									<i class="fas fa-calendar-alt"></i>
									<?php echo esc_html( $education['from_year'] . ' - ' . ( $education['to_year'] ?? esc_html__( 'Present', 'bp-business-profile' ) ) ); ?>
								</span>
							<?php endif; ?>
							<?php if ( ! empty( $education['place'] ) ) : ?>
								<span class="bp-business-education-place">
									<i class="fas fa-map-marker-alt"></i>
									<?php echo esc_html( $education['place'] ); ?>
								</span>
							<?php endif; ?>
						</p>
						<?php if ( ! empty( $education['description'] ) ) : ?>
							<p class="bp-business-education-description"><?php echo esc_html( $education['description'] ); ?></p>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>

    </div>

    <?php bp_business_profile_locate_template( 'single/business-right-sidebar.php', true ); ?>
</div>