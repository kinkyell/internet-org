define(function(require, exports, module) { // jshint ignore:line
    'use strict';

    /**
     * Interface for history manager classes
     *
     * @class AbstractHistoryManager
     * @constructor
     */
    var AbstractHistoryManager = function() {
        var thisProto = Object.getPrototypeOf(this);
        if (thisProto === AbstractHistoryManager.prototype) {
            throw new TypeError('AbstractHistoryManager should not be initialized directly.');
        }
    };

    AbstractHistoryManager.prototype.pushState = function(data, title, url) {};

    AbstractHistoryManager.prototype.replaceState = function(data, title, url) {};

    AbstractHistoryManager.prototype.back = function() {};

    AbstractHistoryManager.prototype.forward = function() {};

    return AbstractHistoryManager;

});
