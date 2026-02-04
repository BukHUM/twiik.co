<?php
/**
 * Related Posts Widget (บทความที่เกี่ยวข้อง)
 *
 * @package Chrysoberyl
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Related Posts Widget Class
 */
class chrysoberyl_Related_Posts_Widget extends WP_Widget {

    public function __construct() {
        parent::__construct(
            'chrysoberyl_related_posts',
            __( 'Chrysoberyl - Theme: Related Posts', 'chrysoberyl' ),
            array( 'description' => __( 'Display related posts by category/tag (best on single post)', 'chrysoberyl' ) )
        );
    }

    public function widget( $args, $instance ) {
        if ( ! is_singular( 'post' ) ) {
            return;
        }
        $title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'บทความที่เกี่ยวข้อง', 'chrysoberyl' );
        $number = ! empty( $instance['number'] ) ? absint( $instance['number'] ) : 4;
        $current_id = get_the_ID();
        $cat_ids = wp_get_post_categories( $current_id );
        $tag_ids = wp_get_post_tags( $current_id, array( 'fields' => 'ids' ) );

        $query_args = array(
            'post_type'      => 'post',
            'posts_per_page' => $number,
            'post__not_in'   => array( $current_id ),
            'post_status'    => 'publish',
            'orderby'        => 'rand',
        );
        if ( ! empty( $cat_ids ) ) {
            $query_args['category__in'] = $cat_ids;
        }
        if ( ! empty( $tag_ids ) ) {
            $query_args['tag__in'] = $tag_ids;
        }
        if ( empty( $cat_ids ) && empty( $tag_ids ) ) {
            $query_args['orderby'] = 'date';
            $query_args['order']   = 'DESC';
        }
        $rel_query = new WP_Query( $query_args );

        if ( ! $rel_query->have_posts() ) {
            return;
        }

        echo $args['before_widget'];
        if ( $title ) {
            echo $args['before_title'] . '<i class="fas fa-link text-accent"></i> ' . esc_html( $title ) . $args['after_title'];
        }
        echo '<div class="space-y-4" role="list">';
        while ( $rel_query->have_posts() ) {
            $rel_query->the_post();
            $post_title = get_the_title();
            if ( empty( $post_title ) ) {
                continue;
            }
            ?>
            <a href="<?php echo esc_url( chrysoberyl_fix_url( get_permalink() ) ); ?>" 
               class="flex gap-3 items-start group p-2 rounded-lg hover:bg-gray-50 transition-colors duration-200"
               role="listitem">
                <?php if ( has_post_thumbnail() ) : ?>
                    <div class="flex-shrink-0 w-16 h-16 rounded-lg overflow-hidden bg-gray-100">
                        <?php the_post_thumbnail( 'chrysoberyl-thumbnail', array( 'class' => 'w-full h-full object-cover', 'alt' => $post_title ) ); ?>
                    </div>
                <?php else : ?>
                    <div class="flex-shrink-0 w-16 h-16 rounded-lg bg-gray-200 flex items-center justify-center">
                        <i class="fas fa-image text-gray-400"></i>
                    </div>
                <?php endif; ?>
                <div class="flex-grow min-w-0">
                    <h4 class="text-sm font-medium text-gray-700 group-hover:text-accent transition line-clamp-2"><?php echo esc_html( $post_title ); ?></h4>
                </div>
            </a>
            <?php
        }
        echo '</div>';
        wp_reset_postdata();
        echo $args['after_widget'];
    }

    public function form( $instance ) {
        $title  = ! empty( $instance['title'] ) ? $instance['title'] : __( 'บทความที่เกี่ยวข้อง', 'chrysoberyl' );
        $number = ! empty( $instance['number'] ) ? absint( $instance['number'] ) : 4;
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title:', 'chrysoberyl' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>"><?php _e( 'Number of posts:', 'chrysoberyl' ); ?></label>
            <input class="tiny-text" id="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>" type="number" min="1" max="10" value="<?php echo esc_attr( $number ); ?>">
        </p>
        <p class="description"><?php _e( 'Shows posts from the same category/tag. Use on single post sidebar.', 'chrysoberyl' ); ?></p>
        <?php
    }

    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title']  = ! empty( $new_instance['title'] ) ? sanitize_text_field( $new_instance['title'] ) : '';
        $instance['number'] = ! empty( $new_instance['number'] ) ? absint( $new_instance['number'] ) : 4;
        $instance['number'] = max( 1, min( 10, $instance['number'] ) );
        return $instance;
    }
}
