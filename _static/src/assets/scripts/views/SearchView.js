define(function(require, exports, module) { // jshint ignore:line
    'use strict';

    var AbstractView = require('./AbstractView');
    var breakpointManager = require('services/breakpointManager');
    var eventHub = require('services/eventHub');
    var Tween = require('gsap-tween');
    var $ = require('jquery');

    var SPEEDS = require('appConfig').animationSpeeds;

    /**
     * A view for displaying main menu
     *
     * @class SearchView
     * @param {jQuery} $element A reference to the containing DOM element.
     * @constructor
     */
    var SearchView = function($element) {
        AbstractView.call(this, $element);
    };

    var proto = AbstractView.createChild(SearchView);

    /**
     * Binds the scope of any handler functions.
     * Should only be run on initialization of the view.
     *
     * @method setupHandlers
     * @returns {SearchView}
     * @private
     */
    proto.setupHandlers = function() {
        this._handleTriggerClick = this._onTriggerClick.bind(this);
        this._handleMenuClose = this._onMenuClose.bind(this);
        this._handleSubmit = this._onSubmit.bind(this);
    };

    /**
     * Create any child objects or references to DOM elements.
     * Should only be run on initialization of the view.
     *
     * @method createChildren
     * @returns {SearchView}
     * @private
     */
    proto.createChildren = function() {
        this.isOpen = false;
        this.$trigger = this.$('.js-searchView-trigger');
        this.$input = this.$('.js-searchView-input');
    };

    /**
     * Remove any child objects or references to DOM elements.
     *
     * @method removeChildren
     * @returns {SearchView}
     * @public
     */
    proto.removeChildren = function() {
        this.$trigger = null;
        this.$input = null;
    };

    /**
     * Performs measurements and applys any positioning style logic.
     * Should be run anytime the parent layout changes.
     *
     * @method layout
     * @returns {SearchView}
     * @public
     */
    proto.layout = function() {
    };

    /**
     * Performs any event binding to handlers.
     *
     * @method onEnable
     * @returns {SearchView}
     * @public
     */
    proto.onEnable = function() {
        this.$trigger.on('click', this._handleTriggerClick);
        this.$element.on('submit', this._handleSubmit);
        eventHub.subscribe('MainMenu:change', this._handleMenuClose);
    };

    /**
     * Tears down any event binding to handlers.
     *
     * @method onDisable
     * @returns {SearchView}
     * @public
     */
    proto.onDisable = function() {
        this.$trigger.off('click', this._handleTriggerClick);
        this.$element.off('submit', this._handleSubmit);
        eventHub.unsubscribe('MainMenu:change', this._handleMenuClose);
    };

    /**
     * Toggles menu
     *
     * @method toggle
     * @public
     */
    proto.toggle = function() {
        this.$input.toggleClass('isOpen', !this.isOpen);
        this.isOpen = !this.isOpen;

        if (this.isOpen) {
            this.$input[0].focus();
        }

        eventHub.publish('Search:toggle', this.isOpen);
    };

    /**
     * Toggles menu
     *
     * @method clear
     * @public
     */
    proto.clear = function() {
        this.$input.val('');
        if (this.isOpen) {
            this.toggle();
        }
    };

    //////////////////////////////////////////////////////////////////////////////////
    // EVENT HANDLERS
    //////////////////////////////////////////////////////////////////////////////////

    /**
     * Sets the menu state after state change
     *
     * @method _onTriggerClick
     * @param {Array} states Active states
     * @private
     */
    proto._onTriggerClick = function(event) {
        var bp = breakpointManager.getBreakpoint();
        var isMobile = bp === 'BASE' || bp === 'SM';

        if (!isMobile) {
            return;
        }

        event.preventDefault();
        this.toggle();
    };

    /**
     * Sets the menu state after state change
     *
     * @method _onSubmit
     * @param {Array} states Active states
     * @private
     */
    proto._onSubmit = function(event) {
        event.preventDefault();
        var searchText = this.$input.val().trim();
        if (!searchText) {
            return;
        }
        eventHub.publish('Search:submit', {
            searchText: searchText
        });
    };

    /**
     * Sets the menu state after breakpoint change
     *
     * @method _onMenuClose
     * @private
     */
    proto._onMenuClose = function() {
        this.clear();
    };


    //////////////////////////////////////////////////////////////////////////////////
    // HELPERS
    //////////////////////////////////////////////////////////////////////////////////


    module.exports = SearchView;

});
