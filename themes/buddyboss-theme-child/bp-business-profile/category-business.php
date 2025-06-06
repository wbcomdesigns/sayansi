<?php
get_header();
global $bp_business_settings, $post;

$general_settings = isset( $bp_business_settings['general_settings'] ) ? $bp_business_settings['general_settings'] : array();
$plural_label     = ( isset( $general_settings['plural_label'] ) ) ? $general_settings['plural_label'] : 'Businesses';
$business_slug     = ( isset( $general_settings['business_slug'] ) ) ? $general_settings['business_slug'] : 'business';
?>

<div id="primary" class="content-area bb-grid-cell">
	<?php	
	// Get the current category object
	$current_cat = get_queried_object();
	if ( $current_cat && !is_wp_error($current_cat) ) {
		$post_type = $post->post_type; // Change this to your actual post type if different
		$post_type_obj = get_post_type_object($post_type);	
		echo '<nav class="breadcrumb">';
		if ( $post_type_obj ) {			
			$post_type_link = site_url() . '/' . $business_slug;			
			echo '<a href="' . esc_url($post_type_link) . '">' . esc_html($post_type_obj->labels->singular_name) . '</a> &raquo; ';
		}
		// Check for parent term
		if ($current_cat->parent) {
			$parent_cat = get_term($current_cat->parent, 'business-category');			
			if ( !is_wp_error( $parent_cat ) ) {
				echo '<a href="' . esc_url( get_term_link( $parent_cat ) ) . '">' . esc_html( $parent_cat->name ) . '</a> &raquo; ';
			}
		}		
		echo '<strong>' . esc_html($current_cat->name) . '</strong>';
		echo '</nav>';
	}
	?>
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
				<?php //bp_business_profile_paginate(); ?>
				<?php
					global $wp_query;
					$total = $wp_query->max_num_pages;
					if ( $total > 1 ) {
						$current_page = max( 1, get_query_var( 'paged' ) );
						// Get current URL without query parameters
						$current_url = get_pagenum_link( 1 );
						$format      = 'page/%#%/';
						echo wp_kses_post(
							paginate_links(
								array(
									'base'      => trailingslashit( $current_url ) . $format,
									'format'    => $format,
									'current'   => $current_page,
									'total'     => $total,
									'end_size'  => 1,
									'mid_size'  => 2,
									'prev_next' => true,
									'type'      => 'plain',
								)
							)
						);
					}
				?>
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