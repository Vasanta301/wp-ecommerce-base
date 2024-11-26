<div x-data="{ openItemIndex: null }">
  <?php foreach ($menu1['items'] as $index1 => $menu1_item): ?>
    <?php $is_last_item = $index1 == count($menu1['items']) - 1; ?>
    <?php $menu2 = $menu1_item['menu']; ?>
    <div class="flex border-t <?= $is_last_item ? 'border-b' : '' ?> border-skin-dark">
      <button class="<?= $args['button_class'] ?> flex items-center w-full" type="button"
        @click="openItemIndex = openItemIndex == <?= $index1 ?> ? null : <?= $index1 ?>"
        x-bind:aria-expanded="openItemIndex == <?= $index1 ?> ? 'true' : 'false'">
        <span class="flex-1 text-left">
          <?= $menu1_item['name'] ?>
        </span>
        <span class="flex-shrink-0 block w-6 h-6 transition-all duration-300"
          :class="openItemIndex == <?= $index1 ?> && 'rotate-180'">
          <?= get_svg('caret-down') ?>
        </span>
      </button>
    </div>
    <?php if (count($menu2['items']) > 0): ?>
      <div class="flex flex-col <?= $is_last_item ? '' : 'border-t' ?> border-solid border-skin-dark"
        id="menu-application-<?= $index1 ?>" x-show="openItemIndex == <?= $index1 ?>" x-collapse.duration.500ms x-cloak>
        <?php foreach ($menu2['items'] as $index2 => $menu2_item): ?>
          <a class="<?= $args['button_class'] ?>" href="<?= $menu2_item['url'] ?>">
            <?= $menu2_item['name'] ?>
          </a>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  <?php endforeach; ?>
</div>