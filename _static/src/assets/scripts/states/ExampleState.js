define(function(require, exports, module) { // jshint ignore:line
    'use strict';

    var AbstractState = require('./AbstractState');

    /**
     * Manages the stack of active states
     *
     * @class ExampleState
     * @constructor
     */
    var ExampleState = function() {
        AbstractState.call(this);
    };

    ExampleState.prototype = Object.create(AbstractState.prototype);
    ExampleState.prototype.constructor = ExampleState;

    return ExampleState;

});
