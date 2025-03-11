<?php
/**
 * The template for member courses for meprlms.
 *
 * This template can be overridden by copying it to yourtheme/buddypress/members/single/courses.php.
 *
 * Wbcon Designs - This template for user profile course
 *
 * @since   2.6.30
 *
 * @package BuddyBoss\MemberpressLMS
 *
 * @version 1.0.0
 */

use memberpress\courses\models as models;
use memberpress\courses\helpers as helpers;

$options        = get_option( 'mpcs-options' );
$progress_color = implode( ', ', helpers\Options::get_rgb( $options, 'progress-color' ) );

$current_course_subtab = 'user-courses';
if ( class_exists( 'BB_MeprLMS_Profile' ) ) {
	$bb_meprlms_profile    = BB_MeprLMS_Profile::get_instance();
	$current_course_subtab = $bb_meprlms_profile->profile_course_subtab;
}

$displayed_user_id = bp_displayed_user_id();

// echo bb_meprlms_get_course_search_form();
?>

<div class="flex bp-secondary-header align-items-center">
	<div class="push-right flex">
	<div id="search-filters" class="search-component-filters subnav-filters"><?php echo bb_meprlms_get_course_search_form(); ?></div>	
		
	<div id="courses-filters" class="courses-component-filters subnav-filters" data-tab="<?php echo esc_attr($current_course_subtab); ?>">
		<div id="courses-order-select" class="component-filters filter">
			<label class="bp-screen-reader-text" for="courses-order-by">
				<span>Order By:</span>
			</label>
			<div class="select-wrap">
				<select id="courses-order-by">
					<option value=""><?php esc_html_e('Course Filter', 'sayansi-core'); ?></option>
					<option value="alphabetical"><?php esc_html_e('Alphabetical', 'sayansi-core'); ?></option>
					<option value="recent"><?php esc_html_e('Newly Created', 'sayansi-core'); ?></option>
				</select>
				<span class="select-arrow" aria-hidden="true"></span>
			</div>
		</div>
	</div>

	<div class="grid-filters">
		<a href="#" class="layout-view layout-grid-view bp-tooltip" data-view="course-grid" data-bp-tooltip-pos="up" data-bp-tooltip="<?php _e( 'Grid View', 'buddyboss' ); ?>"> <i class="bb-icon-l bb-icon-grid-large" aria-hidden="true"></i> </a>
		<a href="#" class="layout-view layout-list-view bp-tooltip list" data-view="course-list" data-bp-tooltip-pos="up" data-bp-tooltip="<?php _e( 'List View', 'buddyboss' );?>"> <i class="bb-icon-l bb-icon-bars" aria-hidden="true"></i> </a>
	</div>
		
	</div>
</div>

<?php
if ( 'instructor-courses' === $current_course_subtab ) {
	$courses = bb_meprlms_get_instructor_courses( $displayed_user_id );
} else {
	$courses = bb_meprlms_get_user_courses( $displayed_user_id );
}

