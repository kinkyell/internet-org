/**
 * Queues promises-based animations
 *
 * @fileoverview
 */
define(function(require, exports, module) { // jshint ignore:line
    'use strict';

    var AnimationQueue = function() {
        this._lastAnimation = Promise.resolve();
    };

    AnimationQueue.prototype.queue = function(callback, context) {
        this._lastAnimation = this._lastAnimation.then(function() {
            var thisArg = typeof context === 'undefined' ? this : context;
            return callback.apply(thisArg, arguments);
        });
        return this._lastAnimation;
    };

    return AnimationQueue;

});
