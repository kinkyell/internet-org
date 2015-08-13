define(function(require, exports, module) { // jshint ignore:line
    'use strict';

    // polyfill promises
    var ES6Promise = require('promise');
    ES6Promise.polyfill();
    require('util/polyfills/index');

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
        // bind methods
        this._handleStateChange = this._onStateChange.bind(this);

        this._setupLayout();
        this._setupStates();

        // load images initially on the page
        this._preloadImages();
    };

    /**
     * Checks if browser has necessary features to run application
     *
     * @method _cutsTheMustard
     * @private
     */
    proto._cutsTheMustard = function() {
        if (
            (typeof Function.prototype.bind !== 'function')
        ) {
            return false;
        }

        return true;
    };

    /**
     * Set up view window
     *
     * @method _setupLayout
     * @private
     */
    proto._setupLayout = function() {
        this.headerView = new HeaderView($('.js-headerView'));
        this.viewWindow = viewWindow;

        //TODO: move this to home state
        this.narrativeView = new NarrativeView($('.js-narrativeView'));
    };

    /**
     * Set up state stack
     *
     * @method _setupStates
     * @private
     */
    proto._setupStates = function() {
        this.stateStack = new StateStack(HomeState);

        eventHub.subscribe('Router:stateChange', this._handleStateChange);
        this.router = new Router();
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
        var isFromHome = this.stateStack.getTop().isHomeState();
        var isToHome;
        var stateConstructor = STATE_TYPES[lastState.type] || PanelState;

        if (states.length > previousStates.length) {
            this.stateStack.push(stateConstructor, lastState, silent);
        } else if (states.length < previousStates.length) {
            this.stateStack.pop();
        } else {
            this.stateStack.swap(stateConstructor, lastState);
        }

        // if going to or from home we need to shift over
        isToHome = this.stateStack.getTop().isHomeState();
        if (isFromHome || isToHome) {
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

        // assetLoader filters out already loaded images, don't worry
        assetLoader.loadImages(stateLinkImgs);
    };

    return App;

});
