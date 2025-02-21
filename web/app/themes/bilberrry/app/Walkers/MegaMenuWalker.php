<?php

namespace App\Walkers;

use Walker_Nav_Menu;

class MegaMenuWalker extends Walker_Nav_Menu
{
    // Start the element output
    public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0)
    {
        $add_mega_menu = get_field('add_mega_menu', $item);
        $columns = get_field('columns', $item);
        $icon = get_field('image', $item);

        $has_children = !empty($args->walker->has_children);

        if ($depth === 0) {
            $output .= '<li class="menu-item' . ($has_children ? ' has-children' : '') . '">';
            $output .= '<a href="' . esc_url($item->url) . '" class="' . ($has_children ? 'has-children' : '') . '">' . esc_html($item->title);
            $output .= '</a>';
            if ($has_children) {
                $grid_columns = $columns && $add_mega_menu ? 'style="display: grid; grid-template-columns: repeat(' . (int) $columns . ', 1fr);"' : '';
                $output .= '<ul class="submenu" ' . $grid_columns . '>';
            }
        } else {
            $output .= '<li class="submenu-item">';
            if ($icon) {
                $output .= '<a href="' . esc_url($item->url) . '">';
                $output .= '<img src="' . esc_url($icon['url']) . '" alt="' . esc_attr($icon['alt']) . '" class="submenu-item-icon">';
                $output .= '<span class="submenu-item-label">' . esc_html($item->title) . '</span></a>';
            } else {
                $output .= '<a href="' . esc_url($item->url) . '">' . esc_html($item->title) . '</a>';
            }
        }
    }

    // End the element output
    public function end_el(&$output, $item, $depth = 0, $args = null)
    {
        $output .= '</li>';
    }

    // Start the submenu output
    public function start_lvl(&$output, $depth = 0, $args = null)
    {
    }

    // End the submenu output
    public function end_lvl(&$output, $depth = 0, $args = null)
    {
        $output .= '</ul>';
    }
}
