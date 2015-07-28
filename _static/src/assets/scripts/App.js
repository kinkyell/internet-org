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
    var Tween = require('gsap-tween');

    var Router = require('services/Router');

    var StateStack = require('services/StateStack');
    var PanelState = require('states/PanelState');
    var HeaderView = require('views/HeaderView');

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
        this.headerView = new HeaderView($('.js-headerView'));
        //this.viewController = new ViewController('.js-view');

        this._setupStates();

        var viewWindow = $('.js-viewWindow');
        var isShifted = false;
        var $panel = $('<div class="viewWindow-panel-content" style="background: #dddddd;">Hello</div>');

        viewWindow.on('click', function() {
            viewWindow.toggleClass('isShifted');
            var feat = viewWindow.find('.viewWindow-panel_feature');
            var isMobile = require('services/breakpointManager').isMobile;

            if (isShifted) {
                Tween.from(viewWindow[0], 0.5, {
                    xPercent: isMobile ? -50 : -33.333
                });

                Tween.to($panel[0], 0.5, {
                    xPercent: 100,
                    onComplete: function() {
                        $panel.detach();
                    }
                });
            } else {
                feat.append($panel);

                Tween.from(viewWindow[0], 0.5, {
                    xPercent: isMobile ? 50 : 33.333
                });

                Tween.set($panel[0], {
                    xPercent: 0
                });
                Tween.from($panel[0], 0.5, {
                    xPercent: 100
                });
            }

            isShifted = !isShifted;
        })
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
