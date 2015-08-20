define(function(require, exports, module) { // jshint ignore:line
    'use strict';

    var BasicState = require('./BasicState');
    var viewWindow = require('services/viewWindow');
    var $ = require('jquery');

    var CarouselView = require('views/CarouselView');

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
    HomeState.prototype.COMPONENTS = {
        '.js-carouselView': CarouselView
    };

    /**
     * Activate state
     *
     * @method onActivate
     * @fires State:activate
     */
    HomeState.prototype.onActivate = function(event) {
        if (event.method !== 'init') {
            //TODO: replace image with narrative stuff
            console.log('this stuff will not happen on state load');
        }

        this.refreshComponents($(document.body));
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
