<?php

add_filter('wp_nav_menu_args', 'tailwind_nav_menu_args');
function tailwind_nav_menu_args($args) {
  $args['type'] = $args['type'] ?? 'desktop';
  $args['container'] = false;
  $args['items_wrap'] = '%3$s';
  $args['walker'] = new Tailwind_Walker_Nav_Menu($args['type']);
  return $args;
}

class Tailwind_Walker_Nav_Menu extends Walker {

  /**
   * desktop or mobile
   */
  private $type;

  /**
   * What the class handles.
   *
   * @since 3.0.0
   * @var string
   *
   * @see Walker::$tree_type
   */
  public $tree_type = array('post_type', 'taxonomy', 'custom');

  /**
   * Database fields to use.
   *
   * @since 3.0.0
   * @todo Decouple this.
   * @var array
   *
   * @see Walker::$db_fields
   */
  public $db_fields = array(
    'parent' => 'menu_item_parent',
    'id' => 'db_id',
  );

  public function __construct($type = 'desktop') {
    $this->type = $type;
  }

  /**
   * Starts the list before the elements are added.
   *
   * @since 3.0.0
   *
   * @see Walker::start_lvl()
   *
   * @param string   $output Used to append additional content (passed by reference).
   * @param int      $depth  Depth of menu item. Used for padding.
   * @param stdClass $args   An object of wp_nav_menu() arguments.
   */
  public function start_lvl(&$output, $depth = 0, $args = null) {
    if ($depth > 0) {
      return;
    }
    if (isset($args->item_spacing) && 'discard' === $args->item_spacing) {
      $t = '';
      $n = '';
    } else {
      $t = "\t";
      $n = "\n";
    }
    $indent = str_repeat($t, $depth);
    if ($this->type == 'desktop') {
      $atts = array(
        'x-show' => 'open',
        'x-transition:enter' => 'transition ease-out duration-200',
        'x-transition:enter-start' => 'opacity-0 -translate-y-5',
        'x-transition:enter-end' => 'opacity-100 translate-y-0',
        'x-transition:leave' => 'transition ease-in duration-150',
        'x-transition:leave-start' => 'opacity-100 translate-y-0',
        'x-transition:leave-end' => 'opacity-0 -translate-y-5',
        'x-ref' => 'panel',
        '@click.away' => 'open = false',
        'x-cloak' => '',
      );
    } else {
      $atts = array(
        'x-show' => 'open',
        'x-collapse.duration.500ms' => '',
      );
    }

    $atts['class'] = $args->sub_menu_class ?? '';
    $attributes = '';
    foreach ($atts as $attr => $value) {
      $attributes .= ' ' . $attr . '="' . $value . '"';
    }

    $output .= "{$n}{$indent}<div$attributes>{$n}";
  }

  /**
   * Ends the list of after the elements are added.
   *
   * @since 3.0.0
   *
   * @see Walker::end_lvl()
   *
   * @param string   $output Used to append additional content (passed by reference).
   * @param int      $depth  Depth of menu item. Used for padding.
   * @param stdClass $args   An object of wp_nav_menu() arguments.
   */
  public function end_lvl(&$output, $depth = 0, $args = null) {
    if ($depth > 0) {
      return;
    }
    if (isset($args->item_spacing) && 'discard' === $args->item_spacing) {
      $t = '';
      $n = '';
    } else {
      $t = "\t";
      $n = "\n";
    }
    $indent = str_repeat($t, $depth);
    $output .= "$indent</div>{$n}";
  }

  /**
   * Starts the element output.
   *
   * @since 3.0.0
   * @since 4.4.0 The {@see 'nav_menu_item_args'} filter was added.
   *
   * @see Walker::start_el()
   *
   * @param string   $output Used to append additional content (passed by reference).
   * @param WP_Post  $item   Menu item data object.
   * @param int      $depth  Depth of menu item. Used for padding.
   * @param stdClass $args   An object of wp_nav_menu() arguments.
   * @param int      $id     Current item ID.
   */
  public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
    if (isset($args->item_spacing) && 'discard' === $args->item_spacing) {
      $t = '';
      $n = '';
    } else {
      $t = "\t";
      $n = "\n";
    }
    $indent = ($depth) ? str_repeat($t, $depth) : '';

    $classes = empty($item->classes) ? array() : (array) $item->classes;
    $classes[] = 'menu-item-' . $item->ID;

