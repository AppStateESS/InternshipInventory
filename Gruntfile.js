module.exports = function(grunt) {

    require("load-grunt-tasks")(grunt);

    grunt.initConfig({
        modList: ['createInterface', 'searchInterface'],
        currMod: '',

        clean: {
            all: {
                src: ['javascript/dist', 'javascript/**/build', 'javascript/**/dist']
            },
            one: {
                src: ['javascript/dist/<%= currMod %>.min.*.js', 'javascript/<%= currMod %>/build', 'javascript/<%= currMod %>/dist']
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
                        cwd: 'javascript/<%= currMod %>/',
                        src: ['**/*.jsx'],
                        dest: 'javascript/<%= currMod %>/dist/',
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
                    'javascript/<%= currMod %>/dist/<%= currMod %>.min.js': [ 'javascript/<%= currMod %>/dist/**/*.js' ]
                }
            }
        },
        hash: {
            options: {
                mapping: 'javascript/<%= currMod %>/dist/assets.json', //mapping file so your server can serve the right files
                //srcBasePath: 'examples/', // the base Path you want to remove from the `key` string in the mapping file
                //destBasePath: 'out/', // the base Path you want to remove from the `value` string in the mapping file
                //flatten: false, // Set to true if you don't want to keep folder structure in the `key` value in the mapping file
                hashLength: 8, // hash length, the max value depends on your hash function
                hashFunction: function(source, encoding){ // default is md5
                    return require('crypto').createHash('sha1').update(source, encoding).digest('hex');
                }
            },
            js: {
                src: 'javascript/<%= currMod %>/dist/*.min.js',  //all your js that needs a hash appended to it
                dest: 'javascript/<%= currMod %>/dist/' //where the new files will be created
            }
        },
        watch: {
            files: ['javascript/<%= currMod %>/*.jsx'],
            tasks: ['default']
        }
    });

    grunt.loadNpmTasks('grunt-contrib-clean');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-hash');
    grunt.loadNpmTasks('grunt-contrib-watch');

    if(!grunt.option('module')){
        grunt.fail.fatal('Missing "module" command line paramter. Use --module=yourMoudleName');
    }

    grunt.config.set('currMod', grunt.option('module'));

    grunt.registerTask("default", ['clean', 'babel', 'uglify', 'hash']);
}
