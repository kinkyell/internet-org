/**
 * Provides helpful wrapper for class dictionaries
 *
 * @fileoverview
 */
define(function(require, exports, module) { // jshint ignore:line
    'use strict';

    /**
     * Class Dictionary Wrapper
     *
     * @class ClassDict
     * @param {Object} classes Key value class pairs
     * @constructor
     */
    var ClassDict = function(classes) {
        this._loadClasses(classes);
    };

    /**
     * Load all states and selector keys onto object
     *
     * @method _loadClasses
     * @private
     * @param {Object} classes Key value class pairs
     * @throws {RangeError} Errors if any keys conflict with object properties
     */
    ClassDict.prototype._loadClasses = function(classes) {
        for (var className in classes) {
            if (classes.hasOwnProperty(className)) {
                if (typeof this[className] !== 'undefined') {
                    throw new RangeError('ClassDict: name clashes with internal property. (' + className + ')');
                }

                this[className] = classes[className];
                this[className + '_SELECTOR'] = '.' + classes[className];
            }
        }
    };

    return ClassDict;

});
