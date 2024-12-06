<?php
/**
 * Register Block Pattern Custom Taxonomies and Settings Page
 *
 * @package VK Pattern Directory Creator
 */

/**
 * Register Block Pattern Custom Taxonomies
 */
function register_custom_taxonomies() {
	$taxonomies = get_option( 'custom_taxonomies', array() );

	foreach ( $taxonomies as $taxonomy ) {
		register_taxonomy(
			$taxonomy['slug'],
			'vk-patterns',
			array(
				'labels' => array(
					'name'              => $taxonomy['name'],
					'singular_name'     => $taxonomy['name'],
					'search_items'      => __( 'Search ' . $taxonomy['name'], 'vk-pattern-directory-creator' ),
					'all_items'         => __( 'All ' . $taxonomy['name'], 'vk-pattern-directory-creator' ),
					'edit_item'         => __( 'Edit ' . $taxonomy['name'], 'vk-pattern-directory-creator' ),
					'update_item'       => __( 'Update ' . $taxonomy['name'], 'vk-pattern-directory-creator' ),
					'add_new_item'      => __( 'Add New ' . $taxonomy['name'], 'vk-pattern-directory-creator' ),
					'new_item_name'     => __( 'New ' . $taxonomy['name'] . ' Name', 'vk-pattern-directory-creator' ),
					'menu_name'         => __( $taxonomy['name'], 'vk-pattern-directory-creator' ),
				),
				'show_ui'           => true,
				'show_admin_column' => true,
				'show_in_rest'      => true,
				'hierarchical'      => isset( $taxonomy['hierarchical'] ) ? (bool) $taxonomy['hierarchical'] : false, // チェックされていない場合はタグとして扱う
				'rewrite'           => array( 'slug' => $taxonomy['slug'] ),
			)
		);
	}
}
add_action( 'init', 'register_custom_taxonomies', 0 );

/**
 * Add Settings Page
 */
function add_taxonomy_settings_page() {
	add_submenu_page(
		'edit.php?post_type=vk-patterns',
		__( 'Add Taxonomies', 'vk-pattern-directory-creator' ),
		__( 'Add Taxonomies', 'vk-pattern-directory-creator' ),
		'manage_options',
		'custom_taxonomies',
		'display_taxonomy_settings_page'
	);
}
add_action( 'admin_menu', 'add_taxonomy_settings_page' );

/**
 * Settings Page Callback
 */
