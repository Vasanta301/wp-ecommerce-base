<nav class="justify-center flex-1 hidden w-full xl:flex xl:items-center" x-data="Components.popoverGroup()">
  <?php
  $menu_color_pattern = 'text-black';
  $args = [
    'theme_location' => 'header',
    'a_class' => 'relative px-6 py-2 text-sm {$menu_color_pattern} font-medium leading-5 after:content-[\'\'] after:absolute after:bottom-1 after:left-1/2 after:-translate-x-1/2 after:w-0 hover:after:w-[calc(100%-50px)] after:h-px after:bg-current after:transition-all desktop-menu-a transition-colors',
    'a_active_class' => 'after:w-[calc(100%-50px)]',
    'button_container_class' => 'relative ',
    'button_class' => '{$menu_color_pattern} inline-flex  gap-2 relative px-4 py-2 text-sm font-medium leading-5 after:content-[\'\'] after:absolute after:bottom-1 after:left-1/2 after:-translate-x-1/2 after:w-0 hover:after:w-[calc(100%-75px)] after:h-px after:bg-current after:transition-all group desktop-menu-button transition-colors',
    'button_active_class' => 'after:w-[calc(100%-75px)]',
    'sub_menu_class' => 'absolute text-stone-900 z-20 top-full p-3 w-screen max-w-xs overflow-hidden flex flex-col items-start rounded',
    'sub_menu_a_class' => '{$menu_color_pattern} relative w-full flex justify-between align-middle p-3 text-sm leading-5 font-bold after:content-[\'\'] after:absolute after:bottom-1.5 after:left-5 after:w-0 hover:after:w-[calc(25%-20px)] after:h-px after:bg-current after:transition-all',
    'sub_menu_a_active_class' => 'after:w-[calc(25%-20px)]',
    'sub_menu_1_a_class' => 'ml-4',
    '',
  ];
  ?>

  <?php wp_nav_menu($args); ?>

  <div class="left-auto flex">
    <?php echo do_shortcode('[cubit_mini_cart]'); ?>
    <?php echo do_shortcode('[cubit_wishlists]'); ?>
    <!-- Add any component here that you want to display in right side of menu -->
  </div>

</nav>