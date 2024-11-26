<?php if ($fields = get_field('logo_grid', 'option')): ?>
    <div id="logo-grid">
        <div class="bg-primary">
            <div class="container flex flex-wrap items-center justify-center gap-5 py-20">
                <?php foreach ($fields['logos'] as $logo_item): ?>
                    <div class="flex items-center justify-center px-4">
                        <div class="w-full h-32 overflow-hidden aspect-square lg:h-40">
                            <?= get_img($logo_item['logo'], '(max-width: 1920px) 100vw, 1920px', ['class' => 'object-cover w-full h-full']); ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
<?php endif; ?>