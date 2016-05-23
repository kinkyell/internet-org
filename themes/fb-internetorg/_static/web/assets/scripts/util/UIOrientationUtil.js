define(function(require, exports, module) { // jshint ignore:line
    'use strict';

    var debounce = require('stark/function/debounce');

    /**
     * A util for addressing an issue in iOS 7.1.2
     * http://stackoverflow.com/questions/22391157/gray-area-visible-when-switching-from-portrait-to-landscape-using-ios-7-1-minima
     *
     * @class UIOrientationUtil
     * @param {jQuery} $element A reference to the containing DOM element.
     * @constructor
     */
    var UIOrientationUtil = function() {
        this.init();
    };

    var proto = UIOrientationUtil.prototype;

    /**
     * Initializes the UI Component View.
     * Runs a single setupHandlers call, followed by createChildren and layout.
     * Exits early if it is already initialized.
     *
     * @method init
     * @public
     */
    proto.init = function() {
        this.setupHandlers();
        this.onEnable();
    };

    /**
     * Binds the scope of any handler functions.
     * Should only be run on initialization of the view.
     *
     * @method setupHandlers
     * @public
     */
    proto.setupHandlers = function() {
        this._onorientationchangeHandler = debounce(this._onorientationchange.bind(this), 500);
    };

    /**
     * Enables the component.
     * Performs any event binding to handlers.
     * Exits early if it is already enabled.
     *
     * @method enable
     * @returns {NarrativeView}
     * @public
     */
    proto.onEnable = function() {
        window.addEventListener('orientationchange', this._onorientationchangeHandler);
    };

    /**
     * Disables the component.
     * Tears down any event binding to handlers.
     * Exits early if it is already disabled.
     *
     * @method disable
     * @public
     */
    proto.onDisable = function() {
        window.removeEventListener('orientationchange', this._onorientationchangeHandler);
    };

    //////////////////////////////////////////////////////////////////////////////////
    // EVENT HANDLERS
    //////////////////////////////////////////////////////////////////////////////////

    /**
     * orientationchange event handler
     *
     * @method _onorientationchange
     * @param {obj} event the event object
     * @private
     */
    proto._onorientationchange = function() {
        window.scrollTo(0, 0);
    };

    module.exports = UIOrientationUtil;

});
