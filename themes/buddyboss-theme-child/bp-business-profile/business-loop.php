<?php
/**
 *This template override for add search, filter, layout
 * this template used under the network->all partners tab
 */
global $wp_query, $bP_business_settings, $business_map_data;
$pagged  = ( isset( $_REQUEST['page'] ) ) ? sanitize_text_field( $_REQUEST['page'] ) : 1; //phpcs:ignore
$args    = array(
				'post_type'      => 'business',
				'post_status' 	 => 'publish',
				'posts_per_page' => apply_filters( 'bp_business_profile_filter_per_page', 20 ),
				'paged'          => $pagged,
				'order'          => 'ASC',
				'orderby'        => 'title',
			);

$meta_query = array();

// Check if 'search_terms' is set and not empty
if ( isset( $_REQUEST['search_terms'] ) && ! empty( $_REQUEST['search_terms'] ) ) {
	// Sanitize the search term
	$args['s'] = sanitize_text_field( $_REQUEST['search_terms'] );
}

// Check if 'order' is set to 'title'
if ( isset( $_REQUEST['order'] ) && $_REQUEST['order'] === 'title' ) {
	$args['order']   = 'ASC';
	$args['orderby'] = 'title';
}

// Check if 'order' is set to 'date'
if ( isset( $_REQUEST['order'] ) && $_REQUEST['order'] === 'date' ) {
	$args['orderby'] = 'date';
	$args['order']   = 'DESC';
}

// Check if 'scope' is set to 'personal'
if ( isset( $_REQUEST['scope'] ) && $_REQUEST['scope'] === 'personal' ) {
	$args['author'] = get_current_user_id();
	unset($args['post_status']);
	if ( isset( $_REQUEST['order'] ) && $_REQUEST['order'] === 'pending' ) {
		$args['post_status'] = ['pending'];
		$args['orderby'] = 'date';
		$args['order']   = 'DESC';
	}
}


if ( isset( $_REQUEST['args']['ids'] ) && $_REQUEST['args']['ids'] != '' ) {
	$args['post__in'] = explode( ',', sanitize_text_field( $_REQUEST['args']['ids'] ) );
}

if ( isset( $_REQUEST['args']['category'] ) && $_REQUEST['args']['category'] != '' ) {
	$filter            = sanitize_text_field( $_REQUEST['args']['category'] );
	$args['tax_query'] = array(
		array(
			'taxonomy' => 'business-category',
			'field'    => 'term_id',
			'terms'    => explode( ',', $filter ),
			'operator' => 'IN',
		),
	);
}

if ( isset( $_REQUEST['filter'] ) && '0' !== $_REQUEST['filter'] && isset( $_REQUEST['type'] ) ) {
	// Sanitize the input
	$filter            = sanitize_text_field( $_REQUEST['filter'] );
	$type              = sanitize_text_field( $_REQUEST['type'] );
	$args['tax_query'] = array(
		array(
			'taxonomy' => $type,
			'field'    => 'term_id',
			'terms'    => $filter,
			'operator' => 'IN',
		),
	);
}

if ( isset( $_REQUEST['custom_filter'] ) && ! empty( $_REQUEST['custom_filter'] ) ) {

	$custom_filter = json_decode( sanitize_text_field( str_replace( '\"', '"', $_REQUEST['custom_filter'] ) ), true );
	if ( ! empty( $custom_filter ) ) {
		foreach ( $custom_filter as $custom_filter_key => $custom_filter_value ) {
			if ( $custom_filter_value != '' && $custom_filter_value != 0 ) {
				$args['tax_query'][] =
					array(
						'taxonomy' => $custom_filter_key,
						'field'    => 'term_id',
						'terms'    => $custom_filter_value,
						'operator' => 'IN',
					);
			}
		}
	}
}

if ( isset( $_REQUEST['args']['limit'] ) && $_REQUEST['args']['limit'] != '' ) {
	$args['offset']         = 0;
	$args['posts_per_page'] = sanitize_text_field( $_REQUEST['args']['limit'] );
}

if ( ( isset( $_REQUEST['args']['city'] ) && $_REQUEST['args']['city'] != '' )
	|| ( isset( $_REQUEST['business-city'] ) && $_REQUEST['business-city'] != '' ) ) {

	if ( isset( $_REQUEST['business-city'] ) ) {
		$city = sanitize_text_field( $_REQUEST['business-city'] );
	} else {
		$city = sanitize_text_field( $_REQUEST['args']['city'] );
	}
	$meta_query[] =
				array(
					'key'     => '_business_city',
					'value'   => $city,
					'compare' => 'like',
				);
}

if ( ( isset( $_REQUEST['args']['state'] ) && $_REQUEST['args']['state'] != '' )
	|| ( isset( $_REQUEST['business-state'] ) && $_REQUEST['business-state'] != '' ) ) {

	if ( isset( $_REQUEST['business-state'] ) ) {
		$state = sanitize_text_field( $_REQUEST['business-state'] );
	} else {
		$state = sanitize_text_field( $_REQUEST['args']['state'] );
	}
	$meta_query[] =
				array(
					'key'     => '_business_state',
					'value'   => $state,
					'compare' => 'like',
				);
}

