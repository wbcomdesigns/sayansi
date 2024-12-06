<?php $is_send_ajax_request = bb_is_send_ajax_request(); ?>

<div class="bb-media-container member-media">
	<?php
	bp_get_template_part( 'document/theatre' );
	bp_get_template_part( 'video/theatre' );
	bp_get_template_part( 'media/theatre' );
	bp_get_template_part( 'video/add-video-thumbnail' );

	?>
	<div class="bp-document-listing">
		<div class="bp-media-header-wrap">
			<h2 class="bb-title"><?php esc_html_e( 'Audio', 'buddyboss' ); ?></h2>
			<?php
			bp_get_template_part( 'document/add-document' );
			?>
			<div id="search-documents-form" class="media-search-form" data-bp-search="document">
				<form action="" method="get" class="bp-dir-search-form search-form-has-reset" id="group-document-search-form" autocomplete="off">
					<button type="submit" id="group-document-search-submit" class="nouveau-search-submit search-form_submit" name="group_document_search_submit">
						<span class="dashicons dashicons-search" aria-hidden="true"></span>
						<span id="button-text" class="bp-screen-reader-text"><?php esc_html_e( 'Search', 'buddyboss' ); ?></span>
					</button>
					<label for="group-document-search" class="bp-screen-reader-text"><?php esc_html_e( 'Search Audio...', 'buddyboss' ); ?></label>
					<input id="group-document-search" name="document_search" type="search" placeholder="<?php esc_attr_e( 'Search Audio...', 'buddyboss' ); ?>">
					<button type="reset" class="search-form_reset">
						<span class="bb-icon-rf bb-icon-times" aria-hidden="true"></span>
						<span class="bp-screen-reader-text"><?php esc_html_e( 'Reset', 'buddyboss' ); ?></span>
					</button>
				</form>
			</div>

		</div>
	</div><!-- .bp-document-listing -->
	<div id="media-stream" class="media" data-bp-list="document" data-ajax="<?php echo esc_attr( $is_send_ajax_request ? 'true' : 'false' ); ?>">
		<?php
		if ( $is_send_ajax_request ) {
			echo '<div id="bp-ajax-loader">';
			bp_nouveau_user_feedback( 'member-document-loading' );
			echo '</div>';
		} else {
			bp_get_template_part( 'document/document-loop' );
		}
		?>
	</div><!-- .media -->
</div>
