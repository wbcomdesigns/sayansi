<?php
$business_link    = esc_url( get_post_type_archive_link( 'business' ) );
$general_settings = get_option( 'bp_business_profile_general_settings' );
if ( isset( $general_settings['business_page'] ) && $general_settings['business_page'] != '' ) {
	$business_link = esc_url( get_permalink( $general_settings['business_page'] ) );
}
?>

<div class="buddypress-wrap">
	<div id="bp-businesses-content" class="entry-content bp-businesses-content">		
		
		<?php if( is_user_logged_in() ) : ?>
			 <?php if ( current_user_can( 'administrator' ) ) : ?>
			<form action="" method="post" id="create-business-form" class="standard-form" enctype="multipart/form-data">
				
				<?php bp_nouveau_template_notices(); ?>
				
				<div class="item-body" id="business-create-body">

					<nav class="<?php bp_nouveau_groups_create_steps_classes(); ?>" id="business-create-tabs" role="navigation" aria-label="<?php esc_attr_e( 'business creation menu', 'bp-business-profile' ); ?>">
						<ol class="business-create-buttons button-tabs">

							<?php bp_business_profile_creation_tabs(); ?>

						</ol>
					</nav>

					<?php bp_business_profile_creation_screen(); ?>

				</div><!-- .item-body -->
			
			</form><!-- #create-business-form -->
			<?php else : ?>

        <div class="bp-template-notice error">
            <?php esc_html_e( 'You do not have permission to access this form.', 'bp-business-profile' ); ?>
        </div>

   	 <?php endif; ?>
		<?php else: ?>
			<div class="bp-businesses-logut-message"><?php esc_html_e( 'Please login to the site to create a business.', 'bp-business-profile' ); ?></div>
		<?php endif; ?>
	</div> <!-- Entry Content finish -->

</div>

