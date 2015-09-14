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
    var TweenLite = require('gsap-tween');

    var TIME_SCALE = AppConfig.narrative.mobile.TIME_SCALE;
    var SECTION_DURATION = AppConfig.narrative.mobile.SECTION_DURATION;
    var EASE = AppConfig.narrative.mobile.EASE;
    var EASE_DIRECTION_FORWARD = AppConfig.narrative.mobile.EASE_DIRECTION_FORWARD;
    var EASE_DIRECTION_REVERSE = AppConfig.narrative.mobile.EASE_DIRECTION_REVERSE;

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

        /**
         * sections configuration params
         *
         * @property _currentSection
         * @type {obj}
         * @private
         */
        this._currentSection = 0;

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

    /**
     * Refresh the objects attributes: used for orientation
     * and related changes
     *
     * @method refresh
     * @public
     */
    proto.refresh = function(position) {
        // reset css properties from previous timeline initialization
        TweenLite.set($('.narrative-section-bd'), { clearProps: 'transform' });
        TweenLite.set($('.statementBlock'), { clearProps: 'transform' });

        this._getSectionOffsets();
        this._timeLine = this._createTimeline('forward');
        this._timeLineReverse = this._createTimeline('reverse');

        if (position > 0) {
            this.gotoSection(this._sectionsConf[position - 1], 'up');
            this.gotoSection(this._sectionsConf[position], 'down');
        } else {
            this.gotoSection(this._sectionsConf[position + 1], 'down');
            this.gotoSection(this._sectionsConf[position], 'up');
        }

        $('body')[0].scrollTop = 0;
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
        var sectionOffset;

        var i = 0;
        var l = this._$sections.length;
        for (; i < l; i++) {
            sectionOffset = sectionHeight * i;
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

        tl.set(this._$narrative, {scrollTo: { y: 0 }});

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

        var i = 0;
        var l = this._sectionsConf.length;
        for (; i < l; i++) {
            var section = this._sectionsConf[i];
            tl.addLabel(section.label, SECTION_DURATION * i);
        }

        tl.timeScale(TIME_SCALE);

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
    proto.gotoSection = function(currPos, destPos) {
        this._isAnimating = true;
        return this._sectionTransition(currPos, destPos);
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
    proto._sectionTransition = function(currPos, destPos) {

        // var sectionPosition = this._sectionsConf.indexOf(section);
        // var prevSection = (direction === 'down') ?
        //     this._sectionsConf[sectionPosition - 1] :
        //     this._sectionsConf[sectionPosition + 1];

        // this._currentSection = sectionPosition;

        return new Promise(function(resolve) {
            var currSection = this._sectionsConf[currPos];
            var destSection = this._sectionsConf[destPos];

            var fromLabel = currSection.label;
            var toLabel = destSection.label;
            // var timeline = (state.position < state.destinationPos) ? this._timeLine : this._timeLineReverse;
            var timeline = this._timeLine;

            timeline.tweenFromTo(fromLabel, toLabel, {
                onComplete: this._onSectionComplete.bind(this, destPos, resolve)
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
            var sectionPosition = this._sectionsConf.indexOf(rootSection);
            var subsectionPosition = rootSection.subSections.indexOf(section);
            var $slidesContainer = this._$sections.eq(sectionPosition).find('.narrative-section-slides');
            var $slides = $slidesContainer.find('> *');
            var destinationPos = (direction === 'down') ? subsectionPosition + 1 : subsectionPosition;
            this._currentSection = sectionPosition;

            var offsetY = 0;
            var i = 0;
            for (; i < destinationPos; i++) {
                offsetY += $slides.eq(i).height();
            }

            TweenLite.to($slidesContainer, 0.35, { scrollTo: { y: offsetY }, onComplete: function() {
                this._onTransitionComplete(resolve);
            }.bind(this)});

        }.bind(this));
    };

    /**
     * Callback for section transition completion
     *
     * @method _onSectionComplete
     * @param {function} resolve promise resolution method
     * @private
     */
    proto._onSectionComplete = function(destPos, resolve) {
        this._onTransitionComplete(destPos, resolve);
    };

    /**
     * Callback for transition completion
     *
     * @method _onTransitionComplete
     * @param {function} resolve promise resolution method
     * @private
     */
    proto._onTransitionComplete = function(destPos, resolve) {
        this._isAnimating = false;
        eventHub.publish('Narrative:sectionChange', this._currentSection);
        resolve(destPos);
    };

    module.exports = NarrativeMobileManager;

});
