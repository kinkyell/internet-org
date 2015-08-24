define(function(require, exports, module) { // jshint ignore:line
    'use strict';

    var $ = require('jquery');
    var AppConfig = require('appConfig');
    var AbstractView = require('./AbstractView');
    var eventHub = require('services/eventHub');
    var breakpointManager = require('services/breakpointManager');
    var NarrativeMobileManager = require('services/NarrativeMobileManager');
    var NarrativeDesktopManager = require('services/NarrativeDesktopManager');

    var CONFIG = {
        NARRATIVE_DT: '.narrativeDT',
        PROGRESS: '.narrative-progress',
        PROGRESS_HIDDEN: 'narrative-progress_isHidden'
    };

    var SECTIONS_CONF = [
        {
            label: 'section00',
            label_r: 'section00_r',
            featureImage: '',
            subSections: []
        },
        {
            label: 'section01',
            label_r: 'section01_r',
            featureImage: '',
            subSections: []
        },
        {
            label: 'section02',
            label_r: 'section02_r',
            featureImage: '',
            subSections: [
                {
                    featureImage: ''
                },
                {
                    featureImage: ''
                },
                {
                    featureImage: ''
                }
            ]
        },
        {
            label: 'section03',
            label_r: 'section03_r',
            featureImage: AppConfig.narrative.desktop.featureImages.IMPACT,
            subSections: [
                {
                    featureImage: ''
                },
                {
                    featureImage: ''
                },
                {
                    featureImage: ''
                }
            ]
        },
        {
            label: 'section04',
            label_r: 'section04_r',
            featureImage: '',
            subSections: []
        }
    ];

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
        this._subPosition = null;

        /**
         * Determines the total number of sections
         *
         * @default null
         * @property _sectionLength
         * @type {bool}
         * @private
         */
        this._sectionLength = null;

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
     * @private
     */
    proto.setupHandlers = function() {
        this._onWheelEventHandler = this._onWheelEvent.bind(this);
        this._onTouchStartHandler = this._onTouchStart.bind(this);
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
        this.$narrativeDT = this.$element.find(CONFIG.NARRATIVE_DT);
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
        this._sectionLength = this.$narrativeSections.length;
        this._getFeatureImages();
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
        // determine bp specific narrative handler
        this._narrativeManager = (breakpointManager.isMobile) ? new NarrativeMobileManager(SECTIONS_CONF) : new NarrativeDesktopManager(SECTIONS_CONF);

        $(window).on('mousewheel DOMMouseScroll', this._onWheelEventHandler);
        this.$body.on('touchstart', this._onTouchStartHandler);
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
    proto.onDisable = function() {
        $(window).off('mousewheel DOMMouseScroll', this._onWheelEventHandler);
        this.$body.off('touchstart', this._onTouchStartHandler);
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

    //////////////////////////////////////////////////////////////////////////////////
    // HELPERS
    //////////////////////////////////////////////////////////////////////////////////
    /**
     * Scoll up to previous section
     *
     * @method _scrollUp
     * @private
     */
    proto._scrollUp = function() {
        if (!this._narrativeManager._isAnimating) {
            var direction = 'up';
            var section = SECTIONS_CONF[this._position];
            var subsLength = section.subSections.length;
            var subPosition = this._subPosition;

            if (subsLength > 0 && subPosition > 0) {
                var destinationSubPos = subPosition - 1;
                var destinationSub = section.subSections[destinationSubPos];

                this._narrativeManager.gotoSubSection(destinationSub, direction).then(function() {
                    this._subPosition -= 1;
                }.bind(this));
            } else {
                var sectionsLength = SECTIONS_CONF.length;
                var destinationSectionPos = this._position - 1;
                var destinationSection = SECTIONS_CONF[destinationSectionPos];
                var destinationSubsLength = destinationSection.subSections.length;
                this._subPosition = destinationSubsLength - 1;

                if (destinationSectionPos >= 0) {
                    this._narrativeManager.gotoSection(destinationSection, direction).then(function() {
                        this._position -= 1;
                    }.bind(this));
                }
            }
        }
    };

    /**
     * Scoll down to next section
     *
     * @method _scrollDown
     * @private
     */
    proto._scrollDown = function() {
        if (!this._narrativeManager._isAnimating) {
            var direction = 'down';
            var section = SECTIONS_CONF[this._position];
            var subsLength = section.subSections.length;
            var subPosition = this._subPosition;
            if (subsLength > 0 && subPosition < subsLength - 1) {
                var destinationSubPos = subPosition + 1;
                var destinationSub = section.subSections[destinationSubPos];

                this._narrativeManager.gotoSubSection(destinationSub, direction).then(function() {
                    this._subPosition += 1;
                }.bind(this));
            } else {
                var sectionsLength = SECTIONS_CONF.length;
                var destinationSectionPos = this._position + 1;
                var destinationSection = SECTIONS_CONF[destinationSectionPos];
                this._subPosition = 0;

                if (destinationSectionPos < sectionsLength) {
                    this._narrativeManager.gotoSection(destinationSection, direction).then(function() {
                        this._position += 1;
                    }.bind(this));
                }
            }
        }
    };

    proto._updateSlideHooks = function() {
        var i = 0;
        var l = this.$narrativeSections.length;
        for (; i < l; i++) {
            this.$narrativeSections.eq(i).removeClass('isActive');
        }

        this.$narrativeSections.eq(this._position).addClass('isActive');
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

    proto._getFeatureImages = function() {
        var featureImages = this.$narrativeDT.data('feature-images');
        featureImages = featureImages.replace(/\r?\n|\r/g, '');
        featureImages = featureImages.replace(/ /g,'');
        featureImages = featureImages.split(',');

        SECTIONS_CONF[0].featureImage = featureImages[0];
        SECTIONS_CONF[1].featureImage = featureImages[1];
        SECTIONS_CONF[2].featureImage = featureImages[2];
        SECTIONS_CONF[2].subSections[0].featureImage = featureImages[2];
        SECTIONS_CONF[2].subSections[1].featureImage = featureImages[3];
        SECTIONS_CONF[2].subSections[2].featureImage = featureImages[4];
        SECTIONS_CONF[3].featureImage = featureImages[5];
        SECTIONS_CONF[3].subSections[0].featureImage = featureImages[5];
        SECTIONS_CONF[3].subSections[1].featureImage = featureImages[6];
        SECTIONS_CONF[3].subSections[2].featureImage = featureImages[7];
        SECTIONS_CONF[4].featureImage = featureImages[8];
    };

    module.exports = NarrativeView;

});
