var webpack = require('webpack');
var path = require('path');
var Promise = require('es6-promise').polyfill();
var AssetsPlugin = require('assets-webpack-plugin');
var entryPointList = require(__dirname + '/entryPoints.js');

//var APP_DIR = path.resolve(__dirname, '');
var JS_DIR = path.resolve(__dirname, 'javascript');



module.exports = {
    devtool: 'eval',
    entry: entryPointList.entryPoints,
    output: {
        path: path.join(JS_DIR, "dist"),
        filename: "[name].dev.js",
        publicPath: "javascript/dist/"
    },
    externals: {
        "jquery": "$"
    },
    module: {
        rules: [
        {
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
        new AssetsPlugin({
            filename: 'assets.json',
            prettyPrint: true
        }),
        //new webpack.optimize.CommonsChunkPlugin("vendor", "vendor.bundle.js")
        new webpack.optimize.CommonsChunkPlugin({ name: 'vendor', filename: 'vendor.bundle.js' })

    ]
}
