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
    var HomeState = require('states/HomeState');
    var PanelState = require('states/PanelState');
    var SearchState = require('states/SearchState');
    var TitledState = require('states/TitledState');
    var NarrativeView = require('views/NarrativeView');
    var HeaderView = require('views/HeaderView');
    var SelectView = require('views/SelectView');
    var LanguageView = require('views/LanguageView');
    var eventHub = require('services/eventHub');
    var assetLoader = require('services/assetLoader');
    var viewWindow = require('services/viewWindow');

    var identity = require('stark/function/identity');

    var FastClick = require('fastclick');
    FastClick.attach(document.body);

    var STATE_TYPES = {
        panel: PanelState,
        home: HomeState,
        search: SearchState,
        titled: TitledState
    };

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
        this.headerView = new HeaderView($('.js-headerView'));
        this.viewWindow = viewWindow;
        this._setupStates();
        this.router = new Router();

        this.narrativeView = new NarrativeView($('.js-narrativeView'));
        debugger;
        // register global components
        $('select.js-select').each(function(idx, el) {
            return new SelectView($(el));
        });
        this.langView = new LanguageView($('#js-LanguageView'));

        this._preloadImages();
    };

    /**
     * Checks if browser has necessary features to run application
     *
     * @method _cutsTheMustard
     * @private
     */
    proto._cutsTheMustard = function() {
        // polyfill getPrototype of
        if ( typeof Object.getPrototypeOf !== 'function' ) {
            if ( typeof 'test'.__proto__ === 'object' ) { //jshint ignore:line
                Object.getPrototypeOf = function(object){
                    return object.__proto__; //jshint ignore:line
                };
            } else {
                Object.getPrototypeOf = function(object){
                    // May break if the constructor has been tampered with
                    return object.constructor.prototype;
                };
            }
        }

        if (
            (typeof Function.prototype.bind !== 'function')
        ) {
            return false;
        }

        return true;
    };

    /**
     * Set up state stack
     *
     * @method _setupStates
     * @private
     */
    proto._setupStates = function() {
        this.states = new StateStack(HomeState);

        eventHub.subscribe('Router:stateChange', this._handleStateChange);
    };

    /**
     * Make updates to state stack on router changes
     *
     * @method _onStateChange
     * @param {Array} states Array of current state objects
     * @param {Array} previousStates Array of previous state objects
     * @param {Boolean} silent Run without animation flag
     * @private
     */
    proto._onStateChange = function(states, previousStates, silent) {
        var lastState = states[states.length - 1] || {
            type: 'home'
        };
        var fromHome = this.states.getTop().isHomeState();
        var toHome;
        var stateCtor = STATE_TYPES[lastState.type] || PanelState;

        if (states.length > previousStates.length) {
            this.states.push(stateCtor, lastState, silent);
        } else if (states.length < previousStates.length) {
            this.states.pop();
        } else {
            this.states.swap(stateCtor, lastState);
        }

        // if going to or from home we need to shift over
        toHome = this.states.getTop().isHomeState();
        if (fromHome || toHome) {
            viewWindow.shift(silent);
        }

        this._preloadImages();
    };

    /**
     * Preload page images
     *
     * @method _preloadImages
     * @private
     */
    proto._preloadImages = function() {
        var stateLinkImgs = Array.prototype.map.call($('.js-stateLink'), function(el) {
            return el.getAttribute('data-image');
        }).filter(identity);

        assetLoader.loadImages(stateLinkImgs);
    };

    return App;

});