    /**
     * Filters the arguments for a single nav menu item.
     *
     * @since 4.4.0
     *
     * @param stdClass $args  An object of wp_nav_menu() arguments.
     * @param WP_Post  $item  Menu item data object.
     * @param int      $depth Depth of menu item. Used for padding.
     */
    $args = apply_filters('nav_menu_item_args', $args, $item, $depth);

    /**
     * Filters the CSS classes applied to a menu item's list item element.
     *
     * @since 3.0.0
     * @since 4.1.0 The `$depth` parameter was added.
     *
     * @param string[] $classes Array of the CSS classes that are applied to the menu item's `<li>` element.
     * @param WP_Post  $item    The current menu item.
     * @param stdClass $args    An object of wp_nav_menu() arguments.
     * @param int      $depth   Depth of menu item. Used for padding.
     */
    $class_names = implode(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args, $depth));
    $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';

    /**
     * Filters the ID applied to a menu item's list item element.
     *
     * @since 3.0.1
     * @since 4.1.0 The `$depth` parameter was added.
     *
     * @param string   $menu_id The ID that is applied to the menu item's `<li>` element.
     * @param WP_Post  $item    The current menu item.
     * @param stdClass $args    An object of wp_nav_menu() arguments.
     * @param int      $depth   Depth of menu item. Used for padding.
     */
    $id = apply_filters('nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args, $depth);
    $id = $id ? ' id="' . esc_attr($id) . '"' : '';

    $is_active = in_array('current-menu-ancestor', $classes);

    $has_sub_menu = $item->url == '#';
    $is_top_level_item = $depth == 0;
    if ($has_sub_menu && $is_top_level_item) {
      if ($this->type == 'desktop') {
        $atts = array(
          'x-data' => 'Components.popover()',
          '@keydown.escape' => 'onEscape',
          '@close-popover-group.window' => 'onClosePopoverGroup',
        );
      } else {
        $atts = array(
          'x-data' => '{ open: ' . ($is_active ? 'true' : 'false') . ' }',
        );
      }

      $atts['class'] = ($args->button_container_class . ($depth == 1 && $item->url == '#') ? ' ' : '') ?? '';
      $attributes = '';
      foreach ($atts as $attr => $value) {
        $attributes .= ' ' . $attr . '="' . $value . '"';
      }
      $output .= $indent . '<div' . $attributes . '>';

      if ($this->type == 'desktop') {
        $atts = array(
          'type' => 'button',
          'aria-expanded' => 'false',
          ':aria-expanded' => 'open.toString()',
          '@click' => 'toggle',
          '@mousedown' => 'if (open) $event.preventDefault()',
        );
      } else {
        $atts = array(
          '@click' => 'open = !open',
        );
      }
      $atts['class'] = $args->button_class ?? '';
      if (isset($args->button_active_class)) {
        $atts[':class'] = "open && '" . $args->button_active_class . "'";
        if ($is_active) {
          $atts['class'] .= ' ' . $args->button_active_class;
        }
      }
      $attributes = '';
      foreach ($atts as $attr => $value) {
        $attributes .= ' ' . $attr . '="' . $value . '"';
      }

      $output .= '<button' . $attributes . '>';
      $title = apply_filters('the_title', $item->title, $item->ID);
      $title = apply_filters('nav_menu_item_title', $title, $item, $args, $depth);
      $output .= "<span>$title</span>";
      $output .= $args->button_after ?? '';
      $output .= '</button>';
    } else {
      $atts = array();
      $atts['title'] = !empty($item->attr_title) ? $item->attr_title : '';
      $atts['target'] = !empty($item->target) ? $item->target : '';
      if ('_blank' === $item->target && empty($item->xfn)) {
        $atts['rel'] = 'noopener';
      } else {
        $atts['rel'] = $item->xfn;
      }
      $atts['href'] = !empty($item->url) ? $item->url : '';
      $atts['aria-current'] = $item->current ? 'page' : '';
      if ($depth == 0) {
        $atts['class'] = $args->a_class ?? '';
        if ($item->current && isset($args->a_active_class)) {
          $atts['class'] .= ' ' . $args->a_active_class;
        }
      }
      if ($depth > 0) {
        $atts['class'] = ($args->sub_menu_a_class . (($depth == 1 && $item->url == '#') ? ' text-xs text-gray-dark uppercase tracking-wider font-medium pointer-events-none !py-1' : '')) ?? '';
        if ($item->current && isset($args->sub_menu_a_active_class)) {
          $atts['class'] .= ' ' . $args->sub_menu_a_active_class;
        }
      }
      if ($depth == 1) {
        $atts['class'] = $atts['class'] . (isset($args->sub_menu_0_a_class) ? ' ' . $args->sub_menu_0_a_class : '');
        if ($item->current && isset($args->sub_menu_0_a_active_class)) {
          $atts['class'] .= ' ' . $args->sub_menu_0_a_active_class;
        }
      }
      if ($depth == 2) {
        $atts['class'] = $atts['class'] . (isset($args->sub_menu_1_a_class) ? ' ' . $args->sub_menu_1_a_class : '');
        if ($item->current && isset($args->sub_menu_1_a_active_class)) {
          $atts['class'] .= ' ' . $args->sub_menu_1_a_active_class;
        }
      }

      /**
       * Filters the HTML attributes applied to a menu item's anchor element.
       *
       * @since 3.6.0
       * @since 4.1.0 The `$depth` parameter was added.
       *
       * @param array $atts {
       *     The HTML attributes applied to the menu item's `<a>` element, empty strings are ignored.
       *
       *     @type string $title        Title attribute.
       *     @type string $target       Target attribute.
       *     @type string $rel          The rel attribute.
       *     @type string $href         The href attribute.
       *     @type string $aria_current The aria-current attribute.
       * }
       * @param WP_Post  $item  The current menu item.
       * @param stdClass $args  An object of wp_nav_menu() arguments.
       * @param int      $depth Depth of menu item. Used for padding.
       */
      $atts = apply_filters('nav_menu_link_attributes', $atts, $item, $args, $depth);

      $attributes = '';
      foreach ($atts as $attr => $value) {
        if (is_scalar($value) && '' !== $value && false !== $value) {
          $value = ('href' === $attr) ? esc_url($value) : esc_attr($value);
          $attributes .= ' ' . $attr . '="' . $value . '"';
        }
      }

      /** This filter is documented in wp-includes/post-template.php */
      $title = apply_filters('the_title', $item->title, $item->ID);

      /**
       * Filters a menu item's title.
       *
       * @since 4.4.0
       *
       * @param string   $title The menu item's title.
       * @param WP_Post  $item  The current menu item.
       * @param stdClass $args  An object of wp_nav_menu() arguments.
       * @param int      $depth Depth of menu item. Used for padding.
       */
      $title = apply_filters('nav_menu_item_title', $title, $item, $args, $depth);

      $item_output = $args->before ?? '';
      $item_output .= '<a' . $attributes . '>';
      $item_output .= ($args->link_before ?? '') . $title . ($args->link_after ?? '');
      $item_output .= '</a>';
      $item_output .= $args->after ?? '';

      /**
       * Filters a menu item's starting output.
       *
       * The menu item's starting output only includes `$args->before`, the opening `<a>`,
       * the menu item's title, the closing `</a>`, and `$args->after`. Currently, there is
       * no filter for modifying the opening and closing `<li>` for a menu item.
       *
       * @since 3.0.0
       *
       * @param string   $item_output The menu item's starting HTML output.
       * @param WP_Post  $item        Menu item data object.
       * @param int      $depth       Depth of menu item. Used for padding.
       * @param stdClass $args        An object of wp_nav_menu() arguments.
       */
      $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }
  }

  /**
   * Ends the element output, if needed.
   *
   * @since 3.0.0
   *
   * @see Walker::end_el()
   *
   * @param string   $output Used to append additional content (passed by reference).
   * @param WP_Post  $item   Page data object. Not used.
   * @param int      $depth  Depth of page. Not Used.
   * @param stdClass $args   An object of wp_nav_menu() arguments.
   */
  public function end_el(&$output, $item, $depth = 0, $args = null) {
    if (isset($args->item_spacing) && 'discard' === $args->item_spacing) {
      $t = '';
      $n = '';
    } else {
      $t = "\t";
      $n = "\n";
    }
    $has_sub_menu = $item->url == '#';
    $is_top_level_item = $depth == 0;
    if ($has_sub_menu && $is_top_level_item) {
      $output .= "</div>{$n}";
    }
  }
}