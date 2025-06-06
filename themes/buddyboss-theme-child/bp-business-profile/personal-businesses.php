<?php

$general_settings = get_option('bp_business_profile_general_settings');


if (is_user_logged_in()) {

  $current_user_id = get_current_user_id();
  $user_post_count = count_user_posts($current_user_id, 'business');

  // Get the user's quota using the provided function
  $user_quota = bp_business_profile_get_user_quota($current_user_id);

  // Determine if the user has quota left (i.e., their business count is less than their quota)
  // We assume that 'unlimited' is not applicable here based on your previous instructions, 
  // so the condition directly compares the post count with the quota.
  $can_create_business = ($user_quota > 0 && $user_post_count < $user_quota);

}

$user_member_type = bp_get_member_type( get_current_user_id() );
$can_create_business = ( user_can( get_current_user_id(), 'administrator' ) || in_array( $user_member_type, array( 'developer' ) ) );
?>

<div class="bp-business-profile-header">
  <?php if (isset($general_settings['business_page']) && !empty($general_settings['business_page'])) : ?>
    <div class="bp-business-profile-create-business">
      <?php
      $displayed_user_id = bp_displayed_user_id(); // Assuming BuddyPress is used and has this function

      // Check if the displayed user is the current user and if they have quota left to create a business
      if ( $current_user_id === $displayed_user_id && isset( $can_create_business ) && $can_create_business ) :
      ?>
        <a class="button" href="<?php echo esc_url(get_the_permalink($general_settings['business_page'])); ?>create-business-page/">
          <?php 
          /* translators: %s is the business name */
          printf(esc_html__('Create a %s', 'bp-business-profile'), esc_html(bp_business_profile_get_singular_label())); 
          ?>
        </a>
      <?php endif; ?>
    </div>
  <?php endif; ?>

</div>

<?php
$paged = ( get_query_var( 'paged' ) ) ? absint( get_query_var( 'paged' ) ) : 1;
$args = array(
  'post_type'      => 'business',
  'posts_per_page' => apply_filters( 'bp_business_profile_filter_per_page', 20 ),
  'post_status'    => 'publish',
  'order'          => 'DESC',
  'paged'          => intval( $paged ),
  'author'         => get_current_user_id(),
);

$business_query = new WP_Query(apply_filters('bp_business_profile_personal_business', $args));

// The Loop.
if ($business_query->have_posts()) :
  // Determine the active view from the cookie.
  $active_view = isset( $_COOKIE['business_view'] ) && $_COOKIE['business_view'] === 'list' ? 'list' : 'grid';
  $view_class  = $active_view; // Use the same variable for view class.
  ?>
	<?php echo '<h3>' . esc_html__( 'All Partners', 'sayansi-core' ) . '</h3>'; ?>
  <div class="bp-business-profile-header">
   <!--  <div class="bp-business-profile-list-grid-filter-wrapper">
      <div class="bp-business-profile-list-grid-filter">
        <a href="#" id="toggle-view-grid" class="btn-grid <?php echo $active_view === 'grid' ? 'active' : ''; ?> bp-tooltip" data-view="grid" data-bp-tooltip-pos="up" data-bp-tooltip="<?php esc_attr_e( 'Grid View', 'bp-business-profile' ); ?>">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M3 4C3 3.44772 3.44772 3 4 3H10C10.5523 3 11 3.44772 11 4V10C11 10.5523 10.5523 11 10 11H4C3.44772 11 3 10.5523 3 10V4ZM3 14C3 13.4477 3.44772 13 4 13H10C10.5523 13 11 13.4477 11 14V20C11 20.5523 10.5523 21 10 21H4C3.44772 21 3 20.5523 3 20V14ZM13 4C13 3.44772 13.4477 3 14 3H20C20.5523 3 21 3.44772 21 4V10C21 10.5523 20.5523 11 20 11H14C13.4477 11 13 10.5523 13 10V4ZM13 14C13 13.4477 13.4477 13 14 13H20C20.5523 13 21 13.4477 21 14V20C21 20.5523 20.5523 21 20 21H14C13.4477 21 13 20.5523 13 20V14ZM15 5V9H19V5H15ZM15 15V19H19V15H15ZM5 5V9H9V5H5ZM5 15V19H9V15H5Z"></path></svg>
        </a>
        <a href="#" id="toggle-view-list" class="btn-list <?php echo $active_view === 'list' ? 'active' : ''; ?> bp-tooltip" data-view="list" data-bp-tooltip-pos="up" data-bp-tooltip="<?php esc_attr_e( 'List View', 'bp-business-profile' ); ?>">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M3 4H21V6H3V4ZM3 11H21V13H3V11ZM3 18H21V20H3V18Z"></path></svg>
        </a>
      </div>
    </div> -->


    <!-- Add search, filter, layout -->
      <div class="buddypress-wrap network-all-partners-wrap">
          <div class="flex bp-secondary-header align-items-center">       
              <div class="push-right flex"> 
                  <div class="bp-ind-members-filter-wrap subnav-filters"> 
                      <form action="" method="get" class="bp-dir-search-individual-members search-individual-members-has-reset" id="" autocomplete="off">
                          <label for="network-all-partners-search" class="bp-screen-reader-text">Search Partners…</label>
                          <input id="network-all-partners-search" name="network_all_partners_search" type="search" placeholder="Search Partners..">                               
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
                  
                   <div class="grid-filters">
                      <a href="#" class="layout-view layout-grid-view bp-tooltip grid" data-view="grid" data-bp-tooltip-pos="up" data-bp-tooltip="<?php _e( 'Grid View', 'sayansi-core' ); ?>"> <i class="bb-icon-l bb-icon-grid-large" aria-hidden="true"></i> </a>

                      <a href="#" class="layout-view layout-list-view bp-tooltip list" data-view="list" data-bp-tooltip-pos="up" data-bp-tooltip="<?php _e( 'List View', 'sayansi-core' );?>"> <i class="bb-icon-l bb-icon-bars" aria-hidden="true"></i> </a>
                  </div>

              </div>
          </div>
      </div>
      <!-- End Add search, filter, layout -->


  </div>

