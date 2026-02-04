<?php
/**
 * Archive Widget (อาร์คิฟตามเดือน)
 *
 * @package Chrysoberyl
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Archive Widget Class
 */
class chrysoberyl_Archive_Widget extends WP_Widget {

    public function __construct() {
        parent::__construct(
            'chrysoberyl_archive',
            __( 'Chrysoberyl - Theme: Archive', 'chrysoberyl' ),
            array( 'description' => __( 'Display monthly archive links', 'chrysoberyl' ) )
        );
    }

    public function widget( $args, $instance ) {
        $title   = ! empty( $instance['title'] ) ? $instance['title'] : __( 'อาร์คิฟ', 'chrysoberyl' );
        $count   = ! empty( $instance['count'] );
        $dropdown = ! empty( $instance['dropdown'] );

        echo $args['before_widget'];
        if ( $title ) {
            echo $args['before_title'] . '<i class="fas fa-archive text-accent"></i> ' . esc_html( $title ) . $args['after_title'];
        }
        if ( $dropdown ) {
            ?>
            <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent" name="archive-dropdown" onchange="document.location.href=this.options[this.selectedIndex].value;">
                <option value=""><?php _e( 'เลือกเดือน', 'chrysoberyl' ); ?></option>
                <?php wp_get_archives( array( 'type' => 'monthly', 'format' => 'option', 'show_post_count' => $count ) ); ?>
            </select>
            <?php
        } else {
            echo '<ul class="space-y-1">';
            wp_get_archives( array( 'type' => 'monthly', 'show_post_count' => $count ) );
            echo '</ul>';
        }
        echo $args['after_widget'];
    }

    public function form( $instance ) {
        $title    = ! empty( $instance['title'] ) ? $instance['title'] : __( 'อาร์คิฟ', 'chrysoberyl' );
        $count    = ! empty( $instance['count'] );
        $dropdown = ! empty( $instance['dropdown'] );
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title:', 'chrysoberyl' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <p>
            <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'count' ) ); ?>" <?php checked( $count ); ?>>
            <label for="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>"><?php _e( 'Show post count', 'chrysoberyl' ); ?></label>
        </p>
        <p>
            <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'dropdown' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'dropdown' ) ); ?>" <?php checked( $dropdown ); ?>>
            <label for="<?php echo esc_attr( $this->get_field_id( 'dropdown' ) ); ?>"><?php _e( 'Display as dropdown', 'chrysoberyl' ); ?></label>
        </p>
        <?php
    }

    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title']    = ! empty( $new_instance['title'] ) ? sanitize_text_field( $new_instance['title'] ) : '';
        $instance['count']    = ! empty( $new_instance['count'] );
        $instance['dropdown'] = ! empty( $new_instance['dropdown'] );
        return $instance;
    }
}
