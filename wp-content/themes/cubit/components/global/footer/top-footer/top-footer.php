<?php
$fields = get_field('footer', 'option');
?>
<div class="flex flex-col w-full pb-6 gap-x-4 gap-y-8 lg:items-center lg:justify-between lg:flex-row">
    <div class="relative h-auto max-w-44">
        <?= get_img($fields['logo'], '(min-height: 1024px) 100vw, 33vw', ['class' => "object-contain transition-all duration-300  h-auto"]) ?>
    </div>
    <h3 class="text-4xl font-semibold lg:5xl"><?= $fields['title']; ?></h3>
    <div class="lg:block flex-0 w-max">
        <?php
        $button = $fields['button']['clone_button'];
        $button_styles = [
            'size' => 'sm',
            'button_type' => 'primary',
            'icon' => 'true',
            'class' => 'font-bold px-8',
        ];
        get_global_component_button($button, $button_styles);
        ?>
    </div>
</div>