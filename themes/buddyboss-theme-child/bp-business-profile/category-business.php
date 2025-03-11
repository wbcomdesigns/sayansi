<?php
get_header();
?>

<div id="primary" class="content-area bb-grid-cell">
	<main id="main" class="site-main">

<div id="bp-businesses-content" class="entry-content bp-businesses-content">
<div id="business-dir-list" class="business dir-list" data-bp-list="business">
<?php

if ( have_posts() ) :
	// Determine the view class based on the cookie.
    $view_class = isset( $_COOKIE['business_view'] ) && $_COOKIE['business_view'] === 'list' ? 'list' : 'grid';
    ?>
	<div id="business-list-container" class="business-list-container">
		<ul id="business-list" class="item-list business-list row <?php echo esc_attr( $view_class ); ?>">
			<?php
			$business_map_data = [];
			while ( have_posts() ) :
				the_post();
				bp_business_profile_locate_template( 'business.php', true );
				$business_id            = intval(get_the_ID());
				$_business_lat               = get_post_meta( $business_id, '_business_lat', true );
				$_business_long              = get_post_meta( $business_id, '_business_long', true );
				$_business_address           = get_post_meta( $business_id, '_business_address', true );
				if( !is_null( $_business_lat ) && $_business_lat != '' && !is_null( $_business_long ) && $_business_long != '' ) {
					$business_map_data['items'][get_the_ID()] = [
														'lat'		=> $_business_lat,
														'lng'		=> $_business_long,
														'position'	=> [ 'lat' => $_business_lat, 'lng' => $_business_long ],
														'address'	=> $_business_address,
														'title'		=> get_the_title(),
														'info'		=> '',
														'ID'		=> $business_id,
													];
				}
			endwhile;
			?>
		</ul>
		<?php if ( ! isset( $_REQUEST['args']['limit'] ) ) { ?>
			<div class="bp-business-pagination bp-business-navigation navigation pagination">
				<?php bp_business_profile_paginate(); ?>
			</div>
		<?php } ?>
	</div>
	<div id="business-map-container" class="business-map-container" style="display:none">
		<div id="bp-business-map" style="height: 500px; width: 100%;"></div>
	</div>

<?php else : ?>

	<?php bp_nouveau_user_feedback( 'businesses-loop-none' ); ?>

<?php endif; ?>
</div>
</div>
	</main><!-- #main -->
</div><!-- #primary -->

<?php get_sidebar( 'page' );?>

<?php get_footer(); ?>