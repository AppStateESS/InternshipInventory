module.exports = function(grunt) {

    require("load-grunt-tasks")(grunt);

    grunt.initConfig({
        clean: {
            all: {
                src: ['javascript/createInterface/dist']
            }
        },
        "babel": {
            options: {
                sourceMap: true
            },
            dist: {
                files: [
                    {
                        expand: true,
                        cwd: 'javascript/createInterface/',
                        src: ['**/*.jsx'],
                        dest: 'javascript/createInterface/dist/',
                        ext: '.js'
                    }
                ]
            }
        },
        uglify: {
            build: {
                options: {
                    mangle: false
                },
                files: {
                    'javascript/createInterface/dist/CreateInterface.min.js': [ 'javascript/createInterface/dist/**/*.js' ]
                }
            }
        },
        hash: {
            options: {
                mapping: 'javascript/createInterface/dist/assets.json', //mapping file so your server can serve the right files
                //srcBasePath: 'examples/', // the base Path you want to remove from the `key` string in the mapping file
                //destBasePath: 'out/', // the base Path you want to remove from the `value` string in the mapping file
                //flatten: false, // Set to true if you don't want to keep folder structure in the `key` value in the mapping file
                hashLength: 8, // hash length, the max value depends on your hash function
                hashFunction: function(source, encoding){ // default is md5
                    return require('crypto').createHash('sha1').update(source, encoding).digest('hex');
                }
            },
            js: {
                src: 'javascript/createInterface/dist/*.min.js',  //all your js that needs a hash appended to it
                dest: 'javascript/createInterface/dist/' //where the new files will be created
            }
        }
    });

    grunt.loadNpmTasks('grunt-contrib-clean');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-hash');

    //grunt.registerTask('clean', 'all');

    grunt.registerTask("default", ['clean', 'babel', 'uglify', 'hash']);
}
