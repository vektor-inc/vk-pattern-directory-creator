<?php
/**
 * VK Patterns Content Archive Settings with Shortcode Generator
 *
 * @package VK Patterns
 */

/**
 * デフォルトオプション取得
 * 
 * @return array
 */
function vkpdc_get_default_options() {
	return array(
		'numberPosts'           => 6,
		'order'                 =>  __( 'DESC', 'vk-pattern-directory-creator' ),
		'orderby'               =>  __( 'date', 'vk-pattern-directory-creator' ),
		'display_new'           => 1,
		'display_taxonomies'    => 1,
		'pattern_id'            => 1,
		'display_date_publiched'=> 1,
		'display_date_modified' => 1,
		'display_author'        => 1,
		'display_btn_view'      => 1,
		'display_btn_copy'      => 1,
		'display_paged'         => 1,
		'display_btn_view_text' => __( 'Read More', 'vk-pattern-directory-creator' ),
		'display_image'         => 'featured',
		'thumbnail_size'        => 'full',
		'new_date'              => 7,
		'new_text'              =>  __( 'NEW!!', 'vk-pattern-directory-creator' ),
		'colWidthMinMobile'           => '300px',
		'colWidthMinTablet'     => '300px',
		'colWidthMinPC'         => '300px',
		'gap'                   => '1.5rem',
		'gapRow'                => '1.5rem',
		'hook_name'             => vkpdc_get_default_hook_name(),
	);
}

// テーマが lightning の場合のデフォルトフック名を取得
function vkpdc_get_default_hook_name() {
    // 現在のテーマが lightning の場合、デフォルトフック名を設定
    if ( wp_get_theme()->get( 'TextDomain' ) === 'lightning' ) {
        return 'lightning_extend_loop';
    }
    return ''; // その他のテーマの場合は空
}

// デフォルトオプションを初期化
function vkpdc_initialize_default_options() {
	$defaults = vkpdc_get_default_options();
	foreach ( $defaults as $key => $value ) {
		if ( get_option( 'vkpdc_' . $key ) === false ) {
			update_option( 'vkpdc_' . $key, $value );
		}
	}
}
register_activation_hook( __FILE__, 'vkpdc_initialize_default_options' );

/**
 * 設定保存処理
 */
function vkpdc_save_settings() {
	check_admin_referer( 'vkpdc_save_settings', 'vkpdc_settings_nonce' );
	$defaults = vkpdc_get_default_options();

	$checkbox_fields = [
		'display_new',
		'display_taxonomies',
		'pattern_id',
		'display_date_publiched',
		'display_date_modified',
		'display_author',
		'display_btn_view',
		'display_btn_copy',
		'display_paged',
	];

	if ( isset( $_POST['reset'] ) ) {
		foreach ( $defaults as $key => $value ) {
			update_option( 'vkpdc_' . $key, $value );
		}
		return __( 'Settings reset to default.', 'vk-pattern-directory-creator' );
	} else {
		foreach ( $checkbox_fields as $key ) {
			$value = isset( $_POST[ $key ] ) ? 1 : 0;
			error_log( "Saving checkbox field: {$key} with value: " . $value );
			update_option( 'vkpdc_' . $key, $value );
		}

		foreach ( $defaults as $key => $default ) {
			if ( ! in_array( $key, $checkbox_fields, true ) ) {
				$value = isset( $_POST[ $key ] ) ? sanitize_text_field( $_POST[ $key ] ) : $default;
				error_log( "Saving other field: {$key} with value: " . $value );
				update_option( 'vkpdc_' . $key, $value );
			}
		}
		return __( 'Settings saved.', 'vk-pattern-directory-creator' );
	}
}

