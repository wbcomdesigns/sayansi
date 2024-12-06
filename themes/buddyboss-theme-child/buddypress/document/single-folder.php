<?php
/**
 * The template for document single folder
 *
 * This template can be overridden by copying it to yourtheme/buddypress/document/single-folder.php.
 *
 * @since   BuddyBoss 1.4.0
 * @package BuddyBoss\Core
 * @version 1.4.0
 */

$is_send_ajax_request = bb_is_send_ajax_request();

global $document_folder_template;
if ( function_exists( 'bp_is_group_single' ) && bp_is_group_single() && bp_is_group_folders() ) {
	$folder_id = (int) bp_action_variable( 1 );
} else {
	$folder_id = (int) bp_action_variable( 0 );
}

$folder_privacy = bb_media_user_can_access( $folder_id, 'folder' );
$can_edit_btn   = true === (bool) $folder_privacy['can_edit'];
$can_add_btn    = true === (bool) $folder_privacy['can_add'];
$can_delete_btn = true === (bool) $folder_privacy['can_delete'];

$bp_is_group = bp_is_group();

$bradcrumbs = bp_document_folder_bradcrumb( $folder_id );
if ( bp_has_folders( array( 'include' => $folder_id ) ) ) :
	while ( bp_folder() ) :
		bp_the_folder();

		$total_media = $document_folder_template->folder->document['total'];
		?>
		<div id="bp-media-single-folder">
			<div class="album-single-view" <?php echo 0 === $total_media ? esc_attr( 'no-photos' ) : ''; ?>>
				<div class="bp-media-header-wrap">
					<div class="bp-media-header-wrap-inner">
						<div class="bb-single-album-header text-center">
							<h4 class="bb-title" id="bp-single-album-title"><?php bp_folder_title(); ?></h4>
						</div> <!-- .bb-single-album-header -->
					</div>
					<?php
					if ( '' !== $bradcrumbs ) {
						echo wp_kses_post( $bradcrumbs );
					}
					?>
				</div> <!-- .bp-media-header-wrap test -->
				<!-- <div id="media-stream" class="media" data-bp-list="document" data-ajax="<?php echo esc_attr( $is_send_ajax_request ? 'true' : 'false' ); ?>"> -->
					<?php
					// if ( $is_send_ajax_request ) {
					// 	echo '<div id="bp-ajax-loader">';
					// 	bp_nouveau_user_feedback( 'member-document-loading' );
					// 	echo '</div>';
					// } else {
					// 	bp_get_template_part( 'document/document-loop' );
					// }
					?>
				<!-- </div> -->

				<!-- tab section -->
				 <?php
					if ( function_exists( 'buddypress' ) && isset( buddypress()->buddyboss ) && bp_is_active( 'media' ) ) {					
						$folder_link = bp_get_folder_folder_link();
						$folder_id   = bp_get_folder_folder_id();
						$document_folder_privacy = bp_get_document_folder_privacy();												
						$endpoint = isset( $_GET['tab'] ) ? $_GET['tab'] : '';													
						?>
						<nav class="bp-navs business-subnavs no-ajax business-subnav business-subnav-plain" id="subnav" role="navigation" aria-label="<?php esc_html_e( 'Business submenu', 'bp-business-profile' ); ?>">
							<ul class="subnav">
									<li class="bp-business-tab bp-business-navigation-link bp-business-navigation-link--audio <?php echo 'audio' === $endpoint ? 'current selected' : ''; ?>">
										<a href="<?php echo esc_url( $folder_link . '?tab=audio' ); ?>" id="edit-details"><?php esc_html_e( 'Audio', 'bp-business-profile' ); ?></a>
									</li>
									<li class="bp-business-tab bp-business-navigation-link bp-business-navigation-link--videos <?php echo 'videos' === $endpoint ? 'current selected' : ''; ?>">
										<a href="<?php echo esc_url( $folder_link . '?tab=videos' ); ?>" id="edit-details"><?php esc_html_e( 'Videos', 'bp-business-profile' ); ?></a>
									</li>
									<li class="bp-business-tab bp-business-navigation-link bp-business-navigation-link--photos <?php echo 'photos' === $endpoint ? 'current selected' : ''; ?>">
										<a href="<?php echo esc_url( $folder_link . '?tab=photos' ); ?>" id="edit-details"><?php esc_html_e( 'Photos', 'bp-business-profile' ); ?></a>
									</li>
									<li class="bp-business-tab bp-business-navigation-link bp-business-navigation-link--documents <?php echo 'document' === $endpoint ? 'current selected' : ''; ?>">
										<a href="<?php echo esc_url( $folder_link . '?tab=document' ); ?>" id="edit-details"><?php esc_html_e( 'Documents', 'bp-business-profile' ); ?></a>
									</li>
								<?php do_action( 'bp_business_profile_media_subnav' ); ?>

							</ul>
						</nav>
				<?php } ?>

				<?php
			switch ( $endpoint ) {
				case 'audio':					
					include get_stylesheet_directory() . '/documents/audio.php';
					break;
				case 'videos':
					include get_stylesheet_directory() . '/documents/videos.php';
					break;
				case 'photos':
					include get_stylesheet_directory() . '/documents/photos.php';
					break;				
				case 'document':
					include get_stylesheet_directory() . '/documents/documents.php';
					break;
				default:
					include get_stylesheet_directory() . '/documents/audio.php';
					break;
			}
			?>
				<!-- tab section end  -->


			</div>
		</div>
		<?php
	endwhile;
endif;
