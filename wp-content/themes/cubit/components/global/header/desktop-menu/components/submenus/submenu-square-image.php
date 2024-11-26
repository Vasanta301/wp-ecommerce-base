<div class="relative flex-auto w-1/2 max-w-[55vh] 4k:max-w-[65vh] h-full border-l border-black">
  <?php foreach ($menu1['items'] as $index1 => $menu1_item): ?>
    <?php $menu2 = $menu1_item['menu']; ?>
    <div class="absolute inset-0 flex flex-col" x-cloak <?= absolute_transition() ?>
      x-show="index0 == <?= $index0 ?> && index1 == <?= $index1 ?>">
      <div class="relative pb-[100%]">
        <div class="absolute inset-0">
          <?= get_img($menu1_item['image_id'], '40vw') ?>
        </div>
      </div>
      <div class="border-t border-black flex-1 py-6 pl-12 pr-8">
        <div class="h-full relative">
          <div class="absolute inset-0 pr-4 overflow-y-auto scrollbar">
            <div class="prose prose-sm">
              <?= $menu1_item['description'] ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
</div>