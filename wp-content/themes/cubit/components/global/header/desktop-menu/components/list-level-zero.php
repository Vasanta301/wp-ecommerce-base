<div class="min-w-[220px] w-[16%] h-full py-6 4k:py-12 px-5 4k:px-10 bg-skin-dark border-r border-black">
  <p class="font-medium">
    Browse by
  </p>
  <div class="mt-2 flex flex-col items-start">
    <?php foreach ($complex_menu['menu']['items'] as $index0 => $menu0): ?>
      <button
        class="relative block py-2 text-sm leading-5 hover:font-medium after:content-[''] after:absolute after:bottom-1.5 after:left-0 after:w-0 hover:after:w-full after:h-px after:bg-current after:transition-all"
        :class="index0 == <?= $index0 ?> && 'font-medium after:w-full'" @click="select(0, <?= $index0 ?>)">
        <?= $menu0['name'] ?>
      </button>
    <?php endforeach; ?>
  </div>
</div>