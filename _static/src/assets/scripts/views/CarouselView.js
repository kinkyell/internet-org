define(function(require, exports, module) { // jshint ignore:line
    'use strict';

    var $ = require('jquery');
    require('jquery-touchswipe');

    var CONFIG = {};

    /**
     * A view for transitioning display panels
     *
     * @class PanelTransitionView
     * @param {jQuery} $element A reference to the containing DOM element.
     * @constructor
     */
    var CarouselView = function($element) {
        if ($element.length === 0) { return; }

        if (!($element instanceof $)) {
            throw new TypeError('CarouselView: jQuery object is required');
        }

        /**
         * A reference to the containing DOM element.
         *
         * @default null
         * @property $element
         * @type {jQuery}
         * @public
         */
        this.$element = $element;

        /**
         * Tracks whether component is enabled.
         *
         * @default false
         * @property isEnabled
         * @type {bool}
         * @public
         */
        this.isEnabled = false;

        /**
         * reference to cached slide tweens
         *
         * @property _slideTweens
         * @type {bool}
         * @private
         */
        this._slideTweens = [];

        /**
         * reference to the current carousel position
         *
         * @default 0
         * @property _position
         * @type {array}
         * @private
         */
        this._position = 0;

        /**
         * reference to the length of the slides array
         *
         * @default null
         * @property _slidesLength
         * @type {bool}
         * @private
         */
        this._slidesLength = null;

        this.init();
    };

    var proto = CarouselView.prototype;

    /**
     * Initializes the UI Component View.
     * Runs a single setupHandlers call, followed by createChildren and layout.
     * Exits early if it is already initialized.
     *
     * @method init
     * @returns {CarouselView}
     * @private
     */
    proto.init = function() {

        this.$element.addClass('carousel_active');

        this
            .setupHandlers()
            .createChildren()
            .enable();

        return this;
    };

    /**
     * Binds the scope of any handler functions.
     * Should only be run on initialization of the view.
     *
     * @method setupHandlers
     * @returns {CarouselView}
     * @private
     */
    proto.setupHandlers = function() {
        this._onSwipeLeftHandler = this._onSwipeLeft.bind(this);
        this._onSwipeRightHandler = this._onSwipeRight.bind(this);

        return this;
    };

    /**
     * Create any child objects or references to DOM elements.
     * Should only be run on initialization of the view.
     *
     * @method createChildren
     * @returns {CarouselView}
     * @private
     */
    proto.createChildren = function() {
        this._$carouselSlides = $('.carousel-slide', this.$element);

        return this;
    };

    /**
     * Remove any child objects or references to DOM elements.
     *
     * @method removeChildren
     * @returns {CarouselView}
     * @public
     */
    proto.removeChildren = function() {

        return this;
    };

    /**
     * Enables the component.
     * Performs any event binding to handlers.
     * Exits early if it is already enabled.
     *
     * @method enable
     * @returns {CarouselView}
     * @public
     */
    proto.enable = function() {
        if (this.isEnabled) {
            return this;
        }
        this.isEnabled = true;

        // Set initial position
        this._transitionSlides('forward');

        this.$element.swipe({
            excludedElements: "button, input, select, textarea, .noSwipe",
            allowPageScroll: "vertical",
            swipe: function(event, direction) {
                if (direction === 'left') {
                    this._onSwipeLeftHandler();
                }

                if (direction === 'right') {
                    this._onSwipeRightHandler();
                }
            }.bind(this)
        });

        return this;
    };

    /**
     * Disables the component.
     * Tears down any event binding to handlers.
     * Exits early if it is already disabled.
     *
     * @method disable
     * @returns {CarouselView}
     * @public
     */
    proto.disable = function() {
        if (!this.isEnabled) {
            return this;
        }
        this.isEnabled = false;

        return this;
    };

    /**
     * Destroys the component.
     * Tears down any events, handlers, elements.
     * Should be called when the object should be left unused.
     *
     * @method destroy
     * @returns {CarouselView}
     * @public
     */
    proto.destroy = function() {
        this.disable()
            .removeChildren();

        return this;
    };

    //////////////////////////////////////////////////////////////////////////////////
    // EVENT HANDLERS
    //////////////////////////////////////////////////////////////////////////////////

    /**
     * Swipe left event handler
     *
     * @method _onSwipeLeft
     * @returns {CarouselView}
     * @private
     */
    proto._onSwipeLeft = function() {
        this._transitionSlides('forward');
    };

    /**
     * Swipe right event handler
     *
     * @method _onSwipeRight
     * @returns {CarouselView}
     * @private
     */
    proto._onSwipeRight = function() {
        this._transitionSlides('back');
    };

    //////////////////////////////////////////////////////////////////////////////////
    // HELPERS
    //////////////////////////////////////////////////////////////////////////////////

    /**
     * Swipe right event handler
     *
     * @method _transitionSlides
     * @returns {CarouselView}
     * @private
     */
    proto._transitionSlides = function(direction) {
        this._position = (direction === 'forward') ? this._position += 1 : this._position -= 1;

        this._completeAnimations();

        var i = 0;
        var l = this._$carouselSlides.length;
        for (; i < l; i++) {
            var $slide = this._$carouselSlides.eq(i);
            // TODO: remove gsap dependency
            var inc = (direction === 'forward') ? '-=100%' : '+=100%';
            var curTween = TweenMax.to($slide, 0.25, {
                left: inc
            });

            this._slideTweens.push(curTween);
        }

        if (this._position === 0) {
            this._updateSlidesLayout('start');
        }

        if (this._position === (this._slidesLength - 1)) {
            this._updateSlidesLayout('end');
        }
    };

    /**
     * Progress all current animations to their finish
     *
     * @method _transitionSlides
     * @returns {CarouselView}
     * @private
     */
    proto._completeAnimations = function() {
        var i = 0;
        var l = this._slideTweens.length;
        for (; i < l; i++) {
            var tw = this._slideTweens[i];
            tw.progress(1);
        }
    };

    /**
     * Adjust the slide layouts after rearranging the DOM
     *
     * @method _updateSlidesLayout
     * @returns {CarouselView}
     * @private
     */
    proto._updateSlidesLayout = function(endcap) {
        if (endcap === 'start') {
            this._position = 1;
            var $lastSlide = $('.carousel-slide:last-child', this.$element);
            this.$element.prepend($lastSlide);
        } else if (endcap === 'end') {
            this._position = (this._slidesLength - 1) - 1;
            var $firstSlide = $('.carousel-slide:first-child', this.$element);
            this.$element.append($firstSlide);
        }

        // Adjust shift
        // window.setTimeout(this._adjustShift.bind(this, endcap), 100);
        this._adjustShift();
    };

    proto._adjustShift = function(endcap) {
        var adjust = (endcap === 'start') ? '-=100%' : '+=100%';
        // var i = 0;
        // var l = this._$carouselSlides.length;
        // for (; i < l; i++) {
            // var $slide = this._$carouselSlides.eq(i);
        // }
        this._$carouselSlides.each(function() {
            TweenMax.to($(this), 0, {
                left: adjust
            });
        });
    };

    module.exports = CarouselView;

});
