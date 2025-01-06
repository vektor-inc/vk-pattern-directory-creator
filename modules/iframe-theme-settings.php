<?php
/**
 * 設定メニュー追加
 */
function vkpdc_add_iframe_admin_menu() {
    add_submenu_page(
        'edit.php?post_type=vk-patterns',
        __( 'Iframe Theme Settings', 'vk-pattern-directory-creator' ),
        __( 'Iframe Theme Settings', 'vk-pattern-directory-creator' ),
        'manage_options',
        'vkpdc-iframe-settings',
        'vkpdc_render_iframe_settings_page'
    );
}
add_action( 'admin_menu', 'vkpdc_add_iframe_admin_menu' );

/**
 * 管理画面の設定ページを表示
 */
function vkpdc_render_iframe_settings_page() {
    $selected_theme = get_option( 'vkpdc_selected_theme', '' );
    $themes = wp_get_themes();

    if ( isset( $_POST['vkpdc_theme'] ) ) {
        check_admin_referer( 'vkpdc_save_iframe_settings' ); // Nonce名も変更
        $selected_theme = sanitize_text_field( $_POST['vkpdc_theme'] );
        update_option( 'vkpdc_selected_theme', $selected_theme );
        echo '<div class="updated"><p>' . __( 'Settings saved.', 'vk-pattern-directory-creator' ) . '</p></div>';
    }

    ?>
    <div class="wrap">
        <h1><?php _e( 'Iframe Theme Settings', 'vk-pattern-directory-creator' ); ?></h1>
        <form method="post" action="">
            <?php wp_nonce_field( 'vkpdc_save_iframe_settings' ); ?>
            <table class="form-table">
                <tr>
                    <th scope="row">
                        <label for="vkpdc_theme"><?php _e( 'Select Theme for Iframe:', 'vk-pattern-directory-creator' ); ?></label>
                    </th>
                    <td>
                        <select id="vkpdc_theme" name="vkpdc_theme">
                            <option value=""><?php _e( 'Default', 'vk-pattern-directory-creator' ); ?></option>
                            <?php foreach ( $themes as $theme_slug => $theme ) : ?>
                                <option value="<?php echo esc_attr( $theme_slug ); ?>" <?php selected( $theme_slug, $selected_theme ); ?>>
                                    <?php echo esc_html( $theme->get( 'Name' ) ); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
            </table>
            <p>
                <input type="submit" class="button-primary" value="<?php _e( 'Save Changes', 'vk-pattern-directory-creator' ); ?>">
            </p>
        </form>
    </div>
    <?php
}
