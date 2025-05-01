<?php
/**
 * Short Description
 *
 * @package    bp3.0_dev
 * @subpackage ${NAMESPACE}
 * @copyright  Copyright (c) 2018, Brajesh Singh
 * @license    https://www.gnu.org/licenses/gpl.html GNU Public License
 * @author     Brajesh Singh
 * @since      1.0.0
 */

    $parent_component   = bcircles_get_parent_component_slug();	
    $circles_screen_url = bcircles_get_circles_screen_url();		
    
    $circle_label = bcircles_get_circle(  $circle->id )->label;

    $circle_url = $circles_screen_url . $circle->id . '/' . $circle_label;
?>
<li class="item bcircles-circle-item">

	<div class="bcircles-circle-content" id="bcircles-entry-content-<?php echo $circle->id ;?>">
		<a class="bcircles-circle-label" href="<?php echo esc_url(strtolower($circle_url)); ?>" data-circle-id="<?php echo $circle->id;?>"><?php echo esc_html( $circle->label );?></a><br>
		<label class="bcircles-circle-member-count"><?php _e( 'Total Members: ', 'buddycircles' ) ?><span class="bcircles-circle-member-count"><?php echo count( bcircles_get_circle_users( $circle->id ) ); ?></span></label><br>
		<label class="bcircles-circle-privacy"><?php _e( 'Circle Privacy: ', 'buddycircles' ) ?><?php echo $privacies[$circle->privacy]; ?></label>
	</div>

	<!-- editing -->
	<?php if ( bcircles_user_can_edit_circle( get_current_user_id(), $circle->id ) ) : ?>
		<div class="bcircles-circle-edit-section" style="display: none">
			<form method="post" class="bcircles-circle-edit-form">
                <div class="bcircles-message"></div>
				<div class="bcircles-row bcircles-row-edit-circle-label">
					<input type="text" name="label" value="<?php echo esc_attr( $circle->label );?>">
				</div>

				<div class="bcircles-row bcircles-row-edit-circle-privacy">
					<?php bcircles_privacy_dropdown( array( 'selected' => $circle->privacy ) ); ?>
				</div>
				<div class="bcircles-row bcircles-row-edit-circle-buttons">
					<input type="submit" name="submit" value="Update" class="bcircles-circle-update-btn" />
					<input type="button" name="submit" value="Cancel" class="bcircles-update-cancel-btn" />
				</div>

				<input type="hidden" name="circle_id" value="<?php echo $circle->id; ?>">
				<input type="hidden" name="user_id" value="<?php echo $circle->user_id; ?>">
				<?php wp_nonce_field( 'bcircles_action', '_wpnonce', true, true ); ?>
			</form>
		</div>

		<div class="bcircles-circle-actions-links">
			<a href='#' class='bcircles-circle-edit'><?php _e( 'Edit', 'buddycircles' ); ?></a>
			<a href='#' data-circle-id="<?php echo $circle->id ?>" class='bcircles-circle-delete'><?php _e( 'Delete', 'buddycircles' ); ?></a>
		</div>
	<?php endif; ?>


</li>
