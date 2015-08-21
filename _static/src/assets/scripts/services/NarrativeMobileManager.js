/**
 * A manager for narrative actions
 *
 * @fileoverview
 */
define(function(require, exports, module) { // jshint ignore:line
    'use strict';

    var $ = require('jquery');
    var breakpointManager = require('services/breakpointManager');
    var AppConfig = require('appConfig');
    var Tween = require('gsap-tween');
    var Timeline = require('gsap-timeline');
    require('gsap-scrollToPlugin');
    require('gsap-cssPlugin');

    var SECTION_SPEED = AppConfig.narrative.SECTION_SPEED;

    /**
     * Constructor for NarrativeMobileManager
     *
     * @class NarrativeMobileManager
     * @constructor
     */
    var NarrativeMobileManager = function() {
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

        this._init();
    };

    var proto = NarrativeMobileManager.prototype;

    /**
     * Set up instance
     *
     * @method _init
     * @private
     */
    proto._init = function() {
        this._setupTransitions();
    };

    // /////////////////////////////////////////////////////////////////////////////////////////
    // Slide Logic
    // /////////////////////////////////////////////////////////////////////////////////////////

    proto._setupTransitions = function() {
        var $transformBlock = $('.js-transformBlock');
        this.tl = new Timeline({ paused: true });

        //  transition 01
        ///////////////////////
        this.tl.to($transformBlock, 0.35, {
            y: '-90px'
        });

        this.tl.to($('.transformBlock-pre-item').eq(0), 0.35, {
            opacity: 0
        }, '-=0.35');

        this.tl.fromTo($('.transformBlock-post-item').eq(0), 0.35, {
            y: '90px',
            opacity: 0
        }, {
            y: '0px',
            opacity: 1
        }, '-=0.35');

        //  transition 02
        ///////////////////////
        this.tl.to($('.transformBlock-post-item').eq(0), 0.35, {
            y: '-45px',
            opacity: 0
        });

        this.tl.fromTo($('.transformBlock-post-item').eq(1), 0.35, {
            y: '90px',
            opacity: 0
        }, {
            y: '0px',
            opacity: 1
        }, '-=0.35');

        //  transition 03
        ///////////////////////
        this.tl.to($('.transformBlock-post-item').eq(1), 0.35, {
            y: '-45px',
            opacity: 0
        });

        this.tl.fromTo($('.transformBlock-post-item').eq(2), 0.35, {
            y: '90px',
            opacity: 0
        }, {
            y: '0px',
            opacity: 1
        }, '-=0.35');

        //  transition 04
        ///////////////////////
        this.tl.to($transformBlock, 0.35, {
            y: '-=90px'
        });

        this.tl.fromTo($('.transformBlock-pre-item').eq(1), 0.35, {
            opacity: 0,
            y: '50px'
        }, {
            opacity: 1,
            y: '0px'
        }, '-=0.35');

        this.tl.to($('.transformBlock-post-item').eq(2), 0.35, {
            y: '-45px',
            opacity: 0
        }, '-=0.35');

        this.tl.fromTo($('.transformBlock-post-item').eq(3), 0.35, {
            y: '90px',
            opacity: 0
        }, {
            y: '0px',
            opacity: 1
        }, '-=0.35');

        this.tl.addLabel('section00', 0);
        this.tl.addLabel('section01', 0.35);
        this.tl.addLabel('section02', 0.7);
        this.tl.addLabel('section03', 1.05);
        this.tl.addLabel('section04', 1.4);

        this.tl.timeScale(1.5);
    };

    proto.gotoSection = function(position) {
        if (position >= this._slidesLength || position < 0 || this._isAnimating) {
            return;
        }

        this._isAnimating = true;
        this._sectionTransition(position);
    };

    proto._sectionTransition = function(position) {
        var $destinationSection = $('.narrative-section').eq(position);
        var $sectionBody = $destinationSection.find('.narrative-section-bd');
        var $sectionBodyCnt = $sectionBody.find('.statementBlock');
        this._slidePosition = (position > this._position) ? 0 : $destinationSection.find('.narrative-section-slides-item:last-child').index(); // jshint ignore:line

        var i = 0;
        var offsetY = 0;
        for (; i < position; i++) {
            offsetY += $('.narrative-section').eq(i).height();
        }

        var bdTwnOffset = 50;
        var bdTwnPos = null;

        if (position === 0) {
            bdTwnPos = 0;
        } else if (this._position < position) {
            bdTwnPos = bdTwnOffset;
        } else {
            bdTwnPos = 0 - bdTwnOffset;
        }

        var bdCntPos = bdTwnPos * 2;

        var bdTwn = Tween.from($sectionBody, 0.5, {y: bdTwnPos + '%'});
        var bdCntTwn = Tween.from($sectionBodyCnt, 0.65, {y: bdCntPos + '%'});

        var tl = new Timeline();
        tl.add(bdTwn);
        tl.add(bdCntTwn, '-=0.5');

        if (this._position === 0 && position === 1) {
            var opacTwn = Tween.from($sectionBody, 0.5, {opacity: 0});
            tl.add(opacTwn, 0);
        }

        tl.to($('.narrative'), 0.35, { scrollTo: { y: offsetY }, onComplete: function() {
            this._position = position;
            this._displayIndicators();
            this._updateIndicators();
            this._updateSlideHooks();
            window.setTimeout(this._onSectionComplete.bind(this, position), this._scrollBuffer);
        }.bind(this) }, '-=0.65');

        tl.timeScale(1.15);
    };

    proto._onSectionComplete = function(position) {
        $(window).on('wheel', this._onWheelEventHandler);
        this._isAnimating = false;
        this._updateSlideHooks();
        eventHub.publish('Narrative:sectionChange', position);
    };

    // /////////////////////////////////////////////////////////////////////////////////////////
    // Slide Logic
    // /////////////////////////////////////////////////////////////////////////////////////////
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
        var destinationSlidePos = this._slidePosition;
        var atEnd;

        if (forward) {
            destinationSlidePos += 1;
            atEnd = destinationSlidePos >= slideCount;
        } else {
            destinationSlidePos -= 1;
            atEnd = destinationSlidePos < 0;
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

        var tl = new Timeline();
        tl.to($slidesContainer, 0.35, { scrollTo: { y: offsetY }, onComplete: function() {
            window.setTimeout(this._onSlideComplete.bind(this, forward), this._scrollBuffer);
        }.bind(this) });

        return true;
    };

    proto._onSlideComplete = function(forward) {
        this._slidePosition = (forward) ? this._slidePosition += 1 : this._slidePosition -= 1;
        this._isAnimating = false;
    };

    proto._hasMultiple = function(position) {
        var $currentSection = $('.narrative-section').eq(position);
        var $slides = $currentSection.find('.narrative-section-slides-item');
        var slidesCount = $slides.length;

        return (slidesCount > 1) ? true : false;
    };

    return new NarrativeMobileManager();

});
