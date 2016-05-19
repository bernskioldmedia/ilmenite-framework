<?php
/**
 * Template Name: Fullwidth
 **/

esc_html_x( 'Fullwidth', 'frontpage page template name', 'THEMETEXTDOMAIN' );

get_header(); ?>

<main class="main" role="main">

	<?php if ( have_posts() ) : ?>

		<?php while ( have_posts() ) : the_post(); ?>

			<?php get_template_part( 'content', 'page' ); ?>

		<?php endwhile; ?>

	<?php endif; ?>

</main>

<?php get_footer(); ?>