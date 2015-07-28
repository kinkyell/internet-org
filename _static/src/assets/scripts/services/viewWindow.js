/**
 * A global manager for the view window
 *
 * @fileoverview
 */
define(function(require, exports, module) { // jshint ignore:line
    'use strict';

    var Tween = require('gsap-tween');
    var $ = require('jquery');
    var breakpointManager = require('services/breakpointManager');

    // speed of shift and feature transitions
    var TRANSITION_SPEED = 0.5; // in seconds

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
        this._isShifted = false;
        this._isFeatureAnimating = false;
        this._isShiftAnimating = false;
    };

    /**
     * Replace feature area with an image
     *
     * @method replaceFeatureImage
     * @param {String} imagePath path for image to set as background image
     * @param {String} direction Direction to animate to/from
     * @param {Boolean} under True will animate the current image out, false will overlap
     * @return {Promise} will fail if animation fails (alread animating) or resolve when complete
     */
    ViewWindow.prototype.replaceFeatureImage = function(imagePath, direction, under) {
        if (this._isFeatureAnimating) {
            return Promise.reject();
        }
        this._isFeatureAnimating = true;

        var $panel = this._getPanelWrap();
        $panel.css('background-image', 'url(' + imagePath + ')');
        return this._updateFeaturePanel($panel, direction, under);
    };

    /**
     * Replace feature area with an html
     *
     * @method replaceFeatureFreature
     * @param {String} html HTML content to add
     * @param {String} direction Direction to animate to/from
     * @param {Boolean} under True will animate the current image out, false will overlap
     * @return {Promise} will fail if animation fails (alread animating) or resolve when complete
     */
    ViewWindow.prototype.replaceFeatureContent = function(html, direction, under) {
        if (this._isFeatureAnimating) {
            return Promise.reject();
        }
        this._isFeatureAnimating = true;

        var $panel = this._getPanelWrap();
        $panel.append(html);
        return this._updateFeaturePanel($panel, direction, under);
    };

    /**
     * Get bare element for new panel content
     *
     * @method _getPanelWrap
     * @return {jQuery} content wrapper element
     * @private
     */
    ViewWindow.prototype._getPanelWrap = function() {
        return $('<div class="viewWindow-panel-content"></div>');
    };

    /**
     * Slide panel based on configuration
     *
     * @method _updateFeaturePanel
     * @param {jQuery} $panel Content to add
     * @param {String} direction Direction to animate to/from
     * @param {Boolean} under True will animate the current image out, false will overlap
     * @return {Promise} will fail if animation fails (alread animating) or resolve when complete
     * @private
     */
    ViewWindow.prototype._updateFeaturePanel = function($panel, direction, under) {
        var animOpts = {};
        var self = this;
        var $animatedPanel;
        var $removedPanel;

        if (under) {
            self.$feature.prepend($panel);
            $animatedPanel = $panel.next();
            $removedPanel = $animatedPanel;
        } else {
            self.$feature.append($panel);
            $animatedPanel = $panel;
            $removedPanel = $panel.prev();
        }

        switch (direction.toLowerCase()) {
        case 'top':
            animOpts.yPercent = -100;
            break;
        case 'bottom':
            animOpts.yPercent = 100;
            break;
        case 'left':
            animOpts.xPercent = -100;
            break;
        case 'right':
            animOpts.xPercent = 100;
            break;
        case 'none':
            break;
        default:
        }

        return new Promise(function(resolve, reject) {
            var method = under ? 'to' : 'from';
            animOpts.onComplete = function() {
                self._isFeatureAnimating = false;

                $removedPanel.remove();
                resolve();
            };

            if (direction === 'none') {
                self._isFeatureAnimating = false;
                resolve();
            } else {
                Tween[method]($animatedPanel[0], TRANSITION_SPEED, animOpts);
            }
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
        var self = this;
        var shiftOn = !self._isShifted;
        var sign;
        var activeSelector;

        if (self._isShiftAnimating) {
            return Promise.reject();
        }
        self._isShiftAnimating = true;

        if (shiftOn) {
            sign = 1;
            activeSelector = ':last-child';
        } else {
            sign = -1;
            activeSelector = ':first-child';
        }

        self.$element.toggleClass('isShifted', shiftOn);

        self._isShifted = !self._isShifted;

        return new Promise(function(resolve, reject) {
            Tween.from(self.element, TRANSITION_SPEED, {
                xPercent: sign * percent,
                onComplete: function() {
                    self.$panels
                        .removeClass('isActive')
                        .filter(':last-child')
                        .addClass('isActive');
                    self._isShiftAnimating = false;
                    resolve();
                }
            });
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
