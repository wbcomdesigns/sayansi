<?php
global $bp_business_settings;

$business_link = get_post_type_archive_link( 'business' );

if ( isset( $bp_business_settings['general_settings']['business_page'] ) && $bp_business_settings['general_settings']['business_page'] != '' ) {
	$business_link = get_permalink( $bp_business_settings['general_settings']['business_page'] ) . 'create-business-page/';
}
if ( isset( $bp_business_settings['general_settings']['create_business_page'] ) && $bp_business_settings['general_settings']['create_business_page'] != '' ) {
	$business_link = get_permalink( $bp_business_settings['general_settings']['create_business_page'] );
}
$orderby          = ( isset( $bp_business_settings['general_settings']['orderby'] ) ) ? $bp_business_settings['general_settings']['orderby'] : 'date';
$order            = ( $orderby === 'title' ) ? 'ASC' : 'DESC';
$general_settings = isset( $bp_business_settings['general_settings'] ) ? $bp_business_settings['general_settings'] : array();
$singular_label   = ( isset( $general_settings['singular_label'] ) ) ? $general_settings['singular_label'] : 'Business';
$plural_label     = ( isset( $general_settings['plural_label'] ) ) ? $general_settings['plural_label'] : 'Businesses';
$dir_filter       = ( isset( $general_settings['dir_filter'] ) ) ? $general_settings['dir_filter'] : '';
$filter           = ( isset( $general_settings['filter'] ) ) ? $general_settings['filter'] : '';
$taxonomy_filter  = isset( $bp_business_settings['taxonomy_filter'] ) ? $bp_business_settings['taxonomy_filter'] : array();
$is_map_api       = ! empty( $bp_business_settings['map_settings']['map_api_key'] ) ? true : false;

?>

