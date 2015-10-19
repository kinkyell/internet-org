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
        apiBase: window.SETTINGS.STATIC_PATH,
        searchPath: window.SETTINGS.SEARCH_PATH, // include prefix '/'
        apiRoutes: window.SETTINGS.ROUTES,
        animationSpeeds: {
            CONTENT_IN: 0.4, // content fade in
            MENU_IN: 0.25, // menu in speed
            MENU_DELAY: 0.175, // delay between menu and shade
            MENU_OUT: 0.25, // menu out speed
            SLIDERS_IN: 0.2, // speed menu sliders come in
            SLIDERS_STAGGER: 0.1, // offset between menu sliders
            SELECT_MENU: 0.2, // select menu speed
            PANEL_SHIFT: 0.4, // panel shift speed
            ADDL_CONTENT: 0.3 // additional content load in
        },
        narrative: {
            mobile: {
                EASE: window.Circ, // gsap ease type
                EASE_DIRECTION_FORWARD: 'easeNone', // gsap ease direction when progressing forward
                EASE_DIRECTION_REVERSE: 'easeNone', // gsap ease direction when progressing backward
                SECTION_DURATION: 0.65, // uniform transformBlock section durations
                TIME_SCALE: 1.5 // Timeline speed multiplier
            },
            desktop: {
                EASE: window.Power0, // gsap ease type
                EASE_DIRECTION_FORWARD: 'easeOut', // gsap ease direction when progressing forward
                EASE_DIRECTION_REVERSE: 'easeIn', // gsap ease direction when progressing backward
                SCROLL_BUFFER: 250, // Buffer for scroll jacking (ms)
                TIME_SCALE: 1, // Timeline speed multiplier
                SECTION_DURATION: 0.35, // uniform transformBlock section durations
                MOVEMENT_Y: 90, // number of pixels to move elements on the Y axis
                STAGGER_DELAY: 0.1, // amount of time in seconds to delay stagger affects
            }
        },
        viewWindow: {
            FEATURE_EASE: window.Power2,
            SHIFT_EASE: window.Power1,
            EASE_DIRECTION: 'easeInOut'
        }
    };

    return appConfig;

});
