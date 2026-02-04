<?php
/**
 * The template for displaying comments
 *
 * This is the template that displays the area that contains both the current comments
 * and the comment form.
 *
 * @package Chrysoberyl
 * @since 1.0.0
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
    return;
}
?>

<div id="comments" class="comments-area mt-12 pt-8 border-t border-gray-200" data-toc-exclude="true">

    <?php
    // You can start editing here -- including this comment!
    if ( have_comments() ) :
        ?>
        <h2 class="comments-title text-2xl font-bold text-gray-900 mb-6">
            <?php
            $comment_count = get_comments_number();
            if ( '1' === $comment_count ) {
                printf(
                    /* translators: 1: title. */
                    esc_html__( 'One thought on &ldquo;%1$s&rdquo;', 'chrysoberyl' ),
                    '<span>' . wp_kses_post( get_the_title() ) . '</span>'
                );
            } else {
                printf(
                    /* translators: 1: comment count number, 2: title. */
                    esc_html( _nx( '%1$s thought on &ldquo;%2$s&rdquo;', '%1$s thoughts on &ldquo;%2$s&rdquo;', $comment_count, 'comments title', 'chrysoberyl' ) ),
                    number_format_i18n( $comment_count ), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                    '<span>' . wp_kses_post( get_the_title() ) . '</span>'
                );
            }
            ?>
        </h2><!-- .comments-title -->

        <?php the_comments_navigation(); ?>

        <ol class="comment-list list-none space-y-6 mt-8">
            <?php
            wp_list_comments(
                array(
                    'style'      => 'ol',
                    'short_ping' => true,
                    'callback'   => 'chrysoberyl_comment',
                )
            );
            ?>
        </ol><!-- .comment-list -->

        <?php
        the_comments_navigation();

        // If comments are closed and there are comments, let's leave a little note (single post only; Codex 8.2: no message on pages).
        if ( is_single() && ! comments_open() ) :
            ?>
            <p class="no-comments text-gray-500 text-center py-8">
                <?php esc_html_e( 'Comments are closed.', 'chrysoberyl' ); ?>
            </p>
            <?php
        endif;

    endif; // Check for have_comments().

    // When comments are closed and there are no comments, show notice on single post only (Theme Unit Test: Comments Disabled; Codex 8.2: no message on pages).
    if ( is_single() && ! have_comments() && ! comments_open() ) :
        ?>
        <p class="no-comments text-gray-500 text-center py-8">
            <?php esc_html_e( 'Comments are closed.', 'chrysoberyl' ); ?>
        </p>
        <?php
    endif;

    $must_log_in_message = '<p class="must-log-in text-gray-700">' . sprintf(
        /* translators: %s: login link (opens modal) */
        __( 'You must be <a href="#chrysoberyl-login-modal" class="chrysoberyl-login-trigger text-google-blue hover:underline font-medium" data-open-login-modal aria-label="%s">logged in</a> to post a comment.', 'chrysoberyl' ),
        esc_attr__( 'Log in to post a comment', 'chrysoberyl' )
    ) . '</p>';
    comment_form(
        array(
            'title_reply'          => __( 'Leave a Reply', 'chrysoberyl' ),
            'title_reply_to'       => __( 'Leave a Reply to %s', 'chrysoberyl' ),
            'title_reply_before'   => '<h3 id="reply-title" class="comment-reply-title text-xl font-bold text-gray-900 mb-4">',
            'title_reply_after'    => '</h3>',
            'cancel_reply_before'  => ' <small class="text-gray-500">',
            'cancel_reply_after'   => '</small>',
            'cancel_reply_link'    => __( 'Cancel reply', 'chrysoberyl' ),
            'label_submit'         => __( 'Post Comment', 'chrysoberyl' ),
            'must_log_in'          => $must_log_in_message,
            'submit_button'        => '<input name="%1$s" type="submit" id="%2$s" class="%3$s bg-accent hover:bg-blue-600 text-white font-semibold py-2 px-6 rounded-lg transition duration-200 cursor-pointer" value="%4$s" />',
            'submit_field'         => '<p class="form-submit">%1$s %2$s</p>',
            'format'               => 'xhtml',
            'comment_field'        => '<p class="comment-form-comment mb-4"><label for="comment" class="block text-sm font-medium text-gray-700 mb-2">' . __( 'Comment', 'chrysoberyl' ) . ' <span class="required text-red-500">*</span></label><textarea id="comment" name="comment" cols="45" rows="8" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-accent outline-none transition"></textarea></p>',
            'fields'               => array(
                'author' => '<p class="comment-form-author mb-4"><label for="author" class="block text-sm font-medium text-gray-700 mb-2">' . __( 'Name', 'chrysoberyl' ) . ( $req ? ' <span class="required text-red-500">*</span>' : '' ) . '</label><input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . ( $req ? ' required' : '' ) . ' class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-accent outline-none transition" /></p>',
                'email'  => '<p class="comment-form-email mb-4"><label for="email" class="block text-sm font-medium text-gray-700 mb-2">' . __( 'Email', 'chrysoberyl' ) . ( $req ? ' <span class="required text-red-500">*</span>' : '' ) . '</label><input id="email" name="email" type="email" value="' . esc_attr( $commenter['comment_author_email'] ) . '" size="30"' . ( $req ? ' required' : '' ) . ' class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-accent outline-none transition" /></p>',
                'url'    => '<p class="comment-form-url mb-4"><label for="url" class="block text-sm font-medium text-gray-700 mb-2">' . __( 'Website', 'chrysoberyl' ) . '</label><input id="url" name="url" type="url" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-accent outline-none transition" /></p>',
            ),
        )
    );
    ?>

