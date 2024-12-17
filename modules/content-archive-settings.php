<?php
/**
 * VK Patterns Content Archive Settings with Shortcode Generator
 *
 * @package VK Patterns
 */

// CSSエンキュー
function vkpdc_enqueue_styles() {
    $css_file = plugin_dir_path( __FILE__ ) . 'assets/build/css/style.css';
    wp_enqueue_style(
        'vkpdc-style',
        plugins_url( 'assets/build/css/style.css', __FILE__ ),
        array(),
        filemtime( $css_file )
    );
}
add_action( 'wp_enqueue_scripts', 'vkpdc_enqueue_styles' );

/**
 * デフォルトオプション取得関数
 */
function vkpdc_get_default_options() {
    return array(
        'numberposts'           => 6,
        'order'                 => 'DESC',
        'orderby'               => 'date',
        'display_image'         => 'featured',
        'thumbnail_size'        => 'large',
        'display_author'        => 1,
        'display_date_publiched'=> 1,
        'display_date_modified' => 1,
        'display_new'           => 1,
        'display_taxonomies'    => 1,
        'pattern_id'            => 1,
        'display_btn_view'      => 1,
        'display_btn_copy'      => 1,
        'display_btn_view_text' => __( 'Read More', 'vk-pattern-directory-creator' ),
        'new_date'              => 7,
        'new_text'              => 'NEW!!',
        'colWidthMin'           => '300px',
        'colWidthMinTablet'     => '300px',
        'colWidthMinPC'         => '300px',
        'gap'                   => '1.5rem',
        'gapRow'                => '1.5rem',
    );
}

/**
 * 設定保存処理
 */
function vkpdc_save_settings() {
    check_admin_referer( 'vkpdc_save_settings', 'vkpdc_settings_nonce' );
    $defaults = vkpdc_get_default_options();

    // チェックボックス項目
    $checkbox_fields = [
        'display_author',
        'display_date_publiched',
        'display_date_modified',
        'display_new',
        'display_taxonomies',
        'pattern_id',
        'display_btn_view',
        'display_btn_copy',
    ];

    if ( isset( $_POST['reset'] ) ) {
        // リセット処理：デフォルト値で上書き
        foreach ( $defaults as $key => $value ) {
            update_option( 'vkpdc_' . $key, $value );
        }
        return __( 'Settings reset to default.', 'vk-pattern-directory-creator' );
    } else {
        // チェックボックス項目は送信がない場合0にリセット
        foreach ( $checkbox_fields as $key ) {
            $value = isset( $_POST[ $key ] ) ? 1 : 0;
            update_option( 'vkpdc_' . $key, $value );
        }

        // その他の項目
        foreach ( $defaults as $key => $default ) {
            if ( ! in_array( $key, $checkbox_fields, true ) ) {
                // テキストや数値のサニタイズ
                $value = isset( $_POST[ $key ] ) ? sanitize_text_field( $_POST[ $key ] ) : $default;
                update_option( 'vkpdc_' . $key, $value );
            }
        }
        return __( 'Settings saved.', 'vk-pattern-directory-creator' );
    }
}

function vkpdc_render_settings_page_with_shortcode() {
    if ( ! current_user_can( 'manage_options' ) ) return;

    $message = '';
    if ( isset( $_POST['vkpdc_settings_nonce'] ) ) {
        $message = vkpdc_save_settings();
    }

    $defaults = vkpdc_get_default_options();
    $options = [];
    foreach ( $defaults as $key => $default ) {
        // オプション値が保存されていればそれを取得
        $options[ $key ] = get_option( 'vkpdc_' . $key, $default );
    }

    ?>
    <div class="wrap">
        <h1><?php esc_html_e( 'VK Patterns Settings', 'vk-pattern-directory-creator' ); ?></h1>
        <?php if ( $message ) : ?>
            <div class="updated"><p><?php echo esc_html( $message ); ?></p></div>
        <?php endif; ?>
        <form method="POST">
            <?php wp_nonce_field( 'vkpdc_save_settings', 'vkpdc_settings_nonce' ); ?>
            <table class="form-table">
                <!-- Number of Posts -->
                <tr>
                    <th><label for="numberposts"><?php esc_html_e( 'Number of Posts', 'vk-pattern-directory-creator' ); ?></label></th>
                    <td>
                        <input type="number" id="numberposts" name="numberposts" value="<?php echo esc_attr( $options['numberposts'] ); ?>" min="1" max="100">
                    </td>
                </tr>

                <!-- チェックボックス項目 -->
                <?php foreach ( ['display_author', 'display_date_publiched', 'display_date_modified', 'display_new', 'display_taxonomies', 'pattern_id', 'display_btn_view', 'display_btn_copy'] as $key ) : ?>
                    <tr>
                        <th><label for="<?php echo esc_attr( $key ); ?>"><?php echo ucfirst( str_replace( '_', ' ', $key ) ); ?></label></th>
                        <td>
                            <input type="checkbox" id="<?php echo esc_attr( $key ); ?>" name="<?php echo esc_attr( $key ); ?>" <?php checked( $options[ $key ], 1 ); ?>>
                            <label><?php esc_html_e( 'Enable', 'vk-pattern-directory-creator' ); ?></label>
                        </td>
                    </tr>
                <?php endforeach; ?>

                <!-- テキストオプション -->
                <tr>
                    <th><label for="display_btn_view_text"><?php esc_html_e( 'View Button Text', 'vk-pattern-directory-creator' ); ?></label></th>
                    <td><input type="text" id="display_btn_view_text" name="display_btn_view_text" value="<?php echo esc_attr( $options['display_btn_view_text'] ); ?>"></td>
                </tr>
                <tr>
                    <th><label for="new_text"><?php esc_html_e( 'New Post Mark', 'vk-pattern-directory-creator' ); ?></label></th>
                    <td><input type="text" id="new_text" name="new_text" value="<?php echo esc_attr( $options['new_text'] ); ?>"></td>
                </tr>
            </table>
            <?php submit_button(); ?>
            <input type="submit" name="reset" class="button button-secondary" value="<?php esc_attr_e( 'Reset to Default', 'vk-pattern-directory-creator' ); ?>">
        </form>
    </div>
    <?php
}

/**
 * 設定ページ追加
 */
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
