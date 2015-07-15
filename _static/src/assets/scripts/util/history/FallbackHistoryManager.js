define(function(require, exports, module) { // jshint ignore:line
    'use strict';

    var AbstractHistoryManager = require('./AbstractHistoryManager');

    /**
     * Interface for history manager classes
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
