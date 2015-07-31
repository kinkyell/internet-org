define(function(require, exports, module) { // jshint ignore:line
    'use strict';

    var AbstractState = require('./AbstractState');

    /**
     * Manages the stack of active states
     *
     * @class BasicState
     * @extends AbstractState
     * @constructor
     */
    var BasicState = function(options) {
        AbstractState.call(this, options);
    };

    BasicState.prototype = Object.create(AbstractState.prototype);
    BasicState.prototype.constructor = BasicState;

    return BasicState;

});
