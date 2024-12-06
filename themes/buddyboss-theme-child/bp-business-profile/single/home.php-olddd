<?php
$business_id = get_the_ID();
?>

<div class="profile-home-header">
    <?php $get_feature_img = get_post_meta( $business_id, 'feature_image', true );
    if( $get_feature_img ){
    ?>
    <img src="<?php echo esc_url( $get_feature_img ); ?>" alt="logo" width="500" height="600">
    <?php } else{
        echo '<p>No Feature Image Found</p>';
    }?>
</div>

<div class="profile-home-column">
    <div class="profile-home-columns">
        <a href="<?php echo esc_url( get_post_meta( $business_id, 'column_one_link', true ) ); ?>">
            <div class="profile-home-columns-img">
                <?php
                $column_one_logo = get_post_meta( $business_id, 'column_one_logo', true );
                if( $column_one_logo ){
                ?>
                <img src="<?php echo esc_url( $column_one_logo ); ?>" alt="logo" width="500" height="600"> 
                <?php } else{ 
                    echo '<p>No Column one Logo Found</p>';
                }?>
                </div>
                <?php
                $column_one_title = get_post_meta( $business_id, 'column_one_title', true );
                if( $column_one_title ){					
                ?>
                <h3 class="profile-home-columns-title"><?php echo get_post_meta( $business_id, 'column_one_title', true ); ?></h3>
                <?php } else{
                    echo '<p>No Column one Tiltle Found</p>';
                }
                $column_one_desc = get_post_meta( $business_id, 'column_one_description', true );
                if($column_one_desc){
                ?>
                <div class="profile-home-columns-content"><?php echo $column_one_desc; ?></div>
                <?php } else{
                    echo '<p>No Column one Content Found</p>';
                }?>
        </a>
    </div>
    <div class="profile-home-columns">
        <a href="<?php echo esc_url( get_post_meta( $business_id, 'column_two_link', true ) ); ?>">
            <div class="profile-home-columns-img">
                <?php
                $column_two_logo = get_post_meta( $business_id, 'column_two_logo', true );
                if( $column_two_logo ){
                ?>
                <img src="<?php echo esc_url( $column_two_logo ); ?>" alt="logo" width="500" height="600"> 
                <?php } else{ 
                    echo '<p>No Column two Logo Found</p>';
                }?>
            </div>
            <?php
            $column_two_title = get_post_meta( $business_id, 'column_two_title', true );
            if( $column_two_title ){					
            ?>
            <h3 class="profile-home-columns-title"><?php echo get_post_meta( $business_id, 'column_two_title', true ); ?></h3>
            <?php } else{
                echo '<p>No Column two Tiltle Found</p>';
            }
            $column_two_desc = get_post_meta( $business_id, 'column_two_description', true );
            if($column_two_desc){
            ?>
            <div class="profile-home-columns-content"><?php echo $column_two_desc; ?></div>
            <?php } else{
                echo '<p>No Column two Content Found</p>';
            }?>
        </a>
    </div>
    <div class="profile-home-columns">
        <a href="<?php echo esc_url( get_post_meta( $business_id, 'column_three_link', true ) ); ?>">
            <div class="profile-home-columns-img">
                <?php
                $column_three_logo = get_post_meta( $business_id, 'column_three_logo', true );
                if( $column_three_logo ){
                ?>
                <img src="<?php echo esc_url( $column_three_logo ); ?>" alt="logo" width="500" height="600"> 
                <?php } else{ 
                    echo '<p>No Column three Logo Found</p>';
                }?>
            </div>
            <?php
            $column_three_title = get_post_meta( $business_id, 'column_three_title', true );
            if( $column_three_title ){					
            ?>
            <h3 class="profile-home-columns-title"><?php echo get_post_meta( $business_id, 'column_three_title', true ); ?></h3>
            <?php } else{
                echo '<p>No Column three Tiltle Found</p>';
            }
            $column_three_desc = get_post_meta( $business_id, 'column_three_description', true );
            if($column_three_desc){
            ?>
            <div class="profile-home-columns-content"><?php echo $column_three_desc; ?></div>
            <?php } else{
                echo '<p>No Column three Content Found</p>';
            }?>
        </a>
    </div>
     <div class="profile-home-columns">
        <a href="<?php echo esc_url( get_post_meta( $business_id, 'column_four_link', true ) ); ?>">
            <div class="profile-home-columns-img">
                <?php
                $column_four_logo = get_post_meta( $business_id, 'column_four_logo', true );
                if( $column_four_logo ){
                ?>
                <img src="<?php echo esc_url( $column_four_logo ); ?>" alt="logo" width="500" height="600"> 
                <?php } else{ 
                    echo '<p>No Column four Logo Found</p>';
                }?>
            </div>
            <?php
            $column_four_title = get_post_meta( $business_id, 'column_four_title', true );
            if( $column_four_title ){					
            ?>
            <h3 class="profile-home-columns-title"><?php echo get_post_meta( $business_id, 'column_four_title', true ); ?></h3>
            <?php } else{
                echo '<p>No Column four Tiltle Found</p>';
            }
            $column_four_desc = get_post_meta( $business_id, 'column_four_description', true );
            if($column_four_desc){
            ?>
            <div class="profile-home-columns-content"><?php echo $column_four_desc; ?></div>
            <?php } else{
                echo '<p>No Column four Content Found</p>';
            }?>
        </a>
    </div>
</div>