define(function(require, exports, module) { // jshint ignore:line
    'use strict';

    var AbstractHistoryManager = require('./AbstractHistoryManager');
    var eventHub = require('services/eventHub');

    /**
     * Interface for history manager classes
     *
     * @class UrlHistoryManager
     * @constructor
     */
    var UrlHistoryManager = function() {
        AbstractHistoryManager.call(this);

        window.addEventListener('popstate', this._handlePopState.bind(this));
    };

    UrlHistoryManager.prototype = Object.create(AbstractHistoryManager.prototype);
    UrlHistoryManager.prototype.constructor = UrlHistoryManager;

    UrlHistoryManager.prototype.pushState = function(data, title, url) {
        window.history.pushState(data, title, url);
    };

    UrlHistoryManager.prototype.replaceState = function(data, title, url) {
        window.history.replaceState(data, title, url);
    };

    UrlHistoryManager.prototype._handlePopState = function(event) {
        eventHub.publish('HistoryManager:popState', event.state);
    };

    UrlHistoryManager.prototype.back = function() {
        return window.history.back();
    };

    UrlHistoryManager.prototype.forward = function() {
        return window.history.forward();
    };

    return UrlHistoryManager;

});
