define(function(require, exports, module) { // jshint ignore:line
    'use strict';

    var $ = require('jquery');
    var AbstractView = require('./AbstractView');

    var CONFIG = {};

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
        return this;
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
        return this;
    };

    /**
     * Performs any event binding to handlers.
     *
     * @method onEnable
     * @returns {MenuView}
     * @public
     */
    proto.onEnable = function() {
        return this;
    };

    /**
     * Tears down any event binding to handlers.
     *
     * @method onDisable
     * @returns {MenuView}
     * @public
     */
    proto.onDisable = function() {
        return this;
    };

    //////////////////////////////////////////////////////////////////////////////////
    // EVENT HANDLERS
    //////////////////////////////////////////////////////////////////////////////////


    //////////////////////////////////////////////////////////////////////////////////
    // HELPERS
    //////////////////////////////////////////////////////////////////////////////////


    module.exports = MenuView;

});
