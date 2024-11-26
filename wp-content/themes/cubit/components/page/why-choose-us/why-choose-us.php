<section id="why-choose-us">
    <div class="container py-20 lg:py-28 ">
        <div class="flex flex-col items-center content-between w-full max-w-6xl gap-8" x-data="{ visible: false }"
            x-intersect="visible = true">
            <div class="w-full transition-all ease-out transform translate-y-full opacity-0 duration-900"
                :class="{ 'opacity-100 !translate-y-0': visible }">
                <h2 class="flex items-center gap-4 text-xl font-semibold text-primary pb-7">
                    <hr class="w-6 border border-black" /><?= $fields['subtitle']; ?>
                </h2>
                <div>
                    <h2 class="text-5xl font-semibold pb-7">
                        <?= $fields['title']; ?>
                    </h2>
                    <div class="pb-8 prose text-neutral-500">
                        <?= $fields['description']; ?>
                    </div>
                    <div class="relative flex flex-col h-full gap-8 px-8 lg:flex-row ">
                        <div class="w-full h-auto px-2 py-6 text-center lg:w-1/3 bg-stone-100 ">
                            <div class="w-24 h-24 mx-auto">
                                <?= get_img($fields['award']['image'], '', ['class' => 'object-contain']); ?>
                            </div>
                            <h3 class="text-xl font-semibold"><?= esc_html($fields['award']['title']); ?></h3>
                        </div>
                        <div class="w-full lg:w-2/3">
                            <?php if ($fields['bullets']) { ?>
                                <ul class="pb-4 text-left list-none list-check">
                                    <?php foreach ($fields['bullets'] as $bullet) { ?>
                                        <li class="pb-2 items-end:pb-0"><?= $bullet['bullet']; ?></li>
                                    <?php } ?>
                                </ul>
                            <?php } ?>
                            <?php if ($fields['show_button']): ?>
                                <div class="max-w-max">
                                    <?php
                                    $button = $fields['button']['clone_button'];
                                    $button_styles = [
                                        'size' => 'lg',
                                        'button_type' => 'black-primary',
                                        'icon' => 'true',
                                        'animation' => 'true',
                                        'class' => 'font-bold px-8',
                                    ];
                                    get_global_component_button($button, $button_styles);
                                    ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="grid w-full grid-cols-1 gap-4 transition-all duration-700 ease-out transform translate-y-full opacity-0 sm:grid-cols-2 lg:grid-cols-3"
                :class="{ 'opacity-100 !translate-y-0': visible }">
                <?php foreach ($fields['services_grid'] as $key => $grid): ?>
                    <div
                        class="flex flex-col flex-1 h-full px-6 py-4 transition-shadow duration-500 items-left bg-stone-100 group hover:shadow-xl">
                        <div class="flex items-center justify-start w-32 h-full">
                            <?= get_img($grid['image'], '(max-width: 650px) 100vw, 1023 (max-width: 650px) 50vw, 33vw', ['class' => 'object-contain']); ?>
                        </div>
                        <div class="flex flex-col justify-between flex-1 mt-auto">
                            <h2 class="py-4 text-2xl font-medium"><?= $grid['title']; ?></h2>
                            <div class="pb-4 prose prose-neutral-500">
                                <?= $grid['description']; ?>
                            </div>
                            <div class="w-max">
                                <?php
                                $button = $grid['button']['clone_button'];
                                $button_styles = [
                                    'size' => 'sm',
                                    'button_type' => 'text',
                                    'icon' => 'true',
                                    'class' => '!px-0 !justify-start text-primary font-semibold group-hover:text-black',
                                ];
                                get_global_component_button($button, $button_styles);
                                ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>