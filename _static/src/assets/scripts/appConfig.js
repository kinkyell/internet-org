/**
 * A global configuration for app constants
 *
 * @fileoverview
 */
define(function(require, exports, module) { // jshint ignore:line
    'use strict';

    var ClassDict = require('util/ClassDict');

    // Global Class name references
    //
    // NOTE: Do NOT include the '.' before the classname. This will be added
    // to the object with a _SELECTOR suffix.
    //
    // NOTE: Only include global classes here. If it's specific to a view,
    // create a ClassDict in the view itself
    //
    var classes = {
        EXAMPLE_CLASS: 'className'
    };


    // Additional Variables
    var appConfig = {
        classes: new ClassDict(classes),
        apiBase: '',
        animationSpeeds: {
            SLIDE_IN: 0.3, // panel sliding in
            SLIDE_OUT: 0.3, // panel sliding out
            SWAP_IN: 0.5, // panel overlapping
            SWAP_IN_DELAY: 0.2, // delay before overlapping
            SWAP_OUT: 0.7, // panel fading out
            CONTENT_IN: 0.4, // content fade in
            MENU_IN: 0.25, // menu in speed
            MENU_DELAY: 0.175, // delay between menu and shade
            MENU_OUT: 0.25, // menu out speed
            SLIDERS_IN: 0.2, // speed menu sliders come in
            SLIDERS_STAGGER: 0.1 // offset between menu sliders
        }
    };

    return appConfig;

});