</div><!-- #comments -->

<?php
/**
 * Custom comment callback function
 *
 * @param WP_Comment $comment The comment object.
 * @param array      $args    An array of arguments.
 * @param int        $depth   The depth of the comment.
 */
function chrysoberyl_comment( $comment, $args, $depth ) {
    if ( 'div' === $args['style'] ) {
        $tag       = 'div';
        $add_below = 'comment';
    } else {
        $tag       = 'li';
        $add_below = 'div-comment';
    }
    ?>
    <<?php echo esc_attr( $tag ); ?> <?php comment_class( empty( $args['has_children'] ) ? 'mb-6' : 'parent mb-6' ); ?> id="comment-<?php comment_ID(); ?>">
    <?php if ( 'div' !== $args['style'] ) : ?>
        <div id="div-comment-<?php comment_ID(); ?>" class="comment-body">
    <?php endif; ?>
    
    <div class="flex gap-4">
        <div class="flex-shrink-0">
            <?php
            if ( $args['avatar_size'] != 0 ) {
                echo get_avatar( $comment, $args['avatar_size'], '', '', array( 'class' => 'rounded-full' ) );
            }
            ?>
        </div>
        
        <div class="flex-grow">
            <div class="comment-meta flex items-center gap-3 mb-2">
                <cite class="fn font-semibold text-gray-900">
                    <?php echo get_comment_author_link(); ?>
                </cite>
                <span class="comment-date text-sm text-gray-500">
                    <time datetime="<?php comment_time( 'c' ); ?>">
                        <?php
                        printf(
                            /* translators: 1: date, 2: time */
                            esc_html__( '%1$s at %2$s', 'chrysoberyl' ),
                            get_comment_date(),
                            get_comment_time()
                        );
                        ?>
                    </time>
                </span>
                <?php edit_comment_link( __( '(Edit)', 'chrysoberyl' ), '<span class="edit-link text-sm text-gray-500">', '</span>' ); ?>
            </div>
            
            <?php if ( '0' === $comment->comment_approved ) : ?>
                <em class="comment-awaiting-moderation text-sm text-yellow-600 italic">
                    <?php esc_html_e( 'Your comment is awaiting moderation.', 'chrysoberyl' ); ?>
                </em>
                <br />
            <?php endif; ?>
            
            <div class="comment-content text-gray-700 leading-relaxed mb-3">
                <?php comment_text(); ?>
            </div>
            
            <div class="comment-reply">
                <?php
                comment_reply_link(
                    array_merge(
                        $args,
                        array(
                            'add_below' => $add_below,
                            'depth'      => $depth,
                            'max_depth'  => $args['max_depth'],
                            'before'     => '<span class="reply text-sm text-accent hover:text-blue-600">',
                            'after'      => '</span>',
                        )
                    )
                );
                ?>
            </div>
        </div>
    </div>
    
    <?php if ( 'div' !== $args['style'] ) : ?>
        </div>
    <?php endif; ?>
    <?php
}
