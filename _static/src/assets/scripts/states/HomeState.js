define(function(require, exports, module) { // jshint ignore:line
    'use strict';

    var BasicState = require('./BasicState');
    var viewWindow = require('services/viewWindow');

    /**
     * Manages home state
     *
     * @class HomeState
     * @extends BasicState
     * @constructor
     */
    var HomeState = function(options) {
        BasicState.call(this);
    };

    HomeState.prototype = Object.create(BasicState.prototype);
    HomeState.prototype.constructor = HomeState;

    HomeState.prototype.activate = function(event) {
        if (event.method !== 'init') {
            //TODO: replace image with narrative stuff
            viewWindow.replaceFeatureImage('http://placehold.it/400x801/eeeeee/888888?text=HOME', 'left');
        }
        BasicState.prototype.activate.call(this, event);
    };

    return HomeState;

});
