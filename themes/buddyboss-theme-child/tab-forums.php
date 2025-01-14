<?php
// forum description display on page load
if( function_exists( 'bbp_get_forum_post_type' ) && 'forum' == bbp_get_forum_post_type() ){	
	$forums_page = get_post( get_the_ID() );
	echo '<p>' . wp_kses_post( $forums_page->post_content ) . '</p>';
} else {
	echo '<p>' . esc_html( 'Description Not Found' ) . '</p>';
}
// forum description display with ajax on click thr forum tab
echo '<p>' . wp_kses_post( $forum_desc ) . '</p>';
?>
<div id="bbpress-forums">
	<?php do_action( 'bbp_template_before_forums_index' ); ?>

	<?php if ( bbp_has_forums() ) : ?>

		<?php bbp_get_template_part( 'loop', 'forums' ); ?>

		<?php bbp_get_template_part( 'pagination', 'forums' ); ?>

	<?php else : ?>

		<?php bbp_get_template_part( 'feedback', 'no-forums' ); ?>

	<?php endif; ?>

	<?php do_action( 'bbp_template_after_forums_index' ); ?>

</div>