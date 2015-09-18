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
    var ViewWindow = require('services/viewWindow');
    var log = require('util/log');
    require('gsap-cssPlugin');

    var SECTION_DURATION = AppConfig.narrative.desktop.SECTION_DURATION;
    var TIME_SCALE = AppConfig.narrative.desktop.TIME_SCALE;

    // var EASE = AppConfig.narrative.desktop.EASE == undefined ? window.Expo : AppConfig.narrative.desktop.EASE;
    var EASE = window.Expo;
    var EASE_DIRECTION_FORWARD = AppConfig.narrative.desktop.EASE_DIRECTION_FORWARD;
    var EASE_DIRECTION_REVERSE = AppConfig.narrative.desktop.EASE_DIRECTION_REVERSE;

    var CONFIG = {
        ACTIVE_POST: 'transformBlock-post-item_isActive'
    };

    /**
     * Constructor for NarrativeDesktopManager
     *
     * @class NarrativeDesktopManager
     * @constructor
     */
    var NarrativeDesktopManager = function(sectionsConf) {
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
        this._scrollBuffer = AppConfig.narrative.desktop.SCROLL_BUFFER;

        /**
         * sections configuration params
         *
         * @property _sectionsConf
         * @type {obj}
         * @private
         */
        this._sectionsConf = sectionsConf;

        this._currentFeature = {
            type: 'image',
            img: this._sectionsConf[0].featureImage
        };

        this._init();
    };

    var proto = NarrativeDesktopManager.prototype;

    /**
     * Set up instance
     *
     * @method _init
     * @private
     */
    proto._init = function() {
        this._createChildren();
        this.viewWindow = ViewWindow;
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
        this._$transformBlock = $('.js-transformBlock');
        this._$transformBlockPre = $('.transformBlock-pre-item');
        this._$transformBlockPost = $('.transformBlock-post-item');
    };

    /**
     * Remove any child objects or references to DOM elements.
     *
     * @method removeChildren
     * @public
     */
    proto.removeChildren = function() {
        this._$transformBlock = null;
        this._$transformBlockPre = null;
        this._$transformBlockPost = null;
    };

    // /////////////////////////////////////////////////////////////////////////////////////////
    // Helpers
    // /////////////////////////////////////////////////////////////////////////////////////////

    /**
     * Begins the sequece for section change
     *
     * @method gotoSection
     * @param {object} section section config params
     * @param {string} direction the direction of the transition
     * @public
     */
    proto._createTimeline = function(direction) {
        var tl = new Timeline({ paused: true });
        var easeDirection = (direction === 'forward') ? EASE_DIRECTION_FORWARD : EASE_DIRECTION_REVERSE;

        //  transition partials
        ///////////////////////
        var postIn = [
            {
                y: '90px',
                opacity: 0,
                ease: EASE[easeDirection]
            },
            {
                y: '0px',
                opacity: 1,
                ease: EASE[easeDirection]
            }
        ];

        var postOut = {
            y: '-45px',
            opacity: 0,
            ease: EASE[easeDirection]
        };

        //  transition 01
        ///////////////////////
        tl.fromTo(
            this._$transformBlock,
            SECTION_DURATION,
            { y: '0px' },
            { y: '-90px', ease: EASE[easeDirection] });

        tl.fromTo(
            this._$transformBlockPre.eq(0),
            SECTION_DURATION,
            { opacity: 1, },
            { opacity: 0, ease: EASE[easeDirection] },
            '-=' + SECTION_DURATION);

        tl.fromTo(
            this._$transformBlockPost.eq(0),
            SECTION_DURATION,
            postIn[0],
            postIn[1],
            '-=' + SECTION_DURATION);

        //  transition 02
        ///////////////////////
        tl.to(
            this._$transformBlockPost.eq(0),
            SECTION_DURATION,
            postOut);

        tl.fromTo(
            this._$transformBlockPost.eq(1),
            SECTION_DURATION,
            postIn[0],
            postIn[1],
            '-=' + SECTION_DURATION);

        //  transition 03
        ///////////////////////
        tl.to(
            this._$transformBlockPost.eq(1),
            SECTION_DURATION,
            postOut);

        tl.fromTo(
            this._$transformBlockPost.eq(2),
            SECTION_DURATION,
            postIn[0],
            postIn[1],
            '-=' + SECTION_DURATION);

        //  transition 04
        ///////////////////////
        tl.to(
            this._$transformBlock,
            SECTION_DURATION, {
                y: '-=90px',
                ease: EASE[easeDirection]
            });

        tl.fromTo(
            this._$transformBlockPre.eq(1),
            SECTION_DURATION, {
                opacity: 0,
                y: '50px',
                ease: EASE[easeDirection]
            }, {
                opacity: 1,
                y: '0px',
                ease: EASE[easeDirection]
            }, '-=' + SECTION_DURATION);

        tl.to(
            this._$transformBlockPost.eq(2),
            SECTION_DURATION,
            postOut,
            '-=' + SECTION_DURATION);

        tl.fromTo(
            this._$transformBlockPost.eq(3),
            SECTION_DURATION,
            postIn[0],
            postIn[1],
            '-=' + SECTION_DURATION);

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
    proto.gotoSubSection = function(destSectionPos, destSlidPos, curSlidePos) {
        this._isAnimating = true;
        return this._subSectionTransition(destSectionPos, destSlidPos, curSlidePos);
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
        return new Promise(function(resolve) {
            var currSection = this._sectionsConf[currPos];
            var destSection = this._sectionsConf[destPos];

            var fromLabel = currSection.label;
            var toLabel = destSection.label;
            var direction = (currPos < destPos) ? 'down' : 'up';
            var imgDirection = (direction === 'down') ? 'bottom' : 'top';
            var timeline = (direction === 'down') ? this._timeLine : this._timeLineReverse;
            var featureImage;
            var content;
            var section = this._sectionsConf[destPos];
            var subsLast = section.subSections.length - 1;

            var diff = Math.abs(currPos - destPos);
            var timeScale = TIME_SCALE * diff;
            timeline.timeScale(timeScale);

            timeline.tweenFromTo(fromLabel, toLabel, {
                onComplete: this._onSectionComplete.bind(this, destPos, resolve)
            });

            if (section.subSections.length > 0 && direction === 'up') {
                featureImage = section.subSections[subsLast].featureImage;
                content = section.subSections[subsLast].content;
            } else {
                featureImage = section.featureImage;
                content = '';
            }

            this.viewWindow.replaceFeatureContent(
                content,
                imgDirection,
                featureImage);

            this._currentFeature = {
                type: 'content',
                img: featureImage,
                content: content
            };
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
    proto._subSectionTransition = function(destSectionPos, destSlidPos, curSlidePos) {
        return new Promise(function(resolve) {
            var section = this._sectionsConf[destSectionPos];
            var direction = (curSlidePos < destSlidPos) ? 'down' : 'up';
            var imgDirection = (direction === 'down') ? 'bottom' : 'top';
            var subSection = section.subSections[destSlidPos - 1];
            var featureImage = (destSlidPos === 0) ? section.featureImage : subSection.featureImage;
            var content = (subSection !== undefined) ? subSection.content : '';

            this.viewWindow.replaceFeatureContent(
                content,
                imgDirection,
                featureImage).then(this._onSubSectionComplete.bind(this, destSlidPos, resolve)).catch(log);

            this._currentFeature = {
                type: 'content',
                img: featureImage,
                content: content
            };
        }.bind(this));
    };

    /**
     * Callback for section transition completion
     *
     * @method _onSubSectionComplete
     * @param {function} resolve promise resolution method
     * @private
     */
    proto._onSubSectionComplete = function(destSlidPos, resolve) {
        window.setTimeout(this._onTransitionComplete.bind(this, destSlidPos, resolve), this._scrollBuffer);
    };

    /**
     * Callback for section transition completion
     *
     * @method _onSectionComplete
     * @param {object} section section config params
     * @param {function} resolve promise resolution method
     * @private
     */
    proto._onSectionComplete = function(destPos, resolve) {
        var i = 0;
        var l = this._$transformBlockPost.length;
        for (; i < l; i++) {
            var $postItem = this._$transformBlockPost.eq(i);
            $postItem.removeClass(CONFIG.ACTIVE_POST);
        }

        this._$transformBlockPost.eq(destPos - 1).addClass('transformBlock-post-item_isActive');

        window.setTimeout(this._onTransitionComplete.bind(this, destPos, resolve), 0);
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
        resolve(destPos);
    };

    module.exports = NarrativeDesktopManager;

});
