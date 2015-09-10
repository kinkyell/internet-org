define(function(require, exports, module) { // jshint ignore:line
    'use strict';

    var AbstractView = require('./AbstractView');
    var $ = require('jquery');
    var Tween = require('gsap-tween');
    var breakpointManager = require('services/breakpointManager');
    var extend = require('stark/object/extend');
    var animSpeeds = require('appConfig').animationSpeeds;
    var eventHub = require('services/eventHub');

    /**
     * A view for displaying main menu
     *
     * @class CustomRadioView
     * @param {jQuery} $element A reference to the containing DOM element.
     * @param {Object} options Select configuration options
     * @param {String} options.prefix Css selector prefix
     * @constructor
     */
    var CustomRadioView = function($element, options) {
        this._options = options || {};
        this._options.prefix = this._options.prefix || 'select';
        AbstractView.call(this, $element);
    };

    var proto = AbstractView.createChild(CustomRadioView);

    /**
     * Binds the scope of any handler functions.
     * Should only be run on initialization of the view.
     *
     * @method setupHandlers
     * @returns {CustomRadioView}
     * @private
     */
    proto.setupHandlers = function() {
    };

    /**
     * Performs any event binding to handlers.
     *
     * @method onEnable
     * @returns {CustomRadioView}
     * @public
     */
    proto.onEnable = function() {
        this.$element.after('<span />');
    };

    /**
     * Tears down any event binding to handlers.
     *
     * @method onDisable
     * @returns {CustomRadioView}
     * @public
     */
    proto.onDisable = function() {
    };

    //////////////////////////////////////////////////////////////////////////////////
    // EVENT HANDLERS
    //////////////////////////////////////////////////////////////////////////////////


    //////////////////////////////////////////////////////////////////////////////////
    // HELPERS
    //////////////////////////////////////////////////////////////////////////////////


    module.exports = CustomRadioView;

});
