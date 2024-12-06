<?php

// Define the fields with additional types
function wbcom_get_business_info_fields() {
    return [
        'feature_image' => [
            'label' => 'Feature Image',
            'type' => 'file',
            'sanitize_callback' => 'sanitize_feature_img_field',
        ],
        'column_one_title' => [
            'label' => 'Column One - Title',
            'type' => 'text',
            'sanitize_callback' => 'sanitize_col_one_title_field',
        ],
        'column_one_description' => [
            'label' => 'Column One - Description',
            'type' => 'textarea',
            'sanitize_callback' => 'sanitize_col_one_desc_field',
        ],
         'column_one_logo' => [
            'label' => 'Column One - Logo',
            'type' => 'file',
            'sanitize_callback' => 'sanitize_col_one_logo_field',
        ],
        'column_one_link' => [
            'label' => 'Column One - Link',
            'type' => 'text',
            'sanitize_callback' => 'sanitize_col_one_link_field',
        ],
         'column_two_title' => [
            'label' => 'Column Two - Title',
            'type' => 'text',
            'sanitize_callback' => 'sanitize_col_two_title_field',
        ],
        'column_two_description' => [
            'label' => 'Column Two - Description',
            'type' => 'textarea',
            'sanitize_callback' => 'sanitize_col_two_desc_field',
        ],
         'column_two_logo' => [
            'label' => 'Column Two - Logo',
            'type' => 'file',
            'sanitize_callback' => 'sanitize_col_two_logo_field',
        ],
        'column_two_link' => [
            'label' => 'Column Two - Link',
            'type' => 'text',
            'sanitize_callback' => 'sanitize_col_two_link_field',
        ],
        'column_three_title' => [
            'label' => 'Column Three - Title',
            'type' => 'text',
            'sanitize_callback' => 'sanitize_col_three_title_field',
        ],
        'column_three_description' => [
            'label' => 'Column Three - Description',
            'type' => 'textarea',
            'sanitize_callback' => 'sanitize_col_three_desc_field',
        ],
         'column_three_logo' => [
            'label' => 'Column Three - Logo',
            'type' => 'file',
            'sanitize_callback' => 'sanitize_col_three_logo_field',
        ],
        'column_three_link' => [
            'label' => 'Column Three - Link',
            'type' => 'text',
            'sanitize_callback' => 'sanitize_col_three_link_field',
        ],
        'column_four_title' => [
            'label' => 'Column Four - Title',
            'type' => 'text',
            'sanitize_callback' => 'sanitize_col_four_title_field',
        ],
        'column_four_description' => [
            'label' => 'Column Four - Description',
            'type' => 'textarea',
            'sanitize_callback' => 'sanitize_col_four_desc_field',
        ],
         'column_four_logo' => [
            'label' => 'Column four - Logo',
            'type' => 'file',
            'sanitize_callback' => 'sanitize_col_four_logo_field',
        ],
        'column_four_link' => [
            'label' => 'Column four - Link',
            'type' => 'text',
            'sanitize_callback' => 'sanitize_col_four_link_field',
        ],
    ];
}

// Sanitize array inputs (for checkbox and multiselect fields)
function wbcom_sanitize_array($input) {
    return array_map('sanitize_text_field', (array) $input);
}

