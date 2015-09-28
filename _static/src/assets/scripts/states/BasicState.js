define(function(require, exports, module) { // jshint ignore:line
    'use strict';

    var AbstractState = require('./AbstractState');
    var $ = require('jquery');

    /**
     * Manages the stack of active states
     *
     * @class BasicState
     * @param {Object} options State configuration options
     * @extends AbstractState
     * @constructor
     */
    var BasicState = function(options) {
        /**
         * List of active component views
         *
         * @default []
         * @property _activeComponents
         * @type {Array}
         * @private
         */
        this._activeComponents = [];

        /**
         * Tracks if menu should be inverted on the right
         *
         * @default false
         * @property invertRight
         * @type {bool}
         * @public
         */
        this.invertRight = false;

        /**
         * Tracks if menu should be inverted on the left
         *
         * @default false
         * @property invertLeft
         * @type {bool}
         * @public
         */
        this.invertLeft = false;

        /**
         * Determines if feature panel should be interactive
         *
         * @default false
         * @property doublePanel
         * @type {bool}
         * @public
         */
        this.doublePanel = false;

        this.refreshComponents = this.refreshComponents.bind(this);
        this.destroyComponents = this.destroyComponents.bind(this);

        AbstractState.call(this, options);
    };

    BasicState.prototype = Object.create(AbstractState.prototype);
    BasicState.prototype.constructor = BasicState;

    /**
     * List of components to initialize
     * @property COMPONENTS
     * @static
     * @type {Object}
     */
    BasicState.prototype.COMPONENTS = {};

    /**
     * Deactivate State
     *
     * @method deactivate
     * @fires State:activate
     */
    BasicState.prototype.deactivate = function() {
        // adds to inheritance chain. Use onActivate for state-specific tasks.
        this.destroyComponents();
        AbstractState.prototype.deactivate.call(this);
    };

    /**
     * Set up components for state
     *
     * @method refreshComponents
     * @param {jQuery} $element Element to search for components
     */
    BasicState.prototype.refreshComponents = function($element) {
        var componentSelectors = Object.keys(this.COMPONENTS);
        componentSelectors.forEach(function(selector) {
            Array.prototype.forEach.call($element.find(selector), function(el) {
                var $el = $(el);
                if ($el.data('_initialized')) {
                    return;
                }
                this._activeComponents.push(new this.COMPONENTS[selector]($el));
                $el.data('_initialized', true);
            }, this);
        }, this);

        // disable button outlines on click
        $element.find('button,input[type="button"],input[type="submit"]').on('mousedown', function(event) {
            event.preventDefault();
        });
    };

    /**
     * Destroy all components initialized in state
     *
     * @method destroyComponents
     */
    BasicState.prototype.destroyComponents = function($element) {
        this._activeComponents.forEach(function(component) {
            component.$element.data('_initialized', false);
            component.destroy();
        }, this);
        this._activeComponents.length = 0;
    };

    /**
     * Get directions for animation panels
     *
     * Rules:
     *  - going forward comes from the right side
     *  - going backward comes from the left
     *  - coming to or from the homepage does not animate the far right panel
     *
     * @method getAnimationDirections
     */
    BasicState.prototype.getAnimationDirections = function(event) {
        var featureTransition;
        var contentTransition;

        switch (event.method) {
        case 'pop':
            featureTransition = 'left';
            contentTransition = 'left';
            if (event.prevState && event.prevState.isHomeState()) {
                featureTransition = 'right';
                contentTransition = 'none';
            }
            break;
        case 'swap':
            featureTransition = 'bottom';
            contentTransition = 'bottom';
            break;
        case 'push':
            featureTransition = 'right';
            contentTransition = 'right';
            if (event.states[event.states.length - 1].isHomeState()) {
                featureTransition = 'left';
                contentTransition = 'none';
            }
            if (event.prevState && event.prevState.isHomeState()) {
                contentTransition = 'none';
            }
            break;
        }

        return {
            feature: featureTransition,
            content: contentTransition
        };
    };

    /**
     * Checks for home state
     *
     * @method isHomeState
     * @returns {Boolean}
     */
    BasicState.prototype.isHomeState = function() {
        return false;
    };

    return BasicState;

});
