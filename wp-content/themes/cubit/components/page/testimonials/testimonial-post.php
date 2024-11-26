<?php
$image_id = get_field('image', $post->ID);
$position = get_field('position', $post->ID);
$testimonial_text = get_field('testimonial', $post->ID);
$image = get_img($image_id, '(max-width: 750px) 750w, 464px', ['class' => 'object-cover w-full h-full group-hover transition-transform duration-300']);

$testimonial = [
    'image' => $image,
    'position' => $position,
    'name' => get_the_title($post->ID),
    'testimonial' => addslashes($testimonial_text),
]
    ?>
<div class="cursor-pointer swiper-slide group max-w-[480px] w-full md:block shadow-lg p-6" x-data="{hover:false}">
    <p class="overflow-hidden text-xl italic font-bold h-28 max-h-28 testimonial-text text-ellipsis line-clamp-4">
        <?= esc_html($testimonial_text); ?>
    </p>
    <div class="relative py-4 cursor-pointer hover:text-primary" @mouseover="hover = true"
        @mouseover.away="hover = false"
        @click="$dispatch('open-testimonial-modal', <?= json_encode_alpine($testimonial) ?>)">
        Read More
    </div>
    <div class="flex items-center gap-4 mt-4">
        <?php if (!empty($image_id)): ?>
            <div class="w-1/4 text-center">
                <?= get_img($image_id, '100vw', ['class' => 'object-cover w-16 h-16 rounded-full']); ?>
            </div>
        <?php endif; ?>
        <div>
            <h3 class="text-base font-semibold testimonial-name text-neutral-500">
                <?= esc_html($name); ?>
            </h3>
            <p class="text-xs font-normal testimonial-position text-neutral-500">
                <?= esc_html($position); ?>
            </p>

        </div>

    </div>
    <span class="absolute w-16 h-16 right-5 bottom-12 text-primary">
        <?= get_svg('quotation-mark'); ?>
    </span>
</div>