const MiniCssExtractPlugin = require( 'mini-css-extract-plugin' ); // eslint-disable-line import/no-extraneous-dependencies
const path = require( 'path' );

const devMode = process.env.NODE_ENV !== 'production';

const scssConfig = [
	{
		loader: MiniCssExtractPlugin.loader,
	},
	{
		loader: require.resolve( 'css-loader' ),
		options: {
			sourceMap: devMode,
			modules: {
				auto: true,
			},
		},
	},
];

module.exports = ( defaultConfig, rootPath ) => {
	return {
		...defaultConfig,
		context: __dirname,
		devtool: devMode ? 'inline-source-map' : false,
		mode: devMode ? 'development' : 'production',
		entry: {
			blocks: rootPath + '/src/blocks.js',
		},
		output: {
			path: rootPath + '/dist/',
			filename: '[name].build.js',
		},
		externals: {
			// eslint-disable-next-line quote-props
			'lodash': 'lodash', // Prevents an editor crash. See https://github.com/WordPress/gutenberg/issues/4043#issuecomment-633081315.
		},
		module: {
			rules: [
				{
					test: /\.js$/,
					exclude: [
						path.resolve( rootPath + '/node_modules' ),
						path.resolve( rootPath + '/build' ),
						path.resolve( rootPath + '/vendor' ),
					],
					use: [
						{
							loader: 'babel-loader',
							options: {
								presets: [ '@babel/preset-react' ],
							},
						},
					],
				},
				{
					test: /editor\.scss$/,
					exclude: [
						path.resolve( rootPath + '/node_modules' ),
						path.resolve( rootPath + '/build' ),
						path.resolve( rootPath + '/vendor' ),
					],
					use: [
						...scssConfig,
						{
							loader: require.resolve( 'sass-loader' ),
							options: {
								sourceMap: devMode,
							},
						},
					],
				},
				{
					test: /style\.scss$/,
					exclude: [
						path.resolve( rootPath + '/node_modules' ),
						path.resolve( rootPath + '/build' ),
						path.resolve( rootPath + '/vendor' ),
					],
					use: [
						...scssConfig,
						{
							loader: require.resolve( 'sass-loader' ),
							options: {
								sourceMap: devMode,
							},
						},
					],
				},
			],
		},
		plugins: [
			// Return webpack plugins that are not MiniCssExtractPlugin instance.
			// Taking this extra step instead of leaving default MiniCssExtractPlugin to respect gitignore rules.
			...defaultConfig.plugins.filter( ( item ) => {
				return ! ( item instanceof MiniCssExtractPlugin );
			} ),
			// Add the new MiniCssExtractPlugin to generate css build files.
			new MiniCssExtractPlugin( {
				filename: '[name].build.css',
			} ),
		],
	};
};
