<?php
/**
 * Social Follow Widget (ติดตามเราบนโซเชียล)
 *
 * @package Chrysoberyl
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Social Follow Widget Class
 */
class chrysoberyl_Social_Follow_Widget extends WP_Widget {

    private static $networks = array(
        'facebook'  => array( 'label' => 'Facebook', 'icon' => 'fab fa-facebook-f', 'url_placeholder' => 'https://facebook.com/yourpage' ),
        'twitter'   => array( 'label' => 'Twitter/X', 'icon' => 'fab fa-twitter', 'url_placeholder' => 'https://twitter.com/yourhandle' ),
        'line'      => array( 'label' => 'Line', 'icon' => 'fab fa-line', 'url_placeholder' => 'https://line.me/ti/p/xxx' ),
        'youtube'   => array( 'label' => 'YouTube', 'icon' => 'fab fa-youtube', 'url_placeholder' => 'https://youtube.com/yourchannel' ),
        'instagram' => array( 'label' => 'Instagram', 'icon' => 'fab fa-instagram', 'url_placeholder' => 'https://instagram.com/yourhandle' ),
        'tiktok'    => array( 'label' => 'TikTok', 'icon' => 'fab fa-tiktok', 'url_placeholder' => 'https://tiktok.com/@yourhandle' ),
    );

    public function __construct() {
        parent::__construct(
            'chrysoberyl_social_follow',
            __( 'Chrysoberyl - Theme: Social Follow', 'chrysoberyl' ),
            array( 'description' => __( 'Display social media follow links', 'chrysoberyl' ) )
        );
    }

    public function widget( $args, $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'ติดตามเรา', 'chrysoberyl' );
        $has_any = false;
        foreach ( array_keys( self::$networks ) as $key ) {
            if ( ! empty( $instance[ 'url_' . $key ] ) && esc_url( $instance[ 'url_' . $key ] ) ) {
                $has_any = true;
                break;
            }
        }
        if ( ! $has_any ) {
            return;
        }

        echo $args['before_widget'];
        if ( $title ) {
            echo $args['before_title'] . '<i class="fas fa-share-alt text-accent"></i> ' . esc_html( $title ) . $args['after_title'];
        }
        echo '<div class="flex flex-wrap gap-3">';
        foreach ( self::$networks as $key => $net ) {
            $url = ! empty( $instance[ 'url_' . $key ] ) ? esc_url( $instance[ 'url_' . $key ] ) : '';
            if ( empty( $url ) ) {
                continue;
            }
            ?>
            <a href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener noreferrer" class="w-10 h-10 flex items-center justify-center rounded-full bg-gray-100 hover:bg-accent hover:text-white text-gray-600 transition-colors" aria-label="<?php echo esc_attr( $net['label'] ); ?>">
                <i class="<?php echo esc_attr( $net['icon'] ); ?>"></i>
            </a>
            <?php
        }
        echo '</div>';
        echo $args['after_widget'];
    }

    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'ติดตามเรา', 'chrysoberyl' );
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title:', 'chrysoberyl' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <?php
        foreach ( self::$networks as $key => $net ) {
            $val = ! empty( $instance[ 'url_' . $key ] ) ? $instance[ 'url_' . $key ] : '';
            ?>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'url_' . $key ) ); ?>"><?php echo esc_html( $net['label'] ); ?> URL</label>
                <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'url_' . $key ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'url_' . $key ) ); ?>" type="url" value="<?php echo esc_attr( $val ); ?>" placeholder="<?php echo esc_attr( $net['url_placeholder'] ); ?>">
            </p>
            <?php
        }
    }

    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ! empty( $new_instance['title'] ) ? sanitize_text_field( $new_instance['title'] ) : '';
        foreach ( array_keys( self::$networks ) as $key ) {
            $instance[ 'url_' . $key ] = ! empty( $new_instance[ 'url_' . $key ] ) ? esc_url_raw( $new_instance[ 'url_' . $key ] ) : '';
        }
        return $instance;
    }
}