function display_taxonomy_settings_page() {

	if ( isset( $_POST['remove_taxonomy'] ) ) {
		// どの行が削除されたかを判定
		$remove_key = array_key_first( $_POST['remove_taxonomy'] );
	
		// 現在のタクソノミーを取得
		$taxonomies = get_option( 'custom_taxonomies', array() );
	
		// 該当する行を削除
		if ( isset( $taxonomies[ $remove_key ] ) ) {
			unset( $taxonomies[ $remove_key ] );
		}
	
		// 更新後のタクソノミーを保存
		update_option( 'custom_taxonomies', $taxonomies );
	
		// 保存完了メッセージを表示
		echo '<div class="updated"><p>' . __( 'Taxonomy removed successfully.', 'vk-pattern-directory-creator' ) . '</p></div>';
	}
	
	if ( isset( $_POST['save_custom_taxonomies'] ) ) {
		check_admin_referer( 'save_custom_taxonomies' );

		$taxonomies = array();
		if ( isset( $_POST['taxonomies'] ) && is_array( $_POST['taxonomies'] ) ) {
			foreach ( $_POST['taxonomies'] as $taxonomy ) {
				$taxonomies[] = array(
					'name'          => sanitize_text_field( $taxonomy['name'] ),
					'slug'          => sanitize_text_field( $taxonomy['slug'] ),
					'hierarchical'  => isset( $taxonomy['hierarchical'] ) ? (bool) $taxonomy['hierarchical'] : false, // チェックされていない場合はタグとして扱う
				);
			}
		}

		update_option( 'custom_taxonomies', $taxonomies );
		echo '<div class="updated"><p>' . __( 'Taxonomies saved.', 'vk-pattern-directory-creator' ) . '</p></div>';
	}

	$taxonomies = get_option( 'custom_taxonomies', array() );

	?>
	<div class="wrap">
		<h1><?php _e( 'Block Pattern Custom Taxonomies', 'vk-pattern-directory-creator' ); ?></h1>
		<form method="post" action="">
			<?php wp_nonce_field( 'save_custom_taxonomies' ); ?>
			<table class="table form-table">
				<thead>
					<tr>
						<th><?php _e( 'Custom taxonomy label', 'vk-pattern-directory-creator' ); ?></th>
						<th><?php _e( 'Custom taxonomy name (slug)', 'vk-pattern-directory-creator' ); ?></th>
						<th><?php _e( 'Hierarchy', 'vk-pattern-directory-creator' ); ?></th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ( $taxonomies as $index => $taxonomy ) : ?>
						<tr>
							<td>
								<input type="text" name="taxonomies[<?php echo $index; ?>][name]" value="<?php echo esc_attr( $taxonomy['name'] ); ?>" placeholder="<?php _e( 'Category', 'vk-pattern-directory-creator' ); ?>" />
							</td>
							<td>
								<input type="text" name="taxonomies[<?php echo $index; ?>][slug]" value="<?php echo esc_attr( $taxonomy['slug'] ); ?>" placeholder="<?php _e( 'category', 'vk-pattern-directory-creator' ); ?>" />
							</td>
							<td>
								<input type="checkbox" name="taxonomies[<?php echo $index; ?>][hierarchical]" <?php checked( $taxonomy['hierarchical'] ); ?> />
							</td>
							<td>
								<input type="submit" name="remove_taxonomy[<?php echo $index; ?>]" class="button remove-taxonomy" value="<?php _e( 'Remove', 'vk-pattern-directory-creator' ); ?>" />
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
				<tfoot>
					<tr>
						<td><p class="description"><?php _e( 'Use only lowercase letters, numbers, hyphens, and underscores.', 'vk-pattern-directory-creator' ); ?></p></td>
						<td><p class="description"><?php _e( 'The URL-friendly version of the name (e.g., category)', 'vk-pattern-directory-creator' ); ?></p></td>
						<td><p class="description"><?php _e( 'Make it a tag (do not hierarchize)', 'vk-pattern-directory-creator' ); ?></p></td>
						<td></td>
					</tr>
				</tfoot>
			</table>
			<p><a href="#" id="add-taxonomy" class="button"><?php _e( 'Add Taxonomy', 'vk-pattern-directory-creator' ); ?></a></p>
			<p><input type="submit" name="save_custom_taxonomies" class="button-primary" value="<?php _e( 'Save Taxonomies', 'vk-pattern-directory-creator' ); ?>" /></p>
		</form>
	</div>
	<style>
		.form-table {
			border-collapse: collapse;
			border-spacing: 0;
			width:100%;
			background-color: #fff;
			border:2px solid #e5e5e5;
		}
		.form-table th, .form-table td {
			border:1px solid #e5e5e5;
			padding:0.5em 0.8em;
		}
		.form-table th {
			background-color: #f5f5f5;
		}
		.form-table input[type="text"] {
			width: 100%;
		}
		.description {
			font-size: 0.9rem;
			color: #666;
		}
		@media screen and (max-width: 782px) {
			.form-table td, .form-table th, .label-responsive {
				display: revert;
			}
		}
		@media (min-width:783px) {
			.form-table th:last-child {
				width: 10%;
			}
		}
	</style>
	<script>
		document.addEventListener('DOMContentLoaded', function () {
			function toggleRemoveButton() {
				document.querySelectorAll('tbody tr').forEach(function(row) {
					if (row.classList.contains('new-taxonomy')) {
						row.querySelector('.remove-taxonomy').disabled = true;
					} else {
						row.querySelector('.remove-taxonomy').disabled = false;
					}
				});
			}

			function toggleSaveButton() {
				var isValid = Array.from(document.querySelectorAll('tbody tr')).every(function(row) {
					if (row.classList.contains('new-taxonomy')) {
						return Array.from(row.querySelectorAll('input[type="text"]')).every(function(input) {
							return input.value.trim() !== '';
						});
					}
					return true;
				});
				document.querySelector('input[name="save_custom_taxonomies"]').disabled = !isValid;
			}

			document.getElementById('add-taxonomy').addEventListener('click', function(e) {
				e.preventDefault();
				var index = this.dataset.index || <?php echo count( $taxonomies ); ?>;
				var row = document.createElement('tr');
				row.classList.add('new-taxonomy');
				row.innerHTML = '<td>' +
					'<input type="text" name="taxonomies[' + index + '][name]" value="" placeholder="<?php _e( 'Category', 'vk-pattern-directory-creator' ); ?>" />' +
					'</td>' +
					'<td>' +
					'<input type="text" name="taxonomies[' + index + '][slug]" value="" placeholder="<?php _e( 'category', 'vk-pattern-directory-creator' ); ?>" />' +
					'</td>' +
					'<td>' +
					'<input type="checkbox" name="taxonomies[' + index + '][hierarchical]" />' +
					'</td>' +
					'<td><input type="submit" name="remove_taxonomy[' + index + ']" class="button remove-taxonomy" value="<?php _e( 'Remove', 'vk-pattern-directory-creator' ); ?>" disabled /></td>';
				document.querySelector('table.form-table tbody').appendChild(row);
				this.dataset.index = parseInt(index) + 1;
				toggleRemoveButton();
				toggleSaveButton();
			});

			document.addEventListener('input', function(e) {
				if (e.target.matches('input[type="text"]')) {
					toggleRemoveButton();
					toggleSaveButton();
				}
			});

			document.querySelector('form').addEventListener('submit', function(e) {
				// Removeボタンがクリックされた場合の処理
				if (e.submitter && e.submitter.classList.contains('remove-taxonomy')) {
					var row = e.submitter.closest('tr');
					var isFilled = Array.from(row.querySelectorAll('input[type="text"]')).every(function(input) {
						return input.value.trim() !== '';
					});

					// すべてのフィールドが空でない場合のみ確認メッセージを表示
					if (isFilled) {
						if (!confirm('<?php _e( 'Are you sure you want to delete this taxonomy?', 'vk-pattern-directory-creator' ); ?>')) {
							e.preventDefault();
						}
					}
					return;
				}

				var isValid = Array.from(document.querySelectorAll('tbody tr')).every(function(row) {
					if (row.classList.contains('new-taxonomy')) {
						return Array.from(row.querySelectorAll('input[type="text"]')).every(function(input) {
							return input.value.trim() !== '';
						});
					}
					return true;
				});

				if (!isValid) {
					e.preventDefault();
					alert('<?php _e( 'Please fill in all fields: Name and Slug.', 'vk-pattern-directory-creator' ); ?>');
				}
			});

			toggleRemoveButton();
			toggleSaveButton();
		});
	</script>
	<?php
}
