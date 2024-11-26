<?php
$fields = get_field('contact_form', 'option');
?>
<div id="contact-form">
    <div class="z-0 bg-gray-100 bg-bottom bg-cover"
        style="background-image: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('<?= get_stylesheet_directory_uri() . '/src/img/contact-form-bg.jpg'; ?>');">
        <div class="container relative z-20 py-28">
            <div class="flex flex-col gap-4 text-white">
                <h2 class="pb-8 text-5xl font-bold text-center"><?= $fields['title']; ?></h2>
                <div class="prose text-center text-white prose-base">
                    <?= $fields['description']; ?>
                </div>
                <div class="pt-4">
                    <h2 class="pb-8 text-4xl font-semibold text-center">
                        <?= $fields['subtitle']; ?>
                    </h2>
                    <?= do_shortcode($fields['short_code']) ?>
                </div>
            </div>
        </div>
    </div>
</div>