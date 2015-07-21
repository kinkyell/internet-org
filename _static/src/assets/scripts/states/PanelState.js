define(function(require, exports, module) { // jshint ignore:line
    'use strict';

    var BasicState = require('./BasicState');
    var $ = require('jquery');
    var apiService = require('services/apiService');
    var Tween = require('gsap-tween');

    var SHADE_TEMPLATE = '<div class="shade"></div>';
    var PANEL_TEMPLATE = '<div class="panel panel_isLoading"><button type="button" class="js-stateBack">Back</button><a href="/deeper" class="js-stateLink">Go deeper</a><a href="/swap" class="js-stateSwap">Swap me</a></div>'; //jshint ignore:line

    var SPEEDS = require('appConfig').animationSpeeds;

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
    PanelState.prototype.activate = function(event) {
        var tweenOpts = {};
        var tweenSpeed;

        this.$panelShade = $(SHADE_TEMPLATE).hide().appendTo('body');
        this.$panelContent = $(PANEL_TEMPLATE).appendTo('body');

        apiService.getExamplePage().then(this._handlePanelContentLoad, this._handlePanelContentError);

        if (event.method === 'push') {
            tweenOpts.xPercent = 100;
            tweenSpeed = SPEEDS.SLIDE_IN;
        } else if (event.method === 'swap') {
            tweenOpts.yPercent = 100;
            tweenSpeed = SPEEDS.SWAP_IN;
        }

        Tween.from(this.$panelContent[0], tweenSpeed, tweenOpts);

        BasicState.prototype.activate.call(this, event);
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
    PanelState.prototype.deactivate = function(event) {
        var tweenOpts = {
            onComplete: function() {
                this.$panelContent.remove();
                this.$panelShade.remove();
            },
            callbackScope: this
        };
        var tweenSpeed;

        if (event.method === 'pop') {
            tweenOpts.xPercent = 100;
            tweenSpeed = SPEEDS.SLIDE_OUT;
        } else if (event.method === 'swap') {
            this.$panelShade.show();
            tweenOpts.opacity = 0.5;
            tweenOpts.transformOrigin = 'center top';
            tweenOpts.transform = 'scale(0.75) translateY(-25vh)';
            tweenSpeed = SPEEDS.SWAP_OUT;
        }

        Tween.to(this.$panelContent[0], tweenSpeed, tweenOpts);

        BasicState.prototype.deactivate.call(this, event);
    };

    return PanelState;

});
