define(function(require, exports, module) { // jshint ignore:line
    'use strict';

    var $ = require('jquery');
    var BasicState = require('./BasicState');
    var NarrativeView = require('views/NarrativeView');
    var viewWindow = require('services/viewWindow');
    var VideoModalView = require('views/VideoModalView');

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
        '.js-videoModal': VideoModalView
    };

    /**
     * Activate state
     *
     * @method onActivate
     * @fires State:activate
     */
    HomeState.prototype.onActivate = function(event) {
        if (event.method !== 'init' && this._narrativeView) {
            var lastFeature = this._narrativeView._narrativeManager._currentFeature;
            if (lastFeature) {
                if (lastFeature.type === 'image') {
                    viewWindow.replaceFeatureImage(lastFeature.img, 'left');
                } else {
                    viewWindow.replaceFeatureContent(lastFeature.content, 'left', lastFeature.img);
                }
            }
            this._narrativeView.enable();
        } else {
            this._narrativeView = new NarrativeView($('.js-narrativeView'));
            var defaultImage = this._narrativeView._sectionConf[0].featureImage;
            viewWindow.replaceFeatureImage(defaultImage, 'left');
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
