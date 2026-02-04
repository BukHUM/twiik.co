<?php
/**
 * Template Name: FAQ
 * FAQ page: hero, category pills, accordion (mockup/faq.html).
 *
 * How to add/edit FAQs:
 * 1. In WP Admin go to FAQs → Add New (or edit existing).
 * 2. Title = question; Content = answer (supports links, bold, etc.).
 * 3. Assign one or more FAQ Categories (About Us, Usage, Join Us) to filter on the page.
 * 4. Use "Order" (page-attributes) to control order; lower number = higher in list.
 * 5. Create a Page (e.g. "FAQ"), set slug "faq" if needed, and assign this template (FAQ).
 *
 * @package Chrysoberyl
 * @since 1.0.0
 */

get_header();

$faq_categories = get_terms( array(
	'taxonomy'   => 'faq_category',
	'hide_empty' => true,
	'orderby'   => 'name',
	'order'     => 'ASC',
) );
$faqs = get_posts( array(
	'post_type'      => 'chrysoberyl_faq',
	'posts_per_page' => -1,
	'orderby'        => 'menu_order title',
	'order'          => 'ASC',
	'post_status'    => 'publish',
) );
$contact_url = home_url( '/contact' );
$contact_page = get_page_by_path( 'contact' );
if ( $contact_page ) {
	$contact_url = get_permalink( $contact_page );
}
?>

<main id="main-content" class="flex-grow w-full">
	<div class="container mx-auto px-4 md:px-6 lg:px-8 max-w-[900px] pb-20">

		<?php while ( have_posts() ) : the_post(); ?>

		<!-- Hero (mockup) -->
		<section class="text-center py-12 md:py-16 mb-12">
			<h1 class="text-4xl md:text-5xl font-normal text-google-gray mb-4">
				<?php the_title(); ?>
			</h1>
			<?php if ( has_excerpt() ) : ?>
				<p class="text-lg text-google-gray-500"><?php the_excerpt(); ?></p>
			<?php else : ?>
				<p class="text-lg text-google-gray-500">
					<?php _e( 'Answers to questions people often ask about Chrysoberyl.', 'chrysoberyl' ); ?>
				</p>
			<?php endif; ?>
		</section>

		<!-- FAQ Category Pills (mockup) -->
		<section class="flex flex-wrap justify-center gap-3 mb-12" role="tablist" aria-label="<?php esc_attr_e( 'Filter by category', 'chrysoberyl' ); ?>">
			<button type="button" class="faq-filter-pill px-5 py-2 rounded-pill text-sm font-medium bg-google-blue text-white" data-filter="all" aria-pressed="true">
				<?php _e( 'All', 'chrysoberyl' ); ?>
			</button>
			<?php
			if ( ! is_wp_error( $faq_categories ) && ! empty( $faq_categories ) ) :
				foreach ( $faq_categories as $term ) :
					?>
					<button type="button" class="faq-filter-pill px-5 py-2 bg-google-gray-100 text-google-gray-500 rounded-pill text-sm font-medium hover:bg-google-gray-200 transition-colors" data-filter="<?php echo esc_attr( $term->slug ); ?>" aria-pressed="false">
						<?php echo esc_html( $term->name ); ?>
					</button>
				<?php endforeach; endif; ?>
		</section>

		<!-- FAQ Accordion (mockup: details/summary) -->
		<section class="space-y-4 mb-16" id="faq-accordion">
			<?php
			$first = true;
			foreach ( $faqs as $faq_post ) :
				$terms = get_the_terms( $faq_post->ID, 'faq_category' );
				$slugs = array();
				if ( ! is_wp_error( $terms ) && $terms ) {
					foreach ( $terms as $t ) {
						$slugs[] = $t->slug;
					}
				}
				$data_cat = ! empty( $slugs ) ? implode( ' ', $slugs ) : 'uncategorized';
				?>
				<details class="faq-item group bg-google-gray-50 rounded-card overflow-hidden <?php echo $first ? 'open' : ''; ?>" data-faq-category="<?php echo esc_attr( $data_cat ); ?>" <?php echo $first ? ' open' : ''; ?>>
					<summary class="flex items-center justify-between px-6 py-5 cursor-pointer list-none focus:outline-none focus-visible:ring-2 focus-visible:ring-google-blue focus-visible:ring-offset-2 rounded-card">
						<h3 class="text-lg font-medium text-google-gray pr-4"><?php echo esc_html( $faq_post->post_title ); ?></h3>
						<svg class="w-6 h-6 text-google-gray-500 shrink-0 group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
						</svg>
					</summary>
					<div class="px-6 pb-5 text-google-gray-500 leading-relaxed faq-answer prose prose-sm max-w-none [&_a]:text-google-blue [&_a]:hover:underline">
						<?php echo wp_kses_post( apply_filters( 'the_content', $faq_post->post_content ) ); ?>
					</div>
				</details>
				<?php
				$first = false;
			endforeach;
			?>

			<?php if ( empty( $faqs ) ) : ?>
				<p class="text-center text-google-gray-500 py-8">
					<?php _e( 'No FAQs yet. Add them in WP Admin → FAQs.', 'chrysoberyl' ); ?>
				</p>
			<?php endif; ?>
		</section>

		<!-- CTA (mockup) -->
		<section class="text-center bg-google-gray-50 rounded-card p-8 md:p-12">
			<h2 class="text-2xl font-normal text-google-gray mb-4">
				<?php _e( 'Still have questions?', 'chrysoberyl' ); ?>
			</h2>
			<p class="text-google-gray-500 mb-6">
				<?php _e( 'If you didn\'t find your answer, get in touch with our team.', 'chrysoberyl' ); ?>
			</p>
			<a href="<?php echo esc_url( $contact_url ); ?>"
				class="inline-flex items-center justify-center px-8 py-3 bg-google-blue text-white font-medium rounded-pill hover:bg-blue-700 transition-all shadow-md hover:shadow-lg">
				<?php _e( 'Contact us', 'chrysoberyl' ); ?>
			</a>
		</section>

		<?php endwhile; ?>

	</div>
</main>

<script>
(function() {
	var pills = document.querySelectorAll('.faq-filter-pill');
	var items = document.querySelectorAll('.faq-item');
	if (!pills.length || !items.length) return;
	pills.forEach(function(pill) {
		pill.addEventListener('click', function() {
			var filter = this.getAttribute('data-filter');
			pills.forEach(function(p) {
				var isActive = p === pill;
				p.setAttribute('aria-pressed', isActive ? 'true' : 'false');
				p.classList.remove('bg-google-blue', 'text-white', 'bg-google-gray-100', 'text-google-gray-500');
				if (isActive) {
					p.classList.add('bg-google-blue', 'text-white');
				} else {
					p.classList.add('bg-google-gray-100', 'text-google-gray-500');
				}
			});
			items.forEach(function(item) {
				var cat = item.getAttribute('data-faq-category') || '';
				var show = filter === 'all' || (cat && cat.indexOf(filter) !== -1);
				item.style.display = show ? '' : 'none';
			});
		});
	});
})();
</script>

<?php
get_footer();
