<div id="banner-home-page">
    <div class="bg-primary bg-gradient-to-b from-[rgba(246,174,31,1)] to-[rgba(246,174,31,0.9)] "
        style="background-image: url('<?= get_stylesheet_directory_uri() . '/src/img/white-patterns.webp'; ?>');">
        <div class="container">
            <div
                class="relative flex flex-col items-center justify-center w-full lg:pb-16 lg:h-[calc(100vh-200px)] h-[calc(100vh-100px)] max-h-[800px] lg:justify-start lg:flex-row">
                <div class="z-10 w-full lg:w-1/2">
                    <h2 class="flex items-center gap-4 text-xl font-semibold text-white pb-7">
                        <hr class="w-6 border border-black" /><?= $fields['subtitle']; ?>
                    </h2>
                    <div x-data="{ visible: false }" x-intersect="visible = true"
                        class="transition-all duration-700 ease-out delay-500 transform translate-y-full opacity-0"
                        :class="{ 'opacity-100 !translate-y-0': visible }">
                        <h2 class="text-5xl font-semibold lg:text-7xl pb-7"><?= nl2br($fields['title']); ?></h2>
                        <div class="pb-8 prose prose-white">
                            <?= $fields['description']; ?>
                        </div>
                        <div class="flex flex-col gap-8 lg:flex-row group lg:w-2/4 md:w-1/3">
                            <?php
                            $button = $fields['button']['clone_button'];
                            $button_styles = [
                                'size' => 'sm',
                                'button_type' => 'black',
                                'icon' => 'true',
                                'animation' => 'true',
                                'class' => 'font-bold px-8   ',
                            ];
                            get_global_component_button($button, $button_styles);
                            ?>
                        </div>
                    </div>
                </div>
                <!-- <?= get_img($fields['image'], '', ['class' => 'absolute -bottom-8 lg:bottom-auto lg:translate-y-1/3 lg:translate-x-1/3']); ?> -->
                <div class="lg:absolute xxs:relative -bottom-20 md:-bottom-4 lg:bottom-auto ">
                    <div
                        class="w-full  custom-md:w-3/4 custom-lg:w-11/12 custom-lg:translate-x-1/3 custom-md:translate-x-[13%] translate-x-0 xs:translate-y-[13%] xxs:-translate-y-1/4 lg:translate-y-1/3  lg:translate-x-[45%]">
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
    </div>
</div>