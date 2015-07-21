define(function(require, exports, module) { // jshint ignore:line
    'use strict';

    // polyfill promises
    var ES6Promise = require('promise');
    ES6Promise.polyfill();

    require('modernizr');
    require('services/apiService');

    require('gsap-cssPlugin');
    require('gsap-timeline');

    var Router = require('services/Router');

    var StateStack = require('services/StateStack');
    var PanelState = require('states/PanelState');

    var eventHub = require('services/eventHub');

    /**
     * Initial application setup. Runs once upon every page load.
     *
     * @class App
     * @constructor
     */
    var App = function() {
        if (!this._cutsTheMustard()) {
            return;
        }
        this.init();
    };

    var proto = App.prototype;

    /**
     * Initializes the application and kicks off loading of prerequisites.
     *
     * @method init
     * @private
     */
    proto.init = function() {
        this._handleStateChange = this._onStateChange.bind(this);
        this.router = new Router();

        this._setupStates();
    };

    /**
     * Checks if browser has necessary features to run application
     *
     * @method _cutsTheMustard
     * @private
     */
    proto._cutsTheMustard = function() {
        if (
            (typeof Object.getPrototypeOf !== 'function') ||
            (typeof Function.prototype.bind !== 'function')
        ) {
            return false;
        }

        return true;
    };

    /**
     * Checks if browser has necessary features to run application
     *
     * @method _setupStates
     * @private
     */
    proto._setupStates = function() {
        this.states = new StateStack();

        eventHub.subscribe('Router:stateChange', this._handleStateChange);
    };

    /**
     * Checks if browser has necessary features to run application
     *
     * @method _onStateChange
     * @private
     */
    proto._onStateChange = function(states, previousStates) {
        if (states.length > previousStates.length) {
            // navigating forward
            console.log('forward', states[states.length - 1]);
            this.states.push(PanelState, {
                stateName: states[states.length - 1]
            });
        } else if (states.length < previousStates.length) {
            console.log('backward');
            this.states.pop();
        } else {
            console.log('swap');
            this.states.swap(PanelState, {
                stateName: states[states.length - 1]
            });
        }
        console.log(this.states);
    };

    return App;

});
