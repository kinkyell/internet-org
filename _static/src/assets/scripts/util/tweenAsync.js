/**
 * Provides helpful wrapper for class dictionaries
 *
 * @fileoverview
 */
define(function(require, exports, module) { // jshint ignore:line
    'use strict';

    var tween = require('gsap-tween');

    function getPromise(method) {
        return function(element, duration, opts) {
            var complete = opts.onComplete;
            return new Promise(function(resolve) {
                opts.onComplete = function(args) {
                    if (complete) {
                        complete.apply(this, args);
                    }
                    resolve();
                };
                tween[method](element, duration, opts);
            });
        };
    }

    return {
        to: getPromise('to'),
        from: getPromise('from')
    };

});
