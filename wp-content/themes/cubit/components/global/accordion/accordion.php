<?php
global $accordion_index;
if (!isset($accordion_index)) {
  $accordion_index = 0;
}
$accordion_index += 1;
if (!isset($fields['open'])) {
  $fields['open'] = false;
}
$open = $fields['open'] ? 'true' : 'false';
?>

<div class="border-b border-black">
  <dt>
    <button type="button"
      class="flex items-center bg-gray w-full pt-4 pb-3 focus:outline-none text-lg 4k:text-xl font-subheading uppercase text-left transition-colors hover:text-gray-dark"
      @click="index = <?= $fields['index'] ?>; allCollapsed = false" aria-controls="accordion-<?= $accordion_index ?>"
      x-bind:aria-expanded="index == <?= $fields['index'] ?> ? 'true' : 'false'">
      <span class="flex-1">
        <?= $fields['title'] ?>
      </span>
      <?php if (!isset($hide_arrow) || $hide_arrow != true): ?>
        <span class="ml-4 block w-6 h-6 transform transition-transform duration-500"
          :class="index == <?= $fields['index'] ?> && !allCollapsed && '-scale-y-100'">
          <?= get_icon('chevron-down') ?>
        </span>
      <?php endif; ?>
    </button>
  </dt>
  <dd id="accordion-<?= $accordion_index ?>" x-show="index == <?= $fields['index'] ?> && !allCollapsed"
    x-collapse.duration.500ms x-cloak>
    <div class="py-4 border-t border-black">
      <div class="prose max-w-full text-gray">
        <?= $fields['content'] ?>
      </div>
    </div>
  </dd>
</div>