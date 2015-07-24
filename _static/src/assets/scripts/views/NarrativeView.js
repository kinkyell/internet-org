define(function(require, exports, module) { // jshint ignore:line
    'use strict';

    var $ = require('jquery');
    require('gsap-cssPlugin');
    require('gsap-scrollToPlugin');
    require('gsap-tween');
    require('gsap-timeline');

    var CONFIG = {};

    /**
     * A view for transitioning display panels
     *
     * @class NarrativeView
     * @param {jQuery} $element A reference to the containing DOM element.
     * @constructor
     */
    var NarrativeView = function($element) {
        if ($element.length === 0) { return; }

        if (!($element instanceof $)) {
            throw new TypeError('MenuView: jQuery object is required');
        }

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
         * Tracks whether component is enabled.
         *
         * @default false
         * @property isEnabled
         * @type {bool}
         * @public
         */
        this.isEnabled = false;

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
         * Speed in milleseconds to provide animation timing
         *
         * @default 650
         * @property _scrollSpeed
         * @type {bool}
         * @private
         */
        this._scrollSpeed = 250;

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
         * reference to the total number of slides
         *
         * @default null
         * @property _slidesLength
         * @type {bool}
         * @private
         */
        this._slidesLength = null;

        this.init();
    };

    var proto = NarrativeView.prototype;

    /**
     * Initializes the UI Component View.
     * Runs a single setupHandlers call, followed by createChildren and layout.
     * Exits early if it is already initialized.
     *
     * @method init
     * @returns {NarrativeView}
     * @private
     */
    proto.init = function() {
        this.setupHandlers()
           .createChildren()
           .layout()
           .enable();

        return this;
    };


    /**
     * Binds the scope of any handler functions.
     * Should only be run on initialization of the view.
     *
     * @method setupHandlers
     * @returns {NarrativeView}
     * @private
     */
    proto.setupHandlers = function() {
        this._onWheelEventHandler = this._onWheelEvent.bind(this);

        this._onPrevSlideClickHandler = this._onPrevSlideClick.bind(this);
        this._onNextSlideClickHandler = this._onNextSlideClick.bind(this);

        return this;
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
        this.$narrativeSections = this.$element.find('> *');

        return this;
    };

    /**
     * Remove any child objects or references to DOM elements.
     *
     * @method removeChildren
     * @returns {NarrativeView}
     * @public
     */
    proto.removeChildren = function() {

        return this;
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
        this._slidesLength = this.$narrativeSections.length;

        return this;
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
    proto.enable = function() {
        if (this.isEnabled) {
            return this;
        }
        this.isEnabled = true;

        $(window).on('wheel', this._onWheelEventHandler);

        $('.js-prev').on('click', this._onPrevSlideClickHandler);
        $('.js-next').on('click', this._onNextSlideClickHandler);

        return this;
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
    proto.disable = function() {
        if (!this.isEnabled) {
            return this;
        }
        this.isEnabled = false;

        return this;
    };

    /**
     * Destroys the component.
     * Tears down any events, handlers, elements.
     * Should be called when the object should be left unused.
     *
     * @method destroy
     * @returns {NarrativeView}
     * @public
     */
    proto.destroy = function() {
        this.disable()
            .removeChildren();

        return this;
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
        event.preventDefault();
        var originalEvent = event.originalEvent;
        var deltaY = this._normalizeDelta(originalEvent.deltaY);

        if(this._direction === 'down' && deltaY > this._factor) {
            this._scrollDown();
        } else if(this._direction === 'up' && deltaY > this._factor) {
            // this._scrollUp();
        }
    };

    proto._onPrevSlideClick = function() {
        event.preventDefault();

        var prevSlidePos = this._position - 1;
        this._gotoSlide(prevSlidePos);
    };

    proto._onNextSlideClick = function(event) {
        event.preventDefault();

        var nextSlidePos = this._position + 1;
        this._gotoSlide(nextSlidePos);
    };

    proto._gotoSlide = function(position) {
        if (position >= this._slidesLength || position < 0) {
            return;
        }

        var $detinationSection = $('.narrative-section').eq(position);
        var $sectionBody = $detinationSection.find('.narrative-section-bd');

        var i = 0
        var offsetY = 0;
        for (; i < position; i++) {
            offsetY += $('.narrative-section').eq(i).height();
        }

        if (position > this._position) {
            var bdTwn = TweenLite.from($sectionBody, 0.5, {top: '50%'});
        } else {
            var bdTwn = TweenLite.from($sectionBody, 0.5, {top: '-50%'});
        }

        var tl = new TimelineLite();

        tl.add(bdTwn);

        if (position === 1 && this._position === 0) {
            var opacTwn = TweenLite.from($sectionBody, 0.5, {opacity: 0});
            tl.add(opacTwn, 0);
        }

        tl.to($('.narrative'), 0.35, { scrollTo: { y: offsetY }, onComplete: function() {
            this._position = position;
        }.bind(this) }, '-=0.5');
    };

    //////////////////////////////////////////////////////////////////////////////////
    // HELPERS
    //////////////////////////////////////////////////////////////////////////////////
    /**
     * normalizes wheel event delta
     *
     * @method _normalizeDelta
     * @param {num} deltaY delta returned from wheel event object
     * @private
     */
    proto._normalizeDelta = function(deltaY) {
        var _deltaY = deltaY;

        if (deltaY > 0) {
            this._direction = 'up';
        } else {
            this._direction = 'down';
            var _deltaY = _deltaY * -1;
        }

        return _deltaY;
    };

    /**
     * Scoll specific section
     *
     * @method _scrollTo
     * @param {num} offsetY the offset to scroll to
     * @param {function} callback a method to call upon completion
     * @private
     */
    proto._scrollTo = function(offsetY, callback) {
        this._isAnimating = true;
        $(window).off('wheel', this._onWheelEventHandler);
        $('.narrative').animate({ scrollTop: offsetY }, this._scrollSpeed, callback);
    };

    /**
     * Scoll down to next section
     *
     * @method _scrollDown
     * @private
     */
    proto._scrollDown = function() {
        if (this._isAnimating) {
            return;
        }

        var $currentSection = $('.narrative-section.isActive');
        var $nextSection = $('.narrative-section.isActive').next();
        var offsetY = $nextSection.position().top;

        console.log('scrollto: ', offsetY);

        this._scrollTo(offsetY, function() {
            console.log('callback', this);
            this._isAnimating = false;
            $(window).on('wheel', this._onWheelEventHandler);

            $currentSection.removeClass('isActive');
            $nextSection.addClass('isActive');
        }.bind(this));
    };

    /**
     * Scoll up to previous section
     *
     * @method _scrollUp
     * @private
     */
    proto._scrollUp = function() {

    };

    module.exports = NarrativeView;

});



















// /////////////////////////////////////////////////
// $('.block').css('height', window.innerHeight);

// var lockScroll = function(){
//     $('body').addClass('no-scroll');
// }

// var unlockScroll = function(){
//     $('body').removeClass('no-scroll');
// }

// var down = true,
//         animating = false
//         factor = 5,
//         scrollSpeed = 650;

// var normalizeDelta = function(deltaY){
//     var _deltaY = deltaY;
//     if(deltaY > 0)
//     {
//         down = false;
//     }
//     else
//     {
//         down = true;
//         _deltaY = _deltaY * -1;
//     }
//     return _deltaY;
// }

// lockScroll();
// window.scroll(0, 0);

// var scrollTo = function(offsetY, callback){
//     animating = true;
//     $('html, body').animate({ scrollTop: offsetY }, scrollSpeed, callback);
// }

// var scrollDown = function(){
//     if(animating == false)
//     {
//         var scrollPos = $(window).scrollTop(),
//                 $activeBlock = $('.block.active'),
//                 activeIndex = $('.block').index($activeBlock),
//                 $nextBlock = $('.block').eq(activeIndex + 1);

//         if($nextBlock.length)
//         {
//             $(window).off('mousewheel', detectScrolls);
//             scrollTo($nextBlock.offset().top, function(){
//                 animating = false;
//                 $activeBlock.removeClass('active');
//                 $nextBlock.addClass('active');
//                 $(window).on('mousewheel', detectScrolls);
//             });
//         }
//     }
// }

// var scrollUp = function(){
//     if(animating == false)
//     {
//         var scrollPos = $(window).scrollTop(),
//                 $activeBlock = $('.block.active'),
//                 activeIndex = $('.block').index($activeBlock),
//                 $prevBlock = $('.block').eq(activeIndex - 1);

//         if(activeIndex > 0)
//         {
//             $(window).off('mousewheel', detectScrolls);
//             scrollTo(scrollPos - window.innerHeight, function(){
//                 animating = false;
//                 $activeBlock.removeClass('active');
//                 $prevBlock.addClass('active');
//                 $(window).on('mousewheel', detectScrolls);
//             });
//         }
//     }
// }

// var detectScrolls = function(e){

//     var deltaY = normalizeDelta(e.deltaY)
//     if(down === true && deltaY > factor)
//     {
//         scrollDown();
//     }
//     else if(down === false && deltaY > factor)
//     {
//         scrollUp();
//     }

// }

// $(window).on('mousewheel', detectScrolls);