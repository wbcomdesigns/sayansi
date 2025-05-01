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
        <h1><?php esc_html_e( 'Forums', 'bbpress'); ?></h1>
    </label>
    
    <?php
    if( function_exists( 'bbp_get_forum_post_type' ) && 'forum' == bbp_get_forum_post_type() ){ 
     $forums_page = get_post( get_the_ID() );
     echo '<p>' . wp_kses_post( $forums_page->post_content ) . '</p>';
    } else {
     echo '<p>' . esc_html( 'Description Not Found' ) . '</p>';
    }
	$bbpress_banner = buddyboss_theme_get_option( 'bbpress_banner_switch' );
    if ( bbp_is_forum_archive() && ! $bbpress_banner ) { ?>
    	<header class="entry-header buddypress-wrap">              
                     
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



            <div class="buddypress-wrap bbpress-forum-wrap">

                <div class="flex bp-secondary-header align-items-center">
                    <div class="push-right flex"> 

                        <div class="bp-forums-filter-wrap subnav-filters">	
                            <form action="" method="get" class="bp-dir-search-form search-form-has-reset" id="" autocomplete="off">
                                <label for="bbpress-forums-search" class="bp-screen-reader-text">Search Forum…</label>
                                <input id="bbpress-forums-search" name="bbpress_forum_search" type="search" placeholder="Search Forum…">
                                <button type="reset" class="search-form_reset">
                                    <span class="bb-icon-rf bb-icon-times" aria-hidden="true"></span>
                                    <span class="bp-screen-reader-text">Reset</span>
                                </button>
                            </form>
                        </div>
                                
                        <div id="forums-filters" class="foums-component-filters clearfix subnav-filters">
                            <div id="forums-order-select" class="component-filters filter">
                                <label class="bp-screen-reader-text" for="forums-order-by">
                                    <span>Order By:</span>
                                </label>
                                <div class="select-wrap">
                                    <select id="forums-order-by" data-bp-filter="groups">
                                        <option value="alphabetical"><?php esc_html_e( 'Alphabetical', 'sayansi-core' ); ?></option>
                                <option value="recent"><?php esc_html_e( 'Newly Created', 'sayansi-core' ); ?></option>
                                    </select>
                                    <span class="select-arrow" aria-hidden="true"></span>
                                </div>
                            </div>
                        </div>

                        <div class="grid-filters" data-object="<?php echo esc_attr( $component ); ?>">
                            <a href="#" class="layout-view layout-grid-view bp-tooltip grid" data-view="grid" data-bp-tooltip-pos="up" data-bp-tooltip="<?php _e( 'Grid View', 'buddyboss' ); ?>"> <i class="bb-icon-l bb-icon-grid-large" aria-hidden="true"></i> </a>

                            <a href="#" class="layout-view layout-list-view bp-tooltip list" data-view="list" data-bp-tooltip-pos="up" data-bp-tooltip="<?php _e( 'List View', 'buddyboss' );?>"> <i class="bb-icon-l bb-icon-bars" aria-hidden="true"></i> </a>
                        </div>

                    </div>
                </div>
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
if ( ! function_exists( 'buddyboss_bbpress' ) && 'right' == $sidebar_position ) {
	get_sidebar( 'bbpress' );
}
?>

<?php
get_footer();