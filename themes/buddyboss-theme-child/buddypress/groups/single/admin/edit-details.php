<?php
/**
 * BP Nouveau Group's edit details template.
 *
 * This template can be overridden by copying it to yourtheme/buddypress/groups/single/admin/edit-details.php.
 *
 * @since   BuddyPress 3.0.0
 * @version 1.0.0
 */

$bp_is_group_create = bp_is_group_create();
$group_id = bp_get_current_group_id(); 	
if ( $bp_is_group_create ) : ?>

	<h3 class="bp-screen-title creation-step-name">
		<?php esc_html_e( 'Enter Group Name &amp; Description', 'buddyboss' ); ?>
	</h3>

<?php else : ?>

	<h2 class="bp-screen-title">
		<?php esc_html_e( 'Edit Group Name &amp; Description', 'buddyboss' ); ?>
	</h2>

<?php endif; ?>

<label for="group-name"><?php esc_html_e( 'Group Name (required)', 'buddyboss' ); ?></label>
<input type="text" name="group-name" id="group-name" value="<?php $bp_is_group_create ? bp_new_group_name() : bp_group_name_editable(); ?>" aria-required="true" />

<label for="group-desc"><?php esc_html_e( 'Group Description', 'buddyboss' ); ?></label>
<textarea name="group-desc" id="group-desc" aria-required="true"><?php $bp_is_group_create ? bp_new_group_description() : bp_group_description_editable(); ?></textarea>

