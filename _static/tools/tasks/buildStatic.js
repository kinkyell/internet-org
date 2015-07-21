/*jshint node:true */
'use strict';

module.exports = function(grunt) {
    grunt.config.merge({
        copy: {
            buildStatic: {
                files: [{
                    expand: true,
                    cwd: '<%= env.DIR_SRC %>',
                    src: [
                        '**/.htaccess',
                        '**/*.{asp,aspx,cshtml,jsp,php,py,rb,txt}',
                        'assets/media/**',
                        '!assets/vendor/**'
                    ],
                    dest: '<%= env.DIR_DEST %>'
                }]
            }
        },

        grunticon: {
            buildStatic: {
                files: [{
                    expand: true,
                    cwd: '<%= env.DIR_SRC %>/assets/media/images/icons',
                    src: ['*.svg', '*.png'],
                    dest: '<%= env.DIR_DEST %>/assets/media/images/icons'
                }],
                options: {
                    enhanceSVG: true,
                    cssprefix: '.icon-',
                    compressPNG: grunt.option('prod')
                }
            }
        }
    });

    grunt.registerTask('buildStatic', [
        'copy:buildStatic'
    ]);

    grunt.registerTask('buildIcons', [
        'grunticon:buildStatic'
    ]);
};
