define(function(require, exports, module) { // jshint ignore:line
    'use strict';

    var $ = require('jquery');
    require('gsap-cssPlugin');
    require('gsap-scrollToPlugin');
    require('gsap-tween');
    require('gsap-timeline');

    var CONFIG = {
        PROGRESS: '.narrative-progress',
        PROGRESS_HIDDEN: 'narrative-progress_isHidden'
    };

    /**
     * A view for transitioning display panels
     *
     * @class NarrativeView
     * @param {jQuery} $element A reference to the containing DOM element.
     * @constructor
     */
    var NarrativeView = function($element) {
        if ($element.length === 0) { return; }

        if (!($element instanceof $)) {
            throw new TypeError('MenuView: jQuery object is required');
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
         * Tracks whether scroll direction is up or down
         *
         * @default 'down'
         * @property _direction
         * @type {string}
         * @private
         */
        this._direction = 'down';

        /**
         * Tracks whether there is an active animation
         *
         * @default false
         * @property _isAnimating
         * @type {bool}
         * @private
         */
        this._isAnimating = false;

        /**
         * Threashold for wheel delta normalization
         *
         * @default 5
         * @property _factor
         * @type {bool}
         * @private
         */
        this._factor = 5;

        /**
         * Speed in milleseconds to provide animation timing
         *
         * @default 650
         * @property _scrollSpeed
         * @type {bool}
         * @private
         */
        this._scrollSpeed = 250;

        /**
         * Tracks the current position of the narrative
         *
         * @default 0
         * @property _position
         * @type {bool}
         * @private
         */
        this._position = 0;

        /**
         * Tracks the current position of slide position within a section
         *
         * @default 0
         * @property _slidePosition
         * @type {bool}
         * @private
         */
        this._slidePosition = 0;

        /**
         * reference to the total number of slides
         *
         * @default null
         * @property _slidesLength
         * @type {bool}
         * @private
         */
        this._slidesLength = null;

        /**
         * Buffer for scroll jacking (ms)
         *
         * @default 0
         * @property _scrollBuffer
         * @type {bool}
         * @private
         */
        this._scrollBuffer = 400;

        /**
         * @type String
         */
        this._eventTouchNamespace = '.scrolltrackertouch';

        this.init();
    };

    var proto = NarrativeView.prototype;

    /**
     * Initializes the UI Component View.
     * Runs a single setupHandlers call, followed by createChildren and layout.
     * Exits early if it is already initialized.
     *
     * @method init
     * @returns {NarrativeView}
     * @private
     */
    proto.init = function() {
        this.setupHandlers()
           .createChildren()
           .layout()
           .enable();

        return this;
    };


    /**
     * Binds the scope of any handler functions.
     * Should only be run on initialization of the view.
     *
     * @method setupHandlers
     * @returns {NarrativeView}
     * @private
     */
    proto.setupHandlers = function() {
        this._onWheelEventHandler = this._onWheelEvent.bind(this);

        return this;
    };

    /**
     * Create any child objects or references to DOM elements.
     * Should only be run on initialization of the view.
     *
     * @method createChildren
     * @returns {NarrativeView}
     * @private
     */
    proto.createChildren = function() {
        this.$body = $(document.body);
        this.$narrativeSections = this.$element.find('> *');
        this.$progress = $(CONFIG.PROGRESS);

        return this;
    };

    /**
     * Remove any child objects or references to DOM elements.
     *
     * @method removeChildren
     * @returns {NarrativeView}
     * @public
     */
    proto.removeChildren = function() {

        return this;
    };

    /**
     * Performs measurements and applys any positioning style logic.
     * Should be run anytime the parent layout changes.
     *
     * @method layout
     * @returns {NarrativeView}
     * @public
     */
    proto.layout = function() {
        this.$narrativeSections.eq(0).addClass('isActive');
        this._slidesLength = this.$narrativeSections.length;

        return this;
    };

    /**
     * Enables the component.
     * Performs any event binding to handlers.
     * Exits early if it is already enabled.
     *
     * @method enable
     * @returns {NarrativeView}
     * @public
     */
    proto.enable = function() {
        if (this.isEnabled) {
            return this;
        }
        this.isEnabled = true;

        $(window).on('mousewheel DOMMouseScroll', this._onWheelEventHandler);
        this.$body.on('touchstart', this._onTouchStart.bind(this));

        return this;
    };

    /**
     * Disables the component.
     * Tears down any event binding to handlers.
     * Exits early if it is already disabled.
     *
     * @method disable
     * @returns {NarrativeView}
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
     * @returns {NarrativeView}
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
     * Mouse Wheel event handler
     *
     * @method _onWheelEvent
     * @private
     */
    proto._onWheelEvent = function(event) {
        var delta = event.originalEvent.wheelDelta / 30 || -event.originalEvent.detail;

        if (delta < -1) {
            this._scrollDown();
        } else if (delta > 1) {
            this._scrollUp();
        }

        event.preventDefault();

        // var originalEvent = event.originalEvent;
        // var deltaY = this._normalizeDelta(originalEvent.deltaY);

        // if(this._direction === 'down' && deltaY > this._factor) {
        //     this._scrollDown();
        // } else if(this._direction === 'up' && deltaY > this._factor) {
        //     this._scrollUp();
        // }

        // event.preventDefault();
    };

    //////////////////////////////////////////////////////////////////////////////////
    // HELPERS
    //////////////////////////////////////////////////////////////////////////////////
    /**
     * normalizes wheel event delta
     *
     * @method _normalizeDelta
     * @param {num} deltaY delta returned from wheel event object
     * @private
     */
    proto._normalizeDelta = function(deltaY) {
        var _deltaY = deltaY;

        if (deltaY > 0) {
            this._direction = 'up';
        } else {
            this._direction = 'down';
            var _deltaY = _deltaY * -1;
        }

        return _deltaY;
    };

    /**
     * Scoll up to previous section
     *
     * @method _scrollUp
     * @private
     */
    proto._scrollUp = function() {
        if (this._gotoNextSlide() || this._isAnimating) {
            return;
        }

        var prevSlidePos = this._position - 1;
        this._gotoSection(prevSlidePos);
    };

    /**
     * Scoll down to next section
     *
     * @method _scrollDown
     * @private
     */
    proto._scrollDown = function() {
        if (this._gotoNextSlide(true) || this._isAnimating) {
            return;
        }

        var nextSlidePos = this._position + 1;
        this._gotoSection(nextSlidePos);
    };

    proto._hasMultiple = function(position) {
        var $currentSection = $('.narrative-section').eq(position);
        var $slides = $currentSection.find('.narrative-section-slides-item');
        var slidesCount = $slides.length;

        return (slidesCount > 1) ? true : false;

    };

    proto._gotoNextSlide = function(forward) {
        if (this._isAnimating) {
            return;
        }

        var $currentSection = $('.narrative-section').eq(this._position);
        var $slidesContainer = $currentSection.find('.narrative-section-slides');
        var $slides = $currentSection.find('.narrative-section-slides-item');
        var slideCount = $slides.length;
        var $currentSlide = $currentSection.find('.narrative-section-slides-item').eq(this._slidePosition);
        var destinationSlidePos = this._slidePosition;

        if (forward) {
            destinationSlidePos += 1;
            var atEnd = destinationSlidePos >= slideCount;
        } else {
            destinationSlidePos -= 1;
            var atEnd = destinationSlidePos < 0;
        }

        var hasMultiple = this._hasMultiple(this._position);

        if (!hasMultiple || atEnd) {
            return false;
        }

        this._isAnimating = true;

        var offsetY = 0;
        var i = 0;
        for (; i < destinationSlidePos; i++) {
            offsetY += $slides.eq(i).height();
        }

        var tl = new TimelineLite();
        tl.to($slidesContainer, 0.35, { scrollTo: { y: offsetY }, onComplete: function() {
            window.setTimeout(this._onSlideComplete.bind(this, forward), this._scrollBuffer);
        }.bind(this) });

        return true;
    };

    proto._onSlideComplete = function(forward) {
        this._slidePosition = (forward) ? this._slidePosition += 1 : this._slidePosition -= 1;
        this._isAnimating = false;
    };

    proto._gotoSection = function(position) {
        if (position >= this._slidesLength || position < 0 || this._isAnimating) {
            return;
        }

        this._isAnimating = true;

        var $destinationSection = $('.narrative-section').eq(position);
        var $sectionBody = $destinationSection.find('.narrative-section-bd');

        var i = 0
        var offsetY = 0;
        for (; i < position; i++) {
            offsetY += $('.narrative-section').eq(i).height();
        }

        this._slidePosition = (position > this._position) ? 0 : $destinationSection.find('.narrative-section-slides-item:last-child').index();

        var bdTwnPos = (position > this._position) ? '50%' : '-50%';
        var bdTwn = TweenLite.from($sectionBody, 0.5, {top: bdTwnPos});

        var tl = new TimelineLite();

        tl.add(bdTwn);

        if (position === 1 && this._position === 0) {
            var opacTwn = TweenLite.from($sectionBody, 0.5, {opacity: 0});
            tl.add(opacTwn, 0);
        }

        tl.to($('.narrative'), 0.35, { scrollTo: { y: offsetY }, onComplete: function() {
            this._position = position;
            this._displayIndicators();
            this._updateIndicators();
            window.setTimeout(this._onSectionComplete.bind(this, position), this._scrollBuffer);
        }.bind(this) }, '-=0.5');
    };

    proto._onSectionComplete = function(position) {
        $(window).on('wheel', this._onWheelEventHandler);
        this._isAnimating = false;
    };

    proto._updateIndicators = function() {
        var $progressIndicators = this.$progress.find('> *');
        var i = 0;
        var l = $progressIndicators.length;
        for (; i < l; i++) {
            var $progressIndicator = $progressIndicators.eq(i);
            $progressIndicator.removeClass('isActive');
            this.$progress.find('> *').eq(this._position).addClass('isActive');
        }
    };

    proto._displayIndicators = function() {
        var slidesLength = this._slidesLength;

        if (this._position === 0 || this._position === (slidesLength - 1)) {
            this.$progress.addClass(CONFIG.PROGRESS_HIDDEN);
        } else {
            this.$progress.removeClass(CONFIG.PROGRESS_HIDDEN);
        }
    };


    proto._onTouchStart = function(e) {
        this.$body
            .on('touchmove' + this._eventTouchNamespace, this._onTouchMove)
            .on('touchend' + this._eventTouchNamespace, this._onTouchEnd)
            .on('touchcancel' + this._eventTouchNamespace, this._onTouchEnd);
    };

    proto._onTouchMove = function(e) {
        e.preventDefault();
        var y = e.originalEvent.touches[0].pageY;
        var delta = -(y -this._touchTracker.y);

        console.log(y, delta);
    };

    proto._onTouchEnd = function(e) {
        this.$body.off(this._eventTouchNamespace);

        console.log('complete');
    };

    module.exports = NarrativeView;

});