<?php

/** 
 * Blog Page 
 */
$GLOBALS['has_padding'] = true;
get_header();

/**
 * If you are using component based approach
 * add section via get_page_component_path as included in content.php
 */

//include (get_template_directory() . '/content.php');

/**
 * If you are using non component based approach
 * add section via get_template_part
 * Changes/add as the code below.
 */
?>

<?php
if (have_posts()): ?>
    <div class="container p-3 px-4 py-20">
        <div class="grid grid-cols-1 gap-8 my-10 md:grid-cols-2 lg:grid-cols-3">
            <?php while (have_posts()):
                the_post();
                get_template_part('components/generic/post/post-card');
                ?>
            <?php endwhile; ?>
        </div>
        <?php the_posts_navigation(); ?>
    <?php else: ?>
        <p><?php _e('Sorry, no posts matched your criteria.'); ?></p>
    </div>
<?php endif; ?>

<?php
/**
 * If you are not ACF for default page content, use following path
 */
//get_template_part('components/generic/page/acf-content');

get_footer();
