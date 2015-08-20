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
    var ViewWindow = require('services/viewWindow');
    require('gsap-cssPlugin');

    var SECTION_SPEED = AppConfig.narrativeSpeeds.SECTION_CHANGE;

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
        this._scrollBuffer = 400;

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
        this._setupTransitions();
        this.viewWindow = ViewWindow;
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
        // if (position >= this._slidesLength || position < 0 || this._isAnimating) {
        //     return;
        // }

        this._isAnimating = true;
        return this._sectionTransition(position);
    };

    proto._onLabelComplete = function(position) {
        this._position = position;
        var i = 0;
        var $postItems = $('.transformBlock-post-item');
        var l = $postItems.length;
        for (; i < l; i++) {
            var $postItem = $postItems.eq(i);
            $postItem.removeClass('transformBlock-post-item_isActive');
        }

        $('.transformBlock-post-item').eq(position - 1).addClass('transformBlock-post-item_isActive');

        window.setTimeout(this._onSectionComplete.bind(this, position), this._scrollBuffer);

        resolve('a random string');

        return this;
    };

    proto._sectionTransition = function(position) {
        // var direction = (this._position < position) ? 'bottom' : 'top';
        // var featureImage = null;
        return new Promise(function(resolve) {

        this.tl.tweenFromTo('section00', 'section01', {
            onComplete: this._onLabelComplete.bind(this, position, resolve)
        });

        switch (position) {
            case 0:
                // featureImage = '/assets/media/uploads/home_DT.jpg';

                this.tl.tweenFromTo('section01', 'section00', {
                    onComplete: this._onLabelComplete.bind(this, position, resolve)
                });
                break;
            case 1:
                // featureImage = '/assets/media/uploads/mission_DT.jpg';

                if (direction === 'bottom') {
                    this.tl.tweenFromTo('section00', 'section01', {
                        onComplete: this._onLabelComplete.bind(this, position, resolve)
                    });
                } else {
                    this.tl.tweenFromTo('section02', 'section01', {
                        onComplete: this._onLabelComplete.bind(this, position, resolve)
                    });
                }
                break;
            case 2:
                // featureImage = '/assets/media/uploads/approach_DT.jpg';

                if (direction === 'bottom') {
                    this.tl.tweenFromTo('section01', 'section02', {
                        onComplete: this._onLabelComplete.bind(this, position, resolve)
                    });
                } else {
                    this.tl.tweenFromTo('section03', 'section02', {
                        onComplete: this._onLabelComplete.bind(this, position, resolve)
                    });
                }
                break;
            case 3:
                // featureImage = '/assets/media/uploads/impact_DT.jpg';

                if (direction === 'bottom') {
                    this.tl.tweenFromTo('section02', 'section03', {
                        onComplete: this._onLabelComplete.bind(this, position, resolve)
                    });
                } else {
                    this.tl.tweenFromTo('section04', 'section03', {
                        onComplete: this._onLabelComplete.bind(this, position, resolve)
                    });
                }
                break;
            case 4:
                // featureImage = '/assets/media/uploads/contact_DT.jpg';

                this.tl.tweenFromTo('section03', 'section04', {
                    onComplete: this._onLabelComplete.bind(this, position, resolve)
                });
                break;
            default:
                // featureImage = '/assets/media/uploads/home_DT.jpg';

                this.tl.tweenFromTo('section01', 'section00', {
                    onComplete: this._onLabelComplete.bind(this, position, resolve)
                });
                break;
        }

        });

        featureImage = '/assets/media/uploads/home_DT.jpg';
        this.viewWindow.replaceFeatureImage(featureImage, direction);
    };

    proto._onSectionComplete = function(position) {
        $(window).on('wheel', this._onWheelEventHandler);
        this._isAnimating = false;
        this._updateSlideHooks();
        eventHub.publish('Narrative:sectionChange', position);
    };

    return new NarrativeDesktopManager();

});
