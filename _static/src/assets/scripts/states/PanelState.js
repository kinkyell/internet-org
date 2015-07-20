define(function(require, exports, module) { // jshint ignore:line
    'use strict';

    var BasicState = require('./BasicState');
    var $ = require('jquery');
    var apiService = require('services/apiService');
    var Tween = require('gsap-tween');

    var PANEL_ANIMATE_SPEED = 0.3;
    var PANEL_TEMPLATE = '<div class="panel panel_isLoading"><button type="button" class="js-stateBack">Back</button><a href="/deeper" class="js-stateLink">Go deeper</a></div>';

    /**
     * Manages the stack of active states
     *
     * @class PanelState
     * @constructor
     */
    var PanelState = function(options) {
        this._handlePanelContentLoad = this._onPanelContentLoad.bind(this);
        BasicState.call(this);
    };

    PanelState.prototype = Object.create(BasicState.prototype);
    PanelState.prototype.constructor = PanelState;

    PanelState.prototype.activate = function() {
        this.$panelContent = $(PANEL_TEMPLATE).appendTo('body');

        apiService.getExamplePage().then(this._handlePanelContentLoad);

        this.tween = Tween.from(this.$panelContent[0], PANEL_ANIMATE_SPEED, {
            xPercent: 100,
            onReverseComplete: function() {
                this.$panelContent.remove();
            },
            callbackScope: this
        });

        BasicState.prototype.activate.call(this);
    };

    PanelState.prototype._onPanelContentLoad = function(markup) {
        if (!this.active) {
            return;
        }
        var $markup = $(markup);
        this.$panelContent.append($markup).removeClass('panel_isLoading');
    };

    PanelState.prototype.deactivate = function() {
        this.tween.reverse();

        BasicState.prototype.deactivate.call(this);
    };

    return PanelState;

});
