<?php
$pages = paginate_links_custom(get_query_var('url_append', ''));
?>

<?php if ($pages) : ?>
<nav class="border-t border-black border-opacity-30 px-4 flex items-center justify-between sm:px-0">
  <div class="-mt-px w-0 flex-1 flex">
    <?php if ($pages['prev']) : ?>
    <a href="<?= $pages['prev'] ?>"
      class="relative group pt-4 pr-1 inline-flex items-center text-sm font-medium text-black">
      <div class="mr-3 h-5 w-5">
        <?= get_icon('arrow-narrow-left') ?>
      </div>
      Previous
      <div class="absolute w-0 left-1/2 -translate-x-1/2 -bottom-0.5 h-px bg-current transition-all group-hover:w-full">
      </div>
    </a>
    <?php endif; ?>
  </div>
  <div class="hidden md:-mt-px md:flex">
    <?php foreach ($pages['numbers'] as $num) : ?>
    <?php if ($num['type'] == 'num') : ?>
    <a href="<?= $num['current'] ? 'javascript:void(0);' : $num['link'] ?>"
      class="relative pt-4 px-4 inline-flex items-center text-sm font-medium group"
      <?= $num['current'] ? 'aria-current="page"' : '' ?>>
      <?= $num['num'] ?>
      <div
        class="absolute <?= $num['current'] ? 'w-3' : 'w-0' ?> left-1/2 -translate-x-1/2 -bottom-0.5 h-px bg-current transition-all group-hover:w-3">
      </div>
    </a>
    <?php endif; ?>
    <?php if ($num['type'] == 'dots') : ?>
    <span class="text-black pt-4 px-4 inline-flex items-center text-sm font-medium">
      ...
    </span>
    <?php endif; ?>
    <?php endforeach; ?>
  </div>
  <div class="-mt-px w-0 flex-1 flex justify-end">
    <?php if ($pages['next']) : ?>
    <a href="<?= $pages['next'] ?>"
      class="relative group pt-4 pl-1 inline-flex items-center text-sm font-medium text-black">
      Next
      <div class="ml-3 h-5 w-5">
        <?= get_icon('arrow-narrow-right') ?>
      </div>
      <div class="absolute w-0 left-1/2 -translate-x-1/2 -bottom-0.5 h-px bg-current transition-all group-hover:w-full">
      </div>
    </a>
    <?php endif; ?>
  </div>
</nav>
<?php endif; ?>