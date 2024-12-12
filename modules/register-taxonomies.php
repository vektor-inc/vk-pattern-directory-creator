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
				'labels' => generate_taxonomy_labels( $taxonomy['name'] ),
				'show_ui' => true,
				'show_admin_column' => true,
				'show_in_rest' => true,
				'rewrite' => array( 'slug' => $taxonomy['slug'] ),
				'hierarchical'      => $taxonomy['hierarchical'],
			)
		);
	}
}
add_action( 'init', 'register_custom_taxonomies', 0 );

/**
 * Generate taxonomy labels
 *
 * @param string $name Taxonomy name.
 * @return array Taxonomy labels.
 */
function generate_taxonomy_labels( $name ) {
	return array(
		'name' => $name,
		'singular_name' => $name,
		'search_items' => __( 'Search ' . $name, 'vk-pattern-directory-creator' ),
		'all_items' => __( 'All ' . $name, 'vk-pattern-directory-creator' ),
		'edit_item' => __( 'Edit ' . $name, 'vk-pattern-directory-creator' ),
		'update_item' => __( 'Update ' . $name, 'vk-pattern-directory-creator' ),
		'add_new_item' => __( 'Add New ' . $name, 'vk-pattern-directory-creator' ),
		'new_item_name' => __( 'New ' . $name . ' Name', 'vk-pattern-directory-creator' ),
		'menu_name' => __( $name, 'vk-pattern-directory-creator' ),
	);
}

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
 * Display Settings Page
 */
function display_taxonomy_settings_page() {
	handle_taxonomy_form_submission();
	$taxonomies = get_option( 'custom_taxonomies', array() );
	render_taxonomy_settings_page( $taxonomies );
}

/**
 * Handle form submission
 */
function handle_taxonomy_form_submission() {
	if ( isset( $_POST['remove_taxonomy'] ) ) {
		remove_taxonomy();
	}

	if ( isset( $_POST['save_custom_taxonomies'] ) ) {
		save_custom_taxonomies();
	}
}

/**
 * Remove taxonomy
 */
function remove_taxonomy() {
	$remove_key = array_key_first( $_POST['remove_taxonomy'] );
	$taxonomies = get_option( 'custom_taxonomies', array() );

	if ( isset( $taxonomies[ $remove_key ] ) ) {
		unset( $taxonomies[ $remove_key ] );
	}

	update_option( 'custom_taxonomies', $taxonomies );
	echo '<div class="updated"><p>' . _e( 'Taxonomy removed successfully.', 'vk-pattern-directory-creator' ) . '</p></div>';
}

/**
 * Save custom taxonomies
 */
function save_custom_taxonomies() {
	check_admin_referer( 'save_custom_taxonomies' );

	$taxonomies = array();
	if ( isset( $_POST['taxonomies'] ) && is_array( $_POST['taxonomies'] ) ) {
		foreach ( $_POST['taxonomies'] as $taxonomy ) {
			// バリデーション：name と slug が空���場合はスキップ
			if ( empty( $taxonomy['name'] ) || empty( $taxonomy['slug'] ) ) {
				continue;
			}
			$taxonomies[] = array(
				'name'          => sanitize_text_field( $taxonomy['name'] ),
				'slug'          => sanitize_title( $taxonomy['slug'] ),
				'hierarchical'  => empty( $taxonomy['hierarchical'] ) ? true : false,
			);
		}
	}

	update_option( 'custom_taxonomies', $taxonomies );
	echo '<div class="updated"><p>' . __( 'Taxonomies saved.', 'vk-pattern-directory-creator' ) . '</p></div>';
}

/**
 * Render taxonomy settings page
 *
 * @param array $taxonomies List of taxonomies.
 */
function render_taxonomy_settings_page( $taxonomies ) {
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
								<input type="checkbox" name="taxonomies[<?php echo $index; ?>][hierarchical]" <?php checked( $taxonomy['hierarchical'], false ); ?> />
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
			width: 100%;
			background-color: #fff;
			border: 2px solid #e5e5e5;
		}
		.form-table th, .form-table td {
			border: 1px solid #e5e5e5;
			padding: 0.5em 0.8em;
		}
		.form-table th {
			background-color: #f5f5f5;
		}
		.form-table input[type="text"] {
			width: 100%;
		}
		.description {
			margin: 0 !important;
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
			function addEmptyRow() {
				var index = document.querySelectorAll('table.form-table tbody tr').length;
				var row = document.createElement('tr');
				row.classList.add('new-taxonomy');
				row.innerHTML = `
					<td><input type="text" name="taxonomies[${index}][name]" placeholder="<?php _e( 'Category', 'vk-pattern-directory-creator' ); ?>" /></td>
					<td><input type="text" name="taxonomies[${index}][slug]" placeholder="<?php _e( 'category', 'vk-pattern-directory-creator' ); ?>" /></td>
					<td><input type="checkbox" name="taxonomies[${index}][hierarchical]" /></td>
					<td><input type="submit" name="remove_taxonomy[${index}]" class="button remove-taxonomy" value="<?php _e( 'Remove', 'vk-pattern-directory-creator' ); ?>" disabled /></td>
				`;
				document.querySelector('table.form-table tbody').appendChild(row);
			}

			function toggleAddButton() {
				var isValid = Array.from(document.querySelectorAll('tbody tr.new-taxonomy')).every(function(row) {
					return Array.from(row.querySelectorAll('input[type="text"]')).every(function(input) {
						return input.value.trim() !== '';
					});
				});
				document.getElementById('add-taxonomy').disabled = !isValid;
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
				addEmptyRow();
				toggleAddButton();
				toggleSaveButton();
			});

			document.addEventListener('input', function(e) {
				if (e.target.matches('input[type="text"]')) {
					toggleAddButton();
					toggleSaveButton();
				}
			});

			document.querySelector('form').addEventListener('submit', function(e) {
				if (e.submitter && e.submitter.classList.contains('remove-taxonomy')) {
					var row = e.submitter.closest('tr');
					var isFilled = Array.from(row.querySelectorAll('input[type="text"]')).every(function(input) {
						return input.value.trim() !== '';
					});

					if (isFilled && row.classList.contains('new-taxonomy')) {
						e.preventDefault();
						alert('<?php _e( 'Please save the new taxonomy before removing it.', 'vk-pattern-directory-creator' ); ?>');
						return;
					}

					if (!isFilled && !confirm('<?php _e( 'Are you sure you want to delete this taxonomy?', 'vk-pattern-directory-creator' ); ?>')) {
						e.preventDefault();
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

			// 初期状態で空の行を追加
			if (document.querySelectorAll('table.form-table tbody tr').length === 0) {
				addEmptyRow();
			}

			toggleAddButton();
			toggleSaveButton();
		});
	</script>
	<?php
}