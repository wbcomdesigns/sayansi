<?php
$business_id = get_the_ID();
$partner_groups = get_post_meta($business_id, 'bp-link-group', true);

$args = array(
    'type'     => 'all',
    'per_page' => 1000,
    'page'     => 1,
    'include'  => $partner_groups,
);

$all_groups = groups_get_groups($args);

if (!empty($all_groups['groups'])) :
?>
    <ul id="groups-list" class="item-list groups-list bp-list list bb-cover-enabled left groups-dir-list grid">
        <?php foreach ($all_groups['groups'] as $group) : 
            if( 'Auto Draft' == $group->name ){
                continue;
            }
            ?>
            <li class="item-entry public group-has-avatar" data-bp-item-id="<?php echo esc_attr($group->id); ?>" data-bp-item-component="groups">
                <div class="list-wrap">
                    <div class="bs-group-cover only-grid-view has-default cover-small">
                        <a href="<?php echo esc_url(bp_get_group_permalink($group)); ?>">
                            <img src="<?php echo esc_url(bp_core_fetch_avatar(['object' => 'group', 'item_id' => $group->id, 'type' => 'cover-image', 'html' => false])); ?>" alt="<?php echo esc_attr($group->name); ?>">
                        </a>
                    </div>
                    
                    <div class="item-avatar">
                        <a href="<?php echo esc_url(bp_get_group_permalink($group)); ?>" class="group-avatar-wrap">
                            <img src="<?php echo esc_url(bp_core_fetch_avatar(['object' => 'group', 'item_id' => $group->id, 'type' => 'full', 'html' => false])); ?>" class="avatar group-<?php echo esc_attr($group->id); ?>-avatar avatar-300 photo" width="300" height="300" alt="<?php echo esc_attr($group->name); ?>">
                        </a>
                    </div>
                    
                    <div class="item">
                        <div class="group-item-wrap">
                            <div class="item-block">
                                <h2 class="list-title groups-title">
                                    <a href="<?php echo esc_url(bp_get_group_permalink($group)); ?>" class="bp-group-home-link">
                                        <?php echo esc_html($group->name); ?>
                                    </a>
                                </h2>
                                <div class="item-meta-wrap has-meta">
                                    <p class="item-meta group-details">
                                        <span class="group-visibility public">Public</span>
                                    </p>
                                    <p class="last-activity item-meta">
                                        <?php echo bp_get_group_last_active($group); ?>
                                    </p>
                                </div>
                            </div>
                            
                            <div class="item-desc group-item-desc only-list-view">
                                <p><?php echo esc_html($group->description); ?></p>
                            </div>
                        </div>

                        <div class="group-footer-wrap">
                            <div class="group-members-wrap">
                                <span class="bs-group-members">
                                    <?php
                                    $group_admins = groups_get_group_admins($group->id);
                                    foreach ($group_admins as $admin) :
                                        ?>
                                        <span class="bs-group-member" data-bp-tooltip-pos="up-left" data-bp-tooltip="<?php echo esc_attr($admin->displayname); ?>">
                                            <a href="<?php echo esc_url(bp_core_get_user_domain($admin->user_id)); ?>">
                                                <img src="<?php echo esc_url(bp_core_fetch_avatar(['item_id' => $admin->user_id, 'type' => 'thumb', 'html' => false])); ?>" alt="<?php echo esc_attr($admin->displayname); ?>" class="round">
                                            </a>
                                        </span>
                                    <?php endforeach; ?>
                                </span>
                            </div>

                            <div class="groups-loop-buttons footer-button-wrap">
                                <div class="bp-generic-meta groups-meta action">
                                    <div id="groupbutton-<?php echo esc_attr($group->id); ?>" class="generic-button">
                                        <button class="group-button join-group button" data-bp-nonce="<?php echo esc_url(wp_nonce_url(bp_get_group_permalink($group) . 'join/', 'join_group')); ?>" data-bp-btn-action="join_group">
                                            Join Group
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
<?php
endif;
?>
