const path = require('path');
const os = require('os');
const WebpackAssetsManifest = require('webpack-assets-manifest');

const manifest = new WebpackAssetsManifest({
	space: 4,
	publicPath: '/',
	output: './dist/manifest.json',
});

module.exports = {
	runtimeCompiler: true,
	assetsDir: '',
	configureWebpack: (config) => {
		config.entry = {
			app: './vue/app.js',
		};
		config.resolve = {
			alias: {
				'@': path.resolve(__dirname, 'vue'),
				'@sass': path.resolve(__dirname, 'sass'),
			},
		};
		config.plugins.push(manifest);
		config.devtool = 'source-map';
		config.output.filename = 'app-[contenthash:8].js';
	},
	parallel: !('linux' === process.platform && os.release().includes('Microsoft')),
	css: {
		loaderOptions: {
			postcss: {
				postcssOptions: {
					plugins: ['tailwindcss', 'autoprefixer'],
				},
			},
		},
	},
};
