<div id="who-we-are">
    <div class="relative w-full lg:h-[60vh] h-screen">
        <div class="h-6">
            <?= get_img($fields['image'], '100vw', ['class' => 'absolute w-full h-full inset-0 z-0 object-cover']); ?>
            <div class="absolute inset-0 z-10 bg-black opacity-30"></div>

            <div
                class="absolute inset-x-0 bottom-0 z-10 flex flex-col items-center justify-center h-full p-4 lg:flex-row">
                <div class="flex flex-col justify-center w-full max-w-xl lg:w-1/2">
                    <div class="flex items-center gap-4 pb-7">
                        <hr class="w-4 border border-black">
                        <h2 class="text-xl font-bold text-primary"><?= $fields['subtitle']; ?></h2>
                    </div>
                    <h2 class="text-3xl font-bold text-white md:text-4xl lg:text-5xl pb-7"><?= $fields['title']; ?></h2>
                    <p class="text-base text-white md:text-lg">
                        <?= $fields['description']; ?>
                    </p>

                    <div class="pt-4">
                        <?php
                        $button = $fields['button']['clone_button'];
                        $button_styles = [
                            'size' => 'sm',
                            'button_type' => 'black-primary',
                            'icon' => 'true',
                            'animation' => 'true',
                            'class' => 'font-bold !w-1/2',
                        ];
                        get_global_component_button($button, $button_styles);
                        ?>
                    </div>
                </div>

                <div class="flex flex-col items-center justify-center w-full mt-8 lg:w-1/2 lg:flex-row lg:mt-0">
                    <?php foreach ($fields['metrics'] as $metrices): ?>
                        <div class="flex flex-col items-center mb-4">
                            <div x-data="metricComponent(<?= $metrices['percentage']; ?>, 2000)" x-init="init">
                                <h2 class="pb-2 text-4xl font-bold text-white md:text-5xl"
                                    x-text="Math.round(current) + '%'"></h2>
                            </div>
                            <div class="flex items-center px-4">
                                <h2 class="text-base font-normal text-center text-white"><?= $metrices['title']; ?></h2>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

            </div>

        </div>
    </div>
</div>