define(function(require, exports, module) { // jshint ignore:line
    'use strict';

    var $ = require('jquery');
    var Scroll = {};//require('scroll');

    /**
     * A view for transitioning display panels
     *
     * @class PanelView
     * @param {jQuery} $element A reference to the containing DOM element.
     * @constructor
     */
    var PanelView = function($element) {

        console.log('panelView');

        if ($element.length === 0) { return; }

        if (!($element instanceof $)) {
            throw new TypeError('MenuView: jQuery object is required');
        }

        /**
         * A reference to the containing DOM element.
         *
         * @default null
         * @property $element
         * @type {jQuery}
         * @public
         */
        this.$element = $element;

        /**
         * Tracks whether component is enabled.
         *
         * @default false
         * @property isEnabled
         * @type {bool}
         * @public
         */
        this.isEnabled = false;

        this.init();
    };

    var proto = PanelView.prototype;

    /**
     * Initializes the UI Component View.
     * Runs a single setupHandlers call, followed by createChildren and layout.
     * Exits early if it is already initialized.
     *
     * @method init
     * @returns {PanelView}
     * @private
     */
    proto.init = function() {
        this.setupHandlers()
           .createChildren()
           .layout()
           .enable();

        return this;
    };


    /**
     * Binds the scope of any handler functions.
     * Should only be run on initialization of the view.
     *
     * @method setupHandlers
     * @returns {PanelView}
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
     * @returns {PanelView}
     * @private
     */
    proto.createChildren = function() {

        return this;
    };

    /**
     * Remove any child objects or references to DOM elements.
     *
     * @method removeChildren
     * @returns {PanelView}
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
     * @returns {PanelView}
     * @public
     */
    proto.layout = function() {
        return this;
    };

    /**
     * Enables the component.
     * Performs any event binding to handlers.
     * Exits early if it is already enabled.
     *
     * @method enable
     * @returns {PanelView}
     * @public
     */
    proto.enable = function() {
        if (this.isEnabled) {
            return this;
        }
        this.isEnabled = true;

        this.scroller = new Scroll();
        this.scroller.subscribe('scroll', function() {
            console.log('scroll');
        });

        return this;
    };

    /**
     * Disables the component.
     * Tears down any event binding to handlers.
     * Exits early if it is already disabled.
     *
     * @method disable
     * @returns {PanelView}
     * @public
     */
    proto.disable = function() {
        if (!this.isEnabled) {
            return this;
        }
        this.isEnabled = false;

        return this;
    };

    /**
     * Destroys the component.
     * Tears down any events, handlers, elements.
     * Should be called when the object should be left unused.
     *
     * @method destroy
     * @returns {PanelView}
     * @public
     */
    proto.destroy = function() {
        this.disable()
            .removeChildren();

        return this;
    };

    //////////////////////////////////////////////////////////////////////////////////
    // EVENT HANDLERS
    //////////////////////////////////////////////////////////////////////////////////


    //////////////////////////////////////////////////////////////////////////////////
    // HELPERS
    //////////////////////////////////////////////////////////////////////////////////


    module.exports = PanelView;

});