// 設定ページをレンダリング
function vkpdc_render_settings_page_with_shortcode() {
	if ( ! current_user_can( 'manage_options' ) ) return;

	$message = '';
	if ( isset( $_POST['vkpdc_settings_nonce'] ) ) {
		$message = vkpdc_save_settings();
	}

	$defaults = vkpdc_get_default_options();
	$options = [];
	foreach ( $defaults as $key => $default ) {
		$options[ $key ] = get_option( 'vkpdc_' . $key, $default );
	}

	// ショートコードを生成
	$generated_shortcode = sprintf(
		'[vkpdc_archive_loop numberPosts="%d" order="%s" orderby="%s" display_new="%d" display_taxonomies="%d" pattern_id="%d" display_date_publiched="%d" display_date_modified="%d" display_author="%d" display_image="%s" thumbnail_size="%s" display_btn_view="%d" display_btn_copy="%d" display_paged="%d" display_btn_view_text="%s" new_date="%d" new_text="%s" colWidthMinMobile="%s" colWidthMinMobileTablet="%s" colWidthMinMobilePC="%s" gap="%s" gapRow="%s"]',
		intval( $options['numberPosts'] ),
		esc_attr( $options['order'] ),
		esc_attr( $options['orderby'] ),
		intval( $options['display_new'] ),
		intval( $options['display_taxonomies'] ),
		intval( $options['pattern_id'] ),
		intval( $options['display_date_publiched'] ),
		intval( $options['display_date_modified'] ),
		intval( $options['display_author'] ),
		esc_attr( $options['display_image'] ),
		esc_attr( $options['thumbnail_size'] ),
		intval( $options['display_btn_view'] ),
		intval( $options['display_btn_copy'] ),
		intval( $options['display_paged'] ),
		esc_attr( $options['display_btn_view_text'] ),
		intval( $options['new_date'] ),
		esc_attr( $options['new_text'] ),
		esc_attr( $options['colWidthMinMobile'] ),
		esc_attr( $options['colWidthMinTablet'] ),
		esc_attr( $options['colWidthMinPC'] ),
		esc_attr( $options['gap'] ),
		esc_attr( $options['gapRow'] )
	);	

	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Archive and Shortcode Setting', 'vk-pattern-directory-creator' ); ?></h1>
		<p><?php _e( 'Please configure your archive display using either a hook or a shortcode, depending on your needs.', 'vk-pattern-directory-creator' ); ?><br><?php _e( 'Use the shortcode generator to customize and copy the desired layout, or specify a WordPress hook to integrate the archive display seamlessly into your theme.', 'vk-pattern-directory-creator' ); ?></p>
		<?php if ( $message ) : ?>
			<div class="updated"><p><?php echo esc_html( $message ); ?></p></div>
		<?php endif; ?>
		<form method="POST">
			<?php wp_nonce_field( 'vkpdc_save_settings', 'vkpdc_settings_nonce' ); ?>
			<h2 class="nav-tab-wrapper">
				<a href="#pattern-list-settings" class="nav-tab nav-tab-active"><?php esc_html_e( 'Pattern List Settings', 'vk-pattern-directory-creator' ); ?></a>
				<a href="#archive-settings" class="nav-tab"><?php esc_html_e( 'Archive Settings (Option)', 'vk-pattern-directory-creator' ); ?></a>
			</h2>
			<div id="pattern-list-settings" class="tab-content" style="display: block;">
				<h2 class="nav-tab-wrapper">
					<a href="#display-conditions" class="nav-tab nav-tab-active"><?php esc_html_e( 'Display Conditions', 'vk-pattern-directory-creator' ); ?></a>
					<a href="#display-items" class="nav-tab"><?php esc_html_e( 'Display Items', 'vk-pattern-directory-creator' ); ?></a>
					<a href="#column-width-setting" class="nav-tab"><?php esc_html_e( 'Column Width Setting', 'vk-pattern-directory-creator' ); ?></a>
				</h2>
				<div id="display-conditions" class="nested-tab-content" style="display: block;">
					<table class="form-table">
						<tr>
							<th><label for="numberPosts"><?php esc_html_e( 'Number of Posts', 'vk-pattern-directory-creator' ); ?></label></th>
							<td>
								<input type="number" id="numberPosts" name="numberPosts" value="<?php echo esc_attr( $options['numberPosts'] ); ?>" min="1" max="100">
							</td>
						</tr>
						<tr>
							<th><label for="order"><?php esc_html_e( 'Order', 'vk-pattern-directory-creator' ); ?></label></th>
							<td>
								<select id="order" name="order">
									<option value="ASC" <?php selected( $options['order'], 'ASC' ); ?>><?php esc_html_e( 'ASC', 'vk-pattern-directory-creator' ); ?></option>
									<option value="DESC" <?php selected( $options['order'], 'DESC' ); ?>><?php esc_html_e( 'DESC', 'vk-pattern-directory-creator' ); ?></option>
								</select>
							</td>
						</tr>
						<tr>
							<th><label for="orderby"><?php esc_html_e( 'Orderby', 'vk-pattern-directory-creator' ); ?></label></th>
							<td>
								<select id="orderby" name="orderby">
									<option value="date" <?php selected( $options['orderby'], 'date' ); ?>><?php esc_html_e( 'Date', 'vk-pattern-directory-creator' ); ?></option>
									<option value="title" <?php selected( $options['orderby'], 'title' ); ?>><?php esc_html_e( 'Title', 'vk-pattern-directory-creator' ); ?></option>
									<option value="rand" <?php selected( $options['orderby'], 'rand' ); ?>><?php esc_html_e( 'Random', 'vk-pattern-directory-creator' ); ?></option>
								</select>
							</td>
						</tr>
					</table>
				</div>
				<div id="display-items" class="nested-tab-content" style="display: none;">
					<table class="form-table">
						<?php foreach ( ['display_new', 'display_taxonomies', 'pattern_id', 'display_date_publiched', 'display_date_modified', 'display_author', 'display_btn_view', 'display_btn_copy', 'display_paged'] as $key ) : ?>
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
									<option value="featured" <?php selected( $options['display_image'], 'featured' ); ?>><?php esc_html_e( 'Prioritize Featured Image', 'vk-pattern-directory-creator' ); ?></option>
									<option value="iframe" <?php selected( $options['display_image'], 'iframe' ); ?>><?php esc_html_e( 'Iframe only', 'vk-pattern-directory-creator' ); ?></option>
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
							<th><label for="new_date"><?php esc_html_e( 'New Post Duration', 'vk-pattern-directory-creator' ); ?></label></th>
							<td>
								<input type="number" id="new_date" name="new_date" value="<?php echo esc_attr( $options['new_date'] ); ?>" min="1" max="365">
								<span><?php esc_html_e( 'days', 'vk-pattern-directory-creator' ); ?></span>
							</td>
						</tr>
						<tr>
							<th><label for="new_text"><?php esc_html_e( 'New Post Mark', 'vk-pattern-directory-creator' ); ?></label></th>
							<td><input type="text" id="new_text" name="new_text" value="<?php echo esc_attr( $options['new_text'] ); ?>"></td>
						</tr>
					</table>
				</div>
				<div id="column-width-setting" class="nested-tab-content" style="display:none;">
					<table class="form-table">
						<tr>
							<th><label for="colWidthMinMobile"><?php esc_html_e( 'Column min width (Mobile)', 'vk-pattern-directory-creator' ); ?></label></th>
							<td><input type="text" id="colWidthMinMobile" name="colWidthMinMobile" value="<?php echo esc_attr( $options['colWidthMinMobile'] ); ?>"></td>
						</tr>
						<tr>
							<th><label for="colWidthMinTablet"><?php esc_html_e( 'Column min width (Tablet)', 'vk-pattern-directory-creator' ); ?></label></th>
							<td><input type="text" id="colWidthMinTablet" name="colWidthMinTablet" value="<?php echo esc_attr( $options['colWidthMinTablet'] ); ?>"></td>
						</tr>
						<tr>
							<th><label for="colWidthMinPC"><?php esc_html_e( 'Column min width (PC)', 'vk-pattern-directory-creator' ); ?></label></th>
							<td><input type="text" id="colWidthMinPC" name="colWidthMinPC" value="<?php echo esc_attr( $options['colWidthMinPC'] ); ?>"></td>
						</tr>
						<tr>
							<th><label for="gap"><?php esc_html_e( 'Gap', 'vk-pattern-directory-creator' ); ?></label></th>
							<td><input type="text" id="gap" name="gap" value="<?php echo esc_attr( $options['gap'] ); ?>"></td>
						</tr>
						<tr>
							<th><label for="gapRow"><?php esc_html_e( 'Row Gap', 'vk-pattern-directory-creator' ); ?></label></th>
							<td><input type="text" id="gapRow" name="gapRow" value="<?php echo esc_attr( $options['gapRow'] ); ?>"></td>
						</tr>
					</table>
				</div>
				<div style="margin: 1.5rem auto;">
					<button type="button" id="vkpdc_generate_shortcode" class="button button-secondary">
						<?php esc_html_e( 'Generate Shortcode', 'vk-pattern-directory-creator' ); ?>
					</button>
				</div>
			</div>
			<div id="archive-settings" class="tab-content" style="display:none;">
				<table class="form-table">
					<tr>
						<th><label for="hook_name"><?php esc_html_e( 'Hook Name', 'vk-pattern-directory-creator' ); ?></label></th>
						<td>
							<input type="text" id="hook_name" name="hook_name" value="<?php echo esc_attr( $options['hook_name'] ); ?>">
							<p class="description"><?php _e( 'Ex) lightning_extend_loop', 'vk-pattern-directory-creator' ); ?></p>
						</td>
					</tr>
					<tr>
						<th><label for="hook_name"><?php esc_html_e( 'Archive Preview', 'vk-pattern-directory-creator' ); ?></label></th>
						<td>
							<a href="/pattern" target="_blank"><?php _e( 'Click here to preview the archive with the current settings.', 'vk-pattern-directory-creator' ); ?></a>
						</td>
					</tr>
				</table>
			</div>
			<div style="display: flex; gap: 1rem; align-items: center; margin: 1.5rem auto 2.6rem;">
				<?php submit_button( __( 'Save Settings', 'vk-pattern-directory-creator' ), 'primary', '', false ); ?>
				<input type="submit" name="reset" class="button button-secondary" value="<?php esc_attr_e( 'Reset to Default', 'vk-pattern-directory-creator' ); ?>">
			</div>
		</form>
		<script>
			// タブ切り替え
			document.addEventListener('DOMContentLoaded', function () {
				// 大カテゴリのタブ切り替え
				const mainTabs = document.querySelectorAll('.nav-tab-wrapper > .nav-tab');
				const mainContents = document.querySelectorAll('.tab-content');

				mainTabs.forEach(tab => {
					tab.addEventListener('click', function (e) {
						e.preventDefault();

						// 大カテゴリのタブ切り替え
						mainTabs.forEach(t => t.classList.remove('nav-tab-active'));
						tab.classList.add('nav-tab-active');

						const target = tab.getAttribute('href').substring(1); // #を除外
						mainContents.forEach(content => {
							content.style.display = content.id === target ? 'block' : 'none';
						});
					});
				});

				// ネストされたタブの切り替え
				const nestedTabsWrapper = document.querySelector('#pattern-list-settings .nav-tab-wrapper');
				const nestedTabs = nestedTabsWrapper.querySelectorAll('.nav-tab');
				const nestedContents = document.querySelectorAll('#pattern-list-settings .nested-tab-content');

				nestedTabs.forEach(tab => {
					tab.addEventListener('click', function (e) {
						e.preventDefault();

						// ネストされたタブの切り替え
						nestedTabs.forEach(t => t.classList.remove('nav-tab-active'));
						tab.classList.add('nav-tab-active');

						// 対応するコンテンツの切り替え
						const target = tab.getAttribute('href').substring(1); // #を除外
						nestedContents.forEach(content => {
							content.style.display = content.id === target ? 'block' : 'none';
						});

						// 親の`pattern-list-settings`は表示を維持
						document.getElementById('pattern-list-settings').style.display = 'block';
					});
				});

				// 初期表示設定
				mainTabs[0]?.classList.add('nav-tab-active'); // 大カテゴリの最初のタブをアクティブ化
				mainContents.forEach((content, index) => {
					content.style.display = index === 0 ? 'block' : 'none'; // 最初のコンテンツだけ表示
				});

				nestedTabs[0]?.classList.add('nav-tab-active'); // ネストされたタブの最初のタブをアクティブ化
				nestedContents.forEach((content, index) => {
					content.style.display = index === 0 ? 'block' : 'none'; // 最初のネストされたコンテンツだけ表示
				});
			});

			// ショートコード生成
			document.addEventListener('DOMContentLoaded', function () {
				const generateButton = document.getElementById('vkpdc_generate_shortcode');
				const shortcodeTextarea = document.createElement('textarea');

				generateButton.addEventListener('click', function () {
					const shortcode = `<?php echo $generated_shortcode; ?>`;

					// テキストエリアに表示して選択
					shortcodeTextarea.value = shortcode;
					shortcodeTextarea.style.width = '100%';
					shortcodeTextarea.style.height = '100px';
					document.body.appendChild(shortcodeTextarea);
					shortcodeTextarea.select();

					// コピー実行
					document.execCommand('copy');
					alert('<?php esc_html_e( 'Shortcode copied to clipboard!', 'vk-pattern-directory-creator' ); ?>');

					// 一時的なテキストエリアを削除
					document.body.removeChild(shortcodeTextarea);
				});
			});
		</script>

		<h2><?php esc_html_e( 'Preview', 'vk-pattern-directory-creator' ); ?></h2>
		<div id="vkpdc-preview-container" style="border: 1px solid #ddd; padding: 10px; margin-top: 20px;">
			<iframe id="vkpdc-preview-iframe" style="width: 100%; height: 600px;" src="<?php echo esc_url( site_url() . '/?vkpdc_preview=true&rand=' . rand() ); ?>"></iframe>
		</div>

	</div>
	<?php
}

