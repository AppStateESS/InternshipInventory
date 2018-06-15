var webpack = require('webpack');
var WebpackStripLoader = require('strip-loader');
var path = require('path');
var Promise = require('es6-promise').polyfill();
var AssetsPlugin = require('assets-webpack-plugin');
var entryPointList = require(__dirname + '/entryPoints.js');

//var APP_DIR = path.resolve(__dirname, '');
var JS_DIR = path.resolve(__dirname, 'javascript');

module.exports = {
    // Don't attempt to continue if there are any errors
    bail: true,
    devtool: 'source-map',
    entry: entryPointList.entryPoints,
    output: {
        path: path.join(JS_DIR, "dist"),
        filename: "[name].[chunkhash:8].min.js",
        chunkFilename: '[name].[chunkhash:8].chunk.js',
        publicPath: "javascript/dist/"
    },
    externals: {
        "jquery": "$"
    },
    module: {
        rules: [{
            enforce: "pre",
            test: /\.(js|jsx)$/,
            use: [
              {
                loader: "eslint-loader",
                options: {configFile: path.join(__dirname, '.eslintrc.js'),
                          useEslintrc: false}
              }
            ],
            include: JS_DIR
        }, {
            test: /\.(js|jsx)$/,
            include: JS_DIR,
            use: [
              {
                loader: 'babel-loader',
                query: {presets: ['es2015', 'react']}
              }
            ]

        }, {
            test: [/\.js$/, /\.es6$/, /\.jsx$/],
            exclude: /node_modules/,
            loader: WebpackStripLoader.loader('console.log')
        }, {
            test: /\.css$/,
            use: [
              {
                loader: "style-loader"
              },
              {
                loader: "css-loader"
              }
            ]
        }]
    },
    plugins: [
        new webpack.DefinePlugin({
            'process.env': {
                'NODE_ENV': JSON.stringify('production')
            }
        }),
        //new webpack.optimize.CommonsChunkPlugin("vendor", "vendor.[chunkhash:8].bundle.js"),
        new webpack.optimize.CommonsChunkPlugin({ name: 'vendor', filename: 'vendor.[chunkhash:8].bundle.js' }),
        new webpack.optimize.UglifyJsPlugin({
            compress: {
                screw_ie8: true, // React doesn't support IE8 anyway
                warnings: false
            },
            mangle: {
                screw_ie8: true
            },
            output: {
                comments: false,
                screw_ie8: true
            }
        }),
        new AssetsPlugin({
            filename: 'assets.json',
            prettyPrint: true
        })    ]
}
