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

<div class="border-b border-white text-white">
  <dt>
  

    <button type="button"
      class="flex items-center justify-between w-[650px] p-4 text-lg font-bold uppercase text-left transition-colors duration-300 border focus:outline-none"
      @click="index === <?= $fields['index'] ?> ? index = null : index = <?= $fields['index'] ?>"
      aria-controls="accordion-<?= $accordion_index ?>"
      x-bind:aria-expanded="index === <?= $fields['index'] ?> ? 'true' : 'false'"
      :class="index === <?= $fields['index'] ?> ? 'bg-black text-white border border-black' : 'bg-white text-black border border-orange-500'">

      <span class="flex-1 font-semibold text-lg max-w-5xl">
        <?= esc_html($fields['title']); ?>
      </span>

      <?php if (!isset($hide_arrow) || $hide_arrow != true): ?>
        <span class="ml-4">
          <!-- Plus/Minus Icon -->
          <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"
              x-show="index !== <?= $fields['index'] ?>" x-cloak />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 12h16"
              x-show="index === <?= $fields['index'] ?>" x-cloak />
          </svg>
        </span>
      <?php endif; ?>
    </button>
  </dt>
  <!-- <dd id="accordion-<?= $accordion_index ?>" x-show="index == <?= $fields['index'] ?> && !allCollapsed"
    x-collapse.duration.500ms x-cloak>
    <div class="lg:py-4 xxs:pt-0 xxs:pb-2">
      <div class=" max-w-full text-white">
        <?= $fields['content'] ?>
      </div>
    </div>
  </dd> -->

  <dd id="accordion-<?= $accordion_index ?>" x-show="index === <?= $fields['index'] ?>" x-collapse.duration.500ms
    x-cloak>
    <div class="py-4 border-t border-black">
      <div class="prose max-w-full text-gray">
        <?= $fields['content'] ?>
      </div>
    </div>
  </dd>
</div>