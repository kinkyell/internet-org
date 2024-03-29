/*jshint node:true, laxbreak:true */
'use strict';

module.exports = function(grunt) {
    grunt.config.merge({
        // Automatically adds/updates bower modules in the
        // config.js file used by RequireJS
        bowerRequirejs: {
            injectScripts: {
                options: {
                    exclude: ['modernizr']
                },
                // Path of shared configuration file
                rjsConfig: '<%= env.DIR_SRC %>/assets/scripts/config.js'
            }
        }
    });

    grunt.registerTask('injectScripts',
        function() {
            grunt.task.run('bowerRequirejs:injectScripts');

            grunt.log.ok(
                'Success! Injected a reference to each library in bower.json to your source code.\n' +
                'Please review config.js and add shim entries where appropriate.'
            );
        }
    );
};
