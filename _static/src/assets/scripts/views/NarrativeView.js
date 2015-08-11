define(function(require, exports, module) { // jshint ignore:line
    'use strict';

    var $ = require('jquery');
    require('gsap-cssPlugin');
    require('gsap-scrollToPlugin');
    require('gsap-tween');
    require('gsap-timeline');
    var AbstractView = require('./AbstractView');
    var ViewWindow = require('services/viewWindow');
    var breakpointManager = require('services/breakpointManager');

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

        this._touchTracker = {
            y: 0
        };

        /**
         * @type String
         */
        this._eventTouchNamespace = '.scrolltrackertouch';

        AbstractView.call(this, $element);
    };

    var proto = AbstractView.createChild(NarrativeView);


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
    };

    /**
     * Remove any child objects or references to DOM elements.
     *
     * @method removeChildren
     * @returns {NarrativeView}
     * @public
     */
    proto.removeChildren = function() {};

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
    proto.onEnable = function() {
        $(window).on('mousewheel DOMMouseScroll', this._onWheelEventHandler);
        this.$body.on('touchstart', this._onTouchStart.bind(this));

        this.viewWindow = ViewWindow;
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
    proto.onDisable = function() {};

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

        if (!breakpointManager.isMobile) {
            return false;
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

        if (breakpointManager.isMobile) {
            this._sectionTransitionMobile(position);
        } else {
            this._sectionTransitionDesktop(position)
        }
    };

    proto._sectionTransitionMobile = function(position) {
        var $destinationSection = $('.narrative-section').eq(position);
        var $sectionBody = $destinationSection.find('.narrative-section-bd');
        var $sectionBodyCnt = $sectionBody.find('.transformBlock');
        this._slidePosition = (position > this._position) ? 0 : $destinationSection.find('.narrative-section-slides-item:last-child').index();

        var i = 0
        var offsetY = 0;
        for (; i < position; i++) {
            offsetY += $('.narrative-section').eq(i).height();
        }

        var bdTwnOffset = 50;
        var bdTwnPos = (position > this._position) ? bdTwnOffset : (0 - bdTwnOffset);
        var bdCntPos = bdTwnPos * 2;
        var bdTwn = TweenLite.from($sectionBody, 0.5, {top: bdTwnPos + '%', paused: false});
        var bdCntTwn = TweenLite.from($sectionBodyCnt, 0.65, {y: bdCntPos + '%', paused: false});

        var tl = new TimelineLite();

        if (this._position === (this._slidesLength - 1) && position === (this._slidesLength - 2)) {
           console.log('second to last');
        }
        // if (position !== (this._slidesLength - 1)) {
        //
        // }

        tl.add(bdTwn);
        tl.add(bdCntTwn, '-=0.5');

        if (position === 1 && this._position === 0) {
            var opacTwn = TweenLite.from($sectionBody, 0.5, {opacity: 0});
            tl.add(opacTwn, 0);
        }

        tl.to($('.narrative'), 0.35, { scrollTo: { y: offsetY }, onComplete: function() {
            this._position = position;
            this._displayIndicators();
            this._updateIndicators();
            this._updateSlideHooks();
            window.setTimeout(this._onSectionComplete.bind(this, position), this._scrollBuffer);
        }.bind(this) }, '-=0.65');
    }

    proto._sectionTransitionDesktop = function(position) {
        var $currentSection = $('.narrative-section').eq(this._position);
        var $destinationSection = $('.narrative-section').eq(position);
        var $sectionBody = $destinationSection.find('.narrative-section-bd');
        var $transformBlock = $currentSection.find('.transformBlock');
        var $linePre = $transformBlock.find('.transformBlock-pre');
        var direction = (this._position < position) ? 'bottom' : 'top';

        var movement = (direction === 'bottom') ? '-=90px' : '+=90px';
        var tl = new TimelineLite();
        tl.to($transformBlock, 0.35, {
            top: movement
        });

        tl.to($linePre, 0.35, {
            opacity: 0
        }, '-=0.35');

        this.viewWindow.replaceFeatureImage('/assets/media/uploads/home.jpg', direction).then(function() {
            this._position = position;
            this._updateSlideHooks();
            this._isAnimating = false;
        }.bind(this));
    }

    proto._updateSlideHooks = function() {
        var i = 0;
        var l = this.$narrativeSections.length;
        for (; i < l; i++) {
            this.$narrativeSections.eq(i).removeClass('isActive');
        }

        this.$narrativeSections.eq(this._position).addClass('isActive');
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
        }

        this.$progress.find('> *').eq(this._position).addClass('isActive');
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
        this._touchTracker.y = e.originalEvent.touches[0].pageY;

        this.$body
            .on('touchmove' + this._eventTouchNamespace, this._onTouchMove.bind(this))
            .on('touchend' + this._eventTouchNamespace, this._onTouchEnd.bind(this))
            .on('touchcancel' + this._eventTouchNamespace, this._onTouchEnd.bind(this));
    };

    proto._onTouchMove = function(e) {
        e.preventDefault();

        var y = e.originalEvent.touches[0].pageY;
        var delta = -(y -this._touchTracker.y);

        if (delta < -1) {
            this._scrollUp();
        } else if (delta > 1) {
            this._scrollDown();
        }
    };

    proto._onTouchEnd = function(e) {
        this.$body.off(this._eventTouchNamespace);
    };

    module.exports = NarrativeView;

});