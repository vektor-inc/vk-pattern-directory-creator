<?php
/**
 * VK Patterns Content Archive Settings with Shortcode Generator
 *
 * @package VK Patterns
 */

function vkpdc_enqueue_styles() {
    wp_enqueue_style(
        'vkpdc-style',
        plugins_url( 'assets/build/css/style.css', __FILE__ ),
        array(),
        filemtime( plugin_dir_path( __FILE__ ) . 'assets/build/css/style.css' )
    );
}
add_action( 'wp_enqueue_scripts', 'vkpdc_enqueue_styles' );

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
            update_option( 'vkpdc_colWidthMin', '300px' );
            update_option( 'vkpdc_colWidthMinTablet', '300px' );
            update_option( 'vkpdc_colWidthMinPC', '300px' );
            update_option( 'vkpdc_gap', '1.5rem' );
            update_option( 'vkpdc_gapRow', '1.5rem' );
            echo '<div class="updated"><p>' . __( 'Settings reset to default.', 'vk-pattern-directory-creator' ) . '</p></div>';
        } else {
            update_option( 'vkpdc_numberposts', intval( $_POST['numberposts'] ) );
            update_option( 'vkpdc_order', sanitize_text_field( $_POST['order'] ) );
            update_option( 'vkpdc_orderby', sanitize_text_field( $_POST['orderby'] ) );
            update_option( 'vkpdc_display_image', sanitize_text_field( $_POST['display_image'] ) );
            update_option( 'vkpdc_display_btn_view_text', sanitize_text_field( $_POST['display_btn_view_text'] ) );
            update_option( 'vkpdc_colWidthMin', sanitize_text_field( $_POST['colWidthMin'] ) );
            update_option( 'vkpdc_colWidthMinTablet', sanitize_text_field( $_POST['colWidthMinTablet'] ) );
            update_option( 'vkpdc_colWidthMinPC', sanitize_text_field( $_POST['colWidthMinPC'] ) );
            update_option( 'vkpdc_gap', sanitize_text_field( $_POST['gap'] ) );
            update_option( 'vkpdc_gapRow', sanitize_text_field( $_POST['gapRow'] ) );
            echo '<div class="updated"><p>' . __( 'Settings saved.', 'vk-pattern-directory-creator' ) . '</p></div>';
        }
    }

    // 現在の設定値を取得
    $numberposts          = get_option( 'vkpdc_numberposts', 6 );
    $order                = get_option( 'vkpdc_order', 'DESC' );
    $orderby              = get_option( 'vkpdc_orderby', 'date' );
    $display_image        = get_option( 'vkpdc_display_image', 'featured' );
    $display_btn_view_text = get_option( 'vkpdc_display_btn_view_text', __( 'Read More', 'vk-pattern-directory-creator' ) );
    $vkpdc_colWidthMin    = get_option( 'vkpdc_colWidthMin', '300px' );
    $vkpdc_colWidthMinTablet = get_option( 'vkpdc_colWidthMinTablet', '300px' );
    $vkpdc_colWidthMinPC  = get_option( 'vkpdc_colWidthMinPC', '300px' );
    $vkpdc_gap            = get_option( 'vkpdc_gap', '1.5rem' );
    $vkpdc_gapRow         = get_option( 'vkpdc_gapRow', '1.5rem' );

    // 動的ショートコード生成
    $generated_shortcode = sprintf(
        '[vkpdc_archive_loop numberposts="%d" order="%s" orderby="%s" display_image="%s" display_btn_view_text="%s" colWidthMin="%s" colWidthMinTablet="%s" colWidthMinPC="%s" gap="%s" gapRow="%s"]',
        $numberposts,
        esc_attr( $order ),
        esc_attr( $orderby ),
        esc_attr( $display_image ),
        esc_attr( $display_btn_view_text ),
        esc_attr( $vkpdc_colWidthMin ),
        esc_attr( $vkpdc_colWidthMinTablet ),
        esc_attr( $vkpdc_colWidthMinPC ),
        esc_attr( $vkpdc_gap ),
        esc_attr( $vkpdc_gapRow )
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
                <!-- カラム幅設定 -->
                <tr>
                    <th><label for="colWidthMin"><?php esc_html_e( 'Column Min Width (Mobile)', 'vk-pattern-directory-creator' ); ?></label></th>
                    <td><input type="text" id="colWidthMin" name="colWidthMin" value="<?php echo esc_attr( get_option( 'vkpdc_colWidthMin', '300px' ) ); ?>"></td>
                </tr>
                <tr>
                    <th><label for="colWidthMinTablet"><?php esc_html_e( 'Column Min Width (Tablet)', 'vk-pattern-directory-creator' ); ?></label></th>
                    <td><input type="text" id="colWidthMinTablet" name="colWidthMinTablet" value="<?php echo esc_attr( get_option( 'vkpdc_colWidthMinTablet', '300px' ) ); ?>"></td>
                </tr>
                <tr>
                    <th><label for="colWidthMinPC"><?php esc_html_e( 'Column Min Width (PC)', 'vk-pattern-directory-creator' ); ?></label></th>
                    <td><input type="text" id="colWidthMinPC" name="colWidthMinPC" value="<?php echo esc_attr( get_option( 'vkpdc_colWidthMinPC', '300px' ) ); ?>"></td>
                </tr>
                <!-- ギャップ設定 -->
                <tr>
                    <th><label for="gap"><?php esc_html_e( 'Column Gap Size', 'vk-pattern-directory-creator' ); ?></label></th>
                    <td><input type="text" id="gap" name="gap" value="<?php echo esc_attr( get_option( 'vkpdc_gap', '1.5rem' ) ); ?>"></td>
                </tr>
                <tr>
                    <th><label for="gapRow"><?php esc_html_e( 'Row Gap Size', 'vk-pattern-directory-creator' ); ?></label></th>
                    <td><input type="text" id="gapRow" name="gapRow" value="<?php echo esc_attr( get_option( 'vkpdc_gapRow', '1.5rem' ) ); ?>"></td>
                </tr>
            </table>
            <?php submit_button(); ?>
            <input type="submit" name="reset" class="button button-secondary" value="<?php esc_attr_e( 'Reset to Default', 'vk-pattern-directory-creator' ); ?>">
        </form>
        <h2><?php esc_html_e( 'Generated Shortcode', 'vk-pattern-directory-creator' ); ?></h2>
        <textarea readonly rows="1" style="width: 100%;"><?php echo esc_html( $generated_shortcode ); ?></textarea>
        <p><?php esc_html_e( 'Copy the shortcode above and paste it into your post or page to display the archive.', 'vk-pattern-directory-creator' ); ?></p>
        <h2><?php esc_html_e( 'Preview', 'vk-pattern-directory-creator' ); ?></h2>
<div id="vkpdc-preview" style="border: 1px solid #ddd; height: 600px; overflow: hidden;">
    <iframe 
        src="<?php echo esc_url( home_url( '/?vkpdc_preview=1' ) ); ?>" 
        style="width: 100%; height: 100%; border: none;">
    </iframe>
</div>

    </div>
    <?php
}

