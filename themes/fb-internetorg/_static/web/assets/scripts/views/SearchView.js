define(function(require, exports, module) { // jshint ignore:line
    'use strict';

    var AbstractView = require('./AbstractView');
    var breakpointManager = require('services/breakpointManager');
    var eventHub = require('services/eventHub');

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
     * Performs any event binding to handlers.
     *
     * @method onEnable
     * @returns {SearchView}
     * @public
     */
    proto.onEnable = function() {
        this.$trigger.on('click', this._handleTriggerClick);
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
        eventHub.unsubscribe('MainMenu:change', this._handleMenuClose);
    };

    /**
     * Toggles menu
     *
     * @method toggle
     * @fires Search:toggle
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
     * Clears menu input and closes
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
     * Toggle search menu on click
     *
     * @method _onTriggerClick
     * @param {ClickEvent} event Click event
     * @private
     */
    proto._onTriggerClick = function(event) {
        if (!breakpointManager.isMobile) {
            return;
        }

        event.preventDefault();
        this.toggle();
    };

    /**
     * Clear the input when menu closes
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
