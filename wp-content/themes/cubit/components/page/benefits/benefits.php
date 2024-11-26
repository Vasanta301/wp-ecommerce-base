<div id="why-choose-us">
    <div class="container pt-20 pb-12 lg:pb-20">
        <div class="border-neutral-500 border-b-[1px]">
            <div class="flex flex-col items-center content-between w-full max-w-6xl gap-8 pb-8 lg:flex-row">
                <div class="w-full">
                    <h2 class="text-6xl font-semibold pb-7">
                        <?= $fields['title']; ?>
                    </h2>
                    <div class="w-full prose prose-pb-8 text-neutral-500">
                        <?= $fields['description']; ?>
                    </div>
                </div>
            </div>
            <?php if (isset($fields['image']) && $fields['image']): ?>
                <div class="w-full pb-8">
                    <?= get_img($fields['image'], '100vw', ['class' => 'object-contain']); ?>
                </div>
            <?php endif; ?>
            <div class="w-full">
                <h4 class="text-2xl font-semibold pb-7">
                    <?= $fields['secondary_title']; ?>
                </h4>
                <?php if (!empty($fields['secondary_description'])): ?>
                    <div class="w-full pb-8 prose text-neutral-500">
                        <?= $fields['secondary_description']; ?>
                    </div>
                <?php endif; ?>
            </div>
            <?php if (!empty($fields['benefits_grid'])): ?>
                <div class="pb-8" x-data="{ visible: false }" x-intersect="visible = true">
                    <div class="grid w-full grid-cols-1 gap-8 transition-all ease-out transform translate-y-full opacity-0 sm:grid-cols-2 lg:grid-cols-3 duration-900"
                        :class="{ 'opacity-100 !translate-y-0': visible }">
                        <?php foreach ($fields['benefits_grid'] as $key => $grid): ?>
                            <div
                                class="flex flex-col flex-1 px-8 py-8 transition-shadow duration-500 items-left bg-stone-100 group hover:shadow-xl">
                                <div class="w-10 h-10 text-primary">
                                    <?= get_benefitsicon($grid['icon']); ?>
                                </div>
                                <h2 class="py-4 text-2xl font-medium"><?= $grid['title']; ?></h2>
                                <div class="pb-4 prose prose-neutral-500">
                                    <?= $grid['description']; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
            <?php if (isset($fields['description_bottom']) && $fields['description_bottom']): ?>
                <div class="w-full pb-10 prose text-neutral-500">
                    <?= $fields['description_bottom']; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>