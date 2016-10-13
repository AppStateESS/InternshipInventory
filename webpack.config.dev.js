var webpack = require('webpack');
var path = require('path');
var Promise = require('es6-promise').polyfill();
var AssetsPlugin = require('assets-webpack-plugin');

//var APP_DIR = path.resolve(__dirname, '');
var JS_DIR = path.resolve(__dirname, 'javascript');

module.exports = {
    devtool: 'eval',
    entry: {
        createInterface: JS_DIR + '/createInterface/CreateInternshipInterface.jsx',
        searchInterface: JS_DIR + '/searchInterface/SearchInterface.jsx',
        editAdmin: JS_DIR + '/editAdmin/editAdmin.jsx',
        editDepartment: JS_DIR + '/editDepartment/deptEditor.jsx',
        stateList: JS_DIR + '/stateList/StateList.jsx',
        emergencyContact: JS_DIR + '/emergencyContact/EmgContactList.jsx',
        facultyEdit: JS_DIR + '/facultyEdit/FacultyEdit.jsx'
    },
    output: {
        path: path.join(JS_DIR, "dist"),
        filename: "[name].dev.js",
        publicPath: "javascript/dist/"
    },
    externals: {
        "jquery": "$"
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
            loader: 'babel-loader'
        }, {
            test: /\.css$/,
            loader: "style-loader!css-loader"
        }]
    },
    eslint: {
        configFile: path.join(__dirname, '.eslintrc.js'),
        useEslintrc: false
    },
    plugins: [
        new AssetsPlugin({
            filename: 'assets.json',
            prettyPrint: true
        })
    ],
    presets: [
        require.resolve("babel-preset-es2015"),
        require.resolve("babel-preset-react")
    ]
}
