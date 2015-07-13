/*jshint node:true, laxbreak:true */
'use strict';

module.exports = function(grunt) {
    var identity = function(input) { return input; };

    grunt.config.merge({
        sass: {
            buildStyles: {
                files: [{
                    expand: true,
                    cwd: '<%= env.DIR_SRC %>/assets/scss',
                    src: ['*.scss'],
                    dest: '<%= env.DIR_DEST %>/assets/styles',
                    ext: '.css'
                }],
                options: {
                outputStyle: (grunt.option('prod') ? 'compressed' : 'nested')
                }
            }
        },

        postcss: {
            options: {
                processors: [
                    require('pixrem')(), // add fallbacks for rem units
                    require('autoprefixer-core')({
                        browsers: ['ie >= 9', 'iOS >= 7', 'Safari >= 7', 'Android >= 4', 'last 2 versions']
                    }), // add vendor prefixes
                ].filter(identity)
            },
            buildStyles: {
                src: '<%= env.DIR_DEST %>/assets/styles/*.css'
            }
        }

    });

    grunt.registerTask('buildStyles', [
        'sass:buildStyles',
        'postcss:buildStyles'
    ]);
};
