<?php
get_header();

$is_buddyboss_bbpress = function_exists( 'buddyboss_bbpress' );

if ( ! $is_buddyboss_bbpress && ! bbp_is_single_user() ) {
	get_template_part( 'template-parts/bbpress-banner' );
}
$sidebar_position = buddyboss_theme_get_option( 'forums' );
if ( ! function_exists( 'buddyboss_bbpress' ) && 'left' == $sidebar_position ) {
	get_sidebar( 'bbpress' );
}
?>
<div id="primary" class="content-area">
    <label class="bbpress-forum-heading">        
        <h1><?php esc_html_e( 'Forum', 'bbpress'); ?></h1>
    </label>
    <?php
	$bbpress_banner = buddyboss_theme_get_option( 'bbpress_banner_switch' );
    if ( bbp_is_forum_archive() && ! $bbpress_banner ) { ?>
    	<header class="entry-header">                        
            <div class="bbpress-forum-discussion" id="bbpress-forum-discussion" role="navigation">
                <ul class="forum-subnav">                    
                    <li class="bbpress-forum">
                        <a href="#" id="custom-forum" data-id="sayansi-forum" class="selected"><?php esc_html_e( 'Forums', 'bbpress'); ?></a>
                    </li> 
                    <li class="bbpress-discussion">
                        <a href="#" id="custom-discussion" data-id="sayansi-discussion"><?php esc_html_e( 'Discussions', 'bbpress' ); ?></a>
                    </li>
                     <li class="bbpress-create-forum">                    
                        <a href="https://sayansi.africanlightsource.org/create-new-forum/" data-id="sayansi-create-forum"><?php esc_html_e( 'Create a Forum', 'bbpress' ); ?></a>
                    </li>
                </ul>
            </div>
        </header>
        <div id="response-container">
            <?php include get_stylesheet_directory() . '/tab-forums.php'; // Default content ?>
        </div>
    <?php
     }
    ?>
</div>
<?php
get_footer();