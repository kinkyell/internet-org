define(function(require, exports, module) { // jshint ignore:line
    'use strict';

    var AbstractView = require('./AbstractView');
    var eventHub = require('services/eventHub');

    /**
     * A view for displaying main menu
     *
     * @class SearchFormView
     * @param {jQuery} $element A reference to the containing DOM element.
     * @constructor
     */
    var SearchFormView = function($element) {
        AbstractView.call(this, $element);
    };

    var proto = AbstractView.createChild(SearchFormView);

    /**
     * Binds the scope of any handler functions.
     * Should only be run on initialization of the view.
     *
     * @method setupHandlers
     * @returns {SearchFormView}
     * @private
     */
    proto.setupHandlers = function() {
        this._handleSubmit = this._onSubmit.bind(this);
    };

    /**
     * Create any child objects or references to DOM elements.
     * Should only be run on initialization of the view.
     *
     * @method createChildren
     * @returns {SearchFormView}
     * @private
     */
    proto.createChildren = function() {
        this.$input = this.$('.js-searchView-input');
    };

    /**
     * Remove any child objects or references to DOM elements.
     *
     * @method removeChildren
     * @returns {SearchFormView}
     * @public
     */
    proto.removeChildren = function() {
        this.$input = null;
    };

    /**
     * Performs measurements and applys any positioning style logic.
     * Should be run anytime the parent layout changes.
     *
     * @method layout
     * @returns {SearchFormView}
     * @public
     */
    proto.layout = function() {
    };

    /**
     * Performs any event binding to handlers.
     *
     * @method onEnable
     * @returns {SearchFormView}
     * @public
     */
    proto.onEnable = function() {
        this.$element.on('submit', this._handleSubmit);
    };

    /**
     * Tears down any event binding to handlers.
     *
     * @method onDisable
     * @returns {SearchFormView}
     * @public
     */
    proto.onDisable = function() {
        this.$element.off('submit', this._handleSubmit);
    };

    //////////////////////////////////////////////////////////////////////////////////
    // EVENT HANDLERS
    //////////////////////////////////////////////////////////////////////////////////

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


    //////////////////////////////////////////////////////////////////////////////////
    // HELPERS
    //////////////////////////////////////////////////////////////////////////////////


    module.exports = SearchFormView;

});
