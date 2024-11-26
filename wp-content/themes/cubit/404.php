<?php
get_header();

$fields = get_field('404_page', 'option');
?>

<section id="page-404">
  <div class="mx-auto">
    <div class="relative min-h-[calc(100vh-20rem)]">
      <div class="absolute inset-0 bg-shade-95">
        </div>
        <div class="relative min-h-[350px] 2xl:min-h-[45vh] py-12 2xl:py-16 4k:py-20 container-banner flex items-end text-skin-light">
          <div>
          <div class="flex flex-row justify-center w-40 h-40">
            <?= get_svg('404') ?>
          </div>
          <a href="<?= home_url() ?>" class="inline-flex items-center gap-2 mt-16 text-2xl btn-secondary font-heading hover:animate-pulse">
            <span class="w-6 h-6">
              <?= get_icon('arrow-left') ?>
            </span>
            <span><?= !empty($fields['button_text']) ? $fields['button_text'] : 'Go Back To Home Page' ?></span>
          </a>
        </div>
      </div>
    </div>
  </div>
</section>

<?php
get_footer();
