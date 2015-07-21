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
     * @extends BasicState
     * @constructor
     */
    var PanelState = function(options) {
        this._handlePanelContentLoad = this._onPanelContentLoad.bind(this);
        this._handlePanelContentError = this._onPanelContentError.bind(this);
        BasicState.call(this);
    };

    PanelState.prototype = Object.create(BasicState.prototype);
    PanelState.prototype.constructor = PanelState;

    /**
     * Activate state
     *  - request panel content from server
     *  - create panel markup
     *  - tween in panel
     *
     * @method activate
     * @fires State:activate
     */
    PanelState.prototype.activate = function() {
        this.$panelContent = $(PANEL_TEMPLATE).appendTo('body');

        apiService.getExamplePage().then(this._handlePanelContentLoad, this._handlePanelContentError);

        this.tween = Tween.from(this.$panelContent[0], PANEL_ANIMATE_SPEED, {
            xPercent: 100,
            onReverseComplete: function() {
                this.$panelContent.remove();
            },
            callbackScope: this
        });

        BasicState.prototype.activate.call(this);
    };

    /**
     * Append markup to panel when loaded
     *
     * @method _onPanelContentLoad
     * @param {String} markup HTML content from ajax request
     * @private
     */
    PanelState.prototype._onPanelContentLoad = function(markup) {
        if (!this.active) {
            return;
        }
        var $markup = $(markup);
        this.$panelContent.append($markup).removeClass('panel_isLoading');
    };

    /**
     * Append error message when content fails to load
     *
     * @method _onPanelContentError
     * @param {Object} error Ajax error object
     * @private
     */
    PanelState.prototype._onPanelContentError = function(error) {
        if (!this.active) {
            return;
        }
        console.log(error);
        this.$panelContent.append('<div class="error">An error occurred.</div>');
    };

    /**
     * Deactivate the panel
     *  - Animate tween out
     *  - Remove markup
     *
     * @method deactivate
     * @fires State:deactivate
     */
    PanelState.prototype.deactivate = function() {
        this.tween.reverse();

        BasicState.prototype.deactivate.call(this);
    };

    return PanelState;

});
