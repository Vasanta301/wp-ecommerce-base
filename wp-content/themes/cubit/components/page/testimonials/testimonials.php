<section id="testimonials">
    <div class="bg-stone-50">
        <div class="container py-16 md:py-24">
            <div>
                <div class="flex items-center gap-4 pb-7">
                    <hr class="w-4 border border-black">
                    <h2 class="text-xl font-bold text-primary"><?= $fields['subtitle']; ?></h2>
                </div>
                <div class="flex flex-col lg:flex-row ">
                    <div class="lg:w-1/2">
                        <h2 class="lg:text-[2.75rem] text-4xl font-semibold text-black pb-7"><?= $fields['title']; ?>
                        </h2>
                    </div>
                    <div class="lg:w-1/2">
                        <p class="text-neutral-500">
                            <?= $fields['description']; ?>
                        </p>
                    </div>
                </div>
                <div class="my-6 border-b-[1px] border-b-black"></div>
            </div>
            <?php
            $testimonials = get_posts(array(
                'post_type' => 'testimonial',
                'numberposts' => -1,
            ));

            if ($testimonials) {
                ?>
                <div class="relative" x-data="Testimonial()" x-init="init()">
                    <div class="swiper-container" x-ref="swiperContainer">
                        <div class="swiper-wrapper">
                            <?php
                            // for ($i = 0; $i < 5; $i++) {
                            foreach ($testimonials as $post):
                                $testimonial = get_field('testimonial', $post->ID);
                                $name = get_field('name', $post->ID);
                                $position = get_field('position', $post->ID);
                                $image = get_field('image', $post->ID);
                                ?>
                                <?php
                                include get_component_path('page/testimonials/testimonial-post');
                                ?>
                            <?php endforeach;
                            // } ?>
                        </div>
                    </div>
                </div>
                <?php
            }
            ?>
            <div class="pt-8">
                <div class="text-sm font-medium leading-4 tracking-wide text-center uppercase lg:hidden sm:block">
                    Swipe to browse
                </div>
            </div>
        </div>
    </div>
    <?php include_once get_component_path('global/modal-testimonial/modal-testimonial') ?>
</section>