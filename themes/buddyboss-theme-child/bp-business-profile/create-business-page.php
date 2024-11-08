<?php
global $bP_business_settings;

$business_category = get_terms(
	array(
		'taxonomy'   => 'business-category',
		'hide_empty' => false,
	)
);

$business_name    = ( isset( $_POST['business-name'] ) ) ? $_POST['business-name'] : ''; //phpcs:ignore
$business_desc    = ( isset( $_POST['business-desc'] ) ) ? $_POST['business-desc'] : ''; //phpcs:ignore
$business_cat     = ( isset( $_POST['business-category'] ) ) ? $_POST['business-category'] : ''; //phpcs:ignore
$general_settings = isset( $bP_business_settings['general_settings'] ) ? $bP_business_settings['general_settings'] : array();
$singular_label   = ( isset( $general_settings['singular_label'] ) ) ? $general_settings['singular_label'] : 'Business';


$business_taxonomies = get_object_taxonomies( 'business' );
if (($key = array_search('business-category', $business_taxonomies)) !== false) {
    unset($business_taxonomies[$key]);
}
?>
<h3 class="bp-screen-title creation-step-name">
	<?php /* translators: %s: */ ?>
	<?php printf( esc_html__( 'Enter %s Name &amp; Description', 'bp-business-profile' ), esc_attr( $singular_label ) ); ?>
</h3>

<?php /* translators: %s: */ ?>
<label for="business-name"><?php printf( esc_html__( '%s Name (required)', 'bp-business-profile' ), esc_attr( $singular_label ) ); ?></label>
<p class="bp-business-description"><?php esc_html_e( 'Use a name that defines your page or the name of your company, brand, or organisation.', 'bp-business-profile' ); ?></p>
<input type="text" name="business-name" id="business-name" value="<?php echo esc_attr( $business_name ); ?>" aria-required="true">
<?php /* translators: %s: */ ?>
<label for="business-desc"><?php printf( esc_html__( '%s Description (required)', 'bp-business-profile' ), esc_attr( $singular_label ) ); ?></label>
<p class="bp-business-description"><?php esc_html_e( 'Give people a brief description of your work.', 'bp-business-profile' ); ?></p>
	<?php
	$args = array(
		'tinymce' => array(
			'toolbar1' => 'bold,italic,underline,separator,alignleft,aligncenter,alignright,separator,link,unlink,undo,redo',
			'toolbar2' => '',
			'toolbar3' => '',
		),
	);


	wp_editor( $business_desc, 'business-desc', $args );	
	?>

	<label for="business-excerpt"><?php printf( esc_html__( '%s Excerpt', 'bp-business-profile' ), esc_attr( $singular_label ) ); ?></label>
	<p class="bp-business-excerpt"><?php esc_html_e( 'Give people a brief excerpt of your work.', 'bp-business-profile' ); ?></p>
		<?php
		$args = array(
			'tinymce' => array(
				'toolbar1' => 'bold,italic,underline,separator,alignleft,aligncenter,alignright,separator,link,unlink,undo,redo',
				'toolbar2' => '',
				'toolbar3' => '',
			),
		);


		wp_editor( $business_desc, 'beam_line_excerpt', $args );
		?>



	<?php if ( ! empty( $business_category ) ) : ?>
		<label for="business-category"><?php esc_html_e( 'Business Category (required)', 'bp-business-profile' ); ?></label>
		<p class="bp-business-description"><?php esc_html_e( 'Enter a category that best describes you.', 'bp-business-profile' ); ?></p>
		<?php
		$filter_drop_down = wp_dropdown_categories(
			array(
				'taxonomy'        => 'business-category',
				'hierarchical'    => 1,
				'hide_empty'      => 0,
				'show_option_all' => __( 'Select business category', 'bp-business-profile' ),
				'name'            => 'business-category-filter',
				'echo'            => 0,
				'orderby'         => 'name',
				'order'           => 'ASC',
			)
		);
		$filter_drop_down = str_replace( '<select', '<select name="business-category" id="business-category" aria-required="true"', $filter_drop_down );
		echo $filter_drop_down; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		?>
	<?php endif; ?>
	
	
	<?php if( !empty( $business_taxonomies ) ): ?>
		
		<?php foreach( $business_taxonomies as $business_taxonomy): 
		
			$business_taxonomy_info = get_taxonomy( $business_taxonomy );
			
			$business_custom_category = get_terms(
						array(
							'taxonomy'   => $business_taxonomy,
							'hide_empty' => false,
						)
					);
		?>
		<?php if ( ! empty( $business_custom_category ) ) : ?>
			
				<label for="business-<?php echo esc_attr($business_taxonomy)?>"><?php echo esc_html($business_taxonomy_info->label) ?></label>
				<select name="business-custom-category[<?php echo esc_attr($business_taxonomy);?>]" id="business-<?php echo esc_attr($business_taxonomy)?>" aria-required="true">
					
					<option value="">
						<?php 
						/* translators: %s is the name of business taxonomy name */
						echo sprintf(esc_html__( 'Select %s', 'bp-business-profile' ), esc_html($business_taxonomy_info->label ) ) ; 
						?>
					</option>					
					
					<?php foreach ( $business_custom_category as $category ) : ?>
						<option value="<?php echo esc_attr( $category->term_id ); ?>"><?php echo esc_html( $category->name ); ?></option>
					<?php endforeach; ?>
				</select>			
		<?php endif; ?>
		
		<?php endforeach; ?>		
		
	<?php endif;?>

<?php wp_nonce_field( 'bp_business_create_new_business' ); ?>
<input type="hidden" name="business-id" id="business-id" value="0">

<div class="submit" id="previous-next">
	<?php /* translators: %s: */ ?>
	<input type="submit" value="<?php printf( esc_html__( 'Create %s and Continue', 'bp-business-profile' ), esc_attr( $singular_label ) ); ?>" id="business-creation-create" name="create_bussinedd_save">
</div>
		
