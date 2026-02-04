<?php
/**
 * Template part: Category filters (mockup index.html — Desktop pills, Mobile dropdown)
 *
 * @package Chrysoberyl
 * @since 1.0.0
 */

$categories = get_categories( array(
    'parent'     => 0,
    'orderby'    => 'count',
    'order'      => 'DESC',
    'number'     => 8,
    'hide_empty' => true,
) );

$is_category = is_category();
$current_cat_id = $is_category ? get_queried_object_id() : 0;
$home_url = home_url( '/' );
$latest_label = __( 'Latest Stories', 'chrysoberyl' );
?>

<section class="mb-8 relative" aria-label="<?php esc_attr_e( 'Category filters', 'chrysoberyl' ); ?>">
    <!-- Mobile Dropdown (mockup: md:hidden) -->
    <div class="md:hidden">
        <button type="button" id="category-filter-toggle"
            class="chrysoberyl-category-toggle w-full px-5 py-3 rounded-lg bg-google-gray-100 text-google-gray font-medium text-sm hover:bg-google-gray-200 transition-colors flex items-center justify-between"
            aria-expanded="false"
            aria-haspopup="true"
            aria-controls="category-dropdown">
            <span id="selected-category"><?php echo esc_html( $is_category ? single_cat_title( '', false ) : $latest_label ); ?></span>
            <svg class="w-5 h-5 transition-transform chrysoberyl-category-chevron" id="category-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </button>
        <div id="category-dropdown"
            class="chrysoberyl-category-dropdown hidden absolute top-full left-0 right-0 mt-2 bg-white rounded-lg shadow-card-hover border border-gray-200 overflow-hidden z-50">
            <a href="<?php echo esc_url( $home_url ); ?>"
                class="chrysoberyl-category-link block px-5 py-3 hover:bg-blue-50 transition-colors border-b border-gray-100 flex items-center justify-between <?php echo ! $is_category ? 'bg-blue-50 text-google-blue' : ''; ?>"
                data-label="<?php echo esc_attr( $latest_label ); ?>">
                <span class="text-google-gray font-medium text-sm"><?php echo esc_html( $latest_label ); ?></span>
                <?php if ( ! $is_category ) : ?>
                <svg class="w-5 h-5 text-google-blue shrink-0" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                </svg>
                <?php endif; ?>
            </a>
            <?php foreach ( $categories as $cat ) :
                $cat_link = get_category_link( $cat->term_id );
                $is_current = $is_category && (int) $cat->term_id === $current_cat_id;
            ?>
            <a href="<?php echo esc_url( $cat_link ); ?>"
                class="chrysoberyl-category-link block px-5 py-3 hover:bg-google-gray-50 transition-colors border-b border-gray-100 flex items-center justify-between <?php echo $is_current ? 'bg-google-gray-50' : ''; ?>"
                data-label="<?php echo esc_attr( $cat->name ); ?>">
                <span class="text-google-gray-500 font-medium text-sm"><?php echo esc_html( $cat->name ); ?></span>
                <?php if ( $is_current ) : ?>
                <svg class="w-5 h-5 text-google-blue shrink-0" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                </svg>
                <?php endif; ?>
            </a>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Desktop Pills (mockup: hidden md:flex) -->
    <div class="hidden md:flex items-center gap-3 overflow-x-auto pb-4 no-scrollbar">
        <a href="<?php echo esc_url( $home_url ); ?>"
            class="px-5 py-2.5 rounded-pill font-medium text-sm whitespace-nowrap transition-colors <?php echo ! $is_category ? 'bg-blue-50 text-google-blue hover:bg-blue-100' : 'border border-gray-200 text-google-gray-500 hover:bg-google-gray-50 hover:border-gray-300'; ?>">
            <?php echo esc_html( $latest_label ); ?>
        </a>
        <?php foreach ( $categories as $cat ) :
            $cat_link = get_category_link( $cat->term_id );
            $is_current = $is_category && (int) $cat->term_id === $current_cat_id;
        ?>
        <a href="<?php echo esc_url( $cat_link ); ?>"
            class="px-5 py-2.5 rounded-pill font-medium text-sm whitespace-nowrap transition-all <?php echo $is_current ? 'bg-google-gray-100 text-google-gray hover:bg-google-gray-200' : 'border border-gray-200 text-google-gray-500 hover:bg-google-gray-50 hover:border-gray-300'; ?>">
            <?php echo esc_html( $cat->name ); ?>
        </a>
        <?php endforeach; ?>
    </div>
</section>
