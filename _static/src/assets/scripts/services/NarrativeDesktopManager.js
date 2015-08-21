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
    var NarrativeDesktopManager = function() {
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
        this._scrollBuffer = AppConfig.narrative.desktop.SCROLL_BUFFER;

        /**
         * section properties
         *
         * @default arr
         * @property _sections
         * @type {arr}
         * @private
         */
        this._sections = [
            {
                label: 'section00',
                label_r: 'section00_r',
                featureImage: AppConfig.narrative.desktop.featureImages.HOME
            },
            {
                label: 'section01',
                label_r: 'section01_r',
                featureImage: AppConfig.narrative.desktop.featureImages.MISSION
            },
            {
                label: 'section02',
                label_r: 'section02_r',
                featureImage: AppConfig.narrative.desktop.featureImages.APPROACH
            },
            {
                label: 'section03',
                label_r: 'section03_r',
                featureImage: AppConfig.narrative.desktop.featureImages.IMPACT
            },
            {
                label: 'section04',
                label_r: 'section04_r',
                featureImage: AppConfig.narrative.desktop.featureImages.FOOT
            }
        ];

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

        //  transition 01
        ///////////////////////
        tl.fromTo(this._$transformBlock, SECTION_DURATION, { y: '0px' }, { y: '-90px', ease: EASE[easeDirection] });
        tl.fromTo(this._$transformBlockPre.eq(0), SECTION_DURATION, { opacity: 1, }, { opacity: 0, ease: EASE[easeDirection] }, '-=' + SECTION_DURATION);
        tl.fromTo(this._$transformBlockPost.eq(0), SECTION_DURATION, postIn[0], postIn[1], '-=' + SECTION_DURATION);

        //  transition 02
        ///////////////////////
        tl.to(this._$transformBlockPost.eq(0), SECTION_DURATION, {
            y: '-45px',
            opacity: 0,
            ease: EASE[easeDirection]
        });

        tl.fromTo(this._$transformBlockPost.eq(1), SECTION_DURATION, postIn[0], postIn[1], '-=' + SECTION_DURATION);

        //  transition 03
        ///////////////////////
        tl.to(this._$transformBlockPost.eq(1), SECTION_DURATION, {
            y: '-45px',
            opacity: 0,
            ease: EASE[easeDirection]
        });

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

        tl.to(this._$transformBlockPost.eq(2), SECTION_DURATION, {
            y: '-45px',
            opacity: 0,
            ease: EASE[easeDirection]
        }, '-=' + SECTION_DURATION);

        tl.fromTo(this._$transformBlockPost.eq(3), SECTION_DURATION, {
            y: '90px',
            opacity: 0,
            ease: EASE[easeDirection]
        }, {
            y: '0px',
            opacity: 1,
            ease: EASE[easeDirection]
        }, '-=' + SECTION_DURATION);

        tl.addLabel(this._sections[0].label, SECTION_DURATION * 0);
        tl.addLabel(this._sections[1].label, SECTION_DURATION * 1);
        tl.addLabel(this._sections[2].label, SECTION_DURATION * 2);
        tl.addLabel(this._sections[3].label, SECTION_DURATION * 3);
        tl.addLabel(this._sections[4].label, SECTION_DURATION * 4);

        tl.timeScale(TIME_SCALE);

        return tl;
    };

    proto.gotoSection = function(state) {
        this._isAnimating = true;
        return this._sectionTransition(state);
    };

    proto._sectionTransition = function(state) {
        return new Promise(function(resolve) {
            var featureImage = this._sections[state.destinationPos].featureImage;
            var fromLabel = this._sections[state.position].label;
            var toLabel = this._sections[state.destinationPos].label;
            var direction = (state.position < state.destinationPos) ? 'bottom' : 'top';
            var timeline = (direction === 'bottom') ? this._timeLine : this._timeLineReverse;

            timeline.tweenFromTo(fromLabel, toLabel, {
                onComplete: this._onLabelComplete.bind(this, state, resolve)
            });

            this.viewWindow.replaceFeatureImage(featureImage, direction);
        }.bind(this));
    };

    proto._onLabelComplete = function(state, resolve) {
        var i = 0;
        var l = this._$transformBlockPost.length;
        for (; i < l; i++) {
            var $postItem = this._$transformBlockPost.eq(i);
            $postItem.removeClass(CONFIG.ACTIVE_POST);
        }

        this._$transformBlockPost.eq(state.position).addClass('transformBlock-post-item_isActive');

        window.setTimeout(this._onSectionComplete.bind(this, state.destinationPos), this._scrollBuffer);

        resolve(state.destinationPos);
    };

    proto._onSectionComplete = function(position) {
        $(window).on('wheel', this._onWheelEventHandler);
        this._isAnimating = false;
        // this._updateSlideHooks();
        eventHub.publish('Narrative:sectionChange', position);
    };

    return new NarrativeDesktopManager();

});
