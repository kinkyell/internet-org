/**
 * A global breakpoint listener instance for events
 *
 * @fileoverview
 */
define(function(require, exports, module) { // jshint ignore:line
    'use strict';

    var Breakpoint = require('stark/micro/Breakpoint');

    return new Breakpoint({
        eventDelay: 0
    });

});
