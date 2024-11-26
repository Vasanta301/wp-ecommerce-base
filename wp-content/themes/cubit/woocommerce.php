<?php
$GLOBALS['has_padding'] = true;
get_header(); ?>

<div class='container px-4 pt-20'>
    <div class="py-10">
        <?php
        if (have_posts()):
            woocommerce_content();
        endif; // End the loop.
        ?>
    </div>
</div>

<?php
get_footer();
