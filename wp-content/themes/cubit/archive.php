<?php
/*
Template Name: Archives
*/
get_header(); ?>

<div id="archive">
	<div id="content">
		<div class="container px-4 py-20 my-10 ">
			<div class="grid grid-cols-1 gap-8 my-10 md:grid-cols-3">
				<h1 class="text-4xl font-bold text-gray-600 uppercase md:col-span-2">
					<?php
					if (is_category()) {
						// Display the category title
						single_cat_title();
					} elseif (is_tag()) {
						// Display the tag title
						single_tag_title();
					} elseif (is_author()) {
						// Display the author name
						the_post();
						echo 'Author: ' . get_the_author();
						rewind_posts();
					} elseif (is_day()) {
						// Display the daily archives title
						echo 'Daily Archives: ' . get_the_date();
					} elseif (is_month()) {
						// Display the monthly archives title
						echo 'Monthly Archives: ' . get_the_date('F Y');
					} elseif (is_year()) {
						// Display the yearly archives title
						echo 'Yearly Archives: ' . get_the_date('Y');
					} else {
						// Default fallback title
						echo 'Archives';
					}
					?>
				</h1>
				<div class="lg:col-span-1">
				</div>
			</div>

			<?php if (have_posts()) : ?>
				<div class="grid grid-cols-1 gap-8 my-10 md:grid-cols-2 lg:grid-cols-3">
					<?php while (have_posts()) : the_post();
						get_template_part('components/generic/post/post-card');
					?>
					<?php endwhile; ?>
				</div>
				<?php the_posts_navigation(); ?>
			<?php else : ?>
				<p><?php _e('Sorry, no posts matched your criteria.'); ?></p>
			<?php endif; ?>
		</div><!-- #content -->
	</div><!-- #container -->
</div>
<?php get_footer(); ?>