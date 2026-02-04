<?php
/**
 * Template part: Sidebar on archive (category, tag, author, date).
 * แสดงเฉพาะ widget ที่เปิดใช้และลำดับจากหลังบ้าน (Theme Options > Widgets).
 *
 * @package Chrysoberyl
 * @since 1.0.0
 */
?>

<aside class="lg:w-1/3 space-y-8 sticky-sidebar" aria-label="<?php esc_attr_e( 'Sidebar', 'chrysoberyl' ); ?>">
    <?php
    foreach ( chrysoberyl_get_widgets_order() as $widget_key ) {
        if ( ! chrysoberyl_is_widget_enabled( $widget_key ) ) {
            continue;
        }
        // related_posts ใช้เฉพาะ single
        if ( $widget_key === 'related_posts' ) {
            continue;
        }
        switch ( $widget_key ) {
            case 'popular_posts':
                $popular_query = chrysoberyl_get_popular_posts( 4, 'views' );
                if ( ! $popular_query->have_posts() ) {
                    $popular_query = chrysoberyl_get_popular_posts( 4, 'date' );
                }
                if ( $popular_query->have_posts() ) :
                    global $post;
                    $current_post = $post;
                    ?>
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 mb-6">
                        <h3 class="font-bold text-xl mb-5 flex items-center gap-2">
                            <i class="fas fa-fire text-accent"></i>
                            <?php _e( 'ยอดนิยม', 'chrysoberyl' ); ?>
                        </h3>
                        <div class="space-y-4" role="list" aria-label="<?php esc_attr_e( 'Popular articles', 'chrysoberyl' ); ?>">
                            <?php
                            $index = 0;
                            while ( $popular_query->have_posts() ) :
                                $popular_query->the_post();
                                $post_obj = $popular_query->post;
                                $post_title = $post_obj->post_title;
                                if ( empty( $post_title ) ) {
                                    continue;
                                }
                                $index++;
                                ?>
                                <a href="<?php echo esc_url( chrysoberyl_fix_url( get_permalink( $post_obj->ID ) ) ); ?>"
                                   class="popular-item flex gap-4 items-start group p-2 rounded-lg hover:bg-gray-50 transition-colors duration-200"
                                   role="listitem">
                                    <span class="text-2xl font-bold text-gray-200 group-hover:text-accent transition-all flex-shrink-0"><?php echo str_pad( (string) $index, 2, '0', STR_PAD_LEFT ); ?></span>
                                    <h4 class="text-sm font-medium text-gray-700 group-hover:text-accent transition line-clamp-2 leading-snug"><?php echo esc_html( $post_title ); ?></h4>
                                </a>
                            <?php
                            endwhile;
                            $post = $current_post;
                            wp_reset_postdata();
                            ?>
                        </div>
                    </div>
                    <?php
                endif;
                break;
            case 'recent_posts':
                $latest_query = new WP_Query( array(
                    'post_type'      => 'post',
                    'posts_per_page' => 4,
                    'post_status'    => 'publish',
                    'orderby'        => 'date',
                    'order'          => 'DESC',
                    'ignore_sticky_posts' => true,
                ) );
                if ( $latest_query->have_posts() ) :
                    ?>
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 mb-6">
                        <h3 class="font-bold text-xl mb-4 flex items-center gap-2">
                            <i class="fas fa-newspaper text-accent"></i>
                            <?php _e( 'ข่าวน่าสนใจ', 'chrysoberyl' ); ?>
                        </h3>
                        <div class="space-y-6">
                            <?php
                            while ( $latest_query->have_posts() ) :
                                $latest_query->the_post();
                                $post_obj = $latest_query->post;
                                $post_title = $post_obj->post_title;
                                $post_permalink = chrysoberyl_fix_url( get_permalink( $post_obj->ID ) );
                                $post_date = get_post_time( 'U', false, $post_obj->ID );
                                $thumbnail_id = get_post_thumbnail_id( $post_obj->ID );
                                $categories = get_the_category( $post_obj->ID );
                                $category_name = ! empty( $categories ) ? $categories[0]->name : '';
                                if ( empty( $post_title ) ) {
                                    continue;
                                }
                                ?>
                                <a href="<?php echo esc_url( $post_permalink ); ?>" class="flex gap-4 group cursor-pointer">
                                    <?php if ( $thumbnail_id ) : ?>
                                        <div class="w-20 h-20 flex-shrink-0 rounded-lg overflow-hidden">
                                            <?php echo get_the_post_thumbnail( $post_obj->ID, 'chrysoberyl-thumbnail', array( 'class' => 'w-full h-full object-cover group-hover:scale-110 transition', 'alt' => esc_attr( $post_title ), 'loading' => 'lazy' ) ); ?>
                                        </div>
                                    <?php else : ?>
                                        <div class="w-20 h-20 flex-shrink-0 rounded-lg overflow-hidden bg-gray-200 flex items-center justify-center">
                                            <i class="fas fa-image text-gray-400 text-2xl"></i>
                                        </div>
                                    <?php endif; ?>
                                    <div>
                                        <h4 class="text-sm font-bold text-gray-900 group-hover:text-accent transition line-clamp-2 mb-1"><?php echo esc_html( $post_title ); ?></h4>
                                        <span class="text-xs text-gray-400">
                                            <?php if ( $category_name ) : ?><?php echo esc_html( $category_name ); ?> • <?php endif; ?>
                                            <?php echo esc_html( human_time_diff( $post_date, (int) current_time( 'timestamp' ) ) ); ?> <?php _e( 'ago', 'chrysoberyl' ); ?>
                                        </span>
                                    </div>
                                </a>
                            <?php
                            endwhile;
                            wp_reset_postdata();
                            ?>
                        </div>
                    </div>
                    <?php
                endif;
                break;
            case 'trending_tags':
                ?>
                <div class="mb-6">
                    <?php get_template_part( 'template-parts/trending-tags' ); ?>
                </div>
                <?php
                break;
            default:
                chrysoberyl_render_sidebar_widget_by_key( $widget_key );
                break;
        }
    }
    ?>
</aside>
