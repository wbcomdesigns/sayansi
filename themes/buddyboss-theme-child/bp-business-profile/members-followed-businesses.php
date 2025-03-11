<?php
global $bp, $wpdb;
$table_name_members = $bp->table_prefix . 'bp_groups_members';

$business_ids = bp_business_profile_get_followed_business_ids();

$paged = ( get_query_var( 'paged' ) ) ? absint( get_query_var( 'paged' ) ) : 1;
$args = array(
	'post_type'      => 'business',
	'posts_per_page' => apply_filters( 'bp_business_profile_filter_per_page', 20 ),
	'post_status'    => 'publish',
	'order'          => 'DESC',
	'post__in'       => $business_ids,
	'paged'       	 => intval( $paged ),
	
);

$business_query = new WP_Query( apply_filters( 'bp_business_profile_personal_business', $args ) );
// The Loop.
if ( $business_query->have_posts() ) :

	// Determine the active view from the cookie.
	$active_view = isset( $_COOKIE['business_view'] ) && $_COOKIE['business_view'] === 'list' ? 'list' : 'grid';
	$view_class  = $active_view; // Use the same variable for view class.
	?>
	<div class="bp-business-profile-header">
		<div class="bp-business-profile-list-grid-filter-wrapper">
			<div class="bp-business-profile-list-grid-filter">
				<a href="#" id="toggle-view-grid" class="btn-grid <?php echo $active_view === 'grid' ? 'active' : ''; ?> bp-tooltip" data-view="grid" data-bp-tooltip-pos="up" data-bp-tooltip="<?php esc_attr_e( 'Grid View', 'bp-business-profile' ); ?>">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M3 4C3 3.44772 3.44772 3 4 3H10C10.5523 3 11 3.44772 11 4V10C11 10.5523 10.5523 11 10 11H4C3.44772 11 3 10.5523 3 10V4ZM3 14C3 13.4477 3.44772 13 4 13H10C10.5523 13 11 13.4477 11 14V20C11 20.5523 10.5523 21 10 21H4C3.44772 21 3 20.5523 3 20V14ZM13 4C13 3.44772 13.4477 3 14 3H20C20.5523 3 21 3.44772 21 4V10C21 10.5523 20.5523 11 20 11H14C13.4477 11 13 10.5523 13 10V4ZM13 14C13 13.4477 13.4477 13 14 13H20C20.5523 13 21 13.4477 21 14V20C21 20.5523 20.5523 21 20 21H14C13.4477 21 13 20.5523 13 20V14ZM15 5V9H19V5H15ZM15 15V19H19V15H15ZM5 5V9H9V5H5ZM5 15V19H9V15H5Z"></path></svg>
				</a>
				<a href="#" id="toggle-view-list" class="btn-list <?php echo $active_view === 'list' ? 'active' : ''; ?> bp-tooltip" data-view="list" data-bp-tooltip-pos="up" data-bp-tooltip="<?php esc_attr_e( 'List View', 'bp-business-profile' ); ?>">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M3 4H21V6H3V4ZM3 11H21V13H3V11ZM3 18H21V20H3V18Z"></path></svg>
				</a>
			</div>
		</div>
	</div>

	<ul id="business-list" class="item-list business-list row <?php echo esc_attr( $view_class ); ?>">
		<?php
		while ( $business_query->have_posts() ) :
			$business_query->the_post();

			$category      = get_the_terms( get_the_ID(), 'business-category' );
			$category_name = '';
			if ( ! empty( $category ) ) {
				$category_name = $category['0']->name;
			}
			$_business_avatar_image = get_post_meta( get_the_ID(), '_business_avatar_image', true );
			$_business_cover_image  = get_post_meta( get_the_ID(), '_business_cover_image', true );
			$average_rating         = round( bp_business_profile_get_average_rating_for_business( get_the_ID() ) );
			$stars_on               = $average_rating;
			$stars_off              = 5 - $stars_on;
			?>
			<li class="col col-md-4 col-sm-6 col-xs-12">
				<div class="bp-business-list-wrap">
					<a href="<?php the_permalink(); ?>" class="bp-business-list-inner-wrap">
						<div class="bp-business-cover-img">

							<?php if ( $_business_cover_image != '' ) : ?>

								<?php echo wp_get_attachment_image( $_business_cover_image, 'full' ); ?>

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
						<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
						<?php if ( $stars_on > 0 ) : ?>
							<div class="bp-business-rating">
								<span class="bp-business-rating-wrap">
									<?php for ( $i = 1; $i <= $stars_on; $i++ ) { ?>
										<span class="fas fa-star stars bp-business-stars bp-business-star-rate"></span>
										<?php
									}
									for ( $i = 1; $i <= $stars_off; $i++ ) {
										?>
										<span class="far fa-star stars bp-business-stars bp-business-star-rate"></span>
									<?php } ?>
								</span>
							</div>
						<?php endif; ?>

						<?php do_action( 'bp_business_before_profile_excerpt' ); ?>

						<?php if (  ! empty( get_field( "beam_line_excerpt" ) ) ) : ?>
							<div class="bp-business-profile-excerpt">
								<?php echo get_field( "beam_line_excerpt" ); ?>
							</div>
							<?php endif; ?>

						<?php do_action( 'bp_business_after_profile_excerpt' ); ?>

					</div>

					<?php do_action( 'bp_business_after_content_wrap' ); ?>

				</div>
			</li>
		<?php endwhile; ?>
	</ul>
	<div class="bp-business-navigation navigation pagination">
		<?php bp_business_user_profile_paginate( $business_query ); ?>
	</div>
	
<?php else : ?>

	<?php bp_nouveau_user_feedback( 'businesses-follow-none' ); ?>

	<?php
endif;

wp_reset_postdata();
