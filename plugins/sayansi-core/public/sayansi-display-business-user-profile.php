<?php
$term_slug = bp_current_action(); // This will output 'beamlines'
if( $term_slug == 'partner' ){
$term_slug = 'agencies';
} else {
$term_slug = bp_current_action();
}
$args = array(
    'post_type'      => 'business', // Specify the post type
    'post_status'    => 'publish',   // Only get published posts
    'author'    	=> bp_displayed_user_id(),   // Only get published posts
    'posts_per_page' => apply_filters('bp_business_profile_filter_per_page', 20), // Number of posts per page
    'paged'          => $paged,      // Current page number for pagination
    'order'          => 'DESC',      // Order posts by descending date
    'orderby'        => 'date',      // Order by date
    'tax_query'      => array(       // Taxonomy query
        array(                       // Each taxonomy query should be an array
            'taxonomy' => 'business-category', // Specify the taxonomy
            'field'    => 'slug',          // Specify the field to use (term_id in this case)
            'terms'    => $term_slug,                 // The term ID you want to filter by
            'operator' => 'IN',                // Operator to use (IN, NOT IN, AND, etc.)
        ),
    ),
);
$business_query = new WP_Query( $args );
?>
<div id="bp-businesses-content" class="entry-content bp-businesses-content">
<div id="business-dir-list" class="business dir-list" data-bp-list="business">
<?php

if ( $business_query->have_posts() ) :
    // Determine the view class based on the cookie.
    $view_class = isset( $_COOKIE['business_view'] ) && $_COOKIE['business_view'] === 'list' ? 'list' : 'grid';
    ?>
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
</div>
</div>