<div id="business-list-container-personal" class="business-list-container">
  <ul id="business-list" class="item-list business-list row <?php echo esc_attr( $view_class ); ?>">
    <?php
    while ($business_query->have_posts()) :
      $business_query->the_post();
      $business_id            = get_the_ID();
      $category               = get_the_terms(get_the_ID(), 'business-category');
      $category_name          = '';
      $_business_avatar_image = get_post_meta(get_the_ID(), '_business_avatar_image', true);
      $_business_cover_image  = get_post_meta(get_the_ID(), '_business_cover_image', true);
      $average_rating         = bp_business_profile_get_average_rating_for_business($business_id);
      $review_count           = array_sum(bp_business_profile_get_rating_counts_for_business($business_id));

      $group_id = (int) get_post_meta($business_id, 'bp-business-group', true);

      if (! empty($category)) {
        $category_name = $category['0']->name;
      }
    ?>
      <li class="col col-md-4 col-sm-6 col-xs-12">
        <div class="bp-business-list-wrap">
          <a href="<?php the_permalink(); ?>" class="bp-business-list-inner-wrap">
            <div class="bp-business-cover-img">

              <?php if ($_business_cover_image != '') : ?>

                <?php echo wp_get_attachment_image($_business_cover_image, 'full'); ?>

              <?php else : ?>

                <?php bp_business_profile_defaut_cover_image(); ?>

              <?php endif ?>

              <?php if ($category_name != '') : ?>

                <span class="bp-business-category"><?php echo esc_html($category_name); ?></span>

              <?php endif; ?>

            </div>
          </a>
          <div class="item-avatar bp-business-avatar">
            <?php if ($_business_avatar_image != '') : ?>

              <?php echo wp_get_attachment_image($_business_avatar_image); ?>

            <?php else : ?>

              <?php bp_business_profile_defaut_avatar(); ?>

            <?php endif; ?>
          </div>

          <?php do_action( 'bp_business_before_content_wrap' ); ?>

          <div class="bp-business-content-wrap">
            <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
            <?php if ($average_rating > 0) : ?>
              <div class="bp-business-rating">
                <span class="bp-business-rating-wrap">
                  <?php bp_business_profile_reviews_html($average_rating, $business_id); ?>
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

          <div class="bp-business-follow-button-container-wrapper">
            <div class="bp-business-item-actions">
              <div id="bp-business-follow-button-<?php echo esc_html($business_id); ?>" class="bp-business-header-nav-button bp-business-follow-button-container bp-business-listing-follow-button">

                <?php echo bp_business_get_follow_button($business_id, $group_id); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
                ?>

              </div>
            </div>
          </div>

        </div>
      </li>
    <?php endwhile; ?>
  </ul>
</div>
  
  <div class="bp-business-navigation navigation pagination">
    <?php bp_business_user_profile_paginate( $business_query ); ?>
  </div>
  
<?php else : ?>

  <?php bp_nouveau_user_feedback('businesses-personal-none'); ?>

<?php
endif;

wp_reset_postdata();