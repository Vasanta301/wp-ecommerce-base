<?php
$button_type = array_key_exists('button_type', $button_styles) ? $button_styles['button_type'] : 'primary';
$component_type = array_key_exists('component_type', $button_styles) ? $button_styles['component_type'] : 'link';
$button_class = array_key_exists('class', $button_styles) ? $button_styles['class'] : '';
$size = array_key_exists('size', $button_styles) ? $button_styles['size'] : 'lg';
$attr = array_key_exists('attr', $button_styles) ? $button_styles['attr'] : '';
$colour = array_key_exists('colour', $button_styles) ? $button_styles['colour'] : 'stone';
$text = array_key_exists('text', $button) ? $button['text'] : '';
$type = array_key_exists('type', $button) ? $button['type'] : 'internal';
$url = array_key_exists('url', $button) ? $button['url'] : '#';
$links_to = array_key_exists('link_to', $button) ? get_permalink($button['link_to']->ID) : '';
$link = $type == 'internal' ? $links_to : $url;
$icon = array_key_exists('icon', $button_styles) ? $button_styles['icon'] : '';

$className = [
  $button_type == 'primary' ? 'bg-black hover:bg-gray-600 text-white' : '',
  $button_type == 'dark' ? 'bg-black bg-gray-800 text-white' : '',
  $button_type == 'outline' ? 'bg-transparent border border border-black text-black hover:bg-gray-700 hover:text-white' : '',
  $size == 'lg' ? 'lg:py-4 lg:px-8 py-2 px-4 mt-3 ' : '',
  $size == 'sm' ? 'py-4 px-4' : '',
  'transition duration-300 ease-out flex justify-center font-normal text-nowrap flex-0 overflow-hidden lg:text-lg sm:text-base',
  $button_class,
];
$classString = implode(' ', array_filter($className));
?>
<?php if ($component_type == 'button') : ?>
  <button type="button" class="<?= $classString ?>" <?= $attr ?>><?= $text ?> <?= get_svg($icon); ?></button>
<?php else : ?>
  <a href="<?= $link ?>" <?php if ($type == 'external') : ?> target="_blank" <?php endif; ?> class="<?= $classString ?>" <?= $attr ?>><?= $text ?> <?= get_svg($icon); ?></a>
<?php endif; ?>
<?php
unset($button);
unset($button_styles);
?>