/**
 * A global breakpoint listener instance for events
 *
 * @fileoverview
 */
define(function(require, exports, module) { // jshint ignore:line
    'use strict';

    var Breakpoint = require('stark/micro/Breakpoint');

    // instance of breakpoint listener
    var bp = new Breakpoint({
        eventDelay: 0
    });

    // convenience flag for mobile
    var setMobile = function() {
        var current = bp.getBreakpoint();
        bp.isMobile = current === 'BASE' || current === 'SM';
    };

    // reset flag on breakpoint change
    bp.subscribe(setMobile);
    setMobile();

    return bp;

});
