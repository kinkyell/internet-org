define(function(require, exports, module) { // jshint ignore:line
    'use strict';

    var eventHub = require('services/eventHub');
    var noop = require('stark/function/noop');

    /**
     * Abstract Base State
     *
     * @class AbstractState
     * @param {Object} options State configuration options
     * @throws {Error} Errors if instantiated directly
     * @constructor
     */
    var AbstractState = function(options) {
        var thisProto = Object.getPrototypeOf(this);
        if (thisProto === AbstractState.prototype) {
            throw new TypeError('AbstractState should not be initialized directly.');
        }

        this.active = false;
        this._options = options || {};
    };

    /**
     * Activate state
     *
     * @method activate
     * @fires State:activate
     */
    AbstractState.prototype.activate = function() {
        /**
         * State activate event
         *
         * @event State:activate
         * @param {AbstractState} state State instance that is activated
         */
        this.active = true;
        this.onActivate.apply(this, arguments);
        eventHub.publish('State:activate', this);
    };

    /**
     * Fires on state activation
     *
     * @method onActivate
     */
    AbstractState.prototype.onActivate = noop;

    /**
     * Deactivate State
     *
     * @method deactivate
     * @fires State:activate
     */
    AbstractState.prototype.deactivate = function() {
        /**
         * State deactivate event
         *
         * @event State:deactivate
         * @param {AbstractState} state State instance that is deactivated
         */
        this.active = false;
        this.onDeactivate.apply(this, arguments);
        eventHub.publish('State:deactivate', this);
    };

    /**
     * Fires on state deactivation
     *
     * @method onDeactivate
     */
    AbstractState.prototype.onDeactivate = noop;

    return AbstractState;

});
