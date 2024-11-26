<?php if ($fields_global = get_field('contact_us_cta', 'option')): ?>


    <div id="contact-us-cta">
        <div class="bg-left bg-opacity-90 relative"
            style="background-color: black; background-image: linear-gradient(rgba(0, 0, 0, 0), rgba(0, 0, 0, 0)), url('<?= get_stylesheet_directory_uri() . '/src/img/white-patterns.webp'; ?>');">

            <div class="container relative flex flex-col lg:flex-row items-center h-full p-4 lg:p-0">

                <div class="lg:my-9 mt-9 z-10 text-center lg:text-left lg:w-1/2">
                    <div x-data="{ visible: false }" x-intersect="visible = true"
                        class="transition-all duration-700 ease-out delay-500 transform translate-y-full opacity-0"
                        :class="{ 'opacity-100 !translate-y-0': visible }">
                        <div class="max-w-sm mx-auto lg:mx-0">
                            <h2 class="text-3xl md:text-4xl lg:text-5xl font-semibold text-white pb-7">
                                <?= $fields_global['title']; ?>
                            </h2>
                        </div>
                    </div>
                    <p class="text-white max-w-lg mx-auto lg:mx-0 text-base md:text-lg">
                        <?= $fields_global['description']; ?>
                    </p>

                    <div class="pt-4 lg:block flex justify-center">
                        <?php
                        $button = $fields_global['button']['clone_button'];
                        $button_styles = [
                            'size' => 'sm',
                            'button_type' => 'white',
                            'icon' => 'true',
                            'animation' => 'true',
                            'class' => 'font-bold !w-1/2',
                        ];
                        get_global_component_button($button, $button_styles);
                        ?>
                    </div>
                </div>

                <div
                    class="lg:absolute right-0 top-1/2 transform lg:-translate-y-1/2 translate-y-[15%] lg:w-2/5 lg:mt-8 lg:mt-0">
                    <?= get_img($fields['image'], '', [
                        'class' => 'transition-all duration-1000 ease-out delay-1000 transform translate-x-full opacity-0',
                        'x-data' => '{ visible: false }',
                        'x-intersect' => 'visible = true',
                        ':class' => "{ 'opacity-100 !translate-x-0': visible }",
                    ]); ?>
                </div>

            </div>
        </div>
    </div>
<?php endif; ?>