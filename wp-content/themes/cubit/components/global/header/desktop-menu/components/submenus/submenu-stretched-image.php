<div class="relative flex-auto w-1/2 h-full flex border-l border-black">
  <?php foreach ($menu1['items'] as $index1 => $menu1_item): ?>
    <?php $menu2 = $menu1_item['menu']; ?>
    <div class="absolute inset-0" x-cloak <?= absolute_transition() ?>
      x-show="index0 == <?= $index0 ?> && index1 == <?= $index1 ?>">
      <div class="h-3/5">
        <?= get_img($menu1_item['image_id'], '40vw') ?>
      </div>
      <div class="h-2/5 border-t border-black py-6 px-12">
        <div class="prose prose-sm">
          <?= $menu1_item['description'] ?>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
</div>