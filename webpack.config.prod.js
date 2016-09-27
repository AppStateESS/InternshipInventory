var webpack = require('webpack');
var WebpackStripLoader = require('strip-loader');
var path = require('path');
var Promise = require('es6-promise').polyfill();

var APP_DIR = path.resolve(__dirname, 'javascript');

module.exports = {
    // Don't attempt to continue if there are any errors
    bail: true,
    devtool: 'source-map',
    entry: {
        createInterface: APP_DIR + '/create.jsx',
        searchInterface: APP_DIR = '/serchInterface/search.jsx'
        editAdmin: APP_DIR + '/editAdmin/editAdmin.jsx'
    },
    output: {
        path: path.join(APP_DIR, "dist"),
        filename: "[name]-[hash].min.js",
        chunkFilename: '[name].[chunkhash:8].chunk.js'
    },
    module: {
        loaders: [{
            enforce: "pre",
            test: /\.(js|jsx)$/,
            loader: "eslint",
            include: JS_DIR + "/*"
        }, {
            test: /\.jsx?/,
            include: APP_DIR,
            loader: 'babel'
        }, {
            test: [/\.js$/, /\.es6$/, /\.jsx$/],
            exclude: /node_modules/,
            loader: WebpackStripLoader.loader('console.log')
        }, {
            test: /\.css$/,
            loader: "style-loader!css-loader"
        }]
    },
    plugins: [
        new webpack.DefinePlugin({
            'process.env': {
                'NODE_ENV': JSON.stringify('production')
            }
        }),
        new webpack.optimize.UglifyJsPlugin({
            compress: {
                screw_ie8: true, // React doesn't support IE8 anyway
                warnings: true
            },
            mangle: {
                screw_ie8: true
            },
            output: {
                comments: false,
                screw_ie8: true
            }
        })
    ]
}
