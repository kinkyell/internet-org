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
        apiBase: ''
    };

    return appConfig;

});
