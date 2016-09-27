var webpack = require('webpack');
var WebpackStripLoader = require('strip-loader');
var path = require('path');
var Promise = require('es6-promise').polyfill();

var APP_DIR = path.resolve(__dirname, 'javascript');

module.exports = {
    entry: {
        createInterface: APP_DIR + '/create.jsx',
        searchInterface: APP_DIR = '/serchInterface/search.jsx'
        editAdmin: APP_DIR + '/editAdmin/editAdmin.jsx'
    },
    output: {
        path: path.join(APP_DIR, "dist"),
        filename: "[name].prod.js"
    },
    module: {
        loaders: [{
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
                warnings: true
            }
        })
    ]
}