if ( ( isset( $_REQUEST['args']['country'] ) && $_REQUEST['args']['country'] != '' )
		|| ( isset( $_REQUEST['business-country'] ) && $_REQUEST['business-country'] != '' ) ) {
	if ( isset( $_REQUEST['business-country'] ) ) {
		$country = sanitize_text_field( $_REQUEST['business-country'] );
	} else {
		$country = sanitize_text_field( $_REQUEST['args']['country'] );
	}
	$meta_query[] =
				array(
					'key'     => '_business_country',
					'value'   => $country,
					'compare' => 'like',
				);
}

if ( isset( $_REQUEST['rating'] ) && ! empty( $_REQUEST['rating'] ) ) {
	$meta_query[] = array(
							'key'     => '_bp_business_aggregate_rating',
							'value'   => sanitize_text_field( $_REQUEST['rating'] ),
							'compare' => '>=',
						);
	
}

if ( ( isset( $_REQUEST['search_location'] ) && $_REQUEST['search_location'] != '' ) ) {	
	$city = sanitize_text_field( $_REQUEST['search_location'] );	
	$meta_query[] = array(
							'key'     => '_business_city',
							'value'   => $city,
							'compare' => '=',
						);
}

/*
 * Add $meta_query variable into $args['meta_query']
 */
if ( ! empty( $meta_query ) ) {
    $args['meta_query'] = $meta_query;
}

if ( isset( $_REQUEST['lat'] ) && $_REQUEST['lat'] != '' && isset( $_REQUEST['long'] ) && $_REQUEST['long'] != ''  && isset( $_REQUEST['radius'] ) && $_REQUEST['radius'] != '') {
	$lat    = sanitize_text_field( $_REQUEST['lat'] );
    $long   = sanitize_text_field( $_REQUEST['long'] );
    $radius = sanitize_text_field( $_REQUEST['radius'] );	
	$posts_by_radius = bp_business_profile_directory_member_radial_distance( $lat, $long, $radius );
	if ( ! empty( $posts_by_radius ) ) {
		$args['post__in'] = $posts_by_radius;
	}
}

$wp_query = $business_query = new WP_Query( apply_filters( 'bp_business_profile_filter', $args ) );


// The Loop.
if ( $business_query->have_posts() ) :
	// Determine the view class based on the cookie.
    $view_class = isset( $_COOKIE['business_view'] ) && $_COOKIE['business_view'] === 'list' ? 'list' : 'grid';
    ?>

    <!-- remove unneccsary filter, which are not working -->
    <style>
        .bb-subnav-filters-container{
            display: none;
        }
        
    </style>
     <?php if( 'connections' == bp_current_component() ) { ?>
	    <!-- Add search, filter, layout -->
	    <div class="buddypress-wrap network-all-partners-wrap">
	        <div class="flex bp-secondary-header align-items-center">
				<div class="bp-business-all-partner-label">
					<h3> <?php echo esc_html( 'All Partners');?></h3>
				</div>
	            <div class="push-right flex"> 
	                <div class="bp-ind-members-filter-wrap subnav-filters">	
	                    <form action="" method="get" class="bp-dir-search-individual-members search-individual-members-has-reset" id="" autocomplete="off">
	                        <label for="network-all-partners-search" class="bp-screen-reader-text">Search Partners…</label>
	                        <input id="network-all-partners-search" name="network_all_partners_search" type="search" placeholder="Search Members..">                               
	                    </form>
	                </div>
	                        
	                <div id="network-all-partners-filters" class="foums-component-filters clearfix subnav-filters">
	                    <div id="network-all-partners-order-select" class="component-filters filter">
	                        <label class="bp-screen-reader-text" for="network-all-partners-order-by">
	                            <span>Order By:</span>
	                        </label>
	                        <div class="select-wrap">
	                            <select id="network-all-partners-order-by">
	                                <option value="alphabetical"><?php esc_html_e( 'Alphabetical', 'sayansi-core' ); ?></option>
	                                <option value="recent"><?php esc_html_e( 'Newly Created', 'sayansi-core' ); ?></option>
	                            </select>
	                            <span class="select-arrow" aria-hidden="true"></span>
	                        </div>
	                    </div>
	                </div>

	                <div class="grid-filters" data-object="<?php echo esc_attr( $component ); ?>">
	                    <a href="#" class="layout-view layout-grid-view bp-tooltip grid" data-view="grid" data-bp-tooltip-pos="up" data-bp-tooltip="<?php _e( 'Grid View', 'sayansi-core' ); ?>"> <i class="bb-icon-l bb-icon-grid-large" aria-hidden="true"></i> </a>

	                    <a href="#" class="layout-view layout-list-view bp-tooltip list" data-view="list" data-bp-tooltip-pos="up" data-bp-tooltip="<?php _e( 'List View', 'sayansi-core' );?>"> <i class="bb-icon-l bb-icon-bars" aria-hidden="true"></i> </a>
	                </div>

	            </div>
	        </div>
	    </div>
	    <!-- End Add search, filter, layout -->
    <?php } ?>

	<div id="business-list-container" class="business-list-container">
		<ul id="business-list" class="item-list business-list row <?php echo esc_attr( $view_class ); ?>">
			<?php
			$business_map_data = [];
			while ( $business_query->have_posts() ) :
				$business_query->the_post();
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
