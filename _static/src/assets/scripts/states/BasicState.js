define(function(require, exports, module) { // jshint ignore:line
    'use strict';

    var AbstractState = require('./AbstractState');
    var $ = require('jquery');

    /**
     * Manages the stack of active states
     *
     * @class BasicState
     * @extends AbstractState
     * @constructor
     */
    var BasicState = function(options) {
        this._activeComponents = [];
        this.refreshComponents = this.refreshComponents.bind(this);
        this.destroyComponents = this.destroyComponents.bind(this);
        AbstractState.call(this, options);
    };

    BasicState.prototype = Object.create(AbstractState.prototype);
    BasicState.prototype.constructor = BasicState;

    BasicState.prototype.COMPONENTS = {};

    BasicState.prototype.activate = function(event) {
        AbstractState.prototype.activate.call(this, event);
    };

    BasicState.prototype.deactivate = function() {
        this.destroyComponents();
        AbstractState.prototype.deactivate.call(this);
    };

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
    };

    BasicState.prototype.destroyComponents = function($element) {
        this._activeComponents.forEach(function(component) {
            component.$element.data('_initialized', false);
            component.destroy();
        }, this);
        this._activeComponents.length = 0;
    };

    return BasicState;

});
