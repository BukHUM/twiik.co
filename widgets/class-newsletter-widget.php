<?php
/**
 * Newsletter Widget
 *
 * @package Chrysoberyl
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Newsletter Widget Class
 */
class chrysoberyl_Newsletter_Widget extends WP_Widget {

    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct(
            'chrysoberyl_newsletter',
            __( 'Chrysoberyl - Theme: Newsletter', 'chrysoberyl' ),
            array(
                'description' => __( 'Newsletter subscription form', 'chrysoberyl' ),
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
        $title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'ไม่พลาดทุกเทรนด์', 'chrysoberyl' );
        $description = ! empty( $instance['description'] ) ? $instance['description'] : __( 'สมัครรับข่าวสารสรุปประจำวันส่งตรงถึงอีเมลของคุณ', 'chrysoberyl' );
        $button_text = ! empty( $instance['button_text'] ) ? $instance['button_text'] : __( 'ติดตาม', 'chrysoberyl' );

        echo $args['before_widget'];

        ?>
        <div class="bg-gradient-to-br from-gray-900 to-gray-800 text-white p-6 rounded-xl relative overflow-hidden shadow-lg">
            <div class="relative z-10">
                <?php if ( $title ) : ?>
                    <div class="flex items-center gap-2 mb-2">
                        <i class="fas fa-envelope text-accent text-xl"></i>
                        <h3 class="font-bold text-xl"><?php echo esc_html( $title ); ?></h3>
                    </div>
                <?php endif; ?>
                
                <?php if ( $description ) : ?>
                    <p class="text-gray-300 text-sm mb-4 leading-relaxed">
                        <?php echo esc_html( $description ); ?>
                    </p>
                <?php endif; ?>
                
                <form class="newsletter-form space-y-3" 
                      action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" 
                      method="post"
                      aria-label="<?php _e( 'Newsletter subscription', 'chrysoberyl' ); ?>">
                    <input type="hidden" name="action" value="chrysoberyl_newsletter_subscribe">
                    <?php wp_nonce_field( 'chrysoberyl_newsletter', 'newsletter_nonce' ); ?>
                    
                    <input type="email" 
                           name="email" 
                           placeholder="<?php _e( 'ใส่อีเมลของคุณ', 'chrysoberyl' ); ?>" 
                           required
                           aria-label="<?php _e( 'Email address', 'chrysoberyl' ); ?>"
                           class="newsletter-input w-full px-4 py-3 rounded-lg text-gray-900 focus:outline-none focus:ring-2 focus:ring-accent transition-all duration-200">
                    
                    <button type="submit"
                            class="w-full bg-accent hover:bg-blue-600 text-white font-bold py-3 rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        <i class="fas fa-paper-plane mr-2"></i><?php echo esc_html( $button_text ); ?>
                    </button>
                </form>
            </div>
            <!-- Decorative elements -->
            <div class="absolute -bottom-10 -right-10 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
            <div class="absolute -top-10 -left-10 w-24 h-24 bg-accent/20 rounded-full blur-xl"></div>
        </div>
        <?php

        echo $args['after_widget'];
    }

    /**
     * Widget form
     *
     * @param array $instance Widget instance.
     */
    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'ไม่พลาดทุกเทรนด์', 'chrysoberyl' );
        $description = ! empty( $instance['description'] ) ? $instance['description'] : __( 'สมัครรับข่าวสารสรุปประจำวันส่งตรงถึงอีเมลของคุณ', 'chrysoberyl' );
        $button_text = ! empty( $instance['button_text'] ) ? $instance['button_text'] : __( 'ติดตาม', 'chrysoberyl' );
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
            <label for="<?php echo esc_attr( $this->get_field_id( 'description' ) ); ?>">
                <?php _e( 'Description:', 'chrysoberyl' ); ?>
            </label>
            <textarea class="widefat" 
                      id="<?php echo esc_attr( $this->get_field_id( 'description' ) ); ?>" 
                      name="<?php echo esc_attr( $this->get_field_name( 'description' ) ); ?>" 
                      rows="3"><?php echo esc_textarea( $description ); ?></textarea>
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'button_text' ) ); ?>">
                <?php _e( 'Button Text:', 'chrysoberyl' ); ?>
            </label>
            <input class="widefat" 
                   id="<?php echo esc_attr( $this->get_field_id( 'button_text' ) ); ?>" 
                   name="<?php echo esc_attr( $this->get_field_name( 'button_text' ) ); ?>" 
                   type="text" 
                   value="<?php echo esc_attr( $button_text ); ?>">
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
        $instance['description'] = ! empty( $new_instance['description'] ) ? sanitize_textarea_field( $new_instance['description'] ) : '';
        $instance['button_text'] = ! empty( $new_instance['button_text'] ) ? sanitize_text_field( $new_instance['button_text'] ) : '';
        return $instance;
    }
}
