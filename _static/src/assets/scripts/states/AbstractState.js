define(function(require, exports, module) { // jshint ignore:line
    'use strict';

    var eventHub = require('services/eventHub');

    /**
     * Abstract Base State
     *
     * @class AbstractState
     * @throws {Error} Errors if instantiated directly
     * @constructor
     */
    var AbstractState = function() {
        var thisProto = Object.getPrototypeOf(this);
        if (thisProto === AbstractState.prototype) {
            throw new TypeError('AbstractState should not be initialized directly.');
        }

        this.active = false;
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
        eventHub.publish('State:activate', this);
    };

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
        eventHub.publish('State:deactivate', this);
    };

    return AbstractState;

});
