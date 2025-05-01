
<?php
?>
<div id="bp-profile-setting-partner-home-content" class="tab-content settings-tab-content">
    <h3><?php echo esc_html( 'Edit Partner Home Page'); ?></h3> 
    <?php
    
    $post_id = get_the_ID();
		$fields = wbcom_get_business_info_fields();

		echo '<form method="post" enctype="multipart/form-data">';
		wp_nonce_field('wbcom_save_business_info', 'wbcom_business_info_nonce');

		foreach ($fields as $field_key => $field) {
			$value = get_post_meta($post_id, $field_key, true);

			echo '<p>';
			echo '<label for="' . esc_attr($field_key) . '">' . esc_html($field['label']) . ':</label><br>';

			// Generate the field based on its type
			switch ($field['type']) {
				case 'textarea':
					echo '<textarea name="' . esc_attr($field_key) . '" id="' . esc_attr($field_key) . '">' . esc_textarea($value) . '</textarea>';
					break;
				case 'text':                                   
						echo '<input type="text" name="' . esc_attr($field_key) .'" value="' . esc_attr($value) . '"> ' .'<br>';               
					break;
				case 'file':
				//   foreach ($field['options'] as $option_value => $option_label) {                    
						echo '<input type="file" name="' . esc_attr($field_key) .'" value="' . esc_attr($value) . '"> ' . '<br>';
						echo '<img src="' . esc_url( $value ) .'" alt="logo" width="500" height="600">';
					// }
					break;
				default: // Default to text input
					echo '<input type="' . esc_attr($field['type']) . '" name="' . esc_attr($field_key) . '" id="' . esc_attr($field_key) . '" value="' . esc_attr($value) . '">';
					break;
			}

			echo '</p>';
		}

		echo '</form>';
    
    
    ?>
</div>
