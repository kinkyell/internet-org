define(function(require, exports, module) { // jshint ignore:line
    'use strict';

    // polyfill promises
    var ES6Promise = require('promise');
    ES6Promise.polyfill();
    require('util/polyfills/index');

    require('modernizr');
    require('services/apiService');
    var $ = require('jquery');
    require('jquery-swipebox');
    require('intl-tel-input');

    // require all gsap plugins so they get registered correctly
    require('gsap-easePack');
    require('gsap-cssPlugin');
    require('gsap-timeline');
    require('gsap-tween');

    var Router = require('services/Router');

    var StateStack = require('services/StateStack');
    var HomeState = require('states/HomeState');
    var PanelState = require('states/PanelState');
    var SearchState = require('states/SearchState');
    var TitledState = require('states/TitledState');
    var HeaderView = require('views/HeaderView');
    var eventHub = require('services/eventHub');
    var assetLoader = require('services/assetLoader');
    var viewWindow = require('services/viewWindow');

    var identity = require('stark/function/identity');
    var UIOrientationUtil = require('util/UIOrientationUtil');

    var STATE_TYPES = {
        panel: PanelState,
        home: HomeState,
        search: SearchState,
        titled: TitledState
    };

    // ios cache fix http://stackoverflow.com/questions/11979156/mobile-safari-back-button
    window.onpageshow = function(event) {
        if (event.persisted) {
            window.location.reload();
        }
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
        this._preloadImages(); // load images initially on the page
        this._setupPhoneValidation();

        this.UIOrientationUtil = new UIOrientationUtil();

        window.addEventListener('touchstart', function setHasTouch () {
            // add touch class to the html/body stuff
            $('html').addClass('touch');

            window.removeEventListener('touchstart', setHasTouch);
        }, false);
    };

    /**
     * Checks if browser has necessary features to run application
     *
     * @method _cutsTheMustard
     * @private
     */
    proto._cutsTheMustard = function() {
        if (
            (typeof Function.prototype.bind !== 'function') //TODO: add more? polyfill?
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
        this._setupPhoneValidation();
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

    // Contact Form Phone Number Validation
    proto._setupPhoneValidation = function() {
        var lib = '/wp-content/themes/vip/prj-internetorg/_static/web/assets/vendor/intl-tel-input/lib/libphonenumber/build/utils.js';
        var options = {
            dropdownContainer: false,
            utilsScript: lib
        };
        $( 'input[name$="-phonenumber"]' ).intlTelInput( options );
    };
    proto._setupPhoneValidation();

    return App;

});
