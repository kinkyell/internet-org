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
        searchPath: '/search/', // include prefix '/'
        animationSpeeds: {
            CONTENT_IN: 0.4, // content fade in
            MENU_IN: 0.25, // menu in speed
            MENU_DELAY: 0.175, // delay between menu and shade
            MENU_OUT: 0.25, // menu out speed
            SLIDERS_IN: 0.2, // speed menu sliders come in
            SLIDERS_STAGGER: 0.1, // offset between menu sliders
            SELECT_MENU: 0.2, // select menu speed
            PANEL_SHIFT: 0.5, // panel shift speed
            ADDL_CONTENT: 0.3 // additional content load in
        },
        narrative: {
            mobile: {
                SECTION_SPEED: 0.35,
            },
            desktop: {
                EASE: Expo, // gsap ease type
                EASE_DIRECTION_FORWARD: 'easeOut', // gsap ease direction when progressing forward
                EASE_DIRECTION_REVERSE: 'easeIn', // gsap ease direction when progressing backward
                SCROLL_BUFFER: 0, // Buffer for scroll jacking (ms)
                TIME_SCALE: 0.5, // Timeline speed multiplier
                SECTION_DURATION: 0.35, // uniform transformBlock section durations
                featureImages: { // Featured images
                    HOME: '/assets/media/uploads/home_DT.jpg',
                    MISSION: '/assets/media/uploads/mission_DT.jpg',
                    APPROACH: '/assets/media/uploads/approach_DT.jpg',
                    IMPACT: '/assets/media/uploads/impact_DT.jpg',
                    FOOT: '/assets/media/uploads/contact_DT.jpg'
                }
            }
        }
    };

    return appConfig;

});
