<?php
/**
 * Most Commented Posts Widget (บทความที่มีความเห็นมาก)
 *
 * @package Chrysoberyl
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Most Commented Widget Class
 */
class chrysoberyl_Most_Commented_Widget extends WP_Widget {

    public function __construct() {
        parent::__construct(
            'chrysoberyl_most_commented',
            __( 'Chrysoberyl - Theme: Most Commented', 'chrysoberyl' ),
            array( 'description' => __( 'Display posts with most comments', 'chrysoberyl' ) )
        );
    }

    public function widget( $args, $instance ) {
        $title  = ! empty( $instance['title'] ) ? $instance['title'] : __( 'บทความที่มีความเห็นมาก', 'chrysoberyl' );
        $number = ! empty( $instance['number'] ) ? absint( $instance['number'] ) : 5;
        $show_count = ! empty( $instance['show_count'] );

        $query = new WP_Query( array(
            'post_type'      => 'post',
            'posts_per_page' => $number,
            'post_status'    => 'publish',
            'orderby'        => 'comment_count',
            'order'          => 'DESC',
        ) );

        if ( ! $query->have_posts() ) {
            return;
        }

        echo $args['before_widget'];
        if ( $title ) {
            echo $args['before_title'] . '<i class="fas fa-comments text-accent"></i> ' . esc_html( $title ) . $args['after_title'];
        }
        echo '<div class="space-y-3" role="list">';
        while ( $query->have_posts() ) {
            $query->the_post();
            $post_title = get_the_title();
            if ( empty( $post_title ) ) {
                continue;
            }
            $comments = get_comments_number();
            ?>
            <a href="<?php echo esc_url( chrysoberyl_fix_url( get_permalink() ) ); ?>" 
               class="flex gap-3 items-start group p-2 rounded-lg hover:bg-gray-50 transition-colors duration-200"
               role="listitem">
                <div class="flex-grow min-w-0">
                    <h4 class="text-sm font-medium text-gray-700 group-hover:text-accent transition line-clamp-2"><?php echo esc_html( $post_title ); ?></h4>
                    <?php if ( $show_count ) : ?>
                        <span class="text-xs text-gray-500"><?php echo (int) $comments; ?> <?php _e( 'ความเห็น', 'chrysoberyl' ); ?></span>
                    <?php endif; ?>
                </div>
            </a>
            <?php
        }
        echo '</div>';
        wp_reset_postdata();
        echo $args['after_widget'];
    }

    public function form( $instance ) {
        $title      = ! empty( $instance['title'] ) ? $instance['title'] : __( 'บทความที่มีความเห็นมาก', 'chrysoberyl' );
        $number     = ! empty( $instance['number'] ) ? absint( $instance['number'] ) : 5;
        $show_count = ! empty( $instance['show_count'] );
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title:', 'chrysoberyl' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>"><?php _e( 'Number of posts:', 'chrysoberyl' ); ?></label>
            <input class="tiny-text" id="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>" type="number" min="1" max="15" value="<?php echo esc_attr( $number ); ?>">
        </p>
        <p>
            <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'show_count' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_count' ) ); ?>" <?php checked( $show_count ); ?>>
            <label for="<?php echo esc_attr( $this->get_field_id( 'show_count' ) ); ?>"><?php _e( 'Show comment count', 'chrysoberyl' ); ?></label>
        </p>
        <?php
    }

    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title']      = ! empty( $new_instance['title'] ) ? sanitize_text_field( $new_instance['title'] ) : '';
        $instance['number']    = ! empty( $new_instance['number'] ) ? absint( $new_instance['number'] ) : 5;
        $instance['number']   = max( 1, min( 15, $instance['number'] ) );
        $instance['show_count'] = ! empty( $new_instance['show_count'] );
        return $instance;
    }
}
