<?php

/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package JobScout
 */

get_header(); ?>

<div id="primary" class="content-area">

	<?php
	/**
	 * Before Posts hook
	 */
	do_action('jobscout_before_posts_content');
	?>

	<main id="main" class="site-main blog-grid">

		<?php if (have_posts()) : ?>
			<div class="blog-grid-wrapper">
				<?php while (have_posts()) : the_post(); ?>
					<article id="post-<?php the_ID(); ?>" <?php post_class('blog-grid-item'); ?>>

						<div class="blog-thumbnail">
							<a href="<?php the_permalink(); ?>">
								<?php the_post_thumbnail('medium_large'); ?>
							</a>
						</div>

						<div class="blog-content">
							<h2 class="blog-title">
								<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
							</h2>

							<div class="blog-excerpt">
								<?php echo wp_trim_words(get_the_excerpt(), 15); ?>
							</div>

							<a href="<?php the_permalink(); ?>" class="read-more">Read More</a>
						</div>

					</article>
				<?php endwhile; ?>
			</div>

		<?php else : ?>
			<?php get_template_part('template-parts/content', 'none'); ?>
		<?php endif; ?>

	</main>

	<?php
	/**
	 * After Posts hook
	 * @hooked jobscout_navigation - 15
	 */
	do_action('jobscout_after_posts_content');
	?>

</div><!-- #primary -->

<?php
// get_sidebar();
get_footer();