<div class="buddypress-wrap">
	<div id="bp-businesses-content" class="entry-content bp-businesses-content">
		<nav class="bp-business-profile-type-navs main-navs bp-navs dir-navs bp-subnavs business-main-nav" role="navigation" aria-label="Directory menu">
			<ul class="component-navigation business-nav">
			
				<li id="bp-business-profile-bp-index-all" class="bp-business-profile-index-scope-link selected" data-bp-scope="all">
					<a data-scope="all" href="">
						<?php 
						/* translators: %s is the name of business name */
						printf( esc_html__( 'All %s', 'bp-business-profile' ), esc_html( $plural_label ) ); ?>
					</a>
				</li>
				
				<?php if ( is_user_logged_in() ) : ?>
				<li id="bp-business-profile-bp-index-personal" class="bp-business-profile-index-scope-link" data-bp-scope="personal">
					<a data-scope="personal" href="">
					<?php 
					/* translators: %s is the name of business name */
					printf( esc_html__( 'My %s', 'bp-business-profile' ), esc_html( $plural_label ) ); 
					?>
					</a>
				</li>
					<?php if ( bp_business_profile_can_create_business( wp_get_current_user()->ID ) ) : ?>
					<li id="bp-business-profile-create-business-link" class="no-ajax business-create create-button">
						<a href="<?php echo esc_url( $business_link ); ?>">
							<?php 
							/* translators: %s is the name of business name */
							printf( esc_html__( 'Create a %s', 'bp-business-profile' ), esc_html( $singular_label ) ); 
							?>
						</a>
					</li>
					<?php endif; ?>
				<?php endif; ?>
			</ul>
		</nav>
		
		
		<div class="subnav-filters filters no-ajax bp-business-main-search-filter-wrapper" id="subnav-filters">
				<div class="subnav-search clearfix">
					<div class="dir-search business-search bp-search" data-bp-search="business">
						<form action="" method="get" class="bp-dir-search-form" id="dir-business-search-form" role="search">
							<label for="dir-business-search" class="bp-screen-reader-text">
								<?php 
								/* translators: %s is the name of business name */
								printf( esc_html__( 'Search %s...', 'bp-business-profile' ), lcfirst( esc_html( $singular_label ) ) ); //phpcs:ignore?>
							</label>

							<input id="dir-business-search" name="business_search" type="search" placeholder="<?php printf( esc_html__( 'Search', 'bp-business-profile' ) . ' %s...', 'by keyword' ); //phpcs:ignore?>">

							<button type="submit" id="dir-business-search-submit" class="nouveau-search-submit" name="dir_business_search_submit">
								<span class="dashicons dashicons-search" aria-hidden="true"></span>
								<span id="button-text" class="bp-screen-reader-text"><?php esc_html_e( 'Search', 'bp-business-profile' ); ?></span>
							</button>
							<?php wp_nonce_field( 'search_business_action', 'search_business_nonce' ); ?>
							<!-- Other form fields -->
	
						</form>

						<?php if ( $is_map_api ) { ?>
						<div class="bp-business-profile-location-filter">
							<input type="search" class="business-location-search" placeholder="<?php esc_attr_e( 'Search by location...', 'bp-business-profile' ); ?>">
							<span class="clear-search" aria-label="Clear search field"><svg width="40px" height="40px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M16 8L8 16M8.00001 8L16 16" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
							<input type="hidden" id="business-location-latitude" name="business_location_latitude">
							<input type="hidden" id="business-location-longitude" name="business_location_longitude">
						</div>
							<?php
							$radius_val    = apply_filters( 'business_profile_custom_radius', array( '5', '25', '50', '100', '150', '200' ) );
							$default_value = '50';
							?>
						
						<select class="bp-business-profile-radius-filter">							
							<?php foreach ( $radius_val as $value ) { ?>
								<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $value, $default_value ); ?>><?php printf( '%s Miles', esc_html( $value) ); ?></option>
							<?php } ?>
						</select>
						<?php } else { ?>
							<div class="bp-business-profile-filter-location">
								<input type="search" class="business-location-search" placeholder="<?php esc_attr_e( 'Search by location...', 'bp-business-profile' ); ?>">
								<span class="clear-search" aria-label="Clear search field"><svg width="40px" height="40px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M16 8L8 16M8.00001 8L16 16" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
							</div>
						<?php } ?>

						<?php if ( isset( $filter ) && 'yes' === $filter ) : ?>
							<?php do_action( 'bp_business_before_business_filters' ); ?>
							<?php
							$filter_drop_down = wp_dropdown_categories(
								array(
									'taxonomy'        => 'business-category',
									'hierarchical'    => 1,
									'hide_empty'      => false,
									'class'           => 'business-filter',
									'show_option_all' => __( 'Select Category', 'bp-business-profile' ),
									'name'            => 'business-category-filter',
									'echo'            => false,
								)
							);
							$filter_drop_down = str_replace( '<select', '<select data-type="business-category"', $filter_drop_down );
							echo $filter_drop_down;// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

							do_action( 'bp_business_after_business_filters' );
							?>
						<?php endif; ?>
					</div>
				</div>

			<div class="bp-business-profile-filter-wrapper">
				<div class="bp-business-profile-filter-toggle">
					<a href="#" class="bp-business-profile-filter"><i class="fa fa-filter" aria-hidden="true"></i></a>
				</div>
			</div>
			<div class="bp-business-profile-filter-dropdown">
				<div class="bp-business-profile-filter-inner">
					<span class="dropdown-close"><i class="fa fa-times"></i></span>
					<div class="bp-business-profile-rating-filter" style="display: none;">
						<label for="rating-filter"><?php esc_html_e( 'Filter by Rating', 'bp-business-profile' ); ?></label>
						<select name="business-rating-filter" id="business-rating-filter" class="business-filter">
							<option value=""><?php esc_html_e( 'All Ratings', 'bp-business-profile' ); ?></option>
							<option value="5"><?php esc_html_e( '5 Stars', 'bp-business-profile' ); ?></option>
							<option value="4"><?php esc_html_e( '4 Stars & Up', 'bp-business-profile' ); ?></option>
							<option value="3"><?php esc_html_e( '3 Stars & Up', 'bp-business-profile' ); ?></option>
							<option value="2"><?php esc_html_e( '2 Stars & Up', 'bp-business-profile' ); ?></option>
							<option value="1"><?php esc_html_e( '1 Star & Up', 'bp-business-profile' ); ?></option>
						</select>
						<input type="hidden" id="business-rating-val"/>
					</div>
					<div class="bp-business-profile-extra-taxonomies">						
					<?php
					$business_taxonomies = get_object_taxonomies( 'business' );

					if ( ( $key = array_search( 'business-category', $business_taxonomies ) ) !== false ) {
						unset( $business_taxonomies[ $key ] );
					}

					if ( ! empty( $business_taxonomies ) ) :
						foreach ( $business_taxonomies as $business_taxonomy ) :

							if ( ! empty( $taxonomy_filter ) && isset( $taxonomy_filter[ $business_taxonomy ] ) && $taxonomy_filter[ $business_taxonomy ] == 'yes' ) {

								$business_custom_taxonomy =  get_taxonomy( $business_taxonomy );
								// Output the label.
								echo '<label for="business-custom-category-filter-' . esc_attr( $business_taxonomy ) . '">';
								/* translators: %s is the business taxonomy name */
								printf( esc_html__( 'Filter by %s', 'bp-business-profile' ), esc_html( $business_custom_taxonomy->label ) );
								echo '</label>';
								$filter_drop_down = wp_dropdown_categories(
									array(
										'taxonomy'        => $business_taxonomy,
										'hierarchical'    => 1,
										'hide_empty'      => false,
										'class'           => 'business-custom-category-filter',
										'show_option_all' => __( 'Select', 'bp-business-profile' ),
										'name'            => 'business-custom-category-filter[' . $business_taxonomy . ']',
										'echo'            => false,
									)
								);
								$filter_drop_down = str_replace( '<select', '<select data-type="' . $business_taxonomy . '"', $filter_drop_down );
								echo $filter_drop_down; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							}
						endforeach;
					endif;
					?>
					</div>
				</div>
			</div>
			
			<div id="comp-filters" class="component-filters clearfix">
				<div id="business-order-select" class="last filter">
					<label for="business-order-by" class="bp-screen-reader-text">
						<span><?php esc_html_e( 'Order By:', 'bp-business-profile' ); ?></span>
					</label>
					<div class="select-wrap">
						<select id="business-order-by" data-bp-filter="business">								
							<option value="date" <?php selected( $orderby, 'date' ); ?>><?php esc_html_e( 'Newly Created', 'bp-business-profile' ); ?></option>
							<option value="title" <?php selected( $orderby, 'title' ); ?>><?php esc_html_e( 'Alphabetical', 'bp-business-profile' ); ?></option>
							<option value="followed" <?php selected( $orderby, 'followed' ); ?>>
								<?php 
								/* translators: %s is the business name */
								printf( esc_html__( 'Followed %s', 'bp-business-profile' ), esc_html( $plural_label ) ); 
								?>
							</option>
						</select>
						<span class="select-arrow" aria-hidden="true"></span>
					</div>
				</div>
			</div>
			<?php
			// Determine the active view from the cookie.
			$active_view = isset( $_COOKIE['business_view'] ) && sanitize_text_field( $_COOKIE['business_view'] ) === 'list' ? 'list' : 'grid';
			?>

			<div class="bp-business-profile-list-grid-filter">
				<a href="#" id="toggle-view-grid" class="btn-grid <?php echo esc_attr( $active_view === 'grid' ? 'active' : '' ); ?> bp-tooltip" data-view="grid" data-bp-tooltip-pos="up" data-bp-tooltip="<?php esc_attr_e( 'Grid View', 'bp-business-profile' ); ?>">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M3 4C3 3.44772 3.44772 3 4 3H10C10.5523 3 11 3.44772 11 4V10C11 10.5523 10.5523 11 10 11H4C3.44772 11 3 10.5523 3 10V4ZM3 14C3 13.4477 3.44772 13 4 13H10C10.5523 13 11 13.4477 11 14V20C11 20.5523 10.5523 21 10 21H4C3.44772 21 3 20.5523 3 20V14ZM13 4C13 3.44772 13.4477 3 14 3H20C20.5523 3 21 3.44772 21 4V10C21 10.5523 20.5523 11 20 11H14C13.4477 11 13 10.5523 13 10V4ZM13 14C13 13.4477 13.4477 13 14 13H20C20.5523 13 21 13.4477 21 14V20C21 20.5523 20.5523 21 20 21H14C13.4477 21 13 20.5523 13 20V14ZM15 5V9H19V5H15ZM15 15V19H19V15H15ZM5 5V9H9V5H5ZM5 15V19H9V15H5Z"></path></svg>
				</a>
				<a href="#" id="toggle-view-list" class="btn-list <?php echo esc_attr( $active_view === 'list' ? 'active' : '' ); ?> bp-tooltip" data-view="list" data-bp-tooltip-pos="up" data-bp-tooltip="<?php esc_attr_e( 'List View', 'bp-business-profile' ); ?>">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M3 4H21V6H3V4ZM3 11H21V13H3V11ZM3 18H21V20H3V18Z"></path></svg>
				</a>
				<?php if( $is_map_api ) { ?>
				<a href="#" id="toggle-view-map" class="btn-map bp-tooltip" data-view="map" data-bp-tooltip-pos="up" data-bp-tooltip="<?php esc_attr_e( 'Map View', 'bp-business-profile' ); ?>">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M2 5L9 2L15 5L21.303 2.2987C21.5569 2.18992 21.8508 2.30749 21.9596 2.56131C21.9862 2.62355 22 2.69056 22 2.75827V19L15 22L9 19L2.69696 21.7013C2.44314 21.8101 2.14921 21.6925 2.04043 21.4387C2.01375 21.3765 2 21.3094 2 21.2417V5ZM16 19.3955L20 17.6812V5.03308L16 6.74736V19.3955ZM14 19.2639V6.73607L10 4.73607V17.2639L14 19.2639ZM8 17.2526V4.60451L4 6.31879V18.9669L8 17.2526Z"></path></svg>
				</a>
				<?php } ?>
			</div>
		</div>
		
		<div id="business-dir-list" class="business dir-list" data-bp-list="business">
			<div id="bp-ajax-loader"><?php bp_nouveau_user_feedback( 'directory-business-loading' ); ?></div>
		</div><!-- #business-dir-list -->
	</div> <!-- Entry Content finish -->

</div>

