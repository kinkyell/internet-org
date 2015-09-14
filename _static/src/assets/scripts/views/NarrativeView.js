define(function(require, exports, module) { // jshint ignore:line
    'use strict';

    var $ = require('jquery');
    var AbstractView = require('./AbstractView');
    var breakpointManager = require('services/breakpointManager');
    var NarrativeMobileManager = require('services/NarrativeMobileManager');
    var NarrativeDesktopManager = require('services/NarrativeDesktopManager');
    var log = require('util/log');
    var debounce = require('stark/function/debounce');
    var eventHub = require('services/eventHub');

    var CONFIG = {
        NARRATIVE_DT: '.narrativeDT',
        PROGRESS: '.narrativeView-progress',
        PROGRESS_HIDDEN: 'narrativeView-progress_isHidden'
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

        /**
         * configuration for section params
         *
         * @default empty
         * @property _sectionConf
         * @type {array}
         * @private
         */
        this._sectionConf = [];

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
     * Initializes the UI Component View.
     * Runs a single setupHandlers call, followed by createChildren and layout.
     * Exits early if it is already initialized.
     *
     * @method init
     * @returns {AbstractView}
     * @private
     */
    proto.init = function() {
        eventHub.publish('Narrative:sectionChange', 0);
    };

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
        this._onTouchMoveHandler = this._onTouchMove.bind(this);
        this._onTouchEndHandler = this._onTouchEnd.bind(this);
        this.refreshNarrativeManager = this.refreshNarrativeManager.bind(this);
        this._onResizeHandler = this._onResize.bind(this);
        this._onResizeHandler = debounce(this._onResizeHandler, 50);
        this._onClickIndicatorHandler = this._onClickIndicator.bind(this);
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
        this.$narrative = $('.narrative');
        this.scrollTop = 0;
        this.$body = $(document.body);
        this.$narrativeSections = this.$narrative.children();
        this.$progress = $(CONFIG.PROGRESS);
        this.$narrativeDT = this.$element.find(CONFIG.NARRATIVE_DT);
        this._$structureSections = $('.narrativeDT-sections');
        this.$viewWindow = $('.viewWindow');
        this._$narrativeAdvance = $('.js-narrativeAdvance');
    };

    /**
     * Remove any child objects or references to DOM elements.
     *
     * @method removeChildren
     * @returns {NarrativeView}
     * @public
     */
    proto.removeChildren = function() {
        this.$body = null;
        this.$narrativeSections = null;
        this.$progress = null;
        this.$narrativeDT = null;
        this._$structureSections = null;
        this.$viewWindow = null;
        this._$narrativeAdvance = null;
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
        this._sectionLength = this.$narrativeSections.length;
        this._getSectionContent().then(this.refreshNarrativeManager);
        this.$viewWindow.before(this.$progress);
        this.$progress.find(':first-child').addClass('isActive');
        this._displayIndicators(0);

    };

    proto.refreshNarrativeManager = function() {
        // determine bp specific narrative handle
        var isMobile = breakpointManager.isMobile;
        var NarrativeManager = isMobile ? NarrativeMobileManager : NarrativeDesktopManager;
        this._narrativeManager = new NarrativeManager(this._sectionConf);
        this._narrativeManager.refresh(this._position);
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
        this.$progress.show();
        this.$narrative[0].scrollTop = this.scrollTop;
        this._currentlyMobile = breakpointManager.isMobile;
        breakpointManager.subscribe(function() {
            if (breakpointManager.isMobile !== this._currentlyMobile) {
                this._currentlyMobile = breakpointManager.isMobile;
                this.refreshNarrativeManager();
            }
        }.bind(this));

        $(window).on('mousewheel DOMMouseScroll', this._onWheelEventHandler);
        $(window).on('touchstart' + this._eventTouchNamespace, this._onTouchStartHandler);
        window.addEventListener('resize', this._onResizeHandler);
        window.addEventListener('orientationchange', this._onResizeHandler);
        this._$narrativeAdvance.on('click', this._onClickAdvance.bind(this));
        this.$progress.on('click', '> *', this._onClickIndicatorHandler);
    };

    /**
     * Disables the component.
     * Tears down any event binding to handlers.
     * Exits early if it is already disabled.
     *
     * @method disable
     * @public
     */
    proto.onDisable = function() {
        this.$progress.hide();
        this.scrollTop = this.$narrative[0].scrollTop;
        $(window).off('mousewheel DOMMouseScroll', this._onWheelEventHandler);
        $(window).off(this._eventTouchNamespace);
        window.removeEventListener('resize', this._onResizeHandler);
        window.removeEventListener('orientationchange', this._onResizeHandler);
        this._$narrativeAdvance.off('click', this._onClickAdvance.bind(this));
        this.$progress.off('click', '> *', this._onClickIndicatorHandler);
    };

    //////////////////////////////////////////////////////////////////////////////////
    // EVENT HANDLERS
    //////////////////////////////////////////////////////////////////////////////////

    /**
     * Window resize event handler
     *
     * @method _onResize
     * @param {obj} event the event object
     * @private
     */
    proto._onResize = function() {
        this._narrativeManager.refresh(this._position);
    };

    /**
     * Mouse Wheel event handler
     *
     * @method _onWheelEvent
     * @param {obj} event the event object
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

    /**
     * Touchstart event handler
     *
     * @method _onTouchStart
     * @param {obj} e the event object
     * @private
     */
    proto._onTouchStart = function(e) {
        this._touchTracker.y = e.originalEvent.touches[0].pageY;

        $(window)
            .on('touchmove' + this._eventTouchNamespace, this._onTouchMoveHandler)
            .on('touchend' + this._eventTouchNamespace, this._onTouchEndHandler)
            .on('touchcancel' + this._eventTouchNamespace, this._onTouchEndHandler);
    };

    /**
     * Touchmove event handler
     *
     * @method _onTouchMove
     * @param {obj} e the event object
     * @private
     */
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

    /**
     * Touchend event handler
     *
     * @method _onTouchEnd
     * @param {obj} e the event object
     * @private
     */
    proto._onTouchEnd = function(e) {
        this.$body.off(this._eventTouchNamespace);
    };

    /**
     * Advance click event handler
     *
     * @method _onClickAdvance
     * @private
     */
    proto._onClickAdvance = function(event) {
        event.preventDefault();
        this._scrollDown();
    };

    /**
     * Indicator click event handler
     *
     * @method _onClickIndicator
     * @private
     */
    proto._onClickIndicator = function(event) {
        event.preventDefault();
        var $indicator = $(event.currentTarget);
        var pos = $indicator.index();
        this._narrativeManager.gotoSection(this._position, pos).then(function(pos) {
            this._position = pos;
        }.bind(this));
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
    proto._scrollUp = function(event) {
        if (!this._narrativeManager._isAnimating) {
            var direction = 'up';
            var section = this._sectionConf[this._position];
            var subsLength = section.subSections.length;
            var subPosition = this._subPosition;

            var destinationSectionPos = this._position - 1;
            var destinationSection = this._sectionConf[destinationSectionPos];
            var destinationSubsLength = destinationSection.subSections.length;

            // if has subs
            // and subs pos MORE THAN 0
            if (subsLength > 0 && subPosition > 0) {
                var destinationSubPos = subPosition - 1;
                var destinationSub = section.subSections[destinationSubPos];
                section = (breakpointManager.isMobile) ? section : null;

                this._narrativeManager.gotoSubSection(destinationSub, direction, section, true).then(function() {
                    this._subPosition -= 1;
                }.bind(this));

                this._updateCtas(false);

            // subs pos IS 0
            } else if (subPosition === 0 && breakpointManager.isMobile === false) {
                this._narrativeManager.gotoSubSection(section, direction, section).then(function() {
                    this._subPosition = -1;
                }.bind(this));

            // Anything Else
            } else {
                this._subPosition = (breakpointManager.isMobile) ? destinationSubsLength : destinationSubsLength - 1;
                this._updateIndicators(this._position - 1);
                this._displayIndicators(this._position - 1);

                if (destinationSectionPos >= 0) {
                    this._narrativeManager.gotoSection(destinationSection, direction).then(function() {
                        this._position -= 1;
                    }.bind(this));
                }

                this._updateCtas(true);
            }
        }
    };

    /**
     * Scoll down to next section
     *
     * @method _scrollDown
     * @private
     */
    proto._scrollDown = function(event) {
        if (!this._narrativeManager._isAnimating) {
            var direction = 'down';
            var section = this._sectionConf[this._position];
            var subsLength = section.subSections.length;
            var subPosition = this._subPosition;

            // if has subs
            // and subs pos is not at the end
            if (subsLength > 0 && subPosition < subsLength - 1) {
                var destinationSubPos = subPosition + 1;
                var destinationSub = section.subSections[destinationSubPos];
                section = (breakpointManager.isMobile) ? section : null;

                this._narrativeManager.gotoSubSection(destinationSub, direction, section, true).then(function() {
                    this._subPosition += 1;
                }.bind(this));

                this._updateCtas(false);

            // Anything Else
            } else {
                var sectionsLength = this._sectionConf.length;
                var destinationSectionPos = this._position + 1;
                var destinationSection = this._sectionConf[destinationSectionPos];
                this._subPosition = -1;
                this._updateIndicators(this._position + 1);
                this._displayIndicators(this._position + 1);

                if (destinationSectionPos < sectionsLength) {
                    this._narrativeManager.gotoSection(destinationSection, direction).then(function() {
                        this._position += 1;
                    }.bind(this)).catch(log);
                }

                this._updateCtas(true);
            }
        }
    };

    proto._updateCtas = function(show) {
        if (show) {
            $('.narrative-section-bd-link').show();
        } else {
            $('.narrative-section-bd-link').hide();
        }
    };

    /**
     * Poles DOM for image attributes and
     * assigns the paths to the config items
     *
     * @method _getFeatureImages
     * @private
     */
    proto._getSectionContent = function() {
        return new Promise(function(resolve) {
            var $sections = this._$structureSections.find('> li');
            var i = 0;
            var l = $sections.length;
            for (; i < l; i++) {
                var confItem = {};
                confItem.label = 'section0' + i;
                confItem.subSections = [];
                var $structureSection = $sections.eq(i);
                var featureImage = $structureSection.data('feature');
                var $subSections = $structureSection.find('> ul > li');
                var subsLength = $subSections.length;

                if (subsLength !== 0) {
                    var p = 0;
                    var pl = subsLength;
                    for (; p < pl; p++) {
                        var $subSection = $subSections.eq(p);
                        var subFeature = $subSection.data('feature');
                        var subContent = $subSection.html();

                        confItem.subSections.push({
                            featureImage: subFeature,
                            content: subContent
                        });
                    }

                }

                confItem.featureImage = featureImage;
                confItem.element = this.$narrativeSections.eq(i);

                this._sectionConf.push(confItem);
            }

            resolve();

        }.bind(this)).catch(log);
    };

    /**
     * Updates the slide indicators
     *
     * @method _updateIndicators
     * @private
     */
    proto._updateIndicators = function(pos) {
        var $progressIndicators = this.$progress.find('> *');
        var i = 0;
        var l = $progressIndicators.length;
        for (; i < l; i++) {
            var $progressIndicator = $progressIndicators.eq(i);
            $progressIndicator.removeClass('isActive');
        }

        this.$progress.find('> *').eq(pos).addClass('isActive');
    };

    /**
     * Updates the display of the slide
     * indicators
     *
     * @method _displayIndicators
     * @private
     */
    proto._displayIndicators = function(pos) {

        if (!breakpointManager.isMobile) {
            this.$progress.removeClass(CONFIG.PROGRESS_HIDDEN);
            return;
        }

        var slidesLength = this._sectionConf.length;

        if (pos === 0 || pos === (slidesLength - 1)) {
            this.$progress.addClass(CONFIG.PROGRESS_HIDDEN);
        } else {
            this.$progress.removeClass(CONFIG.PROGRESS_HIDDEN);
        }
    };

    module.exports = NarrativeView;

});