/**
 * フック設定
 */
function vkpdc_register_shortcode_on_hook() {
	$hook_name = get_option( 'vkpdc_hook_name', '' ); // 保存されたフック名を取得
	if ( ! empty( $hook_name ) ) {
		// フックの実行時に既存のアクションをすべて削除
		add_action( $hook_name, function() use ( $hook_name ) {
			global $wp_filter;

			if ( isset( $wp_filter[ $hook_name ] ) ) {
				$callbacks = $wp_filter[ $hook_name ]->callbacks;

				// すべてのアクションを削除
				foreach ( $callbacks as $priority => $actions ) {
					foreach ( $actions as $key => $action ) {
						// ショートコードのアクションを例外にする
						if (
							is_string( $action['function'] ) && $action['function'] === 'vkpdc_execute_shortcode_on_hook'
						) {
							continue;
						}
						remove_action( $hook_name, $action['function'], $priority );
					}
				}
			}
		}, PHP_INT_MIN ); // 最低優先度で実行

		// ショートコードを実行
		add_action( $hook_name, 'vkpdc_execute_shortcode_on_hook', PHP_INT_MAX ); // 最高優先度でショートコードを追加
	}
}
add_action( 'init', 'vkpdc_register_shortcode_on_hook' );

function vkpdc_execute_shortcode_on_hook() {
	$options = vkpdc_get_default_options();
	$shortcode = sprintf(
		'[vkpdc_archive_loop numberPosts="%d" order="%s" orderby="%s" display_new="%d" display_taxonomies="%d" pattern_id="%d" display_date_publiched="%d" display_date_modified="%d" display_author="%d" display_image="%s" thumbnail_size="%s" display_btn_view="%d" display_btn_copy="%d" display_paged="%d" display_btn_view_text="%s" new_date="%d" new_text="%s" colWidthMinMobile="%s" colWidthMinMobileTablet="%s" colWidthMinMobilePC="%s" gap="%s" gapRow="%s"]',
		intval( $options['numberPosts'] ),
		esc_attr( $options['order'] ),
		esc_attr( $options['orderby'] ),
		intval( $options['display_new'] ),
		intval( $options['display_taxonomies'] ),
		intval( $options['pattern_id'] ),
		intval( $options['display_date_publiched'] ),
		intval( $options['display_date_modified'] ),
		intval( $options['display_author'] ),
		esc_attr( $options['display_image'] ),
		esc_attr( $options['thumbnail_size'] ),
		intval( $options['display_btn_view'] ),
		intval( $options['display_btn_copy'] ),
		intval( $options['display_paged'] ),
		esc_attr( $options['display_btn_view_text'] ),
		intval( $options['new_date'] ),
		esc_attr( $options['new_text'] ),
		esc_attr( $options['colWidthMinMobile'] ),
		esc_attr( $options['colWidthMinTablet'] ),
		esc_attr( $options['colWidthMinPC'] ),
		esc_attr( $options['gap'] ),
		esc_attr( $options['gapRow'] )
	);
	echo do_shortcode( $shortcode ); // ショートコードの結果のみを出力
}

