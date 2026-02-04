<?php
/**
 * The template for displaying single posts.
 * ตรง mockup single — breadcrumb, หัวข้อ, วันที่, หมวด, เนื้อหา, sidebar layout, social share, TOC
 *
 * @package Chrysoberyl
 * @since 1.0.0
 */

get_header();
?>

<!-- Reading Progress Bar (mockup-style) -->
<div class="h-1 bg-gray-200 w-full fixed top-16 z-40">
    <div class="h-full bg-google-blue w-0 transition-all duration-300" id="reading-progress"></div>
</div>

<main id="main-content" class="flex-grow w-full">
    <div class="container mx-auto px-4 md:px-6 lg:px-8 max-w-[1248px] pb-20">

        <!-- Breadcrumb (mockup: mb-6 ใต้ breadcrumb) -->
        <div class="mb-6">
            <?php get_template_part( 'template-parts/breadcrumb' ); ?>
        </div>

        <?php $show_sidebar_single = ( get_option( 'chrysoberyl_sidebar_single_post_enabled', '1' ) === '1' ); ?>

        <article>
            <!-- Article Header (mockup: category mb-4, H1 mb-6, excerpt mb-8, author row pb-8) -->
            <header class="mb-10">
                <?php
                $categories = get_the_category();
                if ( ! empty( $categories ) ) :
                    $category = $categories[0];
                    ?>
                    <a href="<?php echo esc_url( get_category_link( $category->term_id ) ); ?>"
                        class="inline-block mb-4 text-google-blue text-sm font-medium uppercase tracking-wider hover:underline">
                        <?php echo esc_html( $category->name ); ?>
                    </a>
                <?php endif; ?>

                <h1 class="text-3xl md:text-4xl lg:text-5xl font-normal text-google-gray mb-6 leading-tight">
                    <?php
                    if ( get_the_title() ) {
                        the_title();
                    } else {
                        printf( '<a href="%1$s" rel="bookmark">%2$s</a>', esc_url( get_permalink() ), get_the_date() );
                    }
                    ?>
                </h1>

                <?php if ( has_excerpt() ) : ?>
                    <div class="border-l-4 border-google-blue pl-6 mb-8 max-w-3xl">
                        <p class="text-xl text-google-gray-500 leading-relaxed m-0">
                            <?php the_excerpt(); ?>
                        </p>
                    </div>
                <?php else : ?>
                    <div class="mb-8" aria-hidden="true"></div>
                <?php endif; ?>

                <!-- Author & Date row (mockup: avatar, author, date, reading time, share) -->
                <div class="flex flex-wrap items-center gap-6 text-sm text-google-gray-500 pb-8 border-b border-gray-200">
                    <div class="flex items-center gap-3">
                        <?php echo get_avatar( get_the_author_meta( 'ID' ), 40, '', '', array( 'class' => 'w-10 h-10 rounded-full object-cover' ) ); ?>
                        <div>
                            <span class="block font-medium text-google-gray"><?php the_author(); ?></span>
                            <span class="text-xs"><?php echo esc_html( __( 'Author', 'chrysoberyl' ) ); ?></span>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <span><?php echo esc_html( get_the_date() ); ?></span>
                        <span aria-hidden="true">•</span>
                        <span><?php printf( _n( '%d minute to read', '%d minutes to read', chrysoberyl_get_reading_time( get_the_ID() ), 'chrysoberyl' ), chrysoberyl_get_reading_time( get_the_ID() ) ); ?></span>
                    </div>
                    <?php
                    if ( get_option( 'chrysoberyl_social_show_on_post', '1' ) === '1' ) {
                        $display_positions = get_option( 'chrysoberyl_social_display_positions', array( 'single_bottom' ) );
                        if ( in_array( 'single_top', $display_positions, true ) ) :
                            ?>
                            <div class="flex items-center gap-2 ml-auto">
                                <?php get_template_part( 'template-parts/social-share' ); ?>
                            </div>
                            <?php
                        endif;
                    }
                    ?>
                </div>
            </header>

            <!-- Featured Image (mockup: rounded-card aspect-video) -->
            <?php if ( has_post_thumbnail() ) : ?>
                <figure class="mb-10">
                    <?php the_post_thumbnail( 'chrysoberyl-hero', array( 'class' => 'w-full rounded-card aspect-video object-cover shadow-card' ) ); ?>
                    <?php if ( get_the_post_thumbnail_caption() ) : ?>
                        <figcaption class="text-sm text-google-gray-500 mt-3 text-center">
                            <?php the_post_thumbnail_caption(); ?>
                        </figcaption>
                    <?php endif; ?>
                </figure>
            <?php endif; ?>

            <!-- Content + Sidebar grid (mockup: main lg:col-span-8, sidebar lg:col-span-4 sticky) -->
            <div class="<?php echo $show_sidebar_single ? 'grid grid-cols-1 lg:grid-cols-12 gap-12' : ''; ?>">
                <!-- Main Content -->
                <div class="<?php echo $show_sidebar_single ? 'lg:col-span-8' : ''; ?>">
                    <?php
                    $toc_show_on_post = get_option( 'chrysoberyl_toc_show_on_single_post', '1' ) === '1';
                    $toc_show = chrysoberyl_show_toc_for_post() && $toc_show_on_post;
                    $toc_position = get_option( 'chrysoberyl_toc_position', 'top' );
                    if ( $toc_show && $toc_position === 'top' ) :
                    ?>
                        <div class="mb-8">
                            <?php get_template_part( 'template-parts/table-of-contents' ); ?>
                        </div>
                    <?php endif; ?>

                    <!-- Article Body (mockup: prose) -->
                    <div class="prose prose-lg max-w-none text-google-gray leading-relaxed chrysoberyl-single-prose" id="article-content" data-toc-content="true">
                        <div class="chrysoberyl-article-content">
                        <?php
                        the_content();
                        wp_link_pages( array(
                            'before' => '<div class="page-links mt-8 pt-6 border-t border-gray-200">' . __( 'Pages:', 'chrysoberyl' ),
                            'after'  => '</div>',
                        ) );
                        ?>
                        </div>
                    </div>

                    <?php
                    if ( get_option( 'chrysoberyl_social_show_on_post', '1' ) === '1' ) {
                        $display_positions = get_option( 'chrysoberyl_social_display_positions', array( 'single_bottom' ) );
                        if ( in_array( 'single_bottom', $display_positions, true ) ) :
                            ?>
                            <div class="mt-8 pt-6 border-t border-gray-200">
                                <?php get_template_part( 'template-parts/social-share' ); ?>
                            </div>
                            <?php
                        endif;
                    }
                    ?>

                    <!-- Tags (mockup: pills) -->
                    <?php
                    $tags = get_the_tags();
                    if ( $tags ) :
                        ?>
                        <div class="mt-10 flex flex-wrap gap-2">
                            <?php foreach ( $tags as $tag ) : ?>
                                <a href="<?php echo esc_url( get_tag_link( $tag->term_id ) ); ?>"
                                    class="px-4 py-2 bg-google-gray-100 text-google-gray-500 rounded-full text-sm hover:bg-google-blue hover:text-white transition-colors">
                                    #<?php echo esc_html( $tag->name ); ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <!-- After Content Widget -->
                    <?php if ( is_active_sidebar( 'after-content' ) ) : ?>
                        <div class="mt-12">
                            <?php dynamic_sidebar( 'after-content' ); ?>
                        </div>
                    <?php endif; ?>

                    <!-- Comments -->
                    <?php comments_template(); ?>
                </div>

                <?php if ( $show_sidebar_single ) : ?>
                    <!-- Sidebar (mockup: sticky top-24, TOC + widgets) -->
                    <aside class="lg:col-span-4">
                        <div class="sticky top-24 space-y-8">
                            <?php get_template_part( 'template-parts/sidebar-single' ); ?>
                        </div>
                    </aside>
                <?php endif; ?>
            </div>

            <!-- Author Bio (mockup: ด้านล่าง content ก่อน Related Posts) — เปิด/ปิดได้จาก Theme Settings -->
            <?php if ( get_option( 'chrysoberyl_author_box_single_enabled', '1' ) === '1' ) : ?>
                <?php get_template_part( 'template-parts/author-box' ); ?>
            <?php endif; ?>

            <!-- Related Posts (mockup: ด้านนอก widget / นอก grid เต็มความกว้าง) -->
            <?php
            $related_posts = get_posts( array(
                'category__in'   => wp_get_post_categories( get_the_ID() ),
                'numberposts'    => 3,
                'post__not_in'   => array( get_the_ID() ),
            ) );
            if ( ! empty( $related_posts ) ) :
                ?>
                <section class="mt-20" aria-label="<?php esc_attr_e( 'บทความที่เกี่ยวข้อง', 'chrysoberyl' ); ?>">
                    <h2 class="text-2xl font-normal text-google-gray mb-8"><?php _e( 'บทความที่เกี่ยวข้อง', 'chrysoberyl' ); ?></h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <?php foreach ( $related_posts as $related_post ) : ?>
                            <article class="group">
                                <a href="<?php echo esc_url( chrysoberyl_fix_url( get_permalink( $related_post->ID ) ) ); ?>" class="block overflow-hidden rounded-card mb-4 aspect-video shadow-card hover:shadow-card-hover transition-shadow">
                                    <?php
                                    if ( has_post_thumbnail( $related_post->ID ) ) {
                                        echo get_the_post_thumbnail( $related_post->ID, 'chrysoberyl-card', array( 'class' => 'w-full h-full object-cover transition duration-300 group-hover:scale-105' ) );
                                    } else {
                                        echo '<div class="w-full h-full bg-google-gray-100 flex items-center justify-center"><i class="fas fa-image text-google-gray-400 text-3xl"></i></div>';
                                    }
                                    ?>
                                </a>
                                <div class="space-y-2">
                                    <?php
                                    $rel_cats = get_the_category( $related_post->ID );
                                    if ( ! empty( $rel_cats ) ) :
                                        ?>
                                        <span class="text-google-blue text-xs font-medium uppercase tracking-wider"><?php echo esc_html( $rel_cats[0]->name ); ?></span>
                                    <?php endif; ?>
                                    <h3 class="text-lg font-medium text-google-gray group-hover:text-google-blue transition-colors line-clamp-2">
                                        <a href="<?php echo esc_url( chrysoberyl_fix_url( get_permalink( $related_post->ID ) ) ); ?>"><?php echo esc_html( $related_post->post_title ); ?></a>
                                    </h3>
                                    <div class="flex items-center gap-2 text-xs text-google-gray-500">
                                        <span><?php echo esc_html( get_the_date( '', $related_post->ID ) ); ?></span>
                                    </div>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                </section>
            <?php endif; ?>
        </article>
    </div>
</main>

<script>
// Reading Progress Bar
(function() {
    const progressBar = document.getElementById('reading-progress');
    if (!progressBar) return;
    window.addEventListener('scroll', function() {
        const windowHeight = window.innerHeight;
        const documentHeight = document.documentElement.scrollHeight;
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        const scrollPercent = (documentHeight - windowHeight) > 0 ? (scrollTop / (documentHeight - windowHeight)) * 100 : 0;
        progressBar.style.width = Math.min(100, scrollPercent) + '%';
    });
})();
</script>

<?php
get_footer();
