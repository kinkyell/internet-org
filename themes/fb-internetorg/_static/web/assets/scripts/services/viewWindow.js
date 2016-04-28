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
    var apiService = require('services/apiService');
    var AnimationQueue = require('util/AnimationQueue');

    var parseUrl = require('stark/string/parseUrl');
    var vwConfig = require('appConfig').viewWindow;
    var eventHub = require('services/eventHub');
    var assetLoader = require('services/assetLoader');

    // speed of shift and feature transitions
    var TRANSITION_SPEED = require('appConfig').animationSpeeds.PANEL_SHIFT;

    var BG_IMG_REGEX = /^url\((.+)\)$/;
    var SCROLL_BARIER = 70;

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
        // DOM refs
        this.$element = $('.js-viewWindow');
        this.element = this.$element[0];
        this.$panels = this.$element.children();
        this.$feature = this.$element.find('.viewWindow-panel_feature');
        this.$story = this.$element.find('.viewWindow-panel_story');

        if (!this.element) {
            throw new TypeError('ViewWindow: no view windows found.');
        }

        // init properties
        this._featureQueue = new AnimationQueue();
        this._storyQueue = new AnimationQueue();
        this._shiftQueue = new AnimationQueue();
        this._isShifted = false;
        this._featureImage = null;
        this._className = null;
        this._handlePanelScroll = this._onPanelScroll.bind(this);
        this.$story.children().on('scroll', this._handlePanelScroll);

        // get bg image if available
        var childImg = this.$feature.find('.viewWindow-panel-content-inner').css('background-image');

        if (BG_IMG_REGEX.test(childImg)) {
            this._featureImage = childImg.match(BG_IMG_REGEX)[1];
            this._featureImage = parseUrl(this._featureImage).href;
        }
    };

    /**
     * Replace feature area with an image
     *
     * @method replaceFeatureImage
     * @param {String} imagePath path for image to set as background image
     * @param {String} direction Direction to animate to/from
     * @return {Promise} resolves when finished
     */
    ViewWindow.prototype.replaceFeatureImage = function(imagePath, direction, bgClassName) {
        if (imagePath) {
            imagePath = parseUrl(imagePath).href;
        }
        
        var swapImage = function() {
            return this._featureQueue.queue(function() {

                var $panel;

                if (this._featureImage === imagePath && this._className === bgClassName) {
                    return Promise.resolve(this.$feature.children());
                }

                $panel = this._getPanelWrap();

                if (imagePath) {
                    $panel.children().css('background-image', 'url(' + imagePath + ')');
                } else {
                    $panel.children().css('background-image', null);
                }

                $panel.children()
                .css('background-color', '#efede4')
                .addClass(bgClassName);

                this._featureImage = imagePath;
                this._className = bgClassName;

                return this._updatePanel(
                    $panel,
                    this.$feature,
                    direction
                );
            }, this);
        }.bind(this);

        return assetLoader.loadImage(imagePath).then(swapImage, swapImage);
    };

    /**
     * Replace feature area with an html
     *
     * @method replaceFeatureContent
     * @param {String} html HTML content to add
     * @param {String} direction Direction to animate to/from
     * @param {String} bgImg Optional background images to swap on container
     * @param {String} bgClassName Optional background image classname to swap.
     * @return {Promise} resolves when finished
     */
    ViewWindow.prototype.replaceFeatureContent = function(html, direction, bgImg, bgClassName) {
        return this._featureQueue.queue(function() {
            var $panel = this._getPanelWrap();

            $panel.children().append(html);
            this._featureImage = html;

            if (typeof bgImg !== 'undefined') {
                if (bgImg) {
                    $panel.children().css('background-image', 'url(' + bgImg + ')');
                } else {
                    $panel.children().css('background-image', null);
                }

                $panel.children()
                .css('background-color', '#efede4')
                .addClass(bgClassName);
            }

            return this._updatePanel(
                $panel,
                this.$feature,
                direction,
                true
            );
        }, this);
    };

    /**
     * Replace story area with an html
     *
     * @method replaceStoryContent
     * @param {String} html HTML content to add
     * @param {String} direction Direction to animate to/from
     * @return {Promise} resolves when finished
     */
    ViewWindow.prototype.replaceStoryContent = function(html, direction) {
        return this._storyQueue.queue(function() {

            var $panel = this._getPanelWrap();
            $panel.children().append(html);

            return this._updatePanel(
                $panel,
                this.$story,
                direction,
                false,
                true
            );
        }, this);
    };

    /**
     * Get bare element for new panel content
     *
     * @method _getPanelWrap
     * @return {jQuery} content wrapper element
     * @private
     */
    ViewWindow.prototype._getPanelWrap = function() {
        var contentDiv = document.createElement('div');
        contentDiv.className = 'viewWindow-panel-content';
        var contentInnerDiv = document.createElement('div');
        contentInnerDiv.className = 'viewWindow-panel-content-inner';
        contentDiv.appendChild(contentInnerDiv);
        return $(contentDiv);
    };

    /**
     * Get properties for animation
     *
     * @method _getAnimProps
     * @return {jQuery} content wrapper element
     * @private
     */
    ViewWindow.prototype._getAnimProps = function(direction) {
        var inOpts = {
            ease: vwConfig.FEATURE_EASE()[vwConfig.EASE_DIRECTION]
        };
        var outOpts = {
            ease: vwConfig.FEATURE_EASE()[vwConfig.EASE_DIRECTION]
        };
        var directionInvert = document.documentElement.dir === 'ltr' ? 1 : -1;

        switch (direction.toLowerCase()) {
        case 'top':
            inOpts.yPercent = -100;
            outOpts.yPercent = 100;
            break;
        case 'bottom':
            inOpts.yPercent = 100;
            outOpts.yPercent = -100;
            if (breakpointManager.isMobile) {
                outOpts.yPercent = -50;
                outOpts.opacity = 0.5;
                outOpts.transform = 'scale(0.85)';
            }
            break;
        case 'left':
            outOpts.xPercent = directionInvert * 100;
            if (!breakpointManager.isMobile) {
                inOpts.xPercent = directionInvert * -100;
            }
            break;
        case 'right':
            inOpts.xPercent = directionInvert * 100;
            if (!breakpointManager.isMobile) {
                outOpts.xPercent = directionInvert * -100;
            }
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
     * @param {Boolean} doublePanel Flag for setting interaction on both panels
     * @return {Promise} resolves when complete with animation
     * @private
     */
    ViewWindow.prototype._updatePanel = function($panel, $target, direction, doublePanel, scroller) {
        var opts = this._getAnimProps(direction);
        var $newPanel;
        var $removedPanel;
        // var addMethod = direction === 'left' ? 'prepend' : 'append';

        if (direction === 'left') {
            $target.prepend($panel);
            $removedPanel = $panel.next();
        } else {
            $target.append($panel);
            $removedPanel = $panel.prev();
        }
        $newPanel = $panel;
        doublePanel = doublePanel || false;

        $target.addClass('isAnimating');

        if (scroller) {
            $removedPanel.off('scroll', this._handlePanelScroll);
            $newPanel.on('scroll', this._handlePanelScroll);
            this._currentScroll = 0;
            eventHub.publish('viewWindow:scrollBarrier', this._currentScroll > SCROLL_BARIER);
        }

        var cleanup = function() {
            $removedPanel.remove();
            $target
                .removeClass('isAnimating')
                .toggleClass('isDouble', doublePanel);
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
     * @param {Boolean} silent Flag to skip animation
     * @return {Promise} rejects if already animating, resolves when complete
     */
    ViewWindow.prototype.shift = function(silent) {
        return this._shiftQueue.queue(function() {
            var percent = breakpointManager.isMobile ? 50 : 33.333;
            var shiftOn = !this._isShifted;
            var sign;
            var method;
            var directionInvert = document.documentElement.dir === 'ltr' ? 1 : -1;
            var animateElement = breakpointManager.isMobile ? this.element.lastElementChild : this.element;

            this.$panels.addClass('isAnimating');

            if (shiftOn) {
                sign = 1;
                method = 'last';
            } else {
                sign = -1;
                method = 'first';
            }

            this.$element.toggleClass('isShifted', shiftOn);

            this._isShifted = !this._isShifted;

            if (silent) {
                this.$panels.removeClass('isAnimating');
                return Promise.resolve();
            }

            return tweenAsync.from(animateElement, TRANSITION_SPEED, {
                xPercent: directionInvert * sign * percent,
                ease: vwConfig.SHIFT_EASE()[vwConfig.EASE_DIRECTION],
                onComplete: function() {
                    this.$panels.removeClass('isAnimating').removeClass('isActive');
                    this.$panels[method]().addClass('isActive');
                },
                callbackScope: this
            });
        }, this);
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

    /**
     * Get current content panel
     *
     * @method getCurrentStory
     * @return {jQuery} content panel
     */
    ViewWindow.prototype.getCurrentStory = function() {
        return Promise.resolve(this.$story.children().children());
    };

    /**
     * Get current feature panel
     *
     * @method getCurrentFeature
     * @return {jQuery} content panel
     */
    ViewWindow.prototype.getCurrentFeature = function() {
        return Promise.resolve(this.$feature.children().children());
    };

    /**
     * Load in homepage content
     *
     * @method loadHomepageContent
     */
    ViewWindow.prototype.loadHomepageContent = function() {
        if (this._homepageResolution) {
            return this._homepageResolution;
        }
        var $home = this.$element.find('.viewWindow-panel-content-inner_home');
        if ($home.children().length > 0) {
            this._homepageResolution = Promise.resolve();
            return this._homepageResolution;
        }
        this._homepageResolution = apiService.getHomepageContent().then(function(content) {
            var homeEl = $home[0];
            homeEl.parentNode.replaceChild(content.el, homeEl);
        }.bind(this));

        return this._homepageResolution;
    };

    /**
     * Load in homepage content
     *
     * @method _onPanelScroll
     */
    ViewWindow.prototype._onPanelScroll = function(event) {
        var oldScroll = this._currentScroll;
        this._currentScroll = event.target.scrollTop;
        if (oldScroll <= SCROLL_BARIER && this._currentScroll > SCROLL_BARIER) {
            eventHub.publish('viewWindow:scrollBarrier', this._currentScroll > SCROLL_BARIER);
        } else if (oldScroll > SCROLL_BARIER && this._currentScroll <= SCROLL_BARIER) {
            eventHub.publish('viewWindow:scrollBarrier', this._currentScroll > SCROLL_BARIER);
        }
    };

    return new ViewWindow();

});
