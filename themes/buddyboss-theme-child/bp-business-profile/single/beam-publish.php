<?php if ( have_posts() ) : ?>
		<?php
		while ( have_posts() ) :
			the_post();
			global $post;
			$selected_group = get_post_meta( $post->ID, 'bp_blog_pro_group_links', true );
			if ( $selected_group == $current_group_id ) {
				?>
			<div id="post-<?php the_ID(); ?>" class="<?php echo esc_attr( 'bpmb-blog-post' ); ?>">				
					<div class="post-featured-image">
						<a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php esc_html_e( 'Permanent Link to', 'buddypress-member-blog-pro' ); ?> <?php the_title_attribute(); ?>">
							<?php if ( function_exists( 'has_post_thumbnail' ) && has_post_thumbnail( get_the_ID() ) ) : ?>
								<?php the_post_thumbnail(); ?>
							<?php else : ?>
								<img src="<?php echo esc_url( BUDDYPRESS_MEMBER_BLOG_PLUGIN_URL ) . 'public/images/no-post-image.jpg'; ?>" class="attachment-thumbnail size-thumbnail wp-post-image" alt="<?php the_title(); ?>" width="150" height="150">
							<?php endif; ?>
						</a>
					</div>
				<div class="post-content">

					<h3 class="entry-title">
						<a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php esc_html_e( 'Permanent Link to', 'buddypress-member-blog-pro' ); ?> <?php the_title_attribute(); ?>"><?php the_title(); ?></a>
						<?php
						if ( $user_id == $post->post_author ) :
							?>
							<span class="bp-edit-post"><?php echo wp_kses_post( bp_member_blog_get_edit_link() ); ?></span>
						<?php endif; ?>
					</h3>
					<div class="post-date">
						<?php
						/* translators: %s: Category List*/
						printf( esc_html__( '%1$s', 'buddypress-member-blog-pro' ), get_the_date(), wp_kses_post( get_the_category_list( ', ' ) ) );
						?>
					</div>
					<div class="entry-content">
						<?php the_excerpt( __( 'Read the rest of this entry &rarr;', 'buddypress-member-blog-pro' ) ); ?>
						<?php
						wp_link_pages(
							array(
								'before'         => '<div class="page-link"><p>' . __( 'Pages: ', 'buddypress-member-blog-pro' ),
								'after'          => '</p></div>',
								'next_or_number' => 'number',
							)
						);
						?>
					</div>
					<?php
					if ( $user_id == $post->post_author ) :
						$bpmb_pro_groups_instance = Buddypress_Member_Blog_Pro_Groups::instance();
						$bpmb_pro_groups_instance->bp_member_blog_pro_action_links( $post );
					endif;
					?>
				</div>
			</div>
		<?php } endwhile; ?>
			<div class="navigation pagination">
				<?php bp_member_blog_paginate(); ?>
			</div>
		<?php
		else :
			if ( is_user_logged_in() ) {
				$bp_template_option = bp_get_option( '_bp_theme_package_id' );
				$current_group_name = $bp->groups->current_group->name;
				if ( 'nouveau' === $bp_template_option ) {
					echo '<div id="message" class="info bp-feedback bp-messages bp-template-notice">';
					echo '<span class="bp-icon" aria-hidden="true"></span>';
				} else {
					echo '<div id="message" class="info">';
				}
				/* translators: %1$s is replaced with login user full name, %2$1s is replaced with Current Group Name */
				echo '<p>' . sprintf( esc_html__( '%1$s has not posted anything in the group %2$1s.', 'buddypress-member-blog-pro' ), esc_html( $user_full_name ), esc_html( $current_group_name ) ) . '</p>';
				echo '</div>';
			} else {
				$current_group_name = $bp->groups->current_group->name;
				$bp_template_option = bp_get_option( '_bp_theme_package_id' );
				if ( 'nouveau' === $bp_template_option ) {
					echo '<div id="message" class="info bp-feedback bp-messages bp-template-notice">';
					echo '<span class="bp-icon" aria-hidden="true"></span>';
				} else {
					echo '<div id="message" class="info">';
				}
				/* translators: %s is replaced with Current Group Name */
				echo '<p>' . sprintf( esc_html__( "%s doesn't have any posts.", 'buddypress-member-blog-pro' ), esc_html( $current_group_name ) ) . '</p>';
				echo '</div>';
			}
			?>
	<?php endif; ?>