/**
 * プレビュー表示
 */
function vkpdc_preview_output() {
	if ( isset( $_GET['vkpdc_preview'] ) && $_GET['vkpdc_preview'] === 'true' ) {
		// 必要な設定を取得
		$options = [];
		$defaults = vkpdc_get_default_options();
		foreach ( $defaults as $key => $default ) {
			$options[ $key ] = get_option( 'vkpdc_' . $key, $default );
		}

		// ショートコードを出力
		echo '<!DOCTYPE html>
		<html lang="en"style="margin: 0 !important; padding: 1.5rem;">
		<head>
			<meta charset="UTF-8">
			<meta name="viewport" content="width=device-width, initial-scale=1.0">
			<title>VK Patterns Preview</title>';
		wp_head();
		echo '</head>
		<body>';

		echo do_shortcode( sprintf(
			'[vkpdc_archive_loop numberPosts="%d" order="%s" orderby="%s" display_new="%d" display_taxonomies="%d" pattern_id="%d" display_date_publiched="%d" display_date_modified="%d" display_author="%d" display_image="%s" thumbnail_size="%s" display_btn_view="%d" display_btn_copy="%d" display_paged="%d" display_btn_view_text="%s" new_date="%d" new_text="%s" colWidthMinMobile="%s" colWidthMinMobileTablet="%s" colWidthMinMobilePC="%s" gap="%s" gapRow="%s"]',
			intval( $options['numberPosts'] ),
			esc_attr( $options['order'] ),
			esc_attr( $options['orderby'] ),
			intval( $options['display_new'] ),
			intval( $options['display_taxonomies'] ),
			intval( $options['pattern_id'] ),
			intval( $options['display_date_publiched'] ),
			intval( $options['display_date_modified'] ),
			intval( $options['display_author'] ),
			esc_attr( $options['display_image'] ),
			esc_attr( $options['thumbnail_size'] ),
			intval( $options['display_btn_view'] ),
			intval( $options['display_btn_copy'] ),
			intval( $options['display_paged'] ),
			esc_attr( $options['display_btn_view_text'] ),
			intval( $options['new_date'] ),
			esc_attr( $options['new_text'] ),
			esc_attr( $options['colWidthMinMobile'] ),
			esc_attr( $options['colWidthMinTablet'] ),
			esc_attr( $options['colWidthMinPC'] ),
			esc_attr( $options['gap'] ),
			esc_attr( $options['gapRow'] )
		) );

		echo '</body></html>';

		exit;
	}
}
add_action( 'template_redirect', 'vkpdc_preview_output' );

/**
 * Add Settings Page
 */
function add_shortcode_archive_settings_page() {
	add_submenu_page(
		'edit.php?post_type=vk-patterns',
		__( 'Archive and Shortcode Setting', 'vk-pattern-directory-creator' ),
		__( 'Archive and Shortcode Setting', 'vk-pattern-directory-creator' ),
		'manage_options',
		'vk-patterns-shortcode-archive-settings',
		'vkpdc_render_settings_page_with_shortcode'
	);
}
add_action( 'admin_menu', 'add_shortcode_archive_settings_page' );
