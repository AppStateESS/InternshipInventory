var webpack = require('webpack');
var path = require('path');
var Promise = require('es6-promise').polyfill();

var APP_DIR = path.resolve(__dirname, 'javascript');

module.exports = {
    entry: {
        createInterface: APP_DIR + '/createInterface/CreateInternshipInterface.jsx',
        searchInterface: APP_DIR + '/searchInterface/search.jsx',
        editAdmin: APP_DIR + '/editAdmin/editAdmin.jsx'
    },
    output: {
        path: path.join(APP_DIR, "dist"),
        filename: "[name].dev.js"
    },
    module: {
        loaders: [{
            test: /\.jsx?/,
            include: APP_DIR,
            loader: 'babel'
        }, {
            test: /\.css$/,
            loader: "style-loader!css-loader"
        }]
    },
    devtool: 'source-map'
}
