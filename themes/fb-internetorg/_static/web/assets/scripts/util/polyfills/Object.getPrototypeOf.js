/**
 * Object.getPrototypeOf polyfill
 *
 * @fileoverview
 */
define(function(require, exports, module) { // jshint ignore:line
    'use strict';

    // polyfill getPrototype of
    if ( typeof Object.getPrototypeOf !== 'function' ) {
        if ( typeof 'test'.__proto__ === 'object' ) { //jshint ignore:line
            Object.getPrototypeOf = function(object){
                return object.__proto__; //jshint ignore:line
            };
        } else {
            Object.getPrototypeOf = function(object){
                // May break if the constructor has been tampered with
                return object.constructor.prototype;
            };
        }
    }

});
