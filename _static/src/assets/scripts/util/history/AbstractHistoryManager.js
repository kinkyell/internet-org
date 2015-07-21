define(function(require, exports, module) { // jshint ignore:line
    'use strict';

    /**
     * Interface for history manager classes
     *
     * @class AbstractHistoryManager
     * @throws {TypeError} Throws TypeError is initialized directly
     * @constructor
     */
    var AbstractHistoryManager = function() {
        var thisProto = Object.getPrototypeOf(this);
        if (thisProto === AbstractHistoryManager.prototype) {
            throw new TypeError('AbstractHistoryManager should not be initialized directly.');
        }
    };

    /**
     * Pushes state onto history, update url and stored data
     *
     * @method pushState
     * @param {Object} data State to store for history entry
     * @param {String|null} title Page title to update (may be unused)
     * @param {String} url URL to replace current with
     */
    AbstractHistoryManager.prototype.pushState = function(data, title, url) {};

    /**
     * Replaces top state in history, update url and stored data
     *
     * @method replaceState
     * @param {Object} data State to store for history entry
     * @param {String|null} title Page title to update (may be unused)
     * @param {String} url URL to replace current with
     */
    AbstractHistoryManager.prototype.replaceState = function(data, title, url) {};

    /**
     * Move history backward
     *
     * @method back
     */
    AbstractHistoryManager.prototype.back = function() {};

    /**
     * Move history forward
     *
     * @method forward
     */
    AbstractHistoryManager.prototype.forward = function() {};

    return AbstractHistoryManager;

});
