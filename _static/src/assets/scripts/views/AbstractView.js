define(function(require, exports, module) { // jshint ignore:line
    'use strict';

    var $ = require('jquery');

    var noop = function() {};

    /**
     * A view for transitioning display panels
     *
     * @class AbstractView
     * @param {jQuery} $element A reference to the containing DOM element.
     * @constructor
     */
    var AbstractView = function($element) {
        var thisProto = Object.getPrototypeOf(this);
        if (thisProto === AbstractView.prototype) {
            throw new TypeError('AbstractView should not be initialized directly.');
        }

        if (this.enable !== proto.enable || this.disable !== proto.disable) {
            throw new Error('AbstractView: do not override enable or disable. Please use onEnable and onDisable.');
        }

        if ($element.length === 0) { return; }

        if (!($element instanceof $)) {
            throw new TypeError('AbstractView: jQuery object is required');
        }

        /**
         * A reference to the containing jQuery element.
         *
         * @default null
         * @property $element
         * @type {jQuery}
         * @public
         */
        this.$element = $element;

        /**
         * A reference to the containing DOM element.
         *
         * @default null
         * @property $element
         * @type {Element}
         * @public
         */
        this.element = $element[0];

        /**
         * Tracks whether component is enabled.
         *
         * @default false
         * @property isEnabled
         * @type {bool}
         * @public
         */
        this.isEnabled = false;

        this._bootStrapView();
    };

    var proto = AbstractView.prototype;

    /**
     * Initializes the UI Component View.
     * Runs init, a single setupHandlers call, followed by createChildren and layout.
     * Exits early if it is already initialized.
     *
     * @method _bootStrapView
     * @returns {AbstractView}
     * @private
     */
    proto._bootStrapView = function() {
        this.init();
        this.setupHandlers();
        this.createChildren();
        this.layout();
        this.enable();
    };

    /**
     * Initializes the UI Component View.
     * Runs a single setupHandlers call, followed by createChildren and layout.
     * Exits early if it is already initialized.
     *
     * @method init
     * @returns {AbstractView}
     * @private
     */
    proto.init = noop;


    /**
     * Binds the scope of any handler functions.
     * Should only be run on initialization of the view.
     *
     * @method setupHandlers
     * @returns {AbstractView}
     * @private
     */
    proto.setupHandlers = noop;

    /**
     * Create any child objects or references to DOM elements.
     * Should only be run on initialization of the view.
     *
     * @method createChildren
     * @returns {AbstractView}
     * @private
     */
    proto.createChildren = noop;

    /**
     * Remove any child objects or references to DOM elements.
     *
     * @method removeChildren
     * @returns {AbstractView}
     * @public
     */
    proto.removeChildren = noop;

    /**
     * Performs measurements and applys any positioning style logic.
     * Should be run anytime the parent layout changes.
     *
     * @method layout
     * @returns {AbstractView}
     * @public
     */
    proto.layout = noop;

    /**
     * Enables the component.
     * Exits early if it is already enabled.
     *
     * @method enable
     * @returns {AbstractView}
     * @public
     */
    proto.enable = function() {
        if (this.isEnabled) {
            return this;
        }
        this.isEnabled = true;

        return this.onEnable();
    };

    /**
     * Performs any event binding to handlers.
     *
     * @method onEnable
     * @returns {AbstractView}
     * @public
     */
    proto.onEnable = noop;

    /**
     * Disables the component.
     * Exits early if it is already disabled.
     *
     * @method disable
     * @returns {AbstractView}
     * @public
     */
    proto.disable = function() {
        if (!this.isEnabled) {
            return this;
        }
        this.isEnabled = false;

        return this.onDisable();
    };

    /**
     * Tears down any event binding to handlers.
     *
     * @method onDisable
     * @returns {AbstractView}
     * @public
     */
    proto.onDisable = noop;

    /**
     * Destroys the component.
     * Tears down any events, handlers, elements.
     * Should be called when the object should be left unused.
     *
     * @method destroy
     * @returns {AbstractView}
     * @public
     */
    proto.destroy = function() {
        this.disable();
        this.removeChildren();

        return this;
    };

    /**
     * Shortcut menthod for this.$element.find()
     *
     * @method $
     * @returns {jQuery}
     * @public
     */
    proto.$ = function(selector) {
        return this.$element.find(selector);
    };

    //////////////////////////////////////////////////////////////////////////////////
    // EVENT HANDLERS
    //////////////////////////////////////////////////////////////////////////////////


    //////////////////////////////////////////////////////////////////////////////////
    // HELPERS
    //////////////////////////////////////////////////////////////////////////////////

    /**
     * Create a child view
     *
     * @method createChild
     * @static
     * @param {AbstractView} ChildView Constructor for child view subclass
     * @returns {Object} new prototype of child view
     * @public
     */
    AbstractView.createChild = function createChild(ChildView) {
        ChildView.prototype = Object.create(AbstractView.prototype);
        ChildView.prototype.constructor = ChildView;
        return ChildView.prototype;
    };

    module.exports = AbstractView;

});
