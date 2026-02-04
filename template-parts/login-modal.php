<?php
/**
 * Template part: Login modal (single post comment — log in without leaving page)
 *
 * @package Chrysoberyl
 * @since 1.0.0
 */

if ( is_user_logged_in() ) {
    return;
}
?>

<div id="chrysoberyl-login-modal" class="chrysoberyl-login-modal hidden fixed inset-0 flex items-center justify-center p-4" aria-modal="true" aria-hidden="true" aria-labelledby="chrysoberyl-login-modal-title">
    <div class="chrysoberyl-login-backdrop absolute inset-0 bg-gray-600 bg-opacity-75 transition-opacity"></div>

    <div class="chrysoberyl-login-modal-content relative w-full max-w-md bg-white rounded-2xl shadow-2xl p-6 transform transition-all">
        <div class="flex items-center justify-between mb-6">
            <h2 id="chrysoberyl-login-modal-title" class="text-xl font-medium text-google-gray">
                <?php esc_html_e( 'Log in', 'chrysoberyl' ); ?>
            </h2>
            <button type="button" class="chrysoberyl-login-close p-2 text-gray-400 hover:text-gray-600 rounded-full transition-colors" aria-label="<?php esc_attr_e( 'Close', 'chrysoberyl' ); ?>">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div id="chrysoberyl-login-error" class="chrysoberyl-login-error hidden mb-4 p-4 rounded-lg border border-red-300 bg-red-50 text-red-800 text-sm font-medium" role="alert"></div>

        <form id="chrysoberyl-login-form" class="space-y-4" method="post" action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>">
            <input type="hidden" name="action" value="chrysoberyl_ajax_login" />
            <input type="hidden" name="redirect_to" id="chrysoberyl-login-redirect" value="" />
            <?php wp_nonce_field( 'chrysoberyl_ajax_login', 'chrysoberyl_login_nonce' ); ?>

            <p>
                <label for="chrysoberyl-login-user" class="block text-sm font-medium text-gray-700 mb-1">
                    <?php esc_html_e( 'Username or Email', 'chrysoberyl' ); ?>
                </label>
                <input type="text" name="log" id="chrysoberyl-login-user" autocomplete="username" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-google-blue focus:border-google-blue outline-none transition" />
            </p>
            <p>
                <label for="chrysoberyl-login-pwd" class="block text-sm font-medium text-gray-700 mb-1">
                    <?php esc_html_e( 'Password', 'chrysoberyl' ); ?>
                </label>
                <div class="chrysoberyl-login-pwd-wrap relative">
                    <input type="password" name="pwd" id="chrysoberyl-login-pwd" autocomplete="current-password" required
                        class="w-full px-4 py-2 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-google-blue focus:border-google-blue outline-none transition" />
                    <button type="button" id="chrysoberyl-login-pwd-toggle" class="chrysoberyl-login-pwd-toggle absolute right-2 top-1/2 -translate-y-1/2 p-2 text-gray-500 hover:text-gray-700 rounded focus:outline-none focus:ring-2 focus:ring-google-blue" aria-label="<?php esc_attr_e( 'Show password', 'chrysoberyl' ); ?>" title="<?php esc_attr_e( 'Show password', 'chrysoberyl' ); ?>" data-show-label="<?php esc_attr_e( 'Show password', 'chrysoberyl' ); ?>" data-hide-label="<?php esc_attr_e( 'Hide password', 'chrysoberyl' ); ?>">
                        <svg class="chrysoberyl-pwd-icon-show w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        <svg class="chrysoberyl-pwd-icon-hide w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19 12 19c.993 0 1.953-.138 2.863-.395M17 16.95A10.523 10.523 0 0122 12c-1.292-4.338-5.31-7-10.062-7C9.352 5 7.704 5.332 6.14 5.95l1.86 1.86M13 10.5a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-9-9m0 0l-9 9"/></svg>
                    </button>
                </div>
            </p>
            <p class="flex items-center gap-2">
                <input type="checkbox" name="remember" id="chrysoberyl-login-remember" value="forever"
                    class="rounded border-gray-300 text-google-blue focus:ring-google-blue" />
                <label for="chrysoberyl-login-remember" class="text-sm text-gray-600">
                    <?php esc_html_e( 'Remember me', 'chrysoberyl' ); ?>
                </label>
            </p>
            <p class="pt-2">
                <button type="submit" id="chrysoberyl-login-submit" class="w-full py-2.5 px-4 bg-google-blue hover:bg-blue-700 text-white font-medium rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-google-blue focus:ring-offset-2">
                    <?php esc_html_e( 'Log in', 'chrysoberyl' ); ?>
                </button>
            </p>
        </form>

        <p class="mt-4 text-center text-sm text-gray-500">
            <a href="<?php echo esc_url( wp_lostpassword_url( get_permalink() ) ); ?>" class="text-google-blue hover:underline">
                <?php esc_html_e( 'Lost your password?', 'chrysoberyl' ); ?>
            </a>
        </p>
    </div>
</div>
