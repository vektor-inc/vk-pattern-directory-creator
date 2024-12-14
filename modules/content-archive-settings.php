<?php
/**
 * VK Patterns Content Archive Settings with Shortcode Generator
 *
 * @package VK Patterns
 */

function vkpdc_render_settings_page_with_shortcode() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    // 保存処理
    if ( isset( $_POST['vkpdc_settings_nonce'] ) && wp_verify_nonce( $_POST['vkpdc_settings_nonce'], 'vkpdc_save_settings' ) ) {
        if ( isset( $_POST['reset'] ) ) {
            // デフォルト値にリセット
            update_option( 'vkpdc_numberposts', 6 );
            update_option( 'vkpdc_order', 'DESC' );
            update_option( 'vkpdc_orderby', 'date' );
            update_option( 'vkpdc_display_image', 'featured' );
            update_option( 'vkpdc_display_btn_view_text', __( 'Read More', 'vk-pattern-directory-creator' ) );
            echo '<div class="updated"><p>' . __( 'Settings reset to default.', 'vk-pattern-directory-creator' ) . '</p></div>';
        } else {
            update_option( 'vkpdc_numberposts', intval( $_POST['numberposts'] ) );
            update_option( 'vkpdc_order', sanitize_text_field( $_POST['order'] ) );
            update_option( 'vkpdc_orderby', sanitize_text_field( $_POST['orderby'] ) );
            update_option( 'vkpdc_display_image', sanitize_text_field( $_POST['display_image'] ) );
            update_option( 'vkpdc_display_btn_view_text', sanitize_text_field( $_POST['display_btn_view_text'] ) );
            echo '<div class="updated"><p>' . __( 'Settings saved.', 'vk-pattern-directory-creator' ) . '</p></div>';
        }
    }

    // 現在の設定値を取得
    $numberposts          = get_option( 'vkpdc_numberposts', 6 );
    $order                = get_option( 'vkpdc_order', 'DESC' );
    $orderby              = get_option( 'vkpdc_orderby', 'date' );
    $display_image        = get_option( 'vkpdc_display_image', 'featured' );
    $display_btn_view_text = get_option( 'vkpdc_display_btn_view_text', __( 'Read More', 'vk-pattern-directory-creator' ) );

    // 動的ショートコード生成
    $generated_shortcode = sprintf(
        '[vkpdc_archive_loop numberposts="%d" order="%s" orderby="%s" display_image="%s" display_btn_view_text="%s"]',
        $numberposts,
        esc_attr( $order ),
        esc_attr( $orderby ),
        esc_attr( $display_image ),
        esc_attr( $display_btn_view_text )
    );

    ?>
    <div class="wrap">
        <h1><?php esc_html_e( 'VK Patterns Settings', 'vk-pattern-directory-creator' ); ?></h1>
        <form method="POST">
            <?php wp_nonce_field( 'vkpdc_save_settings', 'vkpdc_settings_nonce' ); ?>
            <table class="form-table">
                <tr>
                    <th><label for="numberposts"><?php esc_html_e( 'Number of Posts', 'vk-pattern-directory-creator' ); ?></label></th>
                    <td><input type="number" id="numberposts" name="numberposts" value="<?php echo esc_attr( $numberposts ); ?>" min="1" max="100"></td>
                </tr>
                <tr>
                    <th><label for="order"><?php esc_html_e( 'Order', 'vk-pattern-directory-creator' ); ?></label></th>
                    <td>
                        <select id="order" name="order">
                            <option value="ASC" <?php selected( $order, 'ASC' ); ?>>ASC</option>
                            <option value="DESC" <?php selected( $order, 'DESC' ); ?>>DESC</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th><label for="orderby"><?php esc_html_e( 'Order By', 'vk-pattern-directory-creator' ); ?></label></th>
                    <td>
                        <select id="orderby" name="orderby">
                            <option value="date" <?php selected( $orderby, 'date' ); ?>><?php esc_html_e( 'Published Date', 'vk-pattern-directory-creator' ); ?></option>
                            <option value="modified" <?php selected( $orderby, 'modified' ); ?>><?php esc_html_e( 'Modified Date', 'vk-pattern-directory-creator' ); ?></option>
                            <option value="title" <?php selected( $orderby, 'title' ); ?>><?php esc_html_e( 'Title', 'vk-pattern-directory-creator' ); ?></option>
                            <option value="rand" <?php selected( $orderby, 'rand' ); ?>><?php esc_html_e( 'Random', 'vk-pattern-directory-creator' ); ?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th><label for="display_image"><?php esc_html_e( 'Display Image', 'vk-pattern-directory-creator' ); ?></label></th>
                    <td>
                        <select id="display_image" name="display_image">
                            <option value="none" <?php selected( $display_image, 'none' ); ?>><?php esc_html_e( 'None', 'vk-pattern-directory-creator' ); ?></option>
                            <option value="featured" <?php selected( $display_image, 'featured' ); ?>><?php esc_html_e( 'Featured Image', 'vk-pattern-directory-creator' ); ?></option>
                            <option value="iframe" <?php selected( $display_image, 'iframe' ); ?>><?php esc_html_e( 'Iframe Only', 'vk-pattern-directory-creator' ); ?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th><label for="display_btn_view_text"><?php esc_html_e( 'View Button Text', 'vk-pattern-directory-creator' ); ?></label></th>
                    <td><input type="text" id="display_btn_view_text" name="display_btn_view_text" value="<?php echo esc_attr( $display_btn_view_text ); ?>"></td>
                </tr>
            </table>
            <?php submit_button(); ?>
            <input type="submit" name="reset" class="button button-secondary" value="<?php esc_attr_e( 'Reset to Default', 'vk-pattern-directory-creator' ); ?>">
        </form>
        <h2><?php esc_html_e( 'Generated Shortcode', 'vk-pattern-directory-creator' ); ?></h2>
        <textarea readonly rows="1" style="width: 100%;"><?php echo esc_html( $generated_shortcode ); ?></textarea>
        <p><?php esc_html_e( 'Copy the shortcode above and paste it into your post or page to display the archive.', 'vk-pattern-directory-creator' ); ?></p>
    </div>
    <?php
}

function vkpdc_add_settings_page() {
    add_options_page(
        __( 'VK Patterns Settings', 'vk-pattern-directory-creator' ),
        __( 'VK Patterns', 'vk-pattern-directory-creator' ),
        'manage_options',
        'vk-patterns-settings',
        'vkpdc_render_settings_page_with_shortcode'
    );
}
add_action( 'admin_menu', 'vkpdc_add_settings_page' );
