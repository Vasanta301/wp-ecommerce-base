<?php if ($fields_global = get_field('what_we_offer', 'option')): ?>
    <div id="what-we-offer">
        <div class="relative bg-center bg-no-repeat bg-cover bg-gray-50 py-28"
            style="background-image: linear-gradient(rgba(0, 0, 0, 0), rgba(0, 0, 0, 0)), url('<?= get_stylesheet_directory_uri() . '/src/img/background.png'; ?>');">
            <div class="py-20 xxs:py-[1.8rem] container">
                <div>
                    <div class="flex items-center gap-4 pb-7">
                        <hr class="w-4 border border-black">
                        <h2 class="text-xl font-bold text-primary"><?= esc_html($fields_global['subtitle']); ?></h2>
                    </div>

                    <div class="flex flex-col lg:flex-row">
                        <div class="lg:w-1/2">
                            <h2 class="lg:text-[2.75rem] text-4xl font-semibold text-black pb-7">
                                <?= esc_html($fields_global['title']); ?>
                            </h2>
                        </div>
                        <div class="lg:w-1/2">
                            <p class="text-black">
                                <?= esc_html($fields_global['description']); ?>
                            </p>
                        </div>
                    </div>

                    <div class="grid items-center justify-center gap-8 py-4 mt-8 lg:grid-cols-3 md:grid-cols-2 lg:mt-0">
                        <?php
                        $counter = 1;

                        foreach ($fields['usp_grid'] as $grids): ?>
                            <div
                                class="relative flex flex-col items-center h-full p-4 transition bg-gray-100 group hover:shadow-xl">
                                <div class="flex-grow">
                                    <div class="w-10 px-2 bg-primary ">
                                        <div class="text-lg text-white">
                                            <?= sprintf('%02d.', $counter); ?>
                                        </div>
                                    </div>
                                    <div class="mt-4 text-xl font-semibold text-black pb-7 ">
                                        <h2>
                                            <?= esc_html($grids['title']); ?>
                                        </h2>
                                    </div>
                                    <div class="pb-8 prose text-neutral-500">
                                        <?= esc_html($grids['description']); ?>
                                    </div>
                                </div>
                            </div>
                            <?php
                            $counter++;
                        endforeach; ?>
                    </div>




                </div>
            </div>
        </div>
    </div>
<?php endif; ?>