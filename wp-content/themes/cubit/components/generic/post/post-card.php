<article id="post-<?php the_ID(); ?>" <?php post_class('max-w-xs overflow-hidden shadow transition hover:shadow-lg'); ?>>
    <?php
    /** 
     * 
     * Always retreive image via 
     * get_img($image_id, 'sizes', ['class array']);
     * 
     * Note : Following image is default image for generic example content
     */
    ?>
    <div class="w-full overflow-hidden transition cursor-crosshair hover:scale-105 ">
        <?php
        $thumbnail_id = get_post_thumbnail_id(get_the_ID());
        if ($thumbnail_id) {
            get_img($thumbnail_id, 'object-cover w-full h-56', ['full']);
        } else {
        ?>
            <img src="<?= get_stylesheet_directory_uri() . '/src/img/default.jpg'; ?>" class="object-cover" />
        <?php
        }
        ?>
    </div>
    <div class="p-2 bg-white sm:p-4">
        <h2 class="mb-4 text-2xl font-semibold post-title">
            <?php the_title(); ?>
        </h2>
        <div class="text-gray-700 post-content">
            <?php the_excerpt(); ?>
        </div>
        <div class="my-4">
            <?php
            /**
             * Replace $button with fields value 
             * E.g. - $button = $fields['button']['clone_button'];
             */
            $button = [
                'text' => 'View Post',
                'type' => 'internal',
                'link_to' => get_post(get_the_ID())
            ];
            $button_styles = [
                'size' => 'sm',
                'button_type' => 'primary',
                'attr' => '',
                'class' => 'w-full',
            ];
            get_global_component_button($button, $button_styles);
            ?>
        </div>
    </div>
</article>