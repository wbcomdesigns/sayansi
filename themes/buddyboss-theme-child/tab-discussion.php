<div id="bbpress-forums">
<!-- Topics List -->
	<?php if ( bbp_has_topics('paged=1') ) : ?>

		<?php bbp_get_template_part( 'loop', 'topics' ); ?>

		<?php bbp_get_template_part( 'pagination', 'topics' ); ?>

	<?php else : ?>

		<?php bbp_get_template_part( 'feedback', 'no-topics' ); ?>

	<?php endif; ?>
</div>