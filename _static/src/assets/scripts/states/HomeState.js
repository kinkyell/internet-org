define(function(require, exports, module) { // jshint ignore:line
    'use strict';

    var $ = require('jquery');
    var BasicState = require('./BasicState');
    var NarrativeView = require('views/NarrativeView');

    /**
     * Manages home state
     *
     * @class HomeState
     * @param {Object} options State configuration options
     * @extends BasicState
     * @constructor
     */
    var HomeState = function(options) {
        BasicState.call(this, options);
        this.invertRight = true;
    };

    HomeState.prototype = Object.create(BasicState.prototype);
    HomeState.prototype.constructor = HomeState;

    /**
     * List of components to initialize
     * @property COMPONENTS
     * @static
     * @type {Object}
     */
    HomeState.prototype.COMPONENTS = {};

    /**
     * Activate state
     *
     * @method onActivate
     * @fires State:activate
     */
    HomeState.prototype.onActivate = function(event) {
        if (event.method !== 'init' && this._narrativeView) {
            //TODO: replace image with narrative stuff
            this._narrativeView.enable();
        } else {
            this._narrativeView = new NarrativeView($('.js-narrativeView'));
        }

        this.refreshComponents($(document.body));
    };

    /**
     * Deactivate State
     *
     * @method deactivate
     * @fires State:activate
     */
    HomeState.prototype.deactivate = function() {
        this._narrativeView.disable();
    };

    /**
     * Checks for home state
     *
     * @method isHomeState
     * @returns {Boolean}
     */
    HomeState.prototype.isHomeState = function() {
        return true;
    };

    return HomeState;

});
