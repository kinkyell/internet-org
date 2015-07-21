define(function(require, exports, module) { // jshint ignore:line
    'use strict';

    var AbstractHistoryManager = require('./AbstractHistoryManager');

    /**
     * Falls back to no-js behavior for history management
     *
     * @class FallbackHistoryManager
     * @constructor
     */
    var FallbackHistoryManager = function() {
        AbstractHistoryManager.call(this);
    };

    FallbackHistoryManager.prototype = Object.create(AbstractHistoryManager.prototype);
    FallbackHistoryManager.prototype.constructor = FallbackHistoryManager;

    return FallbackHistoryManager;

});
