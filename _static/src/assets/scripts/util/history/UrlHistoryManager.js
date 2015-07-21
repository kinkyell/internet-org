define(function(require, exports, module) { // jshint ignore:line
    'use strict';

    var AbstractHistoryManager = require('./AbstractHistoryManager');
    var eventHub = require('services/eventHub');

    /**
     * History manager for the browser history api
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

    /**
     * Pushes state onto history, update url and stored data
     *
     * @method pushState
     * @param {Object} data State to store for history entry
     * @param {String|null} title Page title to update (may be unused)
     * @param {String} url URL to replace current with
     */
    UrlHistoryManager.prototype.pushState = function(data, title, url) {
        window.history.pushState(data, title, url);
    };

    /**
     * Replaces top state in history, update url and stored data
     *
     * @method replaceState
     * @param {Object} data State to store for history entry
     * @param {String|null} title Page title to update (may be unused)
     * @param {String} url URL to replace current with
     */
    UrlHistoryManager.prototype.replaceState = function(data, title, url) {
        window.history.replaceState(data, title, url);
    };

    /**
     * Handle the popstate event
     *
     * @method _handlePopState
     * @param {Event} event PopState event from browser
     * @fires HistoryManager:popState
     */
    UrlHistoryManager.prototype._handlePopState = function(event) {
        eventHub.publish('HistoryManager:popState', event.state);
    };

    /**
     * Move history backward
     *
     * @method back
     */
    UrlHistoryManager.prototype.back = function() {
        return window.history.back();
    };

    /**
     * Move history forward
     *
     * @method forward
     */
    UrlHistoryManager.prototype.forward = function() {
        return window.history.forward();
    };

    return UrlHistoryManager;

});
