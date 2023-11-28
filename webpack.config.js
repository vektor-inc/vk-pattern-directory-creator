const defaultConfig = require( '@wordpress/scripts/config/webpack.config' );

module.exports = [
	{
		...defaultConfig,
		entry: {
			'pattern-description':
				__dirname + '/blocks/src/pattern-description/index.js',
			'pattern-display':
				__dirname + '/blocks/src/pattern-display/index.js',
			'pattern-list': __dirname + '/blocks/src/pattern-list/index.js',
		},
		output: {
			path: __dirname + '/blocks/build/',
			filename: '[name]/block.js',
		},
		module: {
			...defaultConfig.module,
			rules: [
				...defaultConfig.module.rules,
				{
					test: /\.js$/,
					exclude: /(node_modules)/,
					use: {
						loader: 'babel-loader',
						options: {
							cacheDirectory: false, // キャッシュをOFF。理由：vk-patterns-js.pot を消した時に変更箇所以外の文字列が抽出されなくなる。
							babelrc: false, // babelrcを反映させない
							configFile: false, // babel.config.jsonを反映させない
							presets: [ '@wordpress/default' ],
						},
					},
				},
			],
		},
	},
];
