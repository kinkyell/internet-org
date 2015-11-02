define(function(require, exports, module) { // jshint ignore:line
    'use strict';

    var eventHub = require('services/eventHub');

    /**
     * Manages the stack of active states
     *
     * @class StateStack
     * @constructor
     */
    var StateStack = function(InitialState) {
        var init;
        /**
         * The list of active states
         *
         * @property _activeStates
         * @type Array
         * @private
         * @default []
         */
        this._activeStates = [];

        if (InitialState) {
            init = new InitialState();
            this._activeStates.push(init);
            init.activate({
                method: 'init',
                states: this._activeStates
            });
        }
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
    StateStack.prototype.push = function(StateCtor, options, silent) {
        var stateInstance = new StateCtor(options);
        var prevState = null;

        this._activeStates.push(stateInstance);

        if (this._activeStates.length > 1) {
            prevState = this._activeStates[this._activeStates.length - 2];
            prevState.deactivate({
                method: 'push',
                states: this._activeStates,
                nextState: stateInstance
            });
        }

        /**
         * State pushed event
         *
         * @event StateStack:push
         * @param {AbstractState} state State instance that was pushed
         */
        eventHub.publish('StateStack:push', stateInstance);
        eventHub.publish('StateStack:change', this._activeStates);

        // activate the new state
        stateInstance.activate({
            method: 'push',
            states: this._activeStates,
            prevState: prevState,
            silent: silent
        });
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

        if (this._activeStates.length) {
            this._activeStates[this._activeStates.length - 1].activate({
                method: 'pop',
                states: this._activeStates,
                prevState: stateInstance
            });
        }

        /**
         * State popped event
         *
         * @event StateStack:pop
         * @param {AbstractState} state State instance that was popped
         */
        eventHub.publish('StateStack:pop', stateInstance);
        eventHub.publish('StateStack:change', this._activeStates);

        stateInstance.deactivate({
            method: 'pop',
            states: this._activeStates,
            nextState: this._activeStates[this._activeStates.length - 1]
        });

        return stateInstance;
    };

    /**
     * Swap top state on stack
     *
     * @method swap
     * @param {AbstractState} StateCtor A state constructor to activate
     * @param {Object} options Options to pass to new state
     * @fires StateStack:swap
     * @returns {Object}
     */
    StateStack.prototype.swap = function(StateCtor, options) {
        var prevInstance = this._activeStates.pop();

        var stateInstance = new StateCtor(options);
        this._activeStates.push(stateInstance);

        /**
         * State pushed event
         *
         * @event StateStack:push
         * @param {AbstractState} state State instance that was pushed
         */
        eventHub.publish('StateStack:swap', stateInstance, prevInstance);
        eventHub.publish('StateStack:change', this._activeStates);

        // deactivate old instance
        prevInstance.deactivate({
            method: 'swap',
            states: this._activeStates,
            nextState: stateInstance
        });

        // activate the new state
        stateInstance.activate({
            method: 'swap',
            states: this._activeStates,
            prevState: prevInstance
        });
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

    /**
     * Get top state
     *
     * @method getTop
     * @returns {AbstractState} the top state
     */
    StateStack.prototype.getTop = function() {
        var len = this.size();
        if (!len) {
            return null;
        }
        return this._activeStates[len - 1];
    };

    return StateStack;

});
