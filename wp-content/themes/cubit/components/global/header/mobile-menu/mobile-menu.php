<?php ob_start(); ?>
<?php $button_after = ob_get_clean(); ?>
<?php
$args = [
  'theme_location' => 'header',
  'a_class' => 'relative px-4 inline-block py-6 font-medium leading-5 ',
  'a_active_class' => 'after:w-[calc(100%-32px)]',
  'button_container_class' => 'w-full first:border-b-0 border-t border-stone-300',
  'button_class' => 'flex justify-between w-full flex-grow flex-shrink-0 relative block px-4 py-6 font-medium leading-5 after:content-none',
  'button_active_class' => 'mobile_menu_button_active',
  'sub_menu_class' => 'flex flex-col items-start pl-5',
  'sub_menu_a_class' => 'relative  block px-4 py-6 leading-5',
  'sub_menu_a_active_class' => 'font-medium after:w-[calc(100%-32px)]',
  'sub_menu_1_a_class' => 'ml-4',
  'button_after' => $button_after,
  'before' => '<div class="w-full border-b border-t first:border-b-0 border-stone-300">',
  'after' => '</div>',
  'type' => 'mobile',
];

global $complex_menus, $header_placeholder_class;

$menu_heading_class = 'px-4 py-3 text-xs leading-4 font-medium tracking-wide text-gray-light uppercase';

function translate_transition() {
  return 'x-transition:enter="duration-500 ease-out" x-transition:enter-start="-translate-x-full"
  x-transition:enter-end="translate-x-0" x-transition:leave="duration-300 ease-in"
  x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full"';
}

?>

<div class="absolute z-10 top-0 left-0 w-screen h-screen px-4 bg-[#F4EEE9] xl:hidden overflow-y-auto" x-show="open"
  x-cloak x-ref="panel" <?= translate_transition() ?> @click.away="open = false"
  @open.window="if (window.innerWidth < 1280) open = true">
  <div id="header-placeholder" class="h-14 <?= $header_placeholder_class ?>"></div>
  <div class="pb-4">
    <nav class="space-y-1">
      <div class="flex flex-col items-start">
        <?php wp_nav_menu($args); ?>
      </div>
    </nav>
  </div>
</div>