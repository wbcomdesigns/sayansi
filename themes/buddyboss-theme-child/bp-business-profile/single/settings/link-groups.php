
<?php
$business_id = get_the_ID();
$partner_groups = get_post_meta( $business_id, 'bp-link-group', true );

// Fetch all groups from the site
$args = array(
    'type' => 'all', // To get all groups
    'per_page' => 1000, // You can set a limit if needed, or paginate
    'page' => 1, // Default to the first page of results
);

$all_groups = groups_get_groups( $args ); // Get all the groups
?>
<div id="bp-profile-setting-link-groups-content" class="tab-content settings-tab-content">
    <!-- Selectize Form with All Groups -->
    <select id="partner_groups" name="partner_groups[]" multiple="multiple" style="width: 100%;">
        <?php
        // Check if there are any groups
        if ( ! empty( $all_groups['groups'] ) ) {
            foreach ( $all_groups['groups'] as $group ) {
                $remove_partner_group = groups_get_groupmeta( $group->id, 'bp-group-business', true );
                if( $remove_partner_group ){
                    continue;
                }
                // Check if the group is already linked to the business (pre-select it)
                $selected = in_array( $group->id, (array) $partner_groups ) ? 'selected' : '';
                echo '<option value="' . esc_attr( $group->id ) . '" ' . $selected . '>' . esc_html( $group->name ) . '</option>';
            }
        }
        ?>
    </select>
</div>