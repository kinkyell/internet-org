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
     * @extends BasicState
     * @constructor
     */
    var HomeState = function(options) {
        this.invertRight = true;
        BasicState.call(this);
    };

    HomeState.prototype = Object.create(BasicState.prototype);
    HomeState.prototype.constructor = HomeState;

    HomeState.prototype.COMPONENTS = {
        '.js-carouselView': CarouselView
    };

    HomeState.prototype.activate = function(event) {
        if (event.method !== 'init') {
            //TODO: replace image with narrative stuff
            viewWindow.replaceFeatureImage('http://placehold.it/400x801/eeeeee/888888?text=HOME', 'left');
        }
        BasicState.prototype.activate.call(this, event);
        this.refreshComponents($(document.body));
    };

    return HomeState;

});
