<?php
/**
 * Search Widget (ช่องค้นหา)
 *
 * @package Chrysoberyl
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Search Widget Class
 */
class chrysoberyl_Search_Widget extends WP_Widget {

    public function __construct() {
        parent::__construct(
            'chrysoberyl_search',
            __( 'Chrysoberyl - Theme: Search', 'chrysoberyl' ),
            array( 'description' => __( 'Search form for sidebar', 'chrysoberyl' ) )
        );
    }

    public function widget( $args, $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'ค้นหา', 'chrysoberyl' );

        echo $args['before_widget'];
        if ( $title ) {
            echo $args['before_title'] . '<i class="fas fa-search text-accent"></i> ' . esc_html( $title ) . $args['after_title'];
        }
        ?>
        <form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
            <div class="flex gap-2">
                <input type="search" class="search-field flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-accent" placeholder="<?php echo esc_attr__( 'ค้นหา...', 'chrysoberyl' ); ?>" value="<?php echo get_search_query(); ?>" name="s" aria-label="<?php esc_attr_e( 'Search', 'chrysoberyl' ); ?>" />
                <button type="submit" class="px-4 py-2 bg-accent hover:bg-blue-600 text-white font-medium rounded-lg transition-colors">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </form>
        <?php
        echo $args['after_widget'];
    }

    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'ค้นหา', 'chrysoberyl' );
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title:', 'chrysoberyl' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <?php
    }

    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ! empty( $new_instance['title'] ) ? sanitize_text_field( $new_instance['title'] ) : '';
        return $instance;
    }
}
