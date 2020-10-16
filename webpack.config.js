const setup = require('./entryPoints.js')
const webpack = require('webpack')
const AssetsPlugin = require('assets-webpack-plugin')
var JS_DIR = setup.path.resolve(__dirname, 'javascript');

module.exports = (env, argv) => {
  const inProduction = argv.mode === 'production'
  const inDevelopment = argv.mode === 'development'

  const settings = {
    entry: setup.entry,
    output: {
      path: setup.path.join(JS_DIR, "dist"),
      filename: "[name].min.js"
    },
    externals: {
      $: 'jQuery',
      jquery: 'jQuery'
    },
    optimization: {
      splitChunks: {
        minChunks: 2,
        cacheGroups: {
          vendors: {
            test: /[\\/]node_modules[\\/]/,
            minChunks: 2,
            name: 'vendor',
            enforce: true,
            chunks: 'all'
          }
        }
      }
    },
    resolve: {
      extensions: ['.js', '.jsx']
    },
    plugins: [new AssetsPlugin({filename: 'assets.json',
                      prettyPrint: true,}) ],
    module: {
      rules: [
        {
          test: /\.(js|jsx)$/,
          enforce: 'pre',
          loader: 'eslint-loader',
          exclude: '/node_modules/',
          include: JS_DIR + "/dist",
          options: {configFile: setup.path.join(__dirname, '.eslintrc.js'),
                   useEslintrc: false}
        }, {
          test: /\.(js|jsx)$/,
          include: JS_DIR,
          loader: 'babel-loader',
          query: {
            presets: ['es2015', 'react']
          }
        }, {
          test: /\.css$/,
          loader: "style-loader!css-loader"
        }
      ]
    }
  }

  if (inDevelopment) {
    /*const BrowserSyncPlugin = require('browser-sync-webpack-plugin')
    settings.plugins.push(
      new BrowserSyncPlugin({host: 'localhost', notify: false, port: 3000, files: ['./javascript/dev/*.js'], proxy: 'localhost/phpwebsite'})
  )*/
    settings.devtool = 'inline-source-map'
    settings.output = {
      path: setup.path.join(JS_DIR, 'dist'),
      filename: '[name].js'
    }
  }

  if (inProduction) {


      settings.plugins.push(
	  new webpack.DefinePlugin({'process.env.NODE_ENV': JSON.stringify('production')})
      )

      settings.output = {
	  path: setup.path.join(JS_DIR, 'dist'),
	  filename: '[name].[chunkhash:8].min.js',
	  chunkFilename: '[name].[chunkhash:8].js'
      }
  }
  return settings
}
