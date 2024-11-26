<section id="about-us">
    <div class="bg-gray-100 bg-left bg-opacity-90"
        style="background-image: linear-gradient(rgba(0, 0, 0, 0), rgba(0, 0, 0, 0)), url('<?= get_stylesheet_directory_uri() . '/src/img/white-patterns.webp'; ?>');">
        <div class="container py-20">

            <div class="flex flex-col items-center content-between w-full max-w-6xl gap-8 lg:flex-row"
                x-data="{ visible: false }" x-intersect="visible = true">

                <div class="relative w-full h-auto lg:w-1/2">
                    <?= get_img($fields['images']['primary'], '', [
                        'class' => 'object-cover object-center w-full lg:w-5/6 h-[550px] transition-all duration-1000 ease-out delay-1000 transform -translate-x-full opacity-0',
                        'x-data' => '{ visible: false }',
                        'x-intersect' => 'visible = true',
                        ':class' => "{ 'opacity-100 translate-x-0': visible }",
                    ]); ?>

                    <div class="absolute right-0 w-3/6 bg-center bg-no-repeat bg-cover border-8 border-white cursor-pointer top-1/4 h-72"
                        style="background-image:url('<?= wp_get_attachment_image_url($fields["images"]["secondary"], "full"); ?>');"
                        x-data="{ open: false }">
                        <?php
                        /***
                        <div class="flex items-center justify-center h-full">
                            <span @click="open = true"
                                class="transition-colors rounded-full bg-primary w-14 h-14 hover:bg-transparent hover:border hover:border-black group">
                                <span
                                    class="w-2 h-2 text-black group-hover:text-white"><?= get_svg(svg_name: 'play'); ?></span>
                            </span>
                        </div>
                        <!-- Modal -->
                        <div x-show="open" x-cloak
                            class="fixed inset-0 z-50 flex items-center justify-center transition-opacity duration-300 ease-out bg-black bg-opacity-75 lg:w-full">

                            <!-- Close Button -->
                            <button @click="open = false" aria-label="Close modal"
                                class="absolute px-4 py-2 text-white transition ease-in-out delay-150 top-5 lg:right-5 right-1">
                                &#10005;
                                <div
                                    class="absolute inset-0 bg-gray-400 border border-gray-100 rounded-full opacity-50 hover:bg-black hover:text-white">
                                </div>
                            </button>

                            <!-- Modal Container -->
                            <div x-data="ModalVideo()"
                                class="relative w-full max-w-full mx-4 overflow-hidden bg-white rounded-lg shadow-lg sm:max-w-lg lg:max-w-4xl lg:h-1/2 h-1/3">
                                <!-- Video Container with Aspect Ratio -->
                                <div class="relative w-full h-full" data-url="<?= $fields["images"]['video_link']; ?>"
                                    x-ref="video" data-type="youtube">
                                </div>
                            </div>
                        </div>
                         ***/
                        ?>
                    </div>
                </div>



                <div class="w-full h-auto lg:w-1/2">
                    <div class="flex items-center gap-4 pb-7">
                        <hr class="w-4 border border-black">
                        <h2 class="text-xl font-bold text-primary"><?= $fields['subtitle']; ?></h2>
                    </div>
                    <h2 class="text-3xl font-bold text-black md:text-4xl lg:text-5xl pb-7"><?= $fields['title']; ?></h2>
                    <div class="flex flex-col gap-4 lg:flex-row">
                        <div class="w-full lg:w-1/2">
                            <div class="prose text-neutral-500">
                                <?= $fields['description']; ?>
                            </div>
                        </div>
                        <div class="w-full overflow-hidden border-l border-black lg:w-1/2">
                            <div class="py-4 pl-4"
                                x-data="{ current: 0, target:  <?= $fields['x_years_of_experience']['number']; ?>, time: 2000}"
                                x-init="() => {
                                start = current; 
                                const interval = Math.max(time / (target - start), 5); 
                                const step = (target - start) / (time / interval);  
                                const handle = setInterval(() => {
                                    if(current < target) 
                                        current += step
                                    else {
                                        clearInterval(handle);
                                        current = target
                                    }   
                                }, interval)
                            }">
                                <div class="flex items-center gap-2 transition-all duration-700 ease-out delay-500 transform -translate-x-full opacity-0"
                                    :class="{ 'opacity-100 !translate-x-0': visible }">
                                    <span class="text-6xl font-bold text-primary"
                                        x-text="Math.round(current) + '+'">0</span>
                                    <span class="font-medium"> <?= $fields['x_years_of_experience']['title']; ?></span>
                                </div>
                            </div>
                            <div class="border-t border-black">
                                <div class="transition-all duration-700 ease-out delay-500 transform -translate-x-full opacity-0"
                                    :class="{ 'opacity-100 translate-x-0': visible }">
                                    <div class="py-4 pl-4">
                                        <h3 class="font-medium"><?= $fields['our_vision']['title']; ?></h3>
                                        <div class="prose text-neutral-500"><?= $fields['our_vision']['description']; ?>
                                        </div>
                                    </div>
                                </div>
                                <?php if ($fields['show_our_mission']): ?>
                                    <div class="transition-all duration-700 ease-out delay-500 transform -translate-x-full opacity-0"
                                        :class="{ 'opacity-100 translate-x-0': visible }">
                                        <div class="py-4 pl-4">
                                            <h3 class="font-medium"><?= $fields['our_mission']['title']; ?></h3>
                                            <div class="prose text-neutral-500">
                                                <?= $fields['our_mission']['description']; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php
                    if ($fields['show_button']): ?>
                        <div class="pt-4">
                            <?php
                            $button = $fields['button']['clone_button'];
                            $button_styles = [
                                'size' => 'sm',
                                'button_type' => 'black-primary',
                                'icon' => 'true',
                                'animation' => 'true',
                                'class' => 'font-bold !w-1/2 lg:!w-1/4 ',
                            ];
                            get_global_component_button($button, $button_styles);
                            ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>
</section>