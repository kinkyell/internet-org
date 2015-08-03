define(function(require, exports, module) { // jshint ignore:line
    'use strict';

    var AbstractView = require('./AbstractView');
    var $ = require('jquery');
    var Tween = require('gsap-tween');
    var breakpointManager = require('services/breakpointManager');
    var extend = require('stark/object/extend');
    var animSpeeds = require('appConfig').animationSpeeds;

    /**
     * A view for displaying main menu
     *
     * @class SelectView
     * @param {jQuery} $element A reference to the containing DOM element.
     * @constructor
     */
    var SelectView = function($element, options) {
        this._options = options || {};
        this._options.prefix = this._options.prefix || 'select';
        AbstractView.call(this, $element);
    };

    var proto = AbstractView.createChild(SelectView);

    /**
     * Binds the scope of any handler functions.
     * Should only be run on initialization of the view.
     *
     * @method setupHandlers
     * @returns {SelectView}
     * @private
     */
    proto.setupHandlers = function() {
        this._handleChange = this._onChange.bind(this);
        this._handleItemClick = this._onItemClick.bind(this);
        this._handleTriggerClick = this._onTriggerClick.bind(this);
        this._handleBodyClick = this._onBodyClick.bind(this);
        this._handleBodyKey = this._onBodyKey.bind(this);
        this._handleBreakpointChange = this._onBreakpointChange.bind(this);
    };

    /**
     * Create any child objects or references to DOM elements.
     * Should only be run on initialization of the view.
     *
     * @method createChildren
     * @returns {SelectView}
     * @private
     */
    proto.createChildren = function() {
        this.$element.wrap('<div class="' + this._options.prefix + '"></div>');
        this.$wrap = this.$element.parent();
        this.$label = $('<div class="' + this._options.prefix + '-label"></div>');
        this._isMenuOpen = false;
        this._isAnimating = false;

        this.$menu = $('<div class="' + this._options.prefix + '-menu"></div>');
        Array.prototype.forEach.call(this.element.options, function(el) {
            var $el = $('<div class="' + this._options.prefix + '-menu-item" tabIndex="0"></div>');
            var $textWrap = $('<span></span>').appendTo($el);
            var displayText = el.getAttribute('data-display');

            // set text
            if (displayText) {
                $textWrap.html(displayText);
            } else {
                $textWrap.text(el.innerText);
            }

            if (el === this.element.selectedOptions[0]) {
                $el.addClass('isSelected');
            }

            this.$menu.append($el);
        }, this);

        // move classes to wrapper
        this.$wrap.addClass(this.element.className);
        this.element.className = '';
    };

    /**
     * Remove any child objects or references to DOM elements.
     *
     * @method removeChildren
     * @returns {SelectView}
     * @public
     */
    proto.removeChildren = function() {
    };

    /**
     * Performs measurements and applys any positioning style logic.
     * Should be run anytime the parent layout changes.
     *
     * @method layout
     * @returns {SelectView}
     * @public
     */
    proto.layout = function() {
        this.$wrap
            .append(this.$label)
            .append(this.$menu);
    };

    /**
     * Performs any event binding to handlers.
     *
     * @method onEnable
     * @returns {SelectView}
     * @public
     */
    proto.onEnable = function() {
        this.$element
            .on('change', this._handleChange)
            .on('mousedown keydown', this._handleTriggerClick);
        this.$menu.on('click', '.' + this._options.prefix + '-menu-item', this._handleItemClick);
        breakpointManager.subscribe(this._handleBreakpointChange);
        this._render();
    };

    /**
     * Tears down any event binding to handlers.
     *
     * @method onDisable
     * @returns {SelectView}
     * @public
     */
    proto.onDisable = function() {
    };

    //////////////////////////////////////////////////////////////////////////////////
    // EVENT HANDLERS
    //////////////////////////////////////////////////////////////////////////////////

    /**
     * Rerender after change
     *
     * @method _onChange
     * @param {ChangeEvent} event Select change event
     * @private
     */
    proto._onChange = function(event) {
        this._render();
    };

    /**
     * Rerender after item click
     *
     * @method _onItemClick
     * @param {ChangeEvent} event Select change event
     * @private
     */
    proto._onItemClick = function(event) {
        var idx = $(event.currentTarget).index();
        this.$element.children().removeAttr('selected');
        this.element.options[idx].setAttribute('selected', '');
        this._render();
        this._toggleMenu();
    };

    /**
     * Rerender after trigger click
     *
     * @method _onTriggerClick
     * @param {ChangeEvent} event Select change event
     * @private
     */
    proto._onTriggerClick = function(event) {
        var openKey = event.keyCode === 32 || event.keyCode === 38 || event.keyCode === 40;

        if (
            breakpointManager.isMobile ||
            (event.type === 'keydown' && (!openKey || this._isMenuOpen)) || // SPACE
            (event.type === 'mousedown' && event.which !== 1) // left click
        ) {
            return;
        }

        event.preventDefault();


        this._toggleMenu();
    };

    /**
     * Rerender after trigger click
     *
     * @method _onMenuClose
     * @param {ChangeEvent} event Select change event
     * @private
     */
    proto._onMenuClose = function() {
        this.$wrap.removeClass('isOpen');
        Tween.set(this.$menu[0], {
            height: 'auto'
        });

        $(document.body)
            .off('click', this._handleBodyClick)
            .off('keydown', this._handleBodyKey);

        this.element.focus();
    };

    /**
     * Rerender after trigger click
     *
     * @method _onMenuOpen
     * @param {ChangeEvent} event Select change event
     * @private
     */
    proto._onMenuOpen = function() {
        this.$wrap.addClass('isOpen');

        var idx = this.element.selectedOptions[0].index;
        this.$menu.children().eq(idx)[0].focus();
        this._highlightedIdx = idx;

        $(document.body)
            .on('click', this._handleBodyClick)
            .on('keydown', this._handleBodyKey);
    };

    /**
     * Rerender after trigger click
     *
     * @method _onBodyClick
     * @param {ChangeEvent} event Select change event
     * @private
     */
    proto._onBodyClick = function(event) {
        if (!this.$wrap[0].contains(event.target)) {
            this._toggleMenu();
        }
    };

    /**
     * Rerender after trigger click
     *
     * @method _onBodyKey
     * @param {ChangeEvent} event Select change event
     * @private
     */
    proto._onBodyKey = function(event) {
        var key = event.keyCode;

        if (this._isAnimating) {
            return;
        }

        if (key === 27) { // ESC
            event.preventDefault();
            this._toggleMenu();
            return;
        }

        if (key === 32 || key === 13) { // SPACE || ENTER
            event.preventDefault();
            this._onOptionConfirm();
            return;
        }

        var movements = {
            37: -1, // LEFT ARROW
            38: -1, // UP ARROW
            39: 1, // RIGHT ARROW
            40: 1, // DOWN ARROW
            9: event.shiftKey ? -1 : 1, // TAB
            33: -5, // PAGE UP
            34: 5, // PAGE DOWN
            35: Infinity, // END
            36: -Infinity // HOME
        };
        if (key in movements) {
            event.preventDefault();
            this._advanceOption(movements[key]);
        }
    };

    /**
     * Handle select confirmation
     *
     * @method _onOptionConfirm
     * @param {ChangeEvent} event Select change event
     * @private
     */
    proto._onOptionConfirm = function() {
        var idx = this._highlightedIdx;
        this.$element.children().removeAttr('selected');
        this.element.options[idx].setAttribute('selected', '');
        this._render();
        this._toggleMenu();
    };

    /**
     * Close menu if going to mobile size
     *
     * @method _onBreakpointChange
     * @private
     */
    proto._onBreakpointChange = function() {
        if (this._isMenuOpen) {
            this._toggleMenu();
        }
    };


    //////////////////////////////////////////////////////////////////////////////////
    // HELPERS
    //////////////////////////////////////////////////////////////////////////////////

    /**
     * Update visual value of select box
     *
     * @method _render
     * @private
     */
    proto._render = function() {
        var option = this.element.selectedOptions[0];
        var displayText = option.getAttribute('data-display');

        if (displayText) {
            this.$label.html(displayText);
        } else {
            this.$label.text(option.innerText);
        }
    };

    /**
     * Toggle select menu
     *
     * @method _toggleMenu
     * @private
     */
    proto._toggleMenu = function() {
        var stopAnimating = function() {
            this._isAnimating = false;
        };
        var tweenOpts = {
            height: 0,
            onComplete: stopAnimating,
            callbackScope: this
        };
        var $menu = this.$menu[0];

        if (this._isAnimating) {
            return false;
        }
        this._isAnimating = true;

        if (this._isMenuOpen) {
            // animate
            extend(tweenOpts, {
                onComplete: function() {
                    stopAnimating.call(this);
                    this._onMenuClose();
                },
                callbackScope: this
            });
            Tween.to($menu, animSpeeds.SELECT_MENU, tweenOpts);
        } else {
            this._onMenuOpen();
            Tween.from($menu, animSpeeds.SELECT_MENU, tweenOpts);
        }

        this._isMenuOpen = !this._isMenuOpen;

    };

    /**
     * Advance selected option
     *
     * @method _advanceOption
     * @param {Number} num Number to advance the options
     * @private
     */
    proto._advanceOption = function(num) {
        var tryIdx = this._highlightedIdx + num;
        var max = this.element.options.length - 1;
        var min = 0;
        this._highlightedIdx = Math.max(Math.min(tryIdx, max), min);
        this.$menu.children().eq(this._highlightedIdx)[0].focus();
    };


    module.exports = SelectView;

});
