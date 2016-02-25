/**
 * Queues promises-based animations
 *
 * @fileoverview
 */
define(function(require, exports, module) { // jshint ignore:line
    'use strict';

    /**
     * Handles animations that need to be queued with promises
     *
     * @class AnimationQueue
     * @constructor
     */
    var AnimationQueue = function() {
        /**
         * Last animation's promise
         *
         * @property _lastAnimation
         * @type Promise
         * @default Promise
         * @private
         */
        this._lastAnimation = Promise.resolve();
    };

    /**
     * Queue animation after last has ended
     *
     * @method queue
     * @param {Function} callback Animation instigator
     * @param {Object} context `this` arg
     * @returns {Promise} Animation promise
     */
    AnimationQueue.prototype.queue = function(callback, context) {
        this._lastAnimation = this._lastAnimation.then(function() {
            var thisArg = typeof context === 'undefined' ? this : context;
            return callback.apply(thisArg, arguments);
        });
        return this._lastAnimation;
    };

    return AnimationQueue;

});
