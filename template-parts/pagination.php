<?php
/**
 * Template part for displaying pagination
 *
 * @package Chrysoberyl
 * @since 1.0.0
 */

if ( ! function_exists( 'chrysoberyl_pagination' ) ) {
    function chrysoberyl_pagination() {
        global $wp_query;

        if ( $wp_query->max_num_pages <= 1 ) {
            return;
        }

        $paged = get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1;
        $max   = intval( $wp_query->max_num_pages );
        $links = array();

        if ( $paged >= 1 ) {
            $links[] = $paged;
        }
        if ( $paged >= 3 ) {
            $links[] = $paged - 1;
            $links[] = $paged - 2;
        }
        if ( ( $paged + 2 ) <= $max ) {
            $links[] = $paged + 2;
            $links[] = $paged + 1;
        }
        $links = array_unique( $links );
        sort( $links );

        echo '<nav class="pagination flex flex-wrap justify-center items-center gap-2 mt-10 mb-16" aria-label="' . esc_attr__( 'Pagination', 'chrysoberyl' ) . '">';

        if ( get_previous_posts_link() ) {
            printf( '<a href="%s" class="px-4 py-2 rounded-pill border border-gray-300 text-google-gray-500 font-medium hover:bg-blue-50 hover:border-blue-200 transition-all">%s</a>',
                esc_url( get_previous_posts_page_link() ),
                '<i class="fas fa-chevron-left mr-1"></i>' . __( 'ก่อนหน้า', 'chrysoberyl' )
            );
        }

        foreach ( $links as $link ) {
            if ( (int) $link === $paged ) {
                printf( '<span class="px-4 py-2 rounded-pill bg-google-blue text-white font-medium">%s</span>', (int) $link );
            } else {
                printf( '<a href="%s" class="px-4 py-2 rounded-pill border border-gray-300 text-google-gray-500 font-medium hover:bg-blue-50 hover:border-blue-200 transition-all">%s</a>',
                    esc_url( get_pagenum_link( (int) $link ) ),
                    (int) $link
                );
            }
        }

        if ( get_next_posts_link() ) {
            printf( '<a href="%s" class="px-4 py-2 rounded-pill border border-gray-300 text-google-gray-500 font-medium hover:bg-blue-50 hover:border-blue-200 transition-all">%s</a>',
                esc_url( get_next_posts_page_link() ),
                __( 'ถัดไป', 'chrysoberyl' ) . ' <i class="fas fa-chevron-right ml-1"></i>'
            );
        }

        echo '</nav>';
    }
}

chrysoberyl_pagination();
