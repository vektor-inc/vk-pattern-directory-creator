module.exports = {
	root: true,
	extends: [ 'plugin:@wordpress/eslint-plugin/recommended' ],
	rules: {
		camelcase: 'off',

		'import/no-unresolved': 'off',
		'import/no-extraneous-dependencies': 'off',
		'react-hooks/exhaustive-deps': 'off',
		'@wordpress/no-unsafe-wp-apis': 'off',
		'jsx-a11y/label-has-for': 'off',
		'jsx-a11y/label-has-associated-control': 'off',
	},
};
