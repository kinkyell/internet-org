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
    var SelectView = function($element) {
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
        this.$element.wrap('<div class="select"></div>');
        this.$wrap = this.$element.parent();
        this.$label = $('<div class="select-label"></div>');
        this._isMenuOpen = this.$element.hasClass('isOpen');
        this._isAnimating = false;

        this.$menu = $('<div class="select-menu"></div>');
        Array.prototype.forEach.call(this.element.options, function(el) {
            var $el = $('<div class="select-menu-item"></div>');
            var displayText = el.getAttribute('data-display');

            // set text
            if (displayText) {
                $el.html(displayText);
            } else {
                $el.text(el.innerText);
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
        this.$menu.on('click', '.select-menu-item', this._handleItemClick);
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
        if (
            breakpointManager.isMobile ||
            (event.type === 'keydown' && event.keyCode !== 32) || // SPACE
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
        if (event.keyCode === 27) { // ESC
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


    module.exports = SelectView;

});
