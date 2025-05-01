<?php
/**
 * Single Circle Template
 */
?>
<?php
$circle    = bcircles_get_circle( bp_action_variable( 0 ) );
$privacies = bcircles_get_privacies();
$compose_url = bp_loggedin_user_domain() . bp_get_messages_slug() . '/compose/?all_connections=1&component=team';
?>
    

    <div class="bcircles-single-circle-info bcircles-circle-privayc-<?php echo esc_attr( $circle->privacy ); ?> bcircles-circle-name-<?php esc_attr( sanitize_title_with_dashes( $circle->label ) ); ?>">

        <h3><?php echo esc_html( $circle->label ); ?><span class="bcircles-single-circle-privacy"><?php echo $privacies[ $circle->privacy ]; ?></span></h3>
       <div class="bcircles-single-circle-button-sec">
		   <div class="bcircles-single-circle-delete-button"><?php echo bcircles_get_circle_delete_link( $circle->id ); ?></div>
		   <div class="bp-connections-send-message">
			   <a href="<?php echo esc_url($compose_url); ?>" class="button send-message-to-connections">
				   <?php _e('Send Message', 'buddyboss'); ?>
			   </a>
		   </div>
		</div>
		
    </div>

<?php

bcircles_locate_template( array('circle-users.php' ), true );
