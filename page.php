<?php
/**
 * The template for displaying static pages (e.g. Privacy, Terms, About).
 * Layout matches mockup/privacy.html and mockup/terms.html: single column, hero with border, prose content.
 * Supports optional sidebar when "Sidebar on Page" option is enabled.
 *
 * @package Chrysoberyl
 * @since 1.0.0
 */

get_header();

// Check if sidebar should be shown on page
$show_sidebar_page = get_option( 'chrysoberyl_sidebar_single_page_enabled', '1' ) === '1';
// Container width: full width (max-w-[1248px]) in both cases for consistent layout
$container_class = 'max-w-[1248px]';
?>

<main id="main-content" class="flex-grow w-full">
	<div class="container mx-auto px-4 md:px-6 lg:px-8 <?php echo esc_attr( $container_class ); ?> pb-20">

		<?php
		while ( have_posts() ) :
			the_post();
			$updated = get_the_modified_date();
			$show_date = ( get_the_modified_date() !== get_the_date() ) ? $updated : get_the_date();
			?>

			<!-- Hero (mockup: privacy/terms) -->
			<section class="py-12 md:py-16 mb-8 border-b border-gray-200">
				<h1 class="text-4xl md:text-5xl font-normal text-google-gray mb-4">
					<?php the_title(); ?>
				</h1>
				<p class="text-google-gray-500">
					<?php
					if ( get_the_modified_date() !== get_the_date() ) {
						printf( __( 'Last updated: %s', 'chrysoberyl' ), esc_html( $updated ) );
					} else {
						printf( __( 'Published: %s', 'chrysoberyl' ), esc_html( get_the_date() ) );
					}
					?>
				</p>
			</section>

			<!-- Content + Sidebar grid (when sidebar enabled) -->
			<div class="<?php echo $show_sidebar_page ? 'grid grid-cols-1 lg:grid-cols-12 gap-12' : ''; ?>">
				<!-- Main Content -->
				<div class="<?php echo $show_sidebar_page ? 'lg:col-span-8' : ''; ?>">

					<?php
					// TOC on page when Theme "Display on" includes Page single and position is top
					$toc_show_on_page = get_option( 'chrysoberyl_toc_show_on_single_page', '0' ) === '1';
					$toc_show_page = $toc_show_on_page && function_exists( 'chrysoberyl_show_toc_for_post' ) && chrysoberyl_show_toc_for_post();
					$toc_position = get_option( 'chrysoberyl_toc_position', 'top' );
					if ( $toc_show_page && $toc_position === 'top' ) :
					?>
					<div class="mb-8">
						<?php get_template_part( 'template-parts/table-of-contents' ); ?>
					</div>
					<?php endif; ?>

					<!-- Content (mockup: article.prose max-w-none) -->
					<article class="chrysoberyl-page-content prose max-w-none">

						<?php if ( has_excerpt() ) : ?>
							<p class="text-lg text-google-gray leading-relaxed mb-8">
								<?php the_excerpt(); ?>
							</p>
						<?php endif; ?>

						<?php if ( has_post_thumbnail() ) : ?>
							<figure class="mb-8 rounded-card overflow-hidden">
								<?php the_post_thumbnail( 'large', array( 'class' => 'w-full h-auto object-cover' ) ); ?>
								<?php if ( get_the_post_thumbnail_caption() ) : ?>
									<figcaption class="text-center text-sm text-google-gray-500 mt-2">
										<?php the_post_thumbnail_caption(); ?>
									</figcaption>
								<?php endif; ?>
							</figure>
						<?php endif; ?>

						<div class="chrysoberyl-article-content" data-toc-content="true">
							<?php
							the_content();

							wp_link_pages( array(
								'before' => '<div class="page-links mt-8 pt-6 border-t border-gray-200">' . __( 'Pages:', 'chrysoberyl' ),
								'after'  => '</div>',
							) );
							?>
						</div>

						<?php
						if ( get_option( 'chrysoberyl_social_show_on_page', '1' ) === '1' ) :
							$display_positions = get_option( 'chrysoberyl_social_display_positions', array( 'single_bottom' ) );
							if ( is_array( $display_positions ) && in_array( 'single_bottom', $display_positions ) ) :
						?>
							<div class="mt-8 pt-6 border-t border-gray-200">
								<?php get_template_part( 'template-parts/social-share' ); ?>
							</div>
						<?php endif; endif; ?>

						<?php if ( is_active_sidebar( 'after-content' ) ) : ?>
							<div class="mt-12">
								<?php dynamic_sidebar( 'after-content' ); ?>
							</div>
						<?php endif; ?>

						<?php
						if ( comments_open() || get_comments_number() ) :
							comments_template();
						endif;
						?>

					</article>

				</div>

				<?php if ( $show_sidebar_page ) : ?>
				<!-- Sidebar (same styling as single post) -->
				<aside class="lg:col-span-4">
					<div class="sticky top-24 space-y-8">
						<?php
						// TOC in sidebar when position is sidebar
						if ( $toc_show_page && $toc_position === 'sidebar' ) :
						?>
							<div class="chrysoberyl-toc-sidebar mb-6">
								<?php get_template_part( 'template-parts/table-of-contents' ); ?>
							</div>
						<?php endif; ?>

						<?php
						// Use Theme Settings widgets with proper styling (same as single post sidebar)
						foreach ( chrysoberyl_get_widgets_order() as $widget_key ) {
							if ( ! chrysoberyl_is_widget_enabled( $widget_key ) ) {
								continue;
							}
							// Related posts is for single posts only
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
											<div class="space-y-4" role="list" aria-label="<?php _e( 'Popular articles', 'chrysoberyl' ); ?>">
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
														<span class="text-2xl font-bold text-gray-200 group-hover:text-accent transition-all flex-shrink-0"><?php echo str_pad( $index, 2, '0', STR_PAD_LEFT ); ?></span>
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
									$current_post_id = get_the_ID();
									$latest_query = new WP_Query( array(
										'post_type'      => 'post',
										'posts_per_page' => 4,
										'post_status'    => 'publish',
										'post__not_in'   => array( $current_post_id ),
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
																<?php echo esc_html( human_time_diff( $post_date, current_time( 'timestamp' ) ) ); ?> <?php _e( 'ago', 'chrysoberyl' ); ?>
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
									if ( function_exists( 'chrysoberyl_render_sidebar_widget_by_key' ) ) {
										chrysoberyl_render_sidebar_widget_by_key( $widget_key );
									}
									break;
							}
						}
						?>
					</div>
				</aside>
				<?php endif; ?>

			</div>

		<?php endwhile; ?>

	</div>
</main>

<?php
get_footer();
