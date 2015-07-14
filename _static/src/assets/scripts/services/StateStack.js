define(function(require, exports, module) { // jshint ignore:line
    'use strict';

    var eventHub = require('services/eventHub');

    /**
     * Manages the stack of active states
     *
     * @class StateStack
     * @constructor
     */
    var StateStack = function() {
        /**
         * The list of active states
         *
         * @property _activeStates
         * @type Array
         * @private
         * @default []
         */
        this._activeStates = [];
    };

    /**
     * Activate a state on top of the state
     *
     * @method push
     * @param {AbstractState} StateCtor A state constructor to activate
     * @param {Object} options Options to pass to new state
     * @fires StateStack:push
     * @returns {Object}
     */
    StateStack.prototype.push = function(StateCtor, options) {
        var stateInstance = new StateCtor(options);
        this._activeStates.push(stateInstance);

        /**
         * State pushed event
         *
         * @event StateStack:push
         * @param {AbstractState} state State instance that was pushed
         */
        eventHub.publish('StateStack:push', stateInstance);

        // activate the new state
        stateInstance.activate();
    };

    /**
     * Remove state from the state stack
     *
     * @method pop
     * @param {AbstractState} StateCtor A state constructor to activate
     * @param {Object} options Options to pass to new state
     * @fires StateStack:pop
     * @returns {Object}
     */
    StateStack.prototype.pop = function() {
        var stateInstance = this._activeStates.pop();

        /**
         * State popped event
         *
         * @event StateStack:pop
         * @param {AbstractState} state State instance that was popped
         */
        eventHub.publish('StateStack:pop', stateInstance);

        stateInstance.deactivate();

        return stateInstance;
    };

    /**
     * Get total number of active states
     *
     * @method size
     * @returns {Number} the number of states on stack
     */
    StateStack.prototype.size = function() {
        return this._activeStates.length;
    };

    return StateStack;

});
