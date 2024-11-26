<div class="container">
    <article id="post-<?php the_ID(); ?>" <?php post_class('bg-white'); ?>>
        <div class="py-6 mx-auto">
            <div class="py-6">
                <?= get_img(get_post_thumbnail_id(), '', ['object-cover object-center w-full h-auto']); ?>
                <div class="mt-2">
                    <h1 class="font-bold md:text-3xl lg:text-3xl xl:text-4xl"><?php the_title(); ?></h1>
                </div>
                <div class="mt-4 prose">
                    <?php the_content(); ?>
                </div>
            </div>
        </div>
    </article>
    <nav class="flex justify-between">
        <div class="w-1/2">
            <?php previous_post_link('%link', '<div class="px-4 py-2 transition bg-gray-200 rounded-lg shadow hover:bg-gray-300">%title</div>', true); ?>
        </div>
        <div class="w-1/2 text-right">
            <?php next_post_link('%link', '<div class="px-4 py-2 transition bg-gray-200 rounded-lg shadow hover:bg-gray-300">%title</div>', true); ?>
        </div>
    </nav>
</div>