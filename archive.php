<?php get_header(); ?>

	<div class="content">

		<?php
		if ( have_posts() ) : ?>

            <?php
                the_archive_title( '<h1 class="page-title">', '</h1>' );
                the_archive_description( '<div class="archive-description">', '</div>' );
            ?>

			<?php

			while ( have_posts() ) : the_post();

				get_template_part( 'partials/content', get_post_format() );

			endwhile;

			the_posts_navigation();

		else :

			get_template_part( 'partials/content', 'none' );

		endif; ?>

	</div>

<?php get_footer(); ?>
