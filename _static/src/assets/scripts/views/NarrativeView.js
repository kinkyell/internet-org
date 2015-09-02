define(function(require, exports, module) { // jshint ignore:line
    'use strict';

    var $ = require('jquery');
    var AbstractView = require('./AbstractView');
    var breakpointManager = require('services/breakpointManager');
    var NarrativeMobileManager = require('services/NarrativeMobileManager');
    var NarrativeDesktopManager = require('services/NarrativeDesktopManager');
    var log = require('util/log');

    var CONFIG = {
        NARRATIVE_DT: '.narrativeDT',
        PROGRESS: '.narrative-progress',
        PROGRESS_HIDDEN: 'narrative-progress_isHidden'
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
        this.$narrativeSections = $('.narrative').find('> *');
        this.$progress = $(CONFIG.PROGRESS);
        this.$narrativeDT = this.$element.find(CONFIG.NARRATIVE_DT);
        this._$structureSections = $('.narrativeDT-sections');
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

        this._getSectionContent().then(function() {

            // determine bp specific narrative handle
            this._narrativeManager = (breakpointManager.isMobile) ?
                new NarrativeMobileManager(this._sectionConf) :
                new NarrativeDesktopManager(this._sectionConf);
        }.bind(this));
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
        $(window).on('mousewheel DOMMouseScroll', this._onWheelEventHandler);
        this.$body.on('touchstart', this._onTouchStartHandler);
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

        this.$body
            .on('touchmove' + this._eventTouchNamespace, this._onTouchMove.bind(this))
            .on('touchend' + this._eventTouchNamespace, this._onTouchEnd.bind(this))
            .on('touchcancel' + this._eventTouchNamespace, this._onTouchEnd.bind(this));
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

            if (breakpointManager.isMobile) {
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

                    this._narrativeManager.gotoSubSection(destinationSub, direction, section, true).then(function() {
                        this._subPosition -= 1;
                    }.bind(this));


                // subs pos IS 0
                // } else if (subPosition === 0) {
                //     this._narrativeManager.gotoSubSection(section, direction, section).then(function() {
                //         this._subPosition = destinationSubsLength;
                //     }.bind(this));

                // Anything Else
                } else {
                    this._subPosition = destinationSubsLength;

                    if (destinationSectionPos >= 0) {
                        this._narrativeManager.gotoSection(destinationSection, direction).then(function() {
                            this._position -= 1;
                        }.bind(this));
                    }
                }

            } else {

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

                    this._narrativeManager.gotoSubSection(destinationSub, direction, null, true).then(function() {
                        this._subPosition -= 1;
                    }.bind(this));


                // subs pos IS 0
                } else if (subPosition === 0) {
                    this._narrativeManager.gotoSubSection(section, direction, section).then(function() {
                        this._subPosition = -1;
                    }.bind(this));

                // Anything Else
                } else {
                    this._subPosition = destinationSubsLength - 1;

                    if (destinationSectionPos >= 0) {
                        this._narrativeManager.gotoSection(destinationSection, direction).then(function() {
                            this._position -= 1;
                        }.bind(this));
                    }
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

            if (breakpointManager.isMobile) {

                var direction = 'down';
                var section = this._sectionConf[this._position];
                var subsLength = section.subSections.length;
                var subPosition = this._subPosition;

                // if has subs
                // and subs pos is not at the end
                if (subsLength > 0 && subPosition < subsLength - 1) {
                    var destinationSubPos = subPosition + 1;
                    var destinationSub = section.subSections[destinationSubPos];

                    this._narrativeManager.gotoSubSection(destinationSub, direction, section, true).then(function() {
                        this._subPosition += 1;
                    }.bind(this)).catch(log);

                // Anything Else
                } else {
                    var sectionsLength = this._sectionConf.length;
                    var destinationSectionPos = this._position + 1;
                    var destinationSection = this._sectionConf[destinationSectionPos];
                    this._subPosition = -1;


                    if (destinationSectionPos < sectionsLength) {
                        this._narrativeManager.gotoSection(destinationSection, direction).then(function() {
                            this._position += 1;
                        }.bind(this)).catch(log);
                    }
                }

            } else {

                var direction = 'down';
                var section = this._sectionConf[this._position];
                var subsLength = section.subSections.length;
                var subPosition = this._subPosition;

                // if has subs
                // and subs pos is not at the end
                if (subsLength > 0 && subPosition < subsLength - 1) {
                    var destinationSubPos = subPosition + 1;
                    var destinationSub = section.subSections[destinationSubPos];

                    this._narrativeManager.gotoSubSection(destinationSub, direction, section, true).then(function() {
                        this._subPosition += 1;
                    }.bind(this));

                // Anything Else
                } else {
                    var sectionsLength = this._sectionConf.length;
                    var destinationSectionPos = this._position + 1;
                    var destinationSection = this._sectionConf[destinationSectionPos];
                    this._subPosition = -1;


                    if (destinationSectionPos < sectionsLength) {
                        this._narrativeManager.gotoSection(destinationSection, direction).then(function() {
                            this._position += 1;
                        }.bind(this)).catch(log);
                    }
                }

            }
        }
    };

    /**
     * Updates the slide styling classes
     *
     * @method _updateSlideHooks
     * @private
     */
    proto._updateSlideHooks = function() {
        var i = 0;
        var l = this.$narrativeSections.length;
        for (; i < l; i++) {
            this.$narrativeSections.eq(i).removeClass('isActive');
        }

        this.$narrativeSections.eq(this._position).addClass('isActive');
    };

    /**
     * Updates the slide indicators
     *
     * @method _updateIndicators
     * @private
     */
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

    /**
     * Updates the display of the slide
     * indicators
     *
     * @method _displayIndicators
     * @private
     */
    proto._displayIndicators = function() {
        var slidesLength = this._slidesLength;

        if (this._position === 0 || this._position === (slidesLength - 1)) {
            this.$progress.addClass(CONFIG.PROGRESS_HIDDEN);
        } else {
            this.$progress.removeClass(CONFIG.PROGRESS_HIDDEN);
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

    module.exports = NarrativeView;

});
