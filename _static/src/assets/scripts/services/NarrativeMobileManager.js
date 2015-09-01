/**
 * A manager for narrative actions
 *
 * @fileoverview
 */
define(function(require, exports, module) { // jshint ignore:line
    'use strict';

    var $ = require('jquery');
    var eventHub = require('services/eventHub');
    var AppConfig = require('appConfig');
    var Timeline = require('gsap-timeline');
    require('gsap-scrollToPlugin');
    require('gsap-cssPlugin');

    var SECTION_DURATION = AppConfig.narrative.mobile.SECTION_DURATION;
    var EASE = AppConfig.narrative.desktop.EASE;
    var EASE_DIRECTION_FORWARD = AppConfig.narrative.desktop.EASE_DIRECTION_FORWARD;
    var EASE_DIRECTION_REVERSE = AppConfig.narrative.desktop.EASE_DIRECTION_REVERSE;

    /**
     * Constructor for NarrativeMobileManager
     *
     * @class NarrativeMobileManager
     * @constructor
     */
    var NarrativeMobileManager = function(sectionsConf) {
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
         * Buffer for scroll jacking (ms)
         *
         * @default 0
         * @property _scrollBuffer
         * @type {bool}
         * @private
         */
        this._scrollBuffer = 400;

        /**
         * sections configuration params
         *
         * @property _sectionsConf
         * @type {obj}
         * @private
         */
        this._sectionsConf = sectionsConf;

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
        this._createChildren();
        this._getSectionOffsets();
        this._timeLine = this._createTimeline('forward');
        this._timeLineReverse = this._createTimeline('reverse');
    };

    /**
     * Set up instance
     *
     * @method _init
     * @private
     */
    proto._createChildren = function() {
        this._$narrative = $('.narrative');
        this._$sections = $('.narrative-section');
    };

    /**
     * Remove any child objects or references to DOM elements.
     *
     * @method removeChildren
     * @public
     */
    proto.removeChildren = function() {
        this._$narrative = null;
        this._$sections = null;
    };

    // /////////////////////////////////////////////////////////////////////////////////////////
    // Helpers
    // /////////////////////////////////////////////////////////////////////////////////////////

    /**
     * Records the scroll position offset for
     * each section animation
     *
     * @method _getSectionOffsets
     * @private
     */
    proto._getSectionOffsets = function() {
        var sectionHeight = this._$sections.eq(0).height();

        var i = 0;
        var l = this._$sections.length;
        for (; i < l; i++) {
            var sectionOffset = sectionHeight * i;
            this._sectionsConf[i].sectionOffset = sectionOffset;
        }
    };

    /**
     * Defines the animation timeline
     *
     * @method _createTimeline
     * @param {string} direction the direction of the transition
     * @private
     */
    proto._createTimeline = function(direction) {
        var tl = new Timeline({ paused: true });
        var easeDirection = (direction === 'forward') ? EASE_DIRECTION_FORWARD : EASE_DIRECTION_REVERSE;

        //  transition 01
        ///////////////////////
        tl.to(
            this._$narrative,
            0.35,
            { scrollTo: { y: this._sectionsConf[1].sectionOffset }, ease: EASE[easeDirection] });

        tl.from(
            this._$sections.eq(1).find('.narrative-section-bd'),
            0.5,
            {y: '50%', ease: EASE[easeDirection]},
            '-=0.35');

        tl.from(
            this._$sections.eq(1).find('.statementBlock'),
            0.65,
            {y: '100%', ease: EASE[easeDirection]},
            '-=0.5');

        //  transition 02
        ///////////////////////
        tl.to(
            this._$narrative,
            0.35,
            { scrollTo: { y: this._sectionsConf[2].sectionOffset }, ease: EASE[easeDirection] });

        tl.from(
            this._$sections.eq(2).find('.narrative-section-bd'),
            0.5,
            {y: '50%', ease: EASE[easeDirection]},
            '-=0.35');

        tl.from(
            this._$sections.eq(2).find('.statementBlock'),
            0.65,
            {y: '100%', ease: EASE[easeDirection]},
            '-=0.5');

        //  transition 05
        ///////////////////////
        tl.to(this._$narrative,
            0.35,
            { scrollTo: { y: this._sectionsConf[3].sectionOffset }, ease: EASE[easeDirection] });

        tl.from(
            this._$sections.eq(3).find('.narrative-section-bd'),
            0.5,
            {y: '50%', ease: EASE[easeDirection]},
            '-=0.35');

        tl.from(
            this._$sections.eq(3).find('.statementBlock'),
            0.65,
            {y: '100%', ease: EASE[easeDirection]},
            '-=0.5');

        //  transition 06
        ///////////////////////
        tl.to(
            this._$narrative,
            0.35,
            { scrollTo: { y: this._sectionsConf[4].sectionOffset }, ease: EASE[easeDirection] });

        tl.addLabel(this._sectionsConf[0].label, SECTION_DURATION * 0);
        tl.addLabel(this._sectionsConf[1].label, SECTION_DURATION * 1);
        tl.addLabel(this._sectionsConf[2].label, SECTION_DURATION * 2);
        tl.addLabel(this._sectionsConf[3].label, SECTION_DURATION * 3);
        tl.addLabel(this._sectionsConf[4].label, SECTION_DURATION * 4);

        // var bdTwnOffset = 50;
        // var bdTwnPos = null;

        // if (position === 0) {
        //     bdTwnPos = 0;
        // } else if (this._position < position) {
        //     bdTwnPos = bdTwnOffset;
        // } else {
        //     bdTwnPos = 0 - bdTwnOffset;
        // }
        // bdTwnPos = bdTwnOffset;

        // var bdCntPos = bdTwnPos * 2;

        // var bdTwn = Tween.from($sectionBody, 0.5, {y: bdTwnPos + '%'});
        // var bdCntTwn = Tween.from($sectionBodyCnt, 0.65, {y: bdCntPos + '%'});

        // tl.add(bdTwn);
        // tl.add(bdCntTwn, '-=0.5');

        // if (this._position === 0 && position === 1) {
        //     var opacTwn = Tween.from($sectionBody, 0.5, {opacity: 0});
        //     tl.add(opacTwn, 0);
        // }

        // tl.to($('.narrative'), 0.35, { scrollTo: { y: offsetY }, onComplete: function() {
        //     this._position = position;
        //     this._displayIndicators();
        //     this._updateIndicators();
        //     this._updateSlideHooks();
        //     window.setTimeout(this._onSectionComplete.bind(this, position), this._scrollBuffer);
        // }.bind(this) }, '-=0.65');

        // tl.timeScale(1.15);

        return tl;
    };

    /**
     * Begins the sequece for section change
     *
     * @method gotoSection
     * @param {object} section section config params
     * @param {string} direction the direction of the transition
     * @public
     */
    proto.gotoSection = function(section, direction) {
        this._isAnimating = true;
        return this._sectionTransition(section, direction);
    };

    /**
     * Begins the sequece for subsection change
     *
     * @method gotoSubSection
     * @param {object} section section config params
     * @param {string} direction the direction of the transition
     * @public
     */
    proto.gotoSubSection = function(section, direction, rootSection) {
        rootSection = (typeof rootSection === 'undefined') ? null : rootSection;
        this._isAnimating = true;
        return this._subSectionTransition(section, direction, rootSection);
    };

    /**
     * Kicks off a section transition
     *
     * @method _sectionTransition
     * @param {object} section section config params
     * @param {string} direction the direction of the transition
     * @private
     */
    proto._sectionTransition = function(section, direction) {
        var sectionPosition = this._sectionsConf.indexOf(section);
        var prevSection = (direction === 'down') ?
            this._sectionsConf[sectionPosition - 1] :
            this._sectionsConf[sectionPosition + 1];

        return new Promise(function(resolve) {
            var fromLabel = prevSection.label;
            var toLabel = section.label;
            // var timeline = (state.position < state.destinationPos) ? this._timeLine : this._timeLineReverse;
            var timeline = this._timeLine;

            timeline.tweenFromTo(fromLabel, toLabel, {
                onComplete: this._onSectionComplete.bind(this, resolve)
            });
        }.bind(this));
    };

    /**
     * Kicks off a subsection transition
     *
     * @method _subSectionTransition
     * @param {object} section section config params
     * @param {string} direction the direction of the transition
     * @private
     */
    proto._subSectionTransition = function(section, direction, rootSection, content) {
        return new Promise(function(resolve) {

            // get slides container
            // get child slides
            // get current subsection position
            // loop over up to current to get total offset
            // scroll container to that offset

            var sectionPosition = this._sectionsConf.indexOf(rootSection);
            var subsectionPosition = rootSection.subSections.indexOf(section);
            var $slidesContainer = this._$sections.eq(sectionPosition).find('.narrative-section-slides');
            var $slides = $slidesContainer.find('> *');
            var destinationPos = (direction === 'down') ? subsectionPosition += 1 : subsectionPosition -= 1;

            var offsetY = 0;
            var i = 0;
            for (; i < destinationPos; i++) {
                offsetY += $slides.eq(i).height();
            }

            TweenLite.to($slidesContainer, 0.35, { scrollTo: { y: offsetY }, onComplete: function() {
                // window.setTimeout(this._onTransitionComplete.bind(this, resolve), this._scrollBuffer);
                this._onTransitionComplete(resolve);
            }.bind(this) });

        }.bind(this));
    };

    /**
     * Callback for section transition completion
     *
     * @method _onSectionComplete
     * @param {function} resolve promise resolution method
     * @private
     */
    proto._onSectionComplete = function(resolve) {
        this._onTransitionComplete(resolve);
    };

    /**
     * Callback for transition completion
     *
     * @method _onTransitionComplete
     * @param {function} resolve promise resolution method
     * @private
     */
    proto._onTransitionComplete = function(resolve) {
        console.log('complete');
        this._isAnimating = false;
        eventHub.publish('Narrative:sectionChange');
        resolve();

        // $(window).on('wheel', this._onWheelEventHandler);
        // this._updateSlideHooks();
    };

    // /////////////////////////////////////////////////////////////////////////////////////////
    // Slide Logic
    // /////////////////////////////////////////////////////////////////////////////////////////
    // proto._gotoNextSlide = function(forward) {
    //     var $currentSection = $('.narrative-section').eq(this._position);
    //     var $slidesContainer = $currentSection.find('.narrative-section-slides');
    //     var $slides = $currentSection.find('.narrative-section-slides-item');
    //     var slideCount = $slides.length;
    //     var destinationSlidePos = this._slidePosition;
    //     var atEnd;

    //     if (forward) {
    //         destinationSlidePos += 1;
    //         atEnd = destinationSlidePos >= slideCount;
    //     } else {
    //         destinationSlidePos -= 1;
    //         atEnd = destinationSlidePos < 0;
    //     }

    //     var hasMultiple = this._hasMultiple(this._position);

    //     if (!hasMultiple || atEnd) {
    //         return false;
    //     }

    //     this._isAnimating = true;

    //     var offsetY = 0;
    //     var i = 0;
    //     for (; i < destinationSlidePos; i++) {
    //         offsetY += $slides.eq(i).height();
    //     }

    //     var tl = new Timeline();
    //     tl.to($slidesContainer, 0.35, { scrollTo: { y: offsetY }, onComplete: function() {
    //         window.setTimeout(this._onSlideComplete.bind(this, forward), this._scrollBuffer);
    //     }.bind(this) });

    //     return true;
    // };

    // proto._onSlideComplete = function(forward) {
    //     this._slidePosition = (forward) ? this._slidePosition += 1 : this._slidePosition -= 1;
    //     this._isAnimating = false;
    // };

    // proto._hasMultiple = function(position) {
    //     var $currentSection = $('.narrative-section').eq(position);
    //     var $slides = $currentSection.find('.narrative-section-slides-item');
    //     var slidesCount = $slides.length;

    //     return (slidesCount > 1) ? true : false;
    // };

    module.exports = NarrativeMobileManager;

});
