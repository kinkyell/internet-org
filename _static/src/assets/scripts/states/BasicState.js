define(function(require, exports, module) { // jshint ignore:line
    'use strict';

    var AbstractState = require('./AbstractState');

    /**
     * Manages the stack of active states
     *
     * @class BasicState
     * @constructor
     */
    var BasicState = function() {
        AbstractState.call(this);
    };

    BasicState.prototype = Object.create(AbstractState.prototype);
    BasicState.prototype.constructor = BasicState;

    return BasicState;

});
