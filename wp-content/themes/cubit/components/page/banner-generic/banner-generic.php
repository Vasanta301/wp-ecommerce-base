<div id="banner-generic">
    <div class="bg-primary bg-gradient-to-b from-[rgba(246,174,31,1)] to-[rgba(246,174,31,0.9)] bg-left"
        style="background-image: url('<?= get_stylesheet_directory_uri() . '/src/img/white-patterns.webp'; ?>');">
        <div class="container ">
            <div class=" h-[calc(100vh-200px)] max-h-[400px] flex items-center justify-center">
                <div>
                    <h2 class="text-5xl font-semibold text-center lg:text-7xl">
                        <?= $fields['title'] ? $fields['title'] : get_the_title() ?>
                    </h2>
                    <div class="flex justify-center pt-5">
                        <?= wp_breadcrumb(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>