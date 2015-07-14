/**
 * A global configuration for app constants
 *
 * @fileoverview
 */
define(function(require, exports, module) { // jshint ignore:line
    'use strict';

    // Class name references
    //
    // NOTE: Do NOT include the '.' before the classname. This will be added
    // to the object with a _SELECTOR suffix.
    //
    // Ex. EXAMPLE_CLASS === 'className', EXAMPLE_CLASS_SELECTOR === '.className'
    var classes = {
        EXAMPLE_CLASS: 'className'
    };

    // add all _SELECTOR varieties
    for (var className in classes) {
        if (classes.hasOwnProperty(className) && !classes[className + '_SELECTOR']) {
            classes[className + '_SELECTOR'] = '.' + classes[className];
        }
    }


    // Additional Variables
    var appConfig = {
        classes: classes
    };

    return appConfig;

});
