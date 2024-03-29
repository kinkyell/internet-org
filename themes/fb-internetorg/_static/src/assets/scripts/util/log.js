/**
 * Log method
 *
 * @fileoverview
 */
define(function(require, exports, module) { // jshint ignore:line
    'use strict';

    /**
     * Log function
     *
     * Sends arguments to console log if available
     */
    var log = function() {
        if (window.SETTINGS.LOG_CONSOLE && console && typeof console.log === 'function') {
            console.log.apply(console, arguments);
        }
    };

    return log;

});