<div class="sayansi-group-details">
    <div class="sayansi-feature-image">
        <label><?php esc_html_e( 'Feature Image' ,'sayansi-core'); ?></label>
        <input type="file" id="group_feature_image" name="group_feature_image" />
        <img src="<?php echo esc_url( groups_get_groupmeta( $group_id, 'group_feature_image' ) ); ?>" alt="Feature Image" width="500" height="600">                          
        <p></p>
    </div>
    <div class="column-one">
        <h2>Column One</h2>
        <div class="sayansi-column-one-title">
            <label><?php esc_html_e( 'Column One - Title' ,'sayansi-core'); ?></label>
            <input type="text" id="group_column_one_title" name="group_column_one_title" value="<?php echo esc_attr( groups_get_groupmeta( $group_id, 'group_column_one_title' ) );?>" placeholder="<?php esc_html_e( 'Enter the title', 'sayansi-core' ); ?>" />                            
        </div>
        <div class="sayansi-column-onde-desc">
            <label><?php esc_html_e( 'Column One - Description' ,'sayansi-core'); ?></label>
            <input type="text" id="group_column_one_desc" name="group_column_one_desc" value="<?php echo esc_attr( groups_get_groupmeta( $group_id, 'group_column_one_desc' ) ); ?>" placeholder="<?php esc_html_e( 'Enter the desc', 'sayansi-core' ); ?>" />                            
        </div>
        <div class="sayansi-column-onde-logo">
            <label><?php esc_html_e( 'Column One - Logo' ,'sayansi-core'); ?></label>
            <input type="file" id="group_column_one_logo" name="group_column_one_logo" />
            <img src="<?php echo esc_url( groups_get_groupmeta( $group_id, 'group_column_one_logo' ) ); ?>" alt="Logo" width="500" height="600"> 
            <p></p>                           
        </div>
        <div class="sayansi-column-onde-link">
            <label><?php esc_html_e( 'Column One - Link' ,'sayansi-core'); ?></label>
            <input type="text" id="group_column_one_link" name="group_column_one_link" value="<?php echo esc_attr( groups_get_groupmeta( $group_id, 'group_column_one_link' ) ); ?>" />                            
        </div>
    </div>
    <div class="column-two">
        <h2>Column Two</h2>
        <div class="sayansi-column-two-title">
            <label><?php esc_html_e( 'Column Two - Title' ,'sayansi-core'); ?></label>
            <input type="text" id="group_column_two_title" name="group_column_two_title" value="<?php echo esc_attr( groups_get_groupmeta( $group_id, 'group_column_two_title' ) ); ?>" placeholder="<?php esc_html_e( 'Enter the title', 'sayansi-core' ); ?>" />                            
        </div>
        <div class="sayansi-column-onde-desc">
            <label><?php esc_html_e( 'Column Two - Description' ,'sayansi-core'); ?></label>
            <input type="text" id="group_column_two_desc" name="group_column_two_desc" value="<?php echo esc_attr( groups_get_groupmeta( $group_id, 'group_column_two_desc' ) ); ?>" placeholder="<?php esc_html_e( 'Enter the desc', 'sayansi-core' ); ?>" />                            
        </div>
        <div class="sayansi-column-onde-logo">
            <label><?php esc_html_e( 'Column Two - Logo' ,'sayansi-core'); ?></label>
            <input type="file" id="group_column_two_logo" name="group_column_two_logo" /> 
            <img src="<?php echo esc_url( groups_get_groupmeta( $group_id, 'group_column_two_logo' ) ); ?>" alt="Logo" width="500" height="600">
            <p></p>                          
        </div>
        <div class="sayansi-column-onde-link">
            <label><?php esc_html_e( 'Column Two - Link' ,'sayansi-core'); ?></label>
            <input type="text" id="group_column_two_link" name="group_column_two_link" value="<?php echo esc_attr( groups_get_groupmeta( $group_id, 'group_column_two_link' ) ); ?>" />                            
        </div>
    </div>
    <div class="column-three">
        <h2>Column Three</h2>
        <div class="sayansi-column-three-title">
            <label><?php esc_html_e( 'Column Three - Title' ,'sayansi-core'); ?></label>
            <input type="text" id="group_column_three_title" name="group_column_three_title" value="<?php echo esc_attr( groups_get_groupmeta( $group_id, 'group_column_three_title' ) ); ?>" placeholder="<?php esc_html_e( 'Enter the title', 'sayansi-core' ); ?>" />                            
        </div>
        <div class="sayansi-column-onde-desc">
            <label><?php esc_html_e( 'Column Three - Description' ,'sayansi-core'); ?></label>
            <input type="text" id="group_column_three_desc" name="group_column_three_desc" value="<?php echo esc_attr( groups_get_groupmeta( $group_id, 'group_column_three_desc' ) ); ?>" placeholder="<?php esc_html_e( 'Enter the desc', 'sayansi-core' ); ?>" />                            
        </div>
        <div class="sayansi-column-onde-logo">
            <label><?php esc_html_e( 'Column Three - Logo' ,'sayansi-core'); ?></label>
            <input type="file" id="group_column_three_logo" name="group_column_three_logo" /> 
            <img src="<?php echo esc_url( groups_get_groupmeta( $group_id, 'group_column_three_logo' ) ); ?>" alt="Logo" width="500" height="600">
            <p></p>                          
        </div>
        <div class="sayansi-column-onde-link">
            <label><?php esc_html_e( 'Column Three - Link' ,'sayansi-core'); ?></label>
            <input type="text" id="group_column_three_link" name="group_column_three_link" value="<?php echo esc_attr( groups_get_groupmeta( $group_id, 'group_column_three_link' ) ); ?>" />                            
        </div>
    </div>
    
    <div class="column-four">
        <h2>Column Four</h2>
        <div class="sayansi-column-four-title">
            <label><?php esc_html_e( 'Column Four - Title' ,'sayansi-core'); ?></label>
            <input type="text" id="group_column_four_title" name="group_column_four_title" value="<?php echo esc_attr( groups_get_groupmeta( $group_id, 'group_column_four_title' ) ); ?>" placeholder="<?php esc_html_e( 'Enter the title', 'sayansi-core' ); ?>" />                            
        </div>
        <div class="sayansi-column-onde-desc">
            <label><?php esc_html_e( 'Column Four - Description' ,'sayansi-core'); ?></label>
            <input type="text" id="group_column_four_desc" name="group_column_four_desc" value="<?php echo esc_attr( groups_get_groupmeta( $group_id, 'group_column_four_desc' ) ); ?>" placeholder="<?php esc_html_e( 'Enter the desc', 'sayansi-core' ); ?>" />                            
        </div>
        <div class="sayansi-column-onde-logo">
            <label><?php esc_html_e( 'Column Four - Logo' ,'sayansi-core'); ?></label>
            <input type="file" id="group_column_four_logo" name="group_column_four_logo" /> 
            <img src="<?php echo esc_url( groups_get_groupmeta( $group_id, 'group_column_four_logo' ) ); ?>" alt="Logo" width="500" height="600">
            <p></p>                          
        </div>
        <div class="sayansi-column-onde-link">
            <label><?php esc_html_e( 'Column Four - Link' ,'sayansi-core'); ?></label>
            <input type="text" id="group_column_four_link" name="group_column_four_link" value="<?php echo esc_attr( groups_get_groupmeta( $group_id, 'group_column_four_link' ) ); ?>" />                            
        </div>
    </div>
</div>	