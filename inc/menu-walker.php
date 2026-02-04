<?php
/**
 * Custom Navigation Walker for Chrysoberyl Theme
 *
 * @package Chrysoberyl
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Custom Walker Class for Navigation Menu
 */
class chrysoberyl_Walker_Nav_Menu extends Walker_Nav_Menu {

    /**
     * Start the element output.
     *
     * @param string $output Passed by reference. Used to append additional content.
     * @param object $item   Menu item data object.
     * @param int    $depth  Depth of menu item. Used for padding.
     * @param array  $args   An array of arguments. @see wp_nav_menu()
     * @param int    $id     Current item ID.
     */
    function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
        $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

        $classes = empty( $item->classes ) ? array() : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;

        /**
         * Filter the CSS class(es) applied to a menu item's list item element.
         */
        $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
        $class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

        /**
         * Filter the ID applied to a menu item's list item element.
         */
        $id = apply_filters( 'nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args );
        $id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

        $output .= $indent . '<li' . $id . $class_names .'>';

        $attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
        $attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
        $attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
        $attributes .= ! empty( $item->url )         ? ' href="'   . esc_attr( $item->url        ) .'"' : '';

        // Get menu item icon
        $icon = function_exists( 'chrysoberyl_get_menu_item_icon' ) ? chrysoberyl_get_menu_item_icon( $item->ID ) : '';
        $icon_html = $icon ? '<i class="' . esc_attr( $icon ) . ' mr-1"></i>' : '';

        // Check if current menu item is active (including ancestor check for subcategories)
        $is_current = in_array( 'current-menu-item', $classes ) 
                     || in_array( 'current_page_item', $classes )
                     || in_array( 'current-menu-ancestor', $classes )
                     || in_array( 'current-menu-parent', $classes );
        
        // Only one category nav item active: single post or category archive (no underline on all)
        static $category_match_used = false;
        if ( ! is_single() && ! is_category() ) {
            $category_match_used = false;
        }
        $is_category_link = strpos( $item->url, '/category/' ) !== false;
        if ( is_category() && $is_current && $is_category_link ) {
            // On category page: highlight only the exact category, not parent (e.g. คริปโต not ไอที)
            if ( in_array( 'current-menu-ancestor', $classes ) && ! in_array( 'current-menu-item', $classes ) ) {
                $is_current = false;
            } elseif ( $category_match_used ) {
                $is_current = false;
            } else {
                $category_match_used = true;
            }
        } elseif ( is_single() && $is_current && $is_category_link ) {
            if ( $category_match_used ) {
                $is_current = false;
            } else {
                $category_match_used = true;
            }
        }
        
        // Also check for category/subcategory relationship (when not already set by WordPress)
        if ( ! $is_current && ( is_single() || is_category() ) ) {
            $menu_url = $item->url;
            
            if ( strpos( $menu_url, '/category/' ) !== false ) {
                $url_parts = parse_url( $menu_url );
                if ( isset( $url_parts['path'] ) ) {
                    $path_parts = explode( '/category/', $url_parts['path'] );
                    if ( isset( $path_parts[1] ) ) {
                        $category_slug = trim( $path_parts[1], '/' );
                        $category_slug = explode( '/', $category_slug );
                        $category_slug = $category_slug[0];
                        
                        $menu_category = get_category_by_slug( $category_slug );
                        
                        if ( $menu_category ) {
                            $menu_category_id = $menu_category->term_id;
                            
                            if ( is_single() ) {
                                if ( $category_match_used ) {
                                    $is_current = false;
                                } else {
                                    $post_categories = get_the_category();
                                    foreach ( $post_categories as $post_category ) {
                                        if ( $post_category->term_id == $menu_category_id ) {
                                            $is_current = true;
                                            $category_match_used = true;
                                            break;
                                        }
                                        $category_ancestors = get_ancestors( $post_category->term_id, 'category' );
                                        if ( in_array( $menu_category_id, $category_ancestors ) ) {
                                            $is_current = true;
                                            $category_match_used = true;
                                            break;
                                        }
                                    }
                                }
                            } elseif ( is_category() ) {
                                if ( $category_match_used ) {
                                    $is_current = false;
                                } else {
                                    $current_category = get_queried_object();
                                    $post_categories = array( $current_category );
                                    foreach ( $post_categories as $post_category ) {
                                        if ( $post_category->term_id == $menu_category_id ) {
                                            $is_current = true;
                                            $category_match_used = true;
                                            break;
                                        }
                                        $category_ancestors = get_ancestors( $post_category->term_id, 'category' );
                                        if ( in_array( $menu_category_id, $category_ancestors ) ) {
                                            $is_current = true;
                                            $category_match_used = true;
                                            break;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        
        // Mockup: text-[15px] font-medium text-google-gray-500 hover:text-google-blue
        $link_classes = $is_current
            ? 'text-[15px] font-medium text-google-blue inline-flex items-center'
            : 'text-[15px] font-medium text-google-gray-500 hover:text-google-blue transition-colors inline-flex items-center';

        $text_span_classes = 'nav-link-text';
        
        // Add custom classes for styling
        $item_output = isset( $args->before ) ? $args->before : '';
        $item_output .= '<a' . $attributes . ' class="' . esc_attr( $link_classes ) . '">';
        $item_output .= $icon_html;
        $item_output .= '<span class="' . esc_attr( $text_span_classes ) . '">';
        $item_output .= ( isset( $args->link_before ) ? $args->link_before : '' ) . apply_filters( 'the_title', $item->title, $item->ID ) . ( isset( $args->link_after ) ? $args->link_after : '' );
        $item_output .= '</span>';
        $item_output .= '</a>';
        $item_output .= isset( $args->after ) ? $args->after : '';

        $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
    }

    /**
     * Start sub-menu (dropdown) output.
     */
    function start_lvl( &$output, $depth = 0, $args = null ) {
        if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
            $t = '';
            $n = '';
        } else {
            $t = "\t";
            $n = "\n";
        }
        $indent = str_repeat( $t, $depth );
        $output .= "{$n}{$indent}<ul class=\"sub-menu chrysoberyl-nav-dropdown\">{$n}";
    }

    /**
     * End sub-menu output.
     */
    function end_lvl( &$output, $depth = 0, $args = null ) {
        if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
            $t = '';
            $n = '';
        } else {
            $t = "\t";
            $n = "\n";
        }
        $indent = str_repeat( $t, $depth );
        $output .= "{$indent}</ul>{$n}";
    }

    /**
     * End the element output.
     */
    function end_el( &$output, $item, $depth = 0, $args = null ) {
        $output .= "</li>\n";
    }
}

/**
 * Mobile Menu Walker
 */
class chrysoberyl_Walker_Nav_Menu_Mobile extends Walker_Nav_Menu {

    /**
     * Start the element output.
     */
    function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
        $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

        $classes = empty( $item->classes ) ? array() : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;

        $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
        $class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

        $id = apply_filters( 'nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args );
        $id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

        $output .= $indent . '<li' . $id . $class_names .'>';

        $attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
        $attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
        $attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
        $attributes .= ! empty( $item->url )         ? ' href="'   . esc_attr( $item->url        ) .'"' : '';

        // Get menu item icon
        $icon = function_exists( 'chrysoberyl_get_menu_item_icon' ) ? chrysoberyl_get_menu_item_icon( $item->ID ) : '';
        $icon_html = $icon ? '<i class="' . esc_attr( $icon ) . ' mr-2"></i>' : '';

        // Mockup: text-base font-medium text-google-gray-500 hover:text-google-blue
        $is_current = in_array( 'current-menu-item', $classes ) || in_array( 'current_page_item', $classes );
        $link_classes = $is_current
            ? 'block text-base font-medium text-google-blue'
            : 'block text-base font-medium text-google-gray-500 hover:text-google-blue';
        
        $item_output = isset( $args->before ) ? $args->before : '';
        $item_output .= '<a' . $attributes . ' class="' . esc_attr( $link_classes ) . '" role="menuitem">';
        $item_output .= $icon_html;
        $item_output .= ( isset( $args->link_before ) ? $args->link_before : '' ) . apply_filters( 'the_title', $item->title, $item->ID ) . ( isset( $args->link_after ) ? $args->link_after : '' );
        $item_output .= '</a>';
        $item_output .= isset( $args->after ) ? $args->after : '';

        $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
    }

    /**
     * Start sub-menu (nested list) output.
     */
    function start_lvl( &$output, $depth = 0, $args = null ) {
        if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
            $t = '';
            $n = '';
        } else {
            $t = "\t";
            $n = "\n";
        }
        $indent = str_repeat( $t, $depth );
        $output .= "{$n}{$indent}<ul class=\"sub-menu chrysoberyl-nav-mobile-sub\">{$n}";
    }

    /**
     * End sub-menu output.
     */
    function end_lvl( &$output, $depth = 0, $args = null ) {
        if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
            $t = '';
            $n = '';
        } else {
            $t = "\t";
            $n = "\n";
        }
        $indent = str_repeat( $t, $depth );
        $output .= "{$indent}</ul>{$n}";
    }

    /**
     * End the element output.
     */
    function end_el( &$output, $item, $depth = 0, $args = null ) {
        $output .= "</li>\n";
    }
}
