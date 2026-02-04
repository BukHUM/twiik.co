<?php
/**
 * Trending Tags Widget
 *
 * @package Chrysoberyl
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Trending Tags Widget Class
 */
class chrysoberyl_Trending_Tags_Widget extends WP_Widget {

    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct(
            'chrysoberyl_trending_tags',
            __( 'Chrysoberyl - Theme: Trending Tags', 'chrysoberyl' ),
            array(
                'description' => __( 'Display trending tags/hashtags', 'chrysoberyl' ),
            )
        );
    }

    /**
     * Widget output
     *
     * @param array $args Widget arguments.
     * @param array $instance Widget instance.
     */
    public function widget( $args, $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Trending Tags', 'chrysoberyl' );
        $number = ! empty( $instance['number'] ) ? absint( $instance['number'] ) : 10;

        echo $args['before_widget'];

        if ( $title ) {
            echo $args['before_title'] . '<i class="fas fa-hashtag text-accent"></i> ' . esc_html( $title ) . $args['after_title'];
        }

        $tags = chrysoberyl_get_trending_tags( $number );

        if ( ! empty( $tags ) ) {
            echo '<div class="flex flex-wrap gap-2">';
            foreach ( $tags as $tag ) {
                ?>
                <a href="<?php echo esc_url( get_tag_link( $tag->term_id ) ); ?>" 
                   class="inline-block px-3 py-1 bg-gray-100 text-gray-700 text-sm rounded-full hover:bg-accent hover:text-white transition-colors duration-200">
                    #<?php echo esc_html( $tag->name ); ?>
                    <span class="text-xs opacity-75">(<?php echo esc_html( $tag->count ); ?>)</span>
                </a>
                <?php
            }
            echo '</div>';
        } else {
            echo '<p>' . __( 'No tags found.', 'chrysoberyl' ) . '</p>';
        }

        echo $args['after_widget'];
    }

    /**
     * Widget form
     *
     * @param array $instance Widget instance.
     */
    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Trending Tags', 'chrysoberyl' );
        $number = ! empty( $instance['number'] ) ? absint( $instance['number'] ) : 10;
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>">
                <?php _e( 'Title:', 'chrysoberyl' ); ?>
            </label>
            <input class="widefat" 
                   id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" 
                   name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" 
                   type="text" 
                   value="<?php echo esc_attr( $title ); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>">
                <?php _e( 'Number of tags:', 'chrysoberyl' ); ?>
            </label>
            <input class="tiny-text" 
                   id="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>" 
                   name="<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>" 
                   type="number" 
                   step="1" 
                   min="1" 
                   value="<?php echo esc_attr( $number ); ?>">
        </p>
        <?php
    }

    /**
     * Update widget
     *
     * @param array $new_instance New instance.
     * @param array $old_instance Old instance.
     * @return array Updated instance.
     */
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ! empty( $new_instance['title'] ) ? sanitize_text_field( $new_instance['title'] ) : '';
        $instance['number'] = ! empty( $new_instance['number'] ) ? absint( $new_instance['number'] ) : 10;
        return $instance;
    }
}
