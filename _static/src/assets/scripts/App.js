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
        search: SearchState
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
        this.states = new StateStack(HomeState);

        eventHub.subscribe('Router:stateChange', this._handleStateChange);
    };

    /**
     * Checks if browser has necessary features to run application
     *
     * @method _onStateChange
     * @private
     */
    proto._onStateChange = function(states, previousStates, silent) {
        var lastState = states[states.length - 1] || {
            type: 'home'
        };
        var fromHome = this.states.getTop() instanceof HomeState;
        var toHome;
        var stateCtor = STATE_TYPES[lastState.type] || PanelState;

        if (states.length > previousStates.length) {
            // navigating forward
            console.log('forward', lastState.path);
            this.states.push(stateCtor, lastState, silent);
        } else if (states.length < previousStates.length) {
            console.log('backward');
            this.states.pop();
        } else {
            console.log('swap');
            this.states.swap(stateCtor, lastState);
        }
        console.log(this.states);

        // if going to or from home we need to shift over
        toHome = this.states.getTop() instanceof HomeState;
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
    proto._preloadImages = function(states, previousStates) {
        var stateLinkImgs = Array.prototype.map.call($('.js-stateLink'), function(el) {
            return el.getAttribute('data-image');
        }).filter(identity);

        assetLoader.loadImages(stateLinkImgs);
    };

    return App;

});