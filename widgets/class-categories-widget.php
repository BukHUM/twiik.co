<?php
/**
 * Categories Widget (รายการหมวดหมู่)
 *
 * @package Chrysoberyl
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Categories Widget Class
 */
class chrysoberyl_Categories_Widget extends WP_Widget {

    public function __construct() {
        parent::__construct(
            'chrysoberyl_categories',
            __( 'Chrysoberyl - Theme: Categories', 'chrysoberyl' ),
            array( 'description' => __( 'Display list of categories with post count', 'chrysoberyl' ) )
        );
    }

    public function widget( $args, $instance ) {
        $title        = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Categories', 'chrysoberyl' );
        // Default show_count to true (Google best practice: show article count in parentheses)
        $show_count   = isset( $instance['show_count'] ) ? ! empty( $instance['show_count'] ) : true;
        // Default hierarchical to true so programmatic render (sidebar-single) and new instances show collapsible list
        $hierarchical = isset( $instance['hierarchical'] ) ? ! empty( $instance['hierarchical'] ) : true;

        if ( ! taxonomy_exists( 'category' ) ) {
            return;
        }

        $list_style = 'chrysoberyl-categories-list';
        $wrap_attr  = '';

        echo $args['before_widget'];
        if ( $title ) {
            echo $args['before_title'] . '<i class="fas fa-folder text-accent flex-shrink-0"></i>' . esc_html( $title ) . $args['after_title'];
        }

        if ( $hierarchical ) {
            $top_cats = get_categories( array(
                'orderby'    => 'name',
                'order'      => 'ASC',
                'parent'     => 0,
                'taxonomy'   => 'category',
                'hide_empty' => false,
            ) );
            if ( ! empty( $top_cats ) ) {
                echo '<div class="' . esc_attr( $list_style ) . ' chrysoberyl-categories-collapsible"' . $wrap_attr . '>';
                echo '<ul class="chrysoberyl-categories-root space-y-0.5">';
                foreach ( $top_cats as $cat ) {
                    $children = get_categories( array(
                        'orderby'    => 'name',
                        'order'      => 'ASC',
                        'parent'     => $cat->term_id,
                        'taxonomy'   => 'category',
                        'hide_empty' => false,
                    ) );
                    $has_children = ! empty( $children );
                    $tid          = (int) $cat->term_id;
                    echo '<li class="chrysoberyl-cat-item-wrap' . ( $has_children ? ' chrysoberyl-cat-has-children' : '' ) . '" data-term-id="' . esc_attr( $tid ) . '">';
                    echo '<div class="chrysoberyl-cat-row flex items-center gap-1 min-w-0">';
                    if ( $has_children ) {
                        echo '<button type="button" class="chrysoberyl-cat-toggle flex-shrink-0 w-6 h-6 flex items-center justify-center rounded text-gray-500 hover:bg-gray-100 hover:text-accent transition-colors" aria-expanded="false" aria-label="' . esc_attr__( 'แสดงหมวดย่อย', 'chrysoberyl' ) . '" data-term-id="' . esc_attr( $tid ) . '" title="' . esc_attr__( 'แสดง/ซ่อนหมวดย่อย', 'chrysoberyl' ) . '"><i class="fas fa-chevron-right chrysoberyl-cat-chevron text-xs transition-transform" aria-hidden="true"></i></button>';
                    } else {
                        echo '<span class="chrysoberyl-cat-no-children flex-shrink-0 w-6 inline-block" aria-hidden="true"></span>';
                    }
                    $this->render_category_link( $cat, $show_count, true );
                    echo '</div>';
                    if ( $has_children ) {
                        echo '<ul class="chrysoberyl-categories-children pl-4 mt-0.5 mb-1 space-y-0.5 border-l-2 border-gray-100 ml-2" role="group" aria-label="' . esc_attr( $cat->name ) . '">';
                        foreach ( $children as $child ) {
                            echo '<li class="chrysoberyl-cat-item-wrap">';
                            $this->render_category_link( $child, $show_count, false );
                            echo '</li>';
                        }
                        echo '</ul>';
                    }
                    echo '</li>';
                }
                echo '</ul></div>';
            }
        } else {
            $categories = get_categories( array(
                'orderby'    => 'name',
                'order'      => 'ASC',
                'taxonomy'   => 'category',
                'hide_empty' => false,
            ) );
            if ( ! empty( $categories ) ) {
                echo '<div class="' . esc_attr( $list_style ) . '"' . $wrap_attr . '>';
                echo '<ul class="chrysoberyl-categories-root space-y-0.5">';
                foreach ( $categories as $cat ) {
                    echo '<li class="chrysoberyl-cat-item-wrap">';
                    $this->render_category_link( $cat, $show_count, false );
                    echo '</li>';
                }
                echo '</ul></div>';
            }
        }
        echo $args['after_widget'];
    }

    /**
     * Output category link with icon and optional count in parentheses (Google best practice).
     * Caller wraps in <li> or div.
     *
     * @param WP_Term $cat        Category term.
     * @param bool    $show_count Whether to show post count in parentheses.
     * @param bool    $in_row     When true, link is inside .chrysoberyl-cat-row (flex-grow).
     */
    private function render_category_link( $cat, $show_count, $in_row = false ) {
        $url       = home_url( '?cat=' . $cat->term_id );
        $name_attr = esc_attr( $cat->name );
        $name_html = esc_html( $cat->name );
        $class     = 'chrysoberyl-cat-link group flex items-center gap-2 py-2 px-2.5 rounded-lg text-sm text-gray-700 hover:bg-gray-50 hover:text-accent transition-colors';
        if ( $in_row ) {
            $class .= ' flex-1 min-w-0';
        }
        echo '<a href="' . esc_url( $url ) . '" class="' . esc_attr( $class ) . '" title="' . $name_attr . '">';
        echo '<span class="chrysoberyl-cat-icon flex-shrink-0 w-5 h-5 flex items-center justify-center text-gray-400 group-hover:text-accent transition-colors" aria-hidden="true">';
        echo '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>';
        echo '</span>';
        echo '<span class="truncate min-w-0">' . $name_html;
        if ( $show_count ) {
            echo ' <span class="chrysoberyl-cat-count text-gray-500 font-normal">(' . (int) $cat->count . ')</span>';
        }
        echo '</span></a>';
    }

    public function form( $instance ) {
        $title        = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Categories', 'chrysoberyl' );
        $show_count   = isset( $instance['show_count'] ) ? ! empty( $instance['show_count'] ) : true;
        $hierarchical = isset( $instance['hierarchical'] ) ? ! empty( $instance['hierarchical'] ) : true;
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title:', 'chrysoberyl' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <p>
            <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'show_count' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_count' ) ); ?>" <?php checked( $show_count ); ?>>
            <label for="<?php echo esc_attr( $this->get_field_id( 'show_count' ) ); ?>"><?php _e( 'Show post count', 'chrysoberyl' ); ?></label>
        </p>
        <p>
            <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'hierarchical' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'hierarchical' ) ); ?>" <?php checked( $hierarchical ); ?>>
            <label for="<?php echo esc_attr( $this->get_field_id( 'hierarchical' ) ); ?>"><?php _e( 'Show hierarchy (main categories only, click to expand subcategories)', 'chrysoberyl' ); ?></label>
        </p>
        <?php
    }

    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title']        = ! empty( $new_instance['title'] ) ? sanitize_text_field( $new_instance['title'] ) : '';
        $instance['show_count']   = ! empty( $new_instance['show_count'] );
        $instance['hierarchical'] = ! empty( $new_instance['hierarchical'] );
        return $instance;
    }
}
