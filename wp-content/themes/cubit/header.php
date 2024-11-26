<!doctype html>
<html <?php language_attributes(); ?>>

<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php wp_head(); ?>
  <link rel="profile" href="https://gmpg.org/xfn/11" />
  <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <style>
    [x-cloak] {
      display: none;
    }
  </style>
</head>
<?php
$header = get_field('header', 'option');
$site_logo = get_field('site_logo', 'option');
?>

<body <?php body_class('bg-skin relative overflow-x-hidden'); ?>>
  <?php wp_body_open(); ?>
  <header>
    <div id="header-container"
      class="fixed top-0 inset-x-0 z-20 py-1 transition-all duration-500  <?= is_user_logged_in() ? 'md:pt-9 pt-12' : ''; ?>">
      <div id="header" class="container relative z-20 w-full">
        <div id="nav-container" class="flex items-center justify-between w-full py-2">
          <div class="flex justify-between w-full gap-12 flex-0 ">
            <a href="<?= home_url() ?>" class="flex items-center text-black max-w-max">
              <?php if (isset($site_logo)): ?>
                <?= get_img($site_logo, '112px', ['class' => 'object-contain transition-all duration-300', 'id' => "nav-logo"]) ?>
              <?php else: ?>
                <?= get_bloginfo('title'); ?>
              <?php endif; ?>
            </a>
            <?php get_template_part('components/global/header/desktop-menu/desktop-menu'); ?>
          </div>
          <div class="flex flex-row justify-end w-full lg:hidden"
            x-data="Components.popover({ open: false, focus: true, preventScrolling: true })" @keydown.escape="onEscape"
            @close-popover-group.window="onClosePopoverGroup">
            <div class="relative" :class="open ? '' : ''">
              <button type="button" class="z-20 inline-flex items-center justify-start text-white rounded-full"
                @click="toggle($event); if (!open) $dispatch('close-mobile-menu')"
                @mousedown="if (open) $event.preventDefault()" aria-expanded="false" :aria-expanded="open.toString()">
                <span class="sr-only">Open menu</span>
                <div class="p-2 border-2 border-white">
                  <div class="relative w-6 h-6">
                    <div class="top"></div>
                    <div class="middle"></div>
                    <div class="bottom"></div>
                  </div>
                </div>
              </button>
            </div>
            <?php global $header_placeholder_class; ?>
            <?php $header_placeholder_class = ''; ?>
            <?php get_template_part('components/global/header/mobile-menu/mobile-menu'); ?>
          </div>
        </div>
      </div>
  </header>
  <?php $pad_body = array_key_exists('has_padding', $GLOBALS) ? $GLOBALS['has_padding'] : false; ?>
  <div id="header-placeholder"
    class="<?= $header_placeholder_class ?> <?= isset($pad_body) && $pad_body ? 'pt-12' : '' ?>"></div>
  <div id="smooth-content" class="w-full overflow-hidden">
    <main id="primary" class="min-h-screen site-main">