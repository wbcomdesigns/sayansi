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

global $wp;

$review       = get_option( 'bp_business_profile_review' );
$review_text  = ( isset( $review['review_text'] ) ) ? $review['review_text'] : array();
$review_on    = ( isset( $review['review_on'] ) ) ? $review['review_on'] : array();
$review_limit = ( isset( $review['review_limit'] ) ) ? $review['review_limit'] : '1';
if ( empty( $review_text ) ) {
	$review_text = array( esc_html__( 'Overall Rating', 'bp-business-profile' ) );
	$review_on   = array( 'on' );
}
$reviews_slug= bp_business_profile_get_business_slug(). '-reviews';
$paged = get_query_var( $reviews_slug ) ? get_query_var( $reviews_slug ) : 1;


if ( strpos( $paged, 'comment-page' ) != false ) {
	$comment_page = explode( 'comment-page-', $paged );
	$paged        = $comment_page[1];
}

/*Get Current Page Var*/
$comments_per_page = get_option( 'comments_per_page' );

/*How many comments offset*/
$offset         = ( ( $paged - 1 ) * $comments_per_page );
$args           = array(
	'post_id'   => get_the_ID(), // use post_id, not post_ID
	'number'    => get_option( 'comments_per_page' ),
	'post_type' => array( 'business' ),
	'paged'     => $paged,
	'status'	=>'approve',
);
$comments_query = new WP_Comment_Query();
$comments       = $comments_query->query( $args );
$count    		= get_comments_number( get_the_ID() );
$counting 		= bp_business_profile_count_reviews( get_the_ID() );
?>
<div id="bp-profile-reviews-content" class="tabs bp-business-settings-content">
	
	<div class="bp-business-section-container">
	
		<div id="comments">
			<h3 class="bp-business-reviews-title">
				<?php
				//$count = $product->get_review_count();
				if ( $count ) {
					/* translators: 1: reviews count 2: product name */
					$reviews_title = sprintf( esc_html( _n( '%1$s review for %2$s', '%1$s reviews for %2$s', $count, 'bp-business-profile' ) ), esc_html( $count ), '<span>' . get_the_title() . '</span>' );
					echo apply_filters( 'bp_business_reviews_title', $reviews_title, $count ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				} else {
					esc_html_e( 'Reviews', 'bp-business-profile' );
				}
				?>
			</h3>

			<?php if ( $comments ) : ?>
				<ol class="commentlist">				
					<?php
					foreach ( $comments as $comment ) :
						$_bp_business_rated_stars = get_comment_meta( $comment->comment_ID, '_bp_business_rated_stars', true );
						$rate_count               = 0;
						if ( ! empty( $_bp_business_rated_stars ) ) {
							$noofcount = 0;
							foreach ( $_bp_business_rated_stars as $rated_star ) {
								$rate_count += $rated_star;
								$noofcount++;
							}
							$rate_count = $rate_count / $noofcount;
						}
						?>
					<li class="<?php echo esc_attr( join( ' ', get_comment_class( '', $comment->comment_ID ) ) ); ?>" id="li-comment-<?php echo esc_attr( $comment->comment_ID ); ?>">
						<div id="comment-<?php echo esc_attr( $comment->comment_ID ); ?>" class="comment_container">
							<?php
							if ( $comment->user_id ) {
								// phpcs:ignore WordPress.Security.EscapeOutput
								echo bp_displayed_user_avatar(
									array(
										'item_id' => $comment->user_id,
									)
								);
							} else {
								// Display Gravatar for a guest user.
								echo get_avatar( $comment->comment_author_email, 50 );
							}
							?>
							<div class="comment-text">		
								<?php
								if ( '0' === $comment->comment_approved ) {
									?>
									<p class="meta">
										<em class="bp_business_profile_review__awaiting-approval">
											<?php esc_html_e( 'Your review is awaiting approval', 'bp-business-profile' ); ?>
										</em>
									</p>
								<?php } else { ?>
									<p class="meta">
										<strong class="bp_business_profile_review__author"><?php comment_author( $comment ); ?> </strong>
										<span class="bp_business_profile_review__dash">&ndash;</span> <time class="bp_business_profile_review__published-date" datetime="<?php echo esc_attr( get_comment_date( 'c', $comment ) ); ?>"><?php echo esc_html( get_comment_date( '', $comment ) ); ?></time>
									</p>
									<?php
								}
								echo '<div class="description">'. comment_text( $comment ) .'</div>';// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								?>
								<div class="review-ratings">
									<?php
									if ( ! empty( $_bp_business_rated_stars ) ) :
										foreach ( $_bp_business_rated_stars as $review_key => $review_field ) {
											$stars_on  = $review_field;
											$stars_off = 5 - $stars_on;
											?>
											<div class="multi-review">
												<div class="bp-business-criteria-title">
													<?php echo esc_html( $review_key ) . ': '; ?>
												</div>
												<div class="bp-business-review">
												<?php for ( $i = 1; $i <= $stars_on; $i++ ) { ?>
														<span class="fas fa-star stars bp-business-stars bp-business-star-rate"></span>
														<?php
												}
												for ( $i = 1; $i <= $stars_off; $i++ ) {
													?>
														<span class="far fa-star stars bp-business-stars bp-business-star-rate"></span>
													<?php } ?>
												</div>
											</div>
											<?php
										}
										endif;
									?>
								</div>								
							</div>
						</div>
					</li>
					<?php endforeach; ?>
				</ol>

				<?php
				if ( get_comments_number( get_the_ID() ) > 1 && get_option( 'page_comments' ) ) :
					echo '<nav class="bp-business-review-pagination">';
					bp_business_review_paginate_comments_links(
						apply_filters(
							'bp_business_review_pagination_args',
							array(
								'prev_text' => is_rtl() ? '&rarr;' : '&larr;',
								'next_text' => is_rtl() ? '&larr;' : '&rarr;',
								'type'      => 'list',
							)
						),
						$comments
					);
					echo '</nav>';
				endif;
				?>
			<?php else : ?>
				<p class="bp-business-noreviews"><?php esc_html_e( 'There are no reviews yet.', 'bp-business-profile' ); ?></p>
			<?php endif; ?>
		</div>
		
		<?php if ( ! is_user_logged_in() || ( is_user_logged_in() && $counting->total_comments < $review_limit ) ) : ?>
			<div id="bp-business-review-form-wrapper">
				<h4>
					<?php esc_html_e( 'Write a Review', 'bp-business-profile' ); ?>
				</h4>
				
				<form id="bp-business-add-review-form" method="POST">
					<textarea class="review_desc" name="review-desc" placeholder="<?php echo esc_attr__( 'Review Description', 'bp-business-profile' ); ?>" rows="3" cols="50"></textarea>
					
					<?php if( ! is_user_logged_in() ) :?>
						<input type="text" name="reviewer-name" placeholder="<?php echo esc_attr__('Name', 'bp-business-profile' )?>" class="bp-bussiness-profile-input" required/>						
						<input type="email" name="reviewer-email" placeholder="<?php echo esc_attr__('Email', 'bp-business-profile' )?>" class="bp-bussiness-profile-input" required/>
					
					<?php endif; ?>
					<div class="bp-business-error-fields">*<?php esc_html_e( 'This field is required.', 'bp-business-profile' ); ?></div>
					
					<?php
					if ( ! empty( $review_text ) ) {
						$field_counter = 1;
						foreach ( $review_text as $key => $text ) :
							if ( isset( $review_on[ $key ] ) && $review_on[ $key ] === 'on' ) {
								?>
									<div class="multi-review">
										<div class="bp-business-criteria-title"><?php echo esc_html( $text ); ?></div>
										<div id="review<?php echo esc_html( $field_counter ); ?>" class="bp-business-review">
											<input type="hidden" id="<?php echo 'clicked' . esc_html( $field_counter ); ?>" value="not_clicked">
											<input type="hidden" name="rated_stars[<?php echo esc_html( $text ); ?>]" class="rated_stars bgr_mrating" id="<?php echo 'rated_stars' . esc_html( $field_counter ); ?>" value="0">
										<?php for ( $i = 1; $i <= 5; $i++ ) { ?>
													<span class="far fa-star bp-business-stars bp-business-star-rate <?php echo esc_attr( $i ); ?>" id="<?php echo esc_attr( $field_counter ) . esc_attr( $i ); ?>" data-attr="<?php echo esc_attr( $i ); ?>" ></span>
												<?php } ?>
										</div>
										<div class="bp-business-error-fields">*<?php esc_html_e( 'This field is required.', 'bp-business-profile' ); ?></div>
									</div>
									<?php
									$field_counter++;
							}
							endforeach;
						?>
								<input type="hidden" id="rating_field_counter" value="<?php echo esc_html( --$field_counter ); ?>">
							<?php
					}
					?>
					<p>
						<button class="btn btn-default bgr-submit-review" name="bp-business-submit-review"><?php echo esc_html__( 'Submit review', 'bp-business-profile' ); ?></button>
					</p>
					<?php wp_nonce_field( 'bp-business-review-save' ); ?>
				</form>
			</div>
		<?php endif; ?>
	</div>
	
	<?php bp_business_profile_locate_template( 'single/business-right-sidebar.php', true ); ?>	
</div>
