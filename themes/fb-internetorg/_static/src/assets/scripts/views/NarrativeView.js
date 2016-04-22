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
    var VideoModalView = require('views/VideoModalView');
    var assetLoader = require('services/assetLoader');
    var identity = require('stark/function/identity');
    var Tween = require('gsap-tween');

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
        this._onResizeHandler = debounce(this._onResize.bind(this), 500);
        this._onClickIndicatorHandler = this._onClickIndicator.bind(this);
        this._onMenuToggleHandler = this._onMenuToggle.bind(this);
        this._onTopScrollHandler = this._onTopScrollTrigger.bind(this);
        this._onKeyDownHandler = this._onKeyDown.bind(this);
        this._onSectionLinkFocusHandler = this._onSectionLinkFocus.bind(this);
        this._onBreakpointChangeHandler = this._onBreakpointChange.bind(this);
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
        this._$interactionPrompt = $('.interactionPrompt');
        this._$narrativeDTLinks = $('.transformBlock a');
        this._$narrativePoweredFooter = $('.pwdByVip-txt_front');
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
        this._$interactionPrompt = null;
        this._$narrativeDTLinks = null;
        this._$narrativePoweredFooter = null;
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
        this._getSectionContent().then(this.refreshNarrativeManager.bind(this, true)).catch(log);
        this.$viewWindow.before(this.$progress);
        this.$progress.find(':first-child').addClass('isActive');
        this._displayIndicators(0);
    };

    proto.refreshNarrativeManager = function(initial) {
        // determine bp specific narrative handle
        var isMobile = breakpointManager.isMobile;
        var NarrativeManager = isMobile ? NarrativeMobileManager : NarrativeDesktopManager;
        this._narrativeManager = new NarrativeManager(this._sectionConf);

        if (!initial) {
            if (typeof this._narrativeManager.refresh === 'function') {
                this._narrativeManager.refresh(this._position, this._subPosition);
            }
        }
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
        this._displayIndicators(0);
        this._updateIndicators(this._position);
        this.$narrative[0].scrollTop = this.scrollTop;
        this._currentlyMobile = breakpointManager.isMobile;
        breakpointManager.subscribe(this._onBreakpointChangeHandler);

        this._enableScrolling();
        eventHub.subscribe('MainMenu:change', this._onMenuToggleHandler);
    };

    /**
     * Enables the component scrolling.
     *
     * @method _enableScrolling
     * @private
     */
    proto._enableScrolling = function() {
        $(window).on('mousewheel DOMMouseScroll', this._onWheelEventHandler);
        $(window).on('touchstart' + this._eventTouchNamespace, this._onTouchStartHandler);
        window.addEventListener('resize', this._onResizeHandler);
        window.addEventListener('orientationchange', this._onResizeHandler);
        this._$narrativeAdvance.on('click', this._onClickAdvance.bind(this));
        this.$progress.on('click', '> *', this._onClickIndicatorHandler);
        eventHub.subscribe('Router:topScroll', this._onTopScrollHandler);
        $(document).on('keydown', this._onKeyDownHandler);
        this._$narrativeDTLinks.on('focus', this._onSectionLinkFocusHandler);
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
        Tween.set(this._$interactionPrompt, {autoAlpha: 0});
        this.$progress.hide();
        this.scrollTop = this.$narrative[0].scrollTop;
        this._disableScrolling();
        breakpointManager.unsubscribe(this._onBreakpointChangeHandler);
        eventHub.unsubscribe('MainMenu:change', this._onMenuToggleHandler);
    };

    /**
     * Disables the component scrolling.
     *
     * @method _disableScrolling
     * @private
     */
    proto._disableScrolling = function() {
        $(window).off('mousewheel DOMMouseScroll', this._onWheelEventHandler);
        $(window).off(this._eventTouchNamespace);
        window.removeEventListener('resize', this._onResizeHandler);
        window.removeEventListener('orientationchange', this._onResizeHandler);
        this._$narrativeAdvance.off('click');
        this.$progress.off('click', '> *', this._onClickIndicatorHandler);
        eventHub.unsubscribe('Router:topScroll', this._onTopScrollHandler);
        $(document).off('keydown', this._onKeyDownHandler);
        this._$narrativeDTLinks.on('focus', this._onSectionLinkFocusHandler);
    };

    //////////////////////////////////////////////////////////////////////////////////
    // EVENT HANDLERS
    //////////////////////////////////////////////////////////////////////////////////

    /**
     * Focus handler for narrative section links
     *
     * @method _onSectionLinkFocus
     * @private
     */
    proto._onSectionLinkFocus = function(event) {
        // var $link = $(event.currentTarget);
        // var $parent = $link.parents('.transformBlock-post-item');
        // var sectionPos = $parent.index() + 1; //accounting for the first section containing no links
        // this._changeSection(sectionPos);
    };

    /**
     * Window resize event handler
     *
     * @method _onResize
     * @param {obj} event the event object
     * @private
     */
    proto._onResize = function() {
        if (breakpointManager.isMobile) {
            this._narrativeManager.refresh(this._position, this._subPosition);
        }
    };

    proto._onBreakpointChange = function() {
        if (breakpointManager.isMobile !== this._currentlyMobile) {
            this._currentlyMobile = breakpointManager.isMobile;
            this.refreshNarrativeManager();

            if (typeof this._narrativeManager.orentationRefresh === 'function') {
                this._narrativeManager.orentationRefresh();
            }
        }
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

    proto._onKeyDown = function(e) {
        switch(e.which) {
            case 38: // up
                this._scrollUp();
                break;

            case 40: // down
                this._scrollDown();
                break;

            default: return; // exit this handler for other keys
        }
        e.preventDefault(); // prevent the default action (scroll / move caret)
    };

    /**
     * Utility function to change sections
     *
     * @method _changeSection
     * @private
     */
    proto._changeSection = function(pos) {
        if (this._position === pos) {
            return;
        }

        this._updateIndicators(pos);
        this._displayIndicators(pos);
        this._narrativeManager.gotoSection(this._position, pos).then(function(pos) {
            this._position = pos;
            eventHub.publish('Narrative:sectionChange', this._position);
        }.bind(this));
    };

    /**
     * Indicator click event handler
     *
     * @method _onClickIndicator
     * @private
     */
    proto._onClickIndicator = function(event) {
        if (this._narrativeManager._isAnimating) {
            return;
        }
        event.preventDefault();
        var $indicator = $(event.currentTarget);
        var pos = $indicator.index();
        this._changeSection(pos);
    };

    /**
     * Top scroll event handler
     *
     * @method _onTopScrollTrigger
     * @private
     */
    proto._onTopScrollTrigger = function() {
        this._changeSection(0);
    };

    /**
     * Scoll up to previous section
     *
     * @method _scrollUp
     * @private
     */
    proto._scrollUp = function(event) {
        if (!this._narrativeManager._isAnimating && this._position > 0) {
            var section = this._sectionConf[this._position];
            var subsLength = section.subSections.length;
            var subPosition = this._subPosition;

            var destinationSectionPos = this._position - 1;
            var destinationSection = this._sectionConf[destinationSectionPos];
            var destinationSubsLength = destinationSection.subSections.length;

            var currPos = this._position;
            var destPos = currPos - 1;

            var destSectionPos = this._position;
            var destSlidPos = this._subPosition -= 1;

            this._updateCtas(true);

            // if has subs
            // and subs pos MORE THAN 0
            if (subsLength > 0 && subPosition > 0) {
                this._narrativeManager.gotoSubSection(destSectionPos, destSlidPos).then(function(pos) {
                    this._subPosition = pos;
                    this._videoModalView = new VideoModalView($('.js-videoModal'));
                }.bind(this)).catch(log);

                if (subPosition === 0) {
                    this._updateCtas(false);
                }

            // Anything Else
            } else {
                this._subPosition = (breakpointManager.isMobile) ? destinationSubsLength : destinationSubsLength;
                this._updateIndicators(this._position - 1);
                this._displayIndicators(this._position - 1);

                if (destPos >= 0) {
                    this._narrativeManager.gotoSection(currPos, destPos).then(function(pos) {
                        this._position = pos;
                        eventHub.publish('Narrative:sectionChange', this._position);
                        this._videoModalView = new VideoModalView($('.js-videoModal'));
                    }.bind(this)).catch(log);
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
    proto._scrollDown = function(event) {
        if (!this._narrativeManager._isAnimating) {
            var section = this._sectionConf[this._position];
            var subsLength = section.subSections.length;
            var subPosition = this._subPosition;
            var sectionsLength = this._sectionConf.length;
            var destSectionPos = this._position;
            var destSlidPos = this._subPosition += 1;

            this._updateCtas(true);

            // if has subs
            // and subs pos is not at the end
            if (subsLength > 0 && subPosition < subsLength) {
                this._narrativeManager.gotoSubSection(destSectionPos, destSlidPos, subPosition).then(function(pos) {
                    this._subPosition = pos;
                    this._videoModalView = new VideoModalView($('.js-videoModal'));
                }.bind(this));

            // Anything Else
            } else {    

                this._subPosition = 0;
                this._updateIndicators(this._position + 1);
                this._displayIndicators(this._position + 1);

                var currPos = this._position;
                var destPos = currPos + 1;

                if (destPos < sectionsLength) {
                    this._narrativeManager.gotoSection(currPos, destPos).then(function(pos) {
                        this._position = pos;
                        eventHub.publish('Narrative:sectionChange', this._position);
                        this._videoModalView = new VideoModalView($('.js-videoModal'));
                    }.bind(this)).catch(log);
                }
            }
        }
    };

    proto._updateCtas = function(show) {
        $('.narrative-section-bd-link').toggle(show);
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

            var imageUrls = this._sectionConf.map(function(conf) {
                return conf.featureImage;
            }).filter(identity);

            var removeAssetShade = function() {
                var shade = document.querySelector('.js-assetShade');
                if (shade) {
                    Tween.to(shade, 0.25, {
                        onComplete: function() {
                            shade.parentNode.removeChild(shade);
                        },
                        opacity: 0
                    });
                }
            };

            assetLoader.loadImages(imageUrls).then(removeAssetShade, removeAssetShade);

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
        var slidesLength = this._sectionConf.length;
        if (pos < 0 || pos > (slidesLength - 1)) {
            return;
        }

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
        var slidesLength = this._sectionConf.length;

        Tween.killTweensOf([this._$interactionPrompt, this._$narrativePoweredFooter]);

        if (!breakpointManager.isMobile && pos < (slidesLength - 1)) {
            Tween.to(this._$interactionPrompt, 0.3, {autoAlpha: 1, delay: 0.3});
            Tween.to(this._$narrativePoweredFooter, 0.2, {autoAlpha: 0});
        } else {
            Tween.to(this._$interactionPrompt, 0.3, {autoAlpha: 0});
            Tween.to(this._$narrativePoweredFooter, 0.3, {autoAlpha: 1, delay: 0.6});
        }

        if (!breakpointManager.isMobile) {
            this.$progress.removeClass(CONFIG.PROGRESS_HIDDEN);
            return;
        }

        if (pos <= 0 || pos >= (slidesLength - 1)) {
            this.$progress.addClass(CONFIG.PROGRESS_HIDDEN);
        } else {
            this.$progress.removeClass(CONFIG.PROGRESS_HIDDEN);
        }
    };

    /**
     * menu toggle event handler
     *
     * @method _onMenuToggle
     * @private
     */
    proto._onMenuToggle = function(isOpen) {
        if (isOpen) {
            this._disableScrolling();
        } else {
            this._enableScrolling();
        }
    };

    module.exports = NarrativeView;

});
