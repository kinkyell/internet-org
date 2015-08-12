/**
 * Log method
 *
 * @fileoverview
 */
define(function(require, exports, module) { // jshint ignore:line
    'use strict';

    var log = function() {
        if (console.log) {
            console.log.apply(console, arguments);
        }
    };

    return log;

});
