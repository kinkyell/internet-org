/**
 * A global manager for the view window
 *
 * @fileoverview
 */
define(function(require, exports, module) { // jshint ignore:line
    'use strict';

    var tweenAsync = require('util/tweenAsync');
    var $ = require('jquery');
    var breakpointManager = require('services/breakpointManager');

    // speed of shift and feature transitions
    var TRANSITION_SPEED = 0.5; // in seconds

    // util function
    var _stopAnimating = function(propName, scope) {
        return function(arg) {
            scope[propName] = false;
            return arg;
        };
    };

    /**
     * Constructor for ViewWindow
     *
     * @class ViewWindow
     * @constructor
     */
    var ViewWindow = function() {
        this._init();
    };

    /**
     * Set up instance
     *
     * @method _init
     * @private
     */
    ViewWindow.prototype._init = function() {
        this.$element = $('.js-viewWindow');
        this.element = this.$element[0];
        this.$panels = this.$element.children();
        this.$feature = this.$element.find('.viewWindow-panel_feature');
        this.$story = this.$element.find('.viewWindow-panel_story');
        this._isShifted = false;
        this._isFeatureAnimating = false;
        this._isStoryAnimating = false;
        this._isShiftAnimating = false;
    };

    /**
     * Replace feature area with an image
     *
     * @method replaceFeatureImage
     * @param {String} imagePath path for image to set as background image
     * @param {String} direction Direction to animate to/from
     * @return {Promise} will fail if animation fails (alread animating) or resolve when complete
     */
    ViewWindow.prototype.replaceFeatureImage = function(imagePath, direction) {
        if (this._isFeatureAnimating) {
            return Promise.reject();
        }
        this._isFeatureAnimating = true;

        var $panel = this._getPanelWrap();
        $panel.children().css('background-image', 'url(' + imagePath + ')');
        return this._updatePanel(
            $panel,
            this.$feature,
            direction
        ).then(_stopAnimating('_isFeatureAnimating', this, 'anExtraArgument'));
    };

    /**
     * Replace feature area with an html
     *
     * @method replaceFeatureContent
     * @param {String} html HTML content to add
     * @param {String} direction Direction to animate to/from
     * @return {Promise} will fail if animation fails (alread animating) or resolve when complete
     */
    ViewWindow.prototype.replaceFeatureContent = function(html, direction) {
        if (this._isFeatureAnimating) {
            return Promise.reject();
        }
        this._isFeatureAnimating = true;

        var $panel = this._getPanelWrap();
        $panel.children().append(html);
        return this._updatePanel(
            $panel,
            this.$feature,
            direction
        ).then(_stopAnimating('_isFeatureAnimating', this));
    };

    /**
     * Replace story area with an html
     *
     * @method replaceStoryContent
     * @param {String} html HTML content to add
     * @param {String} direction Direction to animate to/from
     * @return {Promise} will fail if animation fails (alread animating) or resolve when complete
     */
    ViewWindow.prototype.replaceStoryContent = function(html, direction) {
        if (this._isStoryAnimating) {
            return Promise.reject();
        }
        this._isStoryAnimating = true;

        var $panel = this._getPanelWrap();
        $panel.children().append(html);
        return this._updatePanel(
            $panel,
            this.$story,
            direction
        ).then(_stopAnimating('_isStoryAnimating', this));
    };

    /**
     * Get bare element for new panel content
     *
     * @method _getPanelWrap
     * @return {jQuery} content wrapper element
     * @private
     */
    ViewWindow.prototype._getPanelWrap = function() {
        var content = $('<div class="viewWindow-panel-content"></div>');
        content.append('<div class="viewWindow-panel-content-inner"></div>');
        return content;
    };

    /**
     * Get properties for animation
     *
     * @method _getAnimProps
     * @return {jQuery} content wrapper element
     * @private
     */
    ViewWindow.prototype._getAnimProps = function(direction) {
        var inOpts = {};
        var outOpts = {};

        switch (direction.toLowerCase()) {
        case 'top':
            inOpts.yPercent = -100;
            outOpts.yPercent = 100;
            break;
        case 'bottom':
            inOpts.yPercent = 100;
            outOpts.yPercent = -100;
            break;
        case 'left':
            inOpts.xPercent = -100;
            outOpts.xPercent = 100;
            break;
        case 'right':
            inOpts.xPercent = 100;
            outOpts.xPercent = -100;
            break;
        case 'none':
            break;
        default:
        }

        return {
            in: inOpts,
            out: outOpts
        };
    };

    /**
     * Slide panel based on configuration
     *
     * @method _updatePanel
     * @param {jQuery} $panel Content to add
     * @param {jQuery} $target Target panel to update
     * @param {String} direction Direction to animate from
     * @return {Promise} will fail if animation fails (alread animating) or resolve when complete
     * @private
     */
    ViewWindow.prototype._updatePanel = function($panel, $target, direction) {
        var opts = this._getAnimProps(direction);
        var $newPanel;
        var $removedPanel;

        $target.append($panel);
        $newPanel = $panel;
        $removedPanel = $panel.prev();

        $target.addClass('isAnimating');

        var cleanup = function() {
            $removedPanel.remove();
            $target.removeClass('isAnimating');
        };

        if (direction.toLowerCase() === 'none') {
            cleanup();
            return Promise.resolve($newPanel.children());
        }

        return Promise.all([
            tweenAsync.from($newPanel[0], TRANSITION_SPEED, opts.in),
            tweenAsync.to($removedPanel[0], TRANSITION_SPEED, opts.out)
        ]).then(cleanup).then(function() {
            return $newPanel.children();
        });
    };

    /**
     * Toggle shift of 3up container
     *
     * @method shift
     * @return {Promise} rejects if already animating, resolves when complete
     */
    ViewWindow.prototype.shift = function() {
        var percent = breakpointManager.isMobile ? 50 : 33.333;
        var shiftOn = !this._isShifted;
        var sign;
        var activeSelector;

        if (this._isShiftAnimating) {
            return Promise.reject();
        }
        this._isShiftAnimating = true;

        if (shiftOn) {
            sign = 1;
            activeSelector = ':last-child';
        } else {
            sign = -1;
            activeSelector = ':first-child';
        }

        this.$element.toggleClass('isShifted', shiftOn);

        this._isShifted = !this._isShifted;

        return tweenAsync.from(this.element, TRANSITION_SPEED, {
            xPercent: sign * percent,
            onComplete: function() {
                this.$panels
                    .removeClass('isActive')
                    .filter(activeSelector)
                    .addClass('isActive');
                this._isShiftAnimating = false;
            },
            callbackScope: this
        });

    };

    /**
     * Get state of shifting
     *
     * @method isShifted
     * @return {Boolean} whether container is shifted over
     */
    ViewWindow.prototype.isShifted = function() {
        return this._isShifted;
    };

    return new ViewWindow();

});
