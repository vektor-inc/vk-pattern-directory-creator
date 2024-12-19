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
		'display_btn_view_text' => __( 'Read More', 'vk-pattern-directory-creator' ),
		'display_image'         => 'featured',
		'thumbnail_size'        => 'full',
		'new_date'              => 7,
		'new_text'              =>  __( 'NEW!!', 'vk-pattern-directory-creator' ),
		'colWidthMin'           => '300px',
		'colWidthMinTablet'     => '300px',
		'colWidthMinPC'         => '300px',
		'gap'                   => '1.5rem',
		'gapRow'                => '1.5rem',
		'hook_name'             => '',
	);
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
		'[vkpdc_archive_loop numberPosts="%d" order="%s" orderby="%s" display_new="%d" display_taxonomies="%d" pattern_id="%d" display_date_publiched="%d" display_date_modified="%d" display_author="%d" display_image="%s" thumbnail_size="%s" display_btn_view="%d" display_btn_copy="%d" display_btn_view_text="%s" new_date="%d" new_text="%s" colWidthMin="%s" colWidthMinTablet="%s" colWidthMinPC="%s" gap="%s" gapRow="%s"]',
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
		<h1><?php esc_html_e( 'Shortcode and Archive Setting', 'vk-pattern-directory-creator' ); ?></h1>
		<?php if ( $message ) : ?>
			<div class="updated"><p><?php echo esc_html( $message ); ?></p></div>
		<?php endif; ?>
		<form method="POST">
			<?php wp_nonce_field( 'vkpdc_save_settings', 'vkpdc_settings_nonce' ); ?>
			<h2 class="nav-tab-wrapper">
				<a href="display-conditions" class="nav-tab nav-tab-active"><?php esc_html_e( 'Display conditions', 'vk-pattern-directory-creator' ); ?></a>
				<a href="#display-items" class="nav-tab"><?php esc_html_e( 'Display Items', 'vk-pattern-directory-creator' ); ?></a>
				<a href="#column-width-setting" class="nav-tab"><?php esc_html_e( 'Column Width Setting', 'vk-pattern-directory-creator' ); ?></a>
				<a href="#advanced-settings" class="nav-tab"><?php esc_html_e( 'Advanced Settings', 'vk-pattern-directory-creator' ); ?></a>
			</h2>
			<div id="display-conditions" class="tab-content">
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
			<div id="display-items" class="tab-content" style="display:none;">
				<table class="form-table">
					<?php foreach ( ['display_new', 'display_taxonomies', 'pattern_id', 'display_date_publiched', 'display_date_modified', 'display_author', 'display_btn_view', 'display_btn_copy'] as $key ) : ?>
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
			<div id="column-width-setting" class="tab-content" style="display:none;">
				<table class="form-table">
					<tr>
						<th><label for="colWidthMin"><?php esc_html_e( 'Column min width (Mobile)', 'vk-pattern-directory-creator' ); ?></label></th>
						<td><input type="text" id="colWidthMin" name="colWidthMin" value="<?php echo esc_attr( $options['colWidthMin'] ); ?>"></td>
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
			<div id="advanced-settings" class="tab-content" style="display:none;">
				<table class="form-table">
					<tr>
						<th><label for="hook_name"><?php esc_html_e( 'Hook Name', 'vk-pattern-directory-creator' ); ?></label></th>
						<td>
							<input type="text" id="hook_name" name="hook_name" value="<?php echo esc_attr( $options['hook_name'] ); ?>" placeholder="e.g., lightning_extend_loop">
							<p class="description"><?php esc_html_e( 'Specify the WordPress hook where the shortcode should be executed.', 'vk-pattern-directory-creator' ); ?></p>
						</td>
					</tr>
				</table>
			</div>
			<?php submit_button(); ?>
			<input type="submit" name="reset" class="button button-secondary" value="<?php esc_attr_e( 'Reset to Default', 'vk-pattern-directory-creator' ); ?>">
		</form>

		<div id="vkpdc-preview-container" style="border: 1px solid #ddd; padding: 10px; margin-top: 20px;">
			<iframe id="vkpdc-preview-iframe" style="width: 100%; height: 600px;" src="<?php echo esc_url( site_url() . '/?vkpdc_preview=true&rand=' . rand() ); ?>"></iframe>
		</div>

		<h2><?php esc_html_e( 'Generated Shortcode', 'vk-pattern-directory-creator' ); ?></h2>
		<div style="display: flex; align-items: center;">
			<textarea id="vkpdc_shortcode" readonly rows="3" style="width: 100%; margin-right: 10px;"><?php echo esc_html( $generated_shortcode ); ?></textarea>
			<button type="button" id="vkpdc_copy_shortcode" class="button button-primary">
				<?php esc_html_e( 'Copy Shortcode', 'vk-pattern-directory-creator' ); ?>
			</button>
		</div>
		<p id="vkpdc_copy_message" style="display:none; color: green; margin-top: 5px;">
			<?php esc_html_e( 'Shortcode copied to clipboard!', 'vk-pattern-directory-creator' ); ?>
		</p>

		<script>
		document.addEventListener('DOMContentLoaded', function () {
			const copyButton = document.getElementById('vkpdc_copy_shortcode');
			const shortcodeField = document.getElementById('vkpdc_shortcode');
			const copyMessage = document.getElementById('vkpdc_copy_message');

			copyButton.addEventListener('click', function () {
				shortcodeField.select();
				document.execCommand('copy');
				copyMessage.style.display = 'block';
				setTimeout(() => {
					copyMessage.style.display = 'none';
				}, 2000);
			});

			const tabs = document.querySelectorAll('.nav-tab');
			const tabContents = document.querySelectorAll('.tab-content');

			tabs.forEach(tab => {
				tab.addEventListener('click', function (e) {
					e.preventDefault();
					tabs.forEach(t => t.classList.remove('nav-tab-active'));
					tab.classList.add('nav-tab-active');
					const target = tab.getAttribute('href').substring(1);
					tabContents.forEach(tc => {
						tc.style.display = tc.id === target ? 'block' : 'none';
					});
				});
			});
		});
		</script>
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
		'[vkpdc_archive_loop numberPosts="%d" order="%s" orderby="%s" display_new="%d" display_taxonomies="%d" pattern_id="%d" display_date_publiched="%d" display_date_modified="%d" display_author="%d" display_image="%s" thumbnail_size="%s" display_btn_view="%d" display_btn_copy="%d" display_btn_view_text="%s" new_date="%d" new_text="%s" colWidthMin="%s" colWidthMinTablet="%s" colWidthMinPC="%s" gap="%s" gapRow="%s"]',
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
		esc_attr( $options['display_btn_view_text'] ),
		intval( $options['new_date'] ),
		esc_attr( $options['new_text'] ),
		esc_attr( $options['colWidthMin'] ),
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
			'[vkpdc_archive_loop numberPosts="%d" order="%s" orderby="%s" display_new="%d" display_taxonomies="%d" pattern_id="%d" display_date_publiched="%d" display_date_modified="%d" display_author="%d" display_image="%s" thumbnail_size="%s" display_btn_view="%d" display_btn_copy="%d" display_btn_view_text="%s" new_date="%d" new_text="%s" colWidthMin="%s" colWidthMinTablet="%s" colWidthMinPC="%s" gap="%s" gapRow="%s"]',
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
			esc_attr( $options['display_btn_view_text'] ),
			intval( $options['new_date'] ),
			esc_attr( $options['new_text'] ),
			esc_attr( $options['colWidthMin'] ),
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
		__( 'Shortcode and Archive Setting', 'vk-pattern-directory-creator' ),
		__( 'Shortcode and Archive Setting', 'vk-pattern-directory-creator' ),
		'manage_options',
		'vk-patterns-shortcode-archive-settings',
		'vkpdc_render_settings_page_with_shortcode'
	);
}
add_action( 'admin_menu', 'add_shortcode_archive_settings_page' );
