/**
 * A manager for narrative actions
 *
 * @fileoverview
 */
define(function(require, exports, module) { // jshint ignore:line
    'use strict';

    var $ = require('jquery');
    var eventHub = require('services/eventHub');
    var breakpointManager = require('services/breakpointManager');
    var AppConfig = require('appConfig');
    var Tween = require('gsap-tween');
    var Timeline = require('gsap-timeline');
    var ViewWindow = require('services/viewWindow');
    require('gsap-cssPlugin');

    var SECTION_DURATION = AppConfig.narrative.desktop.SECTION_DURATION;
    var TIME_SCALE = AppConfig.narrative.desktop.TIME_SCALE;

    var EASE = AppConfig.narrative.desktop.EASE;
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

    // /////////////////////////////////////////////////////////////////////////////////////////
    // Slide Logic
    // /////////////////////////////////////////////////////////////////////////////////////////

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
        tl.fromTo(this._$transformBlock, SECTION_DURATION, { y: '0px' }, { y: '-90px', ease: EASE[easeDirection] });
        tl.fromTo(this._$transformBlockPre.eq(0), SECTION_DURATION, { opacity: 1, }, { opacity: 0, ease: EASE[easeDirection] }, '-=' + SECTION_DURATION);
        tl.fromTo(this._$transformBlockPost.eq(0), SECTION_DURATION, postIn[0], postIn[1], '-=' + SECTION_DURATION);

        //  transition 02
        ///////////////////////
        tl.to(this._$transformBlockPost.eq(0), SECTION_DURATION, postOut);
        tl.fromTo(this._$transformBlockPost.eq(1), SECTION_DURATION, postIn[0], postIn[1], '-=' + SECTION_DURATION);

        //  transition 03
        ///////////////////////
        tl.to(this._$transformBlockPost.eq(1), SECTION_DURATION, postOut);
        tl.fromTo(this._$transformBlockPost.eq(2), SECTION_DURATION, postIn[0], postIn[1], '-=' + SECTION_DURATION);

        //  transition 04
        ///////////////////////
        tl.to(this._$transformBlock, SECTION_DURATION, {
            y: '-=90px',
            ease: EASE[easeDirection]
        });

        tl.fromTo(this._$transformBlockPre.eq(1), SECTION_DURATION, {
            opacity: 0,
            y: '50px',
            ease: EASE[easeDirection]
        }, {
            opacity: 1,
            y: '0px',
            ease: EASE[easeDirection]
        }, '-=' + SECTION_DURATION);

        tl.to(this._$transformBlockPost.eq(2), SECTION_DURATION, postOut, '-=' + SECTION_DURATION);

        tl.fromTo(this._$transformBlockPost.eq(3), SECTION_DURATION, postIn[0], postIn[1], '-=' + SECTION_DURATION);

        var i = 0;
        var l = this._sectionsConf.length;
        for (; i < l; i++) {
            var section = this._sectionsConf[i];
            tl.addLabel(section.label, SECTION_DURATION * i);
        }

        tl.timeScale(TIME_SCALE);

        return tl;
    };

    proto.gotoSection = function(section, direction) {
        this._isAnimating = true;
        return this._sectionTransition(section, direction);
    };

    proto.gotoSubSection = function(section, direction) {
        this._isAnimating = true;
        return this._subSectionTransition(section, direction);
    };

    proto._sectionTransition = function(section, direction) {
        var sectionPosition = this._sectionsConf.indexOf(section);
        var prevSection = (direction === 'down') ? this._sectionsConf[sectionPosition - 1] : this._sectionsConf[sectionPosition + 1];
        return new Promise(function(resolve) {
            var fromLabel = prevSection.label;
            var toLabel = section.label;
            var imgDirection = (direction === 'down') ? 'bottom' : 'top';
            var timeline = (direction === 'down') ? this._timeLine : this._timeLineReverse;

            timeline.tweenFromTo(fromLabel, toLabel, {
                onComplete: this._onSectionComplete.bind(this, section, direction, resolve)
            });

            if (section.subSections.length > 0 && direction === 'up') {
                var featureImage = section.subSections[2].featureImage;
            } else {
                var featureImage = section.featureImage;
            }

            this.viewWindow.replaceFeatureImage(featureImage, imgDirection);
        }.bind(this));
    };

    proto._subSectionTransition = function(section, direction) {
        return new Promise(function(resolve) {
            var imgDirection = (direction === 'down') ? 'bottom' : 'top';
            this.viewWindow.replaceFeatureImage(section.featureImage, imgDirection).then(this._onSubSectionComplete.bind(this, resolve));
        }.bind(this));
    };

    proto._onSubSectionComplete = function(resolve) {
        window.setTimeout(this._onTransitionComplete.bind(this, resolve), this._scrollBuffer);
    };

    proto._onSectionComplete = function(section, direction, resolve) {
        var sectionPosition = this._sectionsConf.indexOf(section) - 1;
        var i = 0;
        var l = this._$transformBlockPost.length;
        for (; i < l; i++) {
            var $postItem = this._$transformBlockPost.eq(i);
            $postItem.removeClass(CONFIG.ACTIVE_POST);
        }

        this._$transformBlockPost.eq(sectionPosition).addClass('transformBlock-post-item_isActive');

        window.setTimeout(this._onTransitionComplete.bind(this, resolve), 0);
    };

    proto._onTransitionComplete = function(resolve) {
        // $(window).on('wheel', this._onWheelEventHandler);
        this._isAnimating = false;
        // this._updateSlideHooks();
        eventHub.publish('Narrative:sectionChange');
        resolve();
    };

    module.exports = NarrativeDesktopManager;

});
