define(function(require, exports, module) { // jshint ignore:line
    'use strict';

    // polyfill promises
    var ES6Promise = require('promise');
    ES6Promise.polyfill();

    require('modernizr');
    require('services/apiService');
    var $ = require('jquery');

    require('gsap-cssPlugin');
    require('gsap-timeline');

    var Router = require('services/Router');

    var StateStack = require('services/StateStack');
    var PanelState = require('states/PanelState');
    var HeaderView = require('views/HeaderView');

    var eventHub = require('services/eventHub');
    var viewWindow = require('services/viewWindow');

    var FastClick = require('fastclick');
    FastClick.attach(document.body);

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
        this.headerView = new HeaderView($('.js-headerView'));
        this.viewWindow = viewWindow;

        this._setupStates();

        //TODO: remove test code
        var vw = $('.js-viewWindow');
        var isShifted = false;

        vw.on('click', function() {
            var prom;
            var img;

            if (isShifted) {
                img = 'http://placehold.it/400x801/eeeeee/888888?text=first+panel';
                prom = viewWindow.replaceFeatureImage(img, 'right', true);
                viewWindow.shift();
            } else {
                img = 'http://placehold.it/400x800?text=second+panel';
                prom = viewWindow.replaceFeatureImage(img, 'right', false);
                viewWindow.shift();
            }

            prom.then(function() {
                isShifted = !isShifted;
            });
        });
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
