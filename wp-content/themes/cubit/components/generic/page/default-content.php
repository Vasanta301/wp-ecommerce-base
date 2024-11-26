<section id='default-content'>
    <div class='container py-8 text-ellipsis'>
        <h1 class="pb-4 text-6xl font-semibold leading-snug text-slate-900"><?= get_the_title(); ?></h1>
        <div class="pt-4 prose">
            <?php the_content(); ?>
        </div>
    </div>
</section>