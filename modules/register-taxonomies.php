<?php
/**
 * Register Custom Taxonomies and Settings Page
 *
 * @package VK Pattern Directory Creator
 */

/**
 * Register Custom Taxonomies
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
					'singular_name'     => $taxonomy['singular_name'],
					'search_items'      => __( 'Search ' . $taxonomy['name'], 'vk-pattern-directory-creator' ),
					'all_items'         => __( 'All ' . $taxonomy['name'], 'vk-pattern-directory-creator' ),
					'edit_item'         => __( 'Edit ' . $taxonomy['singular_name'], 'vk-pattern-directory-creator' ),
					'update_item'       => __( 'Update ' . $taxonomy['singular_name'], 'vk-pattern-directory-creator' ),
					'add_new_item'      => __( 'Add New ' . $taxonomy['singular_name'], 'vk-pattern-directory-creator' ),
					'new_item_name'     => __( 'New ' . $taxonomy['singular_name'] . ' Name', 'vk-pattern-directory-creator' ),
					'menu_name'         => __( $taxonomy['name'], 'vk-pattern-directory-creator' ),
				),
				'show_ui'           => true,
				'show_admin_column' => true,
				'show_in_rest'      => true,
				'hierarchical'      => $taxonomy['hierarchical'],
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
					'singular_name' => sanitize_text_field( $taxonomy['singular_name'] ),
					'slug'          => sanitize_text_field( $taxonomy['slug'] ),
					'hierarchical'  => isset( $taxonomy['hierarchical'] ) ? (bool) $taxonomy['hierarchical'] : false,
				);
			}
		}

		update_option( 'custom_taxonomies', $taxonomies );
		echo '<div class="updated"><p>' . __( 'Taxonomies saved.', 'vk-pattern-directory-creator' ) . '</p></div>';
	}

	$taxonomies = get_option( 'custom_taxonomies', array() );

	?>
	<div class="wrap">
		<h1><?php _e( 'Custom Taxonomies', 'vk-pattern-directory-creator' ); ?></h1>
		<form method="post" action="">
			<?php wp_nonce_field( 'save_custom_taxonomies' ); ?>
			<table class="form-table">
				<thead>
					<tr>
						<th><?php _e( 'Name', 'vk-pattern-directory-creator' ); ?></th>
						<th><?php _e( 'Singular Name', 'vk-pattern-directory-creator' ); ?></th>
						<th><?php _e( 'Slug', 'vk-pattern-directory-creator' ); ?></th>
						<th><?php _e( 'Hierarchical', 'vk-pattern-directory-creator' ); ?></th>
						<th><?php _e( 'Actions', 'vk-pattern-directory-creator' ); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ( $taxonomies as $index => $taxonomy ) : ?>
						<tr>
							<td>
								<input type="text" name="taxonomies[<?php echo $index; ?>][name]" value="<?php echo esc_attr( $taxonomy['name'] ); ?>" placeholder="<?php _e( 'Category', 'vk-pattern-directory-creator' ); ?>" />
								<p class="description"><?php _e( 'The plural name of the taxonomy (e.g., Categories)', 'vk-pattern-directory-creator' ); ?></p>
							</td>
							<td>
								<input type="text" name="taxonomies[<?php echo $index; ?>][singular_name]" value="<?php echo esc_attr( $taxonomy['singular_name'] ); ?>" placeholder="<?php _e( 'Category', 'vk-pattern-directory-creator' ); ?>" />
								<p class="description"><?php _e( 'The singular name of the taxonomy (e.g., Category)', 'vk-pattern-directory-creator' ); ?></p>
							</td>
							<td>
								<input type="text" name="taxonomies[<?php echo $index; ?>][slug]" value="<?php echo esc_attr( $taxonomy['slug'] ); ?>" placeholder="<?php _e( 'category', 'vk-pattern-directory-creator' ); ?>" />
								<p class="description"><?php _e( 'The URL-friendly version of the name (e.g., category)', 'vk-pattern-directory-creator' ); ?></p>
							</td>
							<td>
								<input type="checkbox" name="taxonomies[<?php echo $index; ?>][hierarchical]" <?php checked( $taxonomy['hierarchical'] ); ?> />
								<p class="description"><?php _e( 'Is this taxonomy hierarchical (like categories)?', 'vk-pattern-directory-creator' ); ?></p>
							</td>
							<td>
								<input type="submit" name="remove_taxonomy[<?php echo $index; ?>]" class="button remove-taxonomy" value="<?php _e( 'Remove', 'vk-pattern-directory-creator' ); ?>" />
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
			<p><a href="#" id="add-taxonomy" class="button"><?php _e( 'Add Taxonomy', 'vk-pattern-directory-creator' ); ?></a></p>
			<p><input type="submit" name="save_custom_taxonomies" class="button-primary" value="<?php _e( 'Save Taxonomies', 'vk-pattern-directory-creator' ); ?>" /></p>
		</form>
	</div>
	<style>
		.form-table th, .form-table td {
			padding: 10px;
			vertical-align: top;
		}
		.form-table input[type="text"] {
			width: 100%;
		}
		.form-table .button {
			margin-top: 5px;
		}
		.description {
			font-size: 0.9em;
			color: #666;
		}
	</style>
	<script>
		document.addEventListener('DOMContentLoaded', function () {
			function toggleRemoveButton() {
				document.querySelectorAll('tbody tr').forEach(function(row) {
					var isFilled = Array.from(row.querySelectorAll('input[type="text"]')).every(function(input) {
						return input.value.trim() !== '';
					});
				});
			}

			function toggleSaveButton() {
				var isValid = Array.from(document.querySelectorAll('tbody tr')).every(function(row) {
					return Array.from(row.querySelectorAll('input[type="text"]')).every(function(input) {
						return input.value.trim() !== '';
					});
				});
				document.querySelector('input[name="save_custom_taxonomies"]').disabled = !isValid;
			}

			document.getElementById('add-taxonomy').addEventListener('click', function(e) {
				e.preventDefault();
				var index = this.dataset.index || <?php echo count( $taxonomies ); ?>;
				var row = document.createElement('tr');
				row.innerHTML = '<td>' +
					'<input type="text" name="taxonomies[' + index + '][name]" value="" placeholder="<?php _e( 'Category', 'vk-pattern-directory-creator' ); ?>" />' +
					'<p class="description"><?php _e( 'The plural name of the taxonomy (e.g., Categories)', 'vk-pattern-directory-creator' ); ?></p>' +
					'</td>' +
					'<td>' +
					'<input type="text" name="taxonomies[' + index + '][singular_name]" value="" placeholder="<?php _e( 'Category', 'vk-pattern-directory-creator' ); ?>" />' +
					'<p class="description"><?php _e( 'The singular name of the taxonomy (e.g., Category)', 'vk-pattern-directory-creator' ); ?></p>' +
					'</td>' +
					'<td>' +
					'<input type="text" name="taxonomies[' + index + '][slug]" value="" placeholder="<?php _e( 'category', 'vk-pattern-directory-creator' ); ?>" />' +
					'<p class="description"><?php _e( 'The URL-friendly version of the name (e.g., category)', 'vk-pattern-directory-creator' ); ?></p>' +
					'</td>' +
					'<td>' +
					'<input type="checkbox" name="taxonomies[' + index + '][hierarchical]" />' +
					'<p class="description"><?php _e( 'Is this taxonomy hierarchical (like categories)?', 'vk-pattern-directory-creator' ); ?></p>' +
					'</td>' +
					'<td><input type="submit" name="remove_taxonomy[' + index + ']" class="button remove-taxonomy" value="<?php _e( 'Remove', 'vk-pattern-directory-creator' ); ?>" /></td>';
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
				// Removeボタンがクリックされた場合は確認メッセージを表示
				if (e.submitter && e.submitter.classList.contains('remove-taxonomy')) {
					if (!confirm('<?php _e( 'Are you sure you want to delete this taxonomy?', 'vk-pattern-directory-creator' ); ?>')) {
						e.preventDefault();
					}
					return;
				}

				var isValid = Array.from(document.querySelectorAll('tbody tr')).every(function(row) {
					return Array.from(row.querySelectorAll('input[type="text"]')).every(function(input) {
						return input.value.trim() !== '';
					});
				});

				if (!isValid) {
					e.preventDefault();
					alert('<?php _e( 'Please fill in all fields: Name, Singular Name, and Slug.', 'vk-pattern-directory-creator' ); ?>');
				}
			});

			toggleRemoveButton();
			toggleSaveButton();
		});
	</script>
	<?php
}