if ( ! empty( $courses->posts ) ) {
	?>

	<div class="columns mpcs-cards 002">

	<?php
		ob_start();
	foreach ( $courses->posts as $post ) { // Standard WordPress loop.
		setup_postdata( $post );
		$course           = new models\Course( $post->ID );
		$progress         = $course->user_progress( get_current_user_id() );
		$categories       = get_the_terms( $course->ID, 'mpcs-course-categories' );
		$course_is_locked = false;

		if ( \MeprRule::is_locked( $post ) ) {
			$course_is_locked = true;
		}
		?>
			<div class="column col-4 col-md-6 col-xs-12">
				<div class="card s-rounded">
					<div class="card-image">
						<?php if ( $course_is_locked ) { ?>
							<div class="locked-course-overlay">
								<i class="mpcs-icon mpcs-lock"></i>
							</div>
						<?php } ?>
						<a href="<?php the_permalink(); ?>" alt="<?php the_title_attribute(); ?>">
						<?php
						if ( has_post_thumbnail() ) :
							the_post_thumbnail( apply_filters( 'mpcs_course_thumbnail_size', 'mpcs-course-thumbnail' ), array( 'class' => 'img-responsive' ) );
							else :
								?>
								<img src="<?php echo esc_url( bb_meprlms_integration_url( '/assets/images/course-placeholder.jpg' ) ); ?>" class="img-responsive" alt="">
							<?php endif; ?>
						</a>
					</div>

					<div class="card-content-section">
					<div class="card-header">
						<div class="card-title">
							<h2 class="h5"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
							<?php if ( ! empty( $categories ) ) : ?>
								<div class="card-categories">
									<?php foreach ( $categories as $category ) : ?>
										<span class="card-category-name"><?php echo $category->name; ?><span class="card-category__separator">,</span></span>
									<?php endforeach; ?>
								</div>
							<?php endif; ?>
						</div>
					</div>
					<div class="card-body">
						<?php the_excerpt(); ?>
					</div>
					<div class="card-footer">
						<?php
						if ( models\UserProgress::has_started_course( get_current_user_id(), $course->ID ) ) :
							// Get lessons count.
							$total_lessons = $course->number_of_lessons();
							// If total lessons are n and progress is p% then get completed lesson count.
							$completed_lessons = ceil( $total_lessons * $progress / 100 );
							?>
							<div class="mpcs-progress-wrap">
								<div class="mpcs-progress-data">
									<strong class="mpcs-progress-lessons"><?php echo $completed_lessons . '/' . $total_lessons; ?></strong>
									<span class="mpcs-progress-per"><strong><?php echo $progress . '%'; ?></strong> <?php esc_html_e( ' Complete', 'buddyboss-pro' ); ?></span>
								</div>
								<div class="mpcs-progress-bar">
									<div class="mpcs-progress-bar-inner" style="width: <?php echo $progress; ?>%;"></div>
								</div>
							</div>
							<?php
							$next_lesson = models\UserProgress::next_lesson( get_current_user_id(), $course->ID );
							if ( false !== $next_lesson && is_object( $next_lesson ) ) {
								?>
								<a href="<?php echo get_permalink( $next_lesson->ID ); ?>" class="mpcs-btn-secondary">
									<i class="bb-icon-l bb-icon-play"></i><?php esc_html_e( 'Continue Course', 'buddyboss-pro' ); ?>
								</a>
								<?php
							}
							?>
						<?php else : ?>
							<span class="course-author">
								<?php
								$user_id    = get_the_author_meta( 'ID' );
								$author_url = bp_core_get_user_domain( $user_id );
								?>
								<a href="<?php echo esc_url_raw( $author_url ); ?>">
										<?php
										echo bp_core_fetch_avatar(
											array(
												'item_id' => $user_id,
												'html'    => true,
											)
										) . bp_core_get_user_displayname( $user_id );
										?>
								</a>
							</span>
						<?php endif; ?>
					</div>
					</div>
				</div>
			</div>

		<?php
	} // end of the loop.
		wp_reset_postdata();

		echo ob_get_clean();
	?>
	</div>

	<?php
	if ( $courses->max_num_pages > 1 ) {
		?>
		<div class="bb-lms-pagination">
			<?php
				$big          = 999999999; // need an unlikely integer.
				$translated   = esc_html__( 'Page', 'buddyboss-pro' ); // Supply translatable string.
				$current_page = max( 1, get_query_var( 'paged' ) );
				$base         = str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) );

				// Change the default parent slug to respective slug.
				if ( 'user-courses' === $current_course_subtab ) {
					$base = str_replace( "/{$bb_meprlms_profile->courses_slug}/page/", "/{$bb_meprlms_profile->courses_slug}/{$bb_meprlms_profile->accessible_courses_slug}/page/", $base );
				}
				echo paginate_links(
					array(
						'base'               => $base,
						'current'            => $current_page,
						'total'              => $courses->max_num_pages,
						'before_page_number' => '<span class="screen-reader-text">' . $translated . ' </span>',
					)
				);
			?>
		</div>
		<?php
	}
} else {
	if ( get_query_var( 's' ) ) {
		bp_nouveau_user_feedback( 'meprlms-courses-loop-none' );
	} elseif ( 'instructor-courses' === $current_course_subtab ) {
		bp_nouveau_user_feedback( 'meprlms-created-courses-loop-none' );
	} else {
		bp_nouveau_user_feedback( 'meprlms-accessible-courses-loop-none' );
	}
}


// get_sidebar( 'page' );