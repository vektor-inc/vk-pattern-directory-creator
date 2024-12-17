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
        'order'                 =>  __( 'DESC', 'vk-pattern-directory-creator' ),
        'orderby'               =>  __( 'date', 'vk-pattern-directory-creator' ),
        'display_author'        => 1,
        'display_date_publiched'=> 1,
        'display_date_modified' => 1,
        'display_new'           => 1,
        'display_taxonomies'    => 1,
        'pattern_id'            => 1,
        'display_btn_view'      => 1,
        'display_btn_copy'      => 1,
        'display_btn_view_text' => __( 'Read More', 'vk-pattern-directory-creator' ),
        'display_image'         => __( 'featured', 'vk-pattern-directory-creator' ),
        'thumbnail_size'        => __( 'large', 'vk-pattern-directory-creator' ),
		'new_date'              => 7,
        'new_text'              =>  __( 'NEW!!', 'vk-pattern-directory-creator' ),
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

    // ショートコードを生成
    $generated_shortcode = sprintf(
        '[vkpdc_archive_loop numberposts="%d" order="%s" orderby="%s" display_author="%d" display_date_publiched="%d" display_date_modified="%d" display_image="%s" thumbnail_size="%s" display_new="%d" display_taxonomies="%d" pattern_id="%d" display_btn_view="%d" display_btn_copy="%d" display_btn_view_text="%s" new_date="%d" new_text="%s" colWidthMin="%s" colWidthMinTablet="%s" colWidthMinPC="%s" gap="%s" gapRow="%s"]',
        $options['numberposts'],
        esc_attr( $options['order'] ),
        esc_attr( $options['orderby'] ),
        esc_attr( $options['display_image'] ),
        esc_attr( $options['thumbnail_size'] ),
        intval( $options['display_author'] ),
        intval( $options['display_date_publiched'] ),
        intval( $options['display_date_modified'] ),
        intval( $options['display_new'] ),
        intval( $options['display_taxonomies'] ),
        intval( $options['pattern_id'] ),
        intval( $options['display_btn_view'] ),
        intval( $options['display_btn_copy'] ),
        esc_attr( $options['display_btn_view_text'] ),
        intval( $options['new_date'] ),
        esc_attr( $options['new_text'] ),
        esc_attr( $options['colWidthMin'] ),
        esc_attr( $options['colWidthMinTablet'] ),
        esc_attr( $options['colWidthMinPC'] ),
        esc_attr( $options['gap'] ),
        esc_attr( $options['gapRow'] )
    );

    ?>
    <div class="wrap">
        <h1><?php esc_html_e( 'VK Patterns Settings', 'vk-pattern-directory-creator' ); ?></h1>
        <?php if ( $message ) : ?>
            <div class="updated"><p><?php echo esc_html( $message ); ?></p></div>
        <?php endif; ?>
        <form method="POST">
            <?php wp_nonce_field( 'vkpdc_save_settings', 'vkpdc_settings_nonce' ); ?>
            <table class="form-table">
                <tr>
                    <th><label for="numberposts"><?php esc_html_e( 'Number of Posts', 'vk-pattern-directory-creator' ); ?></label></th>
                    <td>
                        <input type="number" id="numberposts" name="numberposts" value="<?php echo esc_attr( $options['numberposts'] ); ?>" min="1" max="100">
                    </td>
                </tr>
                <?php foreach ( ['display_author', 'display_date_publiched', 'display_date_modified', 'display_new', 'display_taxonomies', 'pattern_id', 'display_btn_view', 'display_btn_copy'] as $key ) : ?>
                    <tr>
                        <th><label for="<?php echo esc_attr( $key ); ?>"><?php echo ucfirst( str_replace( '_', ' ', $key ) ); ?></label></th>
                        <td>
                            <input type="checkbox" id="<?php echo esc_attr( $key ); ?>" name="<?php echo esc_attr( $key ); ?>" <?php checked( $options[ $key ], 1 ); ?>>
                            <label><?php esc_html_e( 'Enable', 'vk-pattern-directory-creator' ); ?></label>
                        </td>
                    </tr>
                <?php endforeach; ?>
				<tr>
					<th><label for="display_image"><?php esc_html_e( 'Display Image', 'vk-pattern-directory-creator' ); ?></label></th>
					<td>
						<select id="display_image" name="display_image">
							<option value="none" <?php selected( $options['display_image'], 'none' ); ?>><?php esc_html_e( 'None', 'vk-pattern-directory-creator' ); ?></option>
							<option value="featured" <?php selected( $options['display_image'], 'featured' ); ?>><?php esc_html_e( 'Featured Image', 'vk-pattern-directory-creator' ); ?></option>
							<option value="iframe" <?php selected( $options['display_image'], 'iframe' ); ?>><?php esc_html_e( 'Iframe Only', 'vk-pattern-directory-creator' ); ?></option>
						</select>
					</td>
				</tr>
				<tr>
					<th><label for="thumbnail_size"><?php esc_html_e( 'Thumbnail Size', 'vk-pattern-directory-creator' ); ?></label></th>
					<td>
						<select id="thumbnail_size" name="thumbnail_size">
							<option value="thumbnail" <?php selected( $options['thumbnail_size'], 'thumbnail' ); ?>><?php esc_html_e( 'Thumbnail', 'vk-pattern-directory-creator' ); ?></option>
							<option value="medium" <?php selected( $options['thumbnail_size'], 'medium' ); ?>><?php esc_html_e( 'Medium', 'vk-pattern-directory-creator' ); ?></option>
							<option value="large" <?php selected( $options['thumbnail_size'], 'large' ); ?>><?php esc_html_e( 'Large', 'vk-pattern-directory-creator' ); ?></option>
							<option value="full" <?php selected( $options['thumbnail_size'], 'full' ); ?>><?php esc_html_e( 'Full', 'vk-pattern-directory-creator' ); ?></option>
						</select>
					</td>
				</tr>
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

		<!-- ショートコード生成部分 -->
		<h2><?php esc_html_e( 'Generated Shortcode', 'vk-pattern-directory-creator' ); ?></h2>
		<div style="display: flex; align-items: center;">
			<textarea id="vkpdc_shortcode" readonly rows="1" style="width: 100%; margin-right: 10px;"><?php echo esc_html( $generated_shortcode ); ?></textarea>
			<button type="button" id="vkpdc_copy_shortcode" class="button button-primary">
				<?php esc_html_e( 'Copy Shortcode', 'vk-pattern-directory-creator' ); ?>
			</button>
		</div>
		<p id="vkpdc_copy_message" style="display:none; color: green; margin-top: 5px;">
			<?php esc_html_e( 'Shortcode copied to clipboard!', 'vk-pattern-directory-creator' ); ?>
		</p>

		<script>
		document.addEventListener('DOMContentLoaded', function() {
			const copyButton = document.getElementById('vkpdc_copy_shortcode');
			const shortcodeField = document.getElementById('vkpdc_shortcode');
			const copyMessage = document.getElementById('vkpdc_copy_message');

			copyButton.addEventListener('click', function() {
				shortcodeField.select();
				document.execCommand('copy');
				copyMessage.style.display = 'block';
				setTimeout(() => {
					copyMessage.style.display = 'none';
				}, 2000);
			});
		});
		</script>
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
