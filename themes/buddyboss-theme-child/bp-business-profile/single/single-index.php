<?php

$business_id = get_the_ID();
$tabs_items  = get_post_meta( $business_id, '_business_tabs_items', true );
$author_id   = (int) get_post_field( 'post_author', $business_id );
if ( $tabs_items === '' ) {
	$tabs_items = array();
}


?>

<div id="item-header" role="complementary" data-bp-item-id="<?php the_ID(); ?>" data-bp-item-component="business" class="business-header single-headers">
	
	<?php do_action( 'bp_business_profile_before_profile_header_part' ); ?>
	
	<?php bp_business_profile_header_template_part(); ?>
	
	<?php do_action( 'bp_business_profile_after_profile_header_part' ); ?>

</div><!-- #item-header -->

<div class="container">
	<div class="bp-wrap">
		<nav class="main-navs no-ajax bp-navs single-screen-navs business-main-nav" id="bp-business-main-object-nav" role="navigation" aria-label="Business menu">
			<ul>
				
				
				<?php
				foreach ( get_business_profile_get_business_menu_items() as $endpoint => $label ) :

					$current_user_id = get_current_user_id();

					// Check if current user is the post owner or an administrator
					$is_post_owner = ( $author_id === $current_user_id );
					$is_admin      = user_can( $current_user_id, 'administrator' );
					
					if ( ! $is_post_owner && ! $is_admin ) {
						if ( ! empty( $tabs_items ) && ! in_array( $endpoint, $tabs_items ) ) {
							continue;
						}
					}
					$endpoint_class = $endpoint;
					$reviews_slug	= bp_business_profile_get_business_slug(). '-reviews';
					$settings_slug 	= bp_business_profile_get_business_slug(). '-settings';
					if( $endpoint_class == $reviews_slug) {
						$endpoint_class = 'business-reviews';
					}
					if( $endpoint_class == $settings_slug) {
						$endpoint_class = 'business-settings';
					}

					?>
					<li class="<?php echo esc_attr( bp_business_get_business_menu_item_classes( $endpoint, $endpoint_class ) ); ?>">
						<a href="<?php echo esc_url( bp_business_single_endpoint_url( $endpoint ) ); ?>"><?php echo esc_html( $label ); ?></a>
					</li>
				<?php endforeach; ?>
				
				<?php do_action( 'bp_business_profile_after_business_menu_items' ); ?>
				
			</ul>
		</nav>

		<div id="item-body" class="item-body">
			<?php
				/**
				 * Business Page content.
				 *
				 * @since 1.0.0
				 */
				do_action( 'bp_business_page_content' );
			?>
		</div><!-- #item-body -->

	</div><!-- // .bp-wrap -->
</div><!-- // .container -->
