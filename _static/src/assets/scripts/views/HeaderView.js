define(function(require, exports, module) { // jshint ignore:line
    'use strict';

    var AbstractView = require('./AbstractView');
    var breakpointManager = require('services/breakpointManager');
    var eventHub = require('services/eventHub');

    /**
     * A view for transitioning display panels
     *
     * @class MenuView
     * @param {jQuery} $element A reference to the containing DOM element.
     * @constructor
     */
    var MenuView = function($element) {
        AbstractView.call(this, $element);
    };

    var proto = AbstractView.createChild(MenuView);

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
        this._handleBreakpointChange = this._onBreakpointChange.bind(this);
        return this;
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
        this._numStates = 0; //TODO: update with initial states load
        this.$logo = this.$('.js-headerView-logo');
        this.$menuBtn = this.$('.js-headerView-menuBtn');
    };

    /**
     * Remove any child objects or references to DOM elements.
     *
     * @method removeChildren
     * @returns {MenuView}
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
     * @returns {MenuView}
     * @public
     */
    proto.layout = function() {
        this._render();
        this.$menuBtn.removeClass('u-disableTransitions').show();
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
        breakpointManager.subscribe(this._handleBreakpointChange);
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
        breakpointManager.unsubscribe(this._handleBreakpointChange);
    };

    /**
     * Updates classes and positioning
     *
     * @method _render
     * @public
     */
    proto._render = function() {
        var bp = breakpointManager.getBreakpoint();
        var isNarrow = bp === 'BASE' || bp === 'SM';
        var isHome = this._numStates < 1;

        // update stickinesss of header
        this.$logo.toggleClass('header-logo_invert', false);
        this.$menuBtn.toggleClass('header-menuBtn_invert', !isNarrow && isHome);
    };

    //////////////////////////////////////////////////////////////////////////////////
    // EVENT HANDLERS
    //////////////////////////////////////////////////////////////////////////////////

    /**
     * Sets the menu state after state change
     *
     * @method _onStateChange
     * @param {Array} states Active states
     * @private
     */
    proto._onStateChange = function(states) {
        this._numStates = states.length;
        this._render();
    };

    /**
     * Sets the menu state after breakpoint change
     *
     * @method _onBreakpointChange
     * @private
     */
    proto._onBreakpointChange = function() {
        this._render();
    };


    //////////////////////////////////////////////////////////////////////////////////
    // HELPERS
    //////////////////////////////////////////////////////////////////////////////////


    module.exports = MenuView;

});
