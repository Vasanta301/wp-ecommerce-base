<?php $fields = get_field('info'); ?>
<section id='generic-content'>
    <div class='px-4 py-20'>
        <div class='container'>
            <h2 class="text-xs font-medium tracking-wider text-red-600 uppercase"><?= $fields['subtitle']; ?></h2>
            <h1 class="text-4xl font-bold text-shade-95 font-heading"><?= $fields['title']; ?></h1>
            <div class="pt-4">
                <?= $fields['content']; ?>
            </div>
        </div>
    </div>
</section>