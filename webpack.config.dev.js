var webpack = require('webpack');
var path = require('path');
var Promise = require('es6-promise').polyfill();

var APP_DIR = path.resolve(__dirname, '');
var JS_DIR = path.resolve(__dirname, 'javascript');

module.exports = {
    devtool: 'eval',
    entry: {
        createInterface: JS_DIR + '/createInterface/CreateInternshipInterface.jsx',
        searchInterface: JS_DIR + '/searchInterface/search.jsx',
        editAdmin: JS_DIR + '/editAdmin/editAdmin.jsx'
    },
    output: {
        path: path.join(JS_DIR, "dist"),
        filename: "[name].dev.js"
    },
    module: {
        loaders: [
        {
            enforce: "pre",
            test: /\.(js|jsx)$/,
            loader: "eslint",
            include: JS_DIR
        }, {
            test: /\.(js|jsx)$/,
            include: JS_DIR,
            loader: 'babel'
        }, {
            test: /\.css$/,
            loader: "style-loader!css-loader"
        }]
    },
    eslint: {
        configFile: path.join(__dirname, '.eslintrc.js'),
        useEslintrc: false
    },
}