/**
 * プレビュー用のショートコード出力
 */
function vkpdc_render_preview_page() {
    if ( isset( $_GET['vkpdc_preview'] ) && $_GET['vkpdc_preview'] === '1' ) {
        // 管理画面で設定されたオプションを取得
        $attributes = array(
            'numberPosts'            => get_option( 'vkpdc_numberposts', 6 ),
            'order'                  => get_option( 'vkpdc_order', 'DESC' ),
            'orderby'                => get_option( 'vkpdc_orderby', 'date' ),
            'display_image'          => get_option( 'vkpdc_display_image', 'featured' ),
            'display_btn_view_text'  => get_option( 'vkpdc_display_btn_view_text', __( 'Read More', 'vk-pattern-directory-creator' ) ),
            'colWidthMin'            => get_option( 'vkpdc_colWidthMin', '300px' ),
            'colWidthMinTablet'      => get_option( 'vkpdc_colWidthMinTablet', '300px' ),
            'colWidthMinPC'          => get_option( 'vkpdc_colWidthMinPC', '300px' ),
            'gap'                    => get_option( 'vkpdc_gap', '1.5rem' ),
            'gapRow'                 => get_option( 'vkpdc_gapRow', '1.5rem' ),
        );

        // ショートコード出力
        echo '<!DOCTYPE html><html><head>';
        wp_head();
        echo '</head><body>';
        echo do_shortcode( sprintf(
            '[vkpdc_archive_loop numberposts="%d" order="%s" orderby="%s" display_image="%s" display_btn_view_text="%s" colWidthMin="%s" colWidthMinTablet="%s" colWidthMinPC="%s" gap="%s" gapRow="%s"]',
            $attributes['numberPosts'],
            esc_attr( $attributes['order'] ),
            esc_attr( $attributes['orderby'] ),
            esc_attr( $attributes['display_image'] ),
            esc_attr( $attributes['display_btn_view_text'] ),
            esc_attr( $attributes['colWidthMin'] ),
            esc_attr( $attributes['colWidthMinTablet'] ),
            esc_attr( $attributes['colWidthMinPC'] ),
            esc_attr( $attributes['gap'] ),
            esc_attr( $attributes['gapRow'] )
        ) );
        wp_footer();
        echo '</body></html>';
        exit;
    }
}
add_action( 'template_redirect', 'vkpdc_render_preview_page' );

function vkpdc_add_settings_page() {
    // 現在のテーマがクラシックテーマかどうかを確認
    $theme = wp_get_theme();
    if ( $theme->get( 'Template' ) || $theme->get_stylesheet() ) {
        add_options_page(
            __( 'VK Patterns Settings', 'vk-pattern-directory-creator' ),
            __( 'VK Patterns', 'vk-pattern-directory-creator' ),
            'manage_options',
            'vk-patterns-settings',
            'vkpdc_render_settings_page_with_shortcode'
        );
    }
}
add_action( 'admin_menu', 'vkpdc_add_settings_page' );

// フックを提供して、アーカイブページの表示をカスタマイズ
function vkpdc_customize_archive_page( $query ) {
    if ( $query->is_main_query() && ! is_admin() && $query->is_post_type_archive( 'vk-patterns' ) ) {
        $numberposts = get_option( 'vkpdc_numberposts', 6 );
        $order       = get_option( 'vkpdc_order', 'DESC' );
        $orderby     = get_option( 'vkpdc_orderby', 'date' );

        $query->set( 'posts_per_page', $numberposts );
        $query->set( 'order', $order );
        $query->set( 'orderby', $orderby );
    }
}
add_action( 'pre_get_posts', 'vkpdc_customize_archive_page' );
