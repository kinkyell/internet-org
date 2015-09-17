define(function(require, exports, module) { // jshint ignore:line
    'use strict';

    var AbstractView = require('./AbstractView');
    var breakpointManager = require('services/breakpointManager');
    var eventHub = require('services/eventHub');
    var Tween = require('gsap-tween');
    var $ = require('jquery');

    var SearchFormView = require('./SearchFormView');
    var SelectView = require('./SelectView');
    var LanguageView = require('./LanguageView');

    var SPEEDS = require('appConfig').animationSpeeds;
    var $window = $(window);

    /**
     * A view for displaying main menu
     *
     * @class MenuView
     * @param {jQuery} $element A reference to the containing DOM element.
     * @constructor
     */
    var MenuView = function($element) {
        AbstractView.call(this, $element);
    };

    var proto = AbstractView.createChild(MenuView);
    var $win = $window;

    /**
     * Binds the scope of any handler functions.
     * Should only be run on initialization of the view.
     *
     * @method setupHandlers
     * @returns {MenuView}
     * @private
     */
    proto.setupHandlers = function() {
        this._handleStateChange = this._onStateChange.bind(this);
        this._handleBgClick = this._onBgClick.bind(this);
        this._handleEscPress = this._onEscPress.bind(this);
        this._handleResize = this._onResize.bind(this);
    };

    /**
     * Create any child objects or references to DOM elements.
     * Should only be run on initialization of the view.
     *
     * @method createChildren
     * @returns {MenuView}
     * @private
     */
    proto.createChildren = function() {
        this.isOpen = false;
        this.$panel = this.$('.js-menuView-panel');
        this.$sliders = this.$('.js-menuView-slider');

        this.searchFormView = new SearchFormView(this.$('.js-searchFormView'));
        this.selectView = new SelectView(this.$('.js-select'), {
            prefix: 'langSelect',
            anchorBottom: true
        });
        this.langView = new LanguageView($('#js-LanguageView'));
    };

    /**
     * Remove any child objects or references to DOM elements.
     *
     * @method removeChildren
     * @returns {MenuView}
     * @public
     */
    proto.removeChildren = function() {
        this.$panel = null;
        this.$sliders = null;
    };

    /**
     * Performs any event binding to handlers.
     *
     * @method onEnable
     * @returns {MenuView}
     * @public
     */
    proto.onEnable = function() {
        eventHub.subscribe('StateStack:change', this._handleStateChange);
        $window.on('resize orientationchange', this._handleResize);
    };

    /**
     * Tears down any event binding to handlers.
     *
     * @method onDisable
     * @returns {MenuView}
     * @public
     */
    proto.onDisable = function() {
        eventHub.unsubscribe('StateStack:change', this._handleStateChange);
        $window.off('resize orientationchange', this._handleResize);
    };

    /**
     * Opens menu
     *
     * @method open
     * @public
     */
    proto.open = function() {
        var wrapperOpts = {
            onComplete: function() {
                this.isAnimating = false;
                // setTimeout(function() {
                //     this.element.removeAttribute('style');
                //     this.$element.css('min-height', $window.height());
                // }.bind(this), 1000);
            },
            callbackScope: this
        };
        var panelOpts = {};
        var directionInvert = document.documentElement.dir === 'ltr' ? 1 : -1;

        if (this.isOpen || this.isAnimating) {
            return;
        }

        this.isOpen = true;
        this.isAnimating = true;
        eventHub.publish('MainMenu:change', this.isOpen);
        eventHub.publish('MainMenu:open');
        this.$element.removeClass('u-isVisuallyHidden');

        this.$element.on('click', this._handleBgClick);
        $win.on('keyup', this._handleEscPress);

        if (breakpointManager.isMobile) {
            wrapperOpts.transform = 'scale(0.85)';
            wrapperOpts.opacity = 0;
        } else {
            wrapperOpts.backgroundColor = 'rgba(0, 0, 0, 0)';
            panelOpts.xPercent = directionInvert * 100;
            panelOpts.delay = SPEEDS.MENU_DELAY;
            Tween.from(this.$panel[0], SPEEDS.MENU_IN, panelOpts);
        }

        Tween.from(this.element, SPEEDS.MENU_IN, wrapperOpts);
        this._animateSliders(panelOpts.delay);

        this.$element.css('min-height', $window.height());
    };

    /**
     * Closes menu
     *
     * @method close
     * @public
     */
    proto.close = function() {
        var wrapperTween;
        var panelTween;
        var wrapperOpts = {
            onComplete: function() {
                this.$element.addClass('u-isVisuallyHidden');
                wrapperTween.progress(0);
                wrapperTween.kill();
                if (panelTween) {
                    panelTween.progress(0);
                    panelTween.kill();
                }
                this.isAnimating = false;
                this.$element.css('min-height', 0);
            },
            callbackScope: this
        };
        var panelOpts = {};
        var directionInvert = document.documentElement.dir === 'ltr' ? 1 : -1;

        if (!this.isOpen || this.isAnimating) {
            return;
        }
        this.isOpen = false;
        this.isAnimating = true;
        eventHub.publish('MainMenu:change', this.isOpen);
        eventHub.publish('MainMenu:close');

        this.$element.off('click', this._handleBgClick);
        $win.off('keyup', this._handleEscPress);

        if (breakpointManager.isMobile) {
            wrapperOpts.transform = 'scale(0.85)';
            wrapperOpts.opacity = 0;
        } else {
            wrapperOpts.backgroundColor = 'rgba(0, 0, 0, 0)';
            wrapperOpts.delay = SPEEDS.MENU_DELAY;
            panelOpts.xPercent = directionInvert * 100;
            panelTween = Tween.to(this.$panel[0], SPEEDS.MENU_OUT, panelOpts);
        }

        wrapperTween = Tween.to(this.element, SPEEDS.MENU_OUT, wrapperOpts);
    };

    /**
     * Toggles menu
     *
     * @method toggle
     * @public
     */
    proto.toggle = function() {
        return this.isOpen ? this.close() : this.open();
    };

    /**
     * Animates Slider section
     *
     * @method _animateSliders
     * @public
     */
    proto._animateSliders = function(baseDelay) {
        baseDelay = (baseDelay || 0) + SPEEDS.SLIDERS_STAGGER;
        this.$sliders.each(function(idx, el) {
            Tween.from(el, SPEEDS.SLIDERS_IN, {
                onComplete: function() {
                    var isIE = (document.all && !window.atob) || window.navigator.msPointerEnabled;
                    // IE bug fix
                    if (idx === this.$sliders.length - 1 && isIE) {
                        document.querySelector('.mainMenu').style.transform = 'scale(1)';
                    } else {
                        document.querySelector('.mainMenu').style.transform = null;
                    }
                },
                callbackScope: this,
                opacity: 0,
                xPercent: 30,
                delay: baseDelay + (idx * SPEEDS.SLIDERS_STAGGER)
            });
        }.bind(this));
    };

    //////////////////////////////////////////////////////////////////////////////////
    // EVENT HANDLERS
    //////////////////////////////////////////////////////////////////////////////////

    /**
     * Reset height on window resize
     *
     * @method _onResize
     * @param {Array} states Active states
     * @private
     */
    proto._onResize = function(states) {
        if (this.isOpen) {
            this.$element.css('min-height', $window.height());
        }
    };

    /**
     * Sets the menu state after state change
     *
     * @method _onStateChange
     * @param {Array} states Active states
     * @private
     */
    proto._onStateChange = function(states) {
        this.close();
    };

    /**
     * Close menu if background is clicked
     *
     * @method _onBgClick
     * @param {ClickEvent} event Click event
     * @private
     */
    proto._onBgClick = function(event) {
        if (!this.$panel[0].contains(event.target)) {
            this.close();
        }
    };

    /**
     * Close menu if background esc is pressed
     *
     * @method _onEscPress
     * @param {KeyboardEvent} event Key up event
     * @private
     */
    proto._onEscPress = function(event) {
        if (event.keyCode === 27) { // ESC: 27
            event.preventDefault();
            this.close();
        }
    };


    //////////////////////////////////////////////////////////////////////////////////
    // HELPERS
    //////////////////////////////////////////////////////////////////////////////////


    module.exports = MenuView;

});
