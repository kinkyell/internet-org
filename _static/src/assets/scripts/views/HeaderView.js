define(function(require, exports, module) { // jshint ignore:line
    'use strict';

    var AbstractView = require('./AbstractView');
    var MenuView = require('./MenuView');
    var SearchView = require('./SearchView');
    var breakpointManager = require('services/breakpointManager');
    var eventHub = require('services/eventHub');
    var $ = require('jquery');

    /**
     * A view for transitioning display panels
     *
     * @class HeaderView
     * @param {jQuery} $element A reference to the containing DOM element.
     * @constructor
     */
    var HeaderView = function($element) {
        AbstractView.call(this, $element);
    };

    var proto = AbstractView.createChild(HeaderView);

    /**
     * Binds the scope of any handler functions.
     * Should only be run on initialization of the view.
     *
     * @method setupHandlers
     * @returns {HeaderView}
     * @private
     */
    proto.setupHandlers = function() {
        this._handleStateChange = this._onStateChange.bind(this);
        this._handleBreakpointChange = this._onBreakpointChange.bind(this);
        this._handleMenuBtnClick = this._onMenuBtnClick.bind(this);
        this._handleMenuChange = this._onMenuChange.bind(this);
        this._handleSearchToggle = this._onSearchToggle.bind(this);
        this._handleNarrativeChange = this._onNarrativeChange.bind(this);
        this._handleScrollBarrier = this._onScrollBarrier.bind(this);
    };

    /**
     * Create any child objects or references to DOM elements.
     * Should only be run on initialization of the view.
     *
     * @method createChildren
     * @returns {HeaderView}
     * @private
     */
    proto.createChildren = function() {
        this._isHome = true;
        this._invertLeft = false;
        this._invertRight = true;
        this._isFirstNarrative = true;
        this._pastBarrier = false;
        this.isLogoCentered = false;
        this.$logo = this.$('.js-headerView-logo');
        this.$menuBtn = this.$('.js-headerView-menuBtn');
        this.$menuBtnIcon = this.$menuBtn.find('.menuTrigger');
        this.$menuText = this.$('.js-headerView-menuBtn-text');
        this.$menuIcon = this.$('.js-headerView-menuBtn-icon');
        this.$backBtn = this.$('.js-headerView-backBtn');

        this.menuView = new MenuView($('.js-menuView'));
        this.searchView = new SearchView($('.js-searchView'));
    };

    /**
     * Remove any child objects or references to DOM elements.
     *
     * @method removeChildren
     * @returns {HeaderView}
     * @public
     */
    proto.removeChildren = function() {
        this.$menuView.destroy();
        this.$menuView = null;
        this.$menuBtn = null;
        this.$menuText = null;
        this.$menuIcon = null;
        this.$backBtn = null;
    };

    /**
     * Performs measurements and applys any positioning style logic.
     * Should be run anytime the parent layout changes.
     *
     * @method layout
     * @returns {HeaderView}
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
     * @returns {HeaderView}
     * @public
     */
    proto.onEnable = function() {
        eventHub.subscribe('StateStack:change', this._handleStateChange);
        eventHub.subscribe('MainMenu:change', this._handleMenuChange);
        eventHub.subscribe('Search:toggle', this._handleSearchToggle);
        eventHub.subscribe('Narrative:sectionChange', this._handleNarrativeChange);
        eventHub.subscribe('viewWindow:scrollBarrier', this._handleScrollBarrier);
        breakpointManager.subscribe(this._handleBreakpointChange);
        this.$menuBtn.on('click', this._handleMenuBtnClick);

        this.$menuBtn.on('mousedown', function(event) {
            event.preventDefault(); // prevent focus by mouse
        });
    };

    /**
     * Tears down any event binding to handlers.
     *
     * @method onDisable
     * @returns {HeaderView}
     * @public
     */
    proto.onDisable = function() {
        eventHub.unsubscribe('StateStack:change', this._handleStateChange);
        eventHub.unsubscribe('MainMenu:change', this._handleMenuChange);
        eventHub.unsubscribe('Search:toggle', this._handleSearchToggle);
        eventHub.unsubscribe('Narrative:sectionChange', this._handleNarrativeChange);
        eventHub.unsubscribe('viewWindow:scrollBarrier', this._handleScrollBarrier);
        breakpointManager.unsubscribe(this._handleBreakpointChange);
        this.$menuBtn.off('click', this._handleMenuBtnClick);
    };

    /**
     * Updates classes and positioning
     *
     * @method _render
     * @public
     */
    proto._render = function() {
        var isNarrow = breakpointManager.isMobile;
        var isHome = this._isHome;
        var isMenuOpen = this.menuView.isOpen;
        var isPastBarrier = this._pastBarrier;
        var isOnFirstNarrative = (isHome && this._isFirstNarrative);
        var shouldBeCentered = (isMenuOpen || !isHome);
        var shouldBeRaised = (isMenuOpen && this.searchView.isOpen);
        var shouldHaveBackBtn = (isNarrow && !isMenuOpen && !isHome);
        var shouldHaveInvertedLogo = (
            !isMenuOpen &&
            ((!isNarrow && this._invertLeft) || (isNarrow && isOnFirstNarrative))
        );
        var shouldHaveInvertedMenu = (!isMenuOpen && ((!isNarrow && this._invertRight) || isOnFirstNarrative));
        var shouldHaveMinimalLogo = shouldBeCentered || (isNarrow && !isOnFirstNarrative);

        // background bar visibility
        this.$element.toggleClass('isVisible', !isHome && !isMenuOpen && isPastBarrier);

        // invert logo when over imagery
        this.$logo.toggleClass('header-logo_invert', shouldHaveInvertedLogo);
        this.$backBtn.toggleClass('header-backBtn_invert', shouldHaveInvertedLogo);

        // update back btn
        this.$backBtn
            .toggleClass('header-backBtn_invert', shouldHaveInvertedLogo)
            .toggleClass('isActive', shouldHaveBackBtn)
            .attr('aria-hidden', shouldHaveBackBtn ? 'false' : 'true')
            .attr('tabindex', shouldHaveBackBtn ? '0' : '-1')

        // update menu btn icon
        this.$menuBtnIcon.toggleClass('menuTrigger_onDark', shouldHaveInvertedMenu);

        // hide menu text when open
        this.$menuText.toggleClass('u-isVisuallyHidden', isMenuOpen);

        // toggle icon
        this.$menuIcon.toggleClass('isOpen', isMenuOpen);

        // update logo
        this.$logo
            .toggleClass('header-logo_invert', shouldHaveInvertedLogo)
            .toggleClass('header-logo_min', shouldHaveMinimalLogo)
            .toggleClass('mix-header-logo_center', shouldBeCentered)
            .toggleClass('mix-header-logo_up', shouldBeRaised);
    };

    //////////////////////////////////////////////////////////////////////////////////
    // EVENT HANDLERS
    //////////////////////////////////////////////////////////////////////////////////

    /**
     * Sets the header state after state change
     *
     * @method _onStateChange
     * @param {Array} states Active states
     * @private
     */
    proto._onStateChange = function(states) {
        var topState = states[states.length - 1];
        this._isHome = topState.isHomeState();
        this._invertLeft = topState.invertLeft || false;
        this._invertRight = topState.invertRight || false;
        this._render();
    };

    /**
     * Sets the header state after breakpoint change
     *
     * @method _onBreakpointChange
     * @private
     */
    proto._onBreakpointChange = function() {
        this._render();
    };

    /**
     * Sets the header state after menu toggles
     *
     * @method _onMenuChange
     * @private
     */
    proto._onMenuChange = function() {
        this._render();
    };

    /**
     * Sets the header state after search toggles
     *
     * @method _onSearchToggle
     * @private
     */
    proto._onSearchToggle = function() {
        this._render();
    };

    /**
     * Sets the header state after search toggles
     *
     * @method _onScrollBarrier
     * @private
     */
    proto._onScrollBarrier = function(pastBarrier) {
        this._pastBarrier = pastBarrier;
        this._render();
    };

    /**
     * Sets the header state after breakpoint change
     *
     * @method _onMenuBtnClick
     * @private
     */
    proto._onMenuBtnClick = function() {
        this.menuView.toggle();
    };

    /**
     * Handle updates as narrative changes
     *
     * @method  _onNarrativeChange
     * @private
     * @param   {Number} section Section index
     */
    proto._onNarrativeChange = function(section) {
        this._isFirstNarrative = (section === 0);
        this._render();
    };


    //////////////////////////////////////////////////////////////////////////////////
    // HELPERS
    //////////////////////////////////////////////////////////////////////////////////


    module.exports = HeaderView;

});
