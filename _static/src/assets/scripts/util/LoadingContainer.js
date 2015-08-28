/**
 * Provides helpful wrapper for class dictionaries
 *
 * @fileoverview
 */
define(function(require, exports, module) { // jshint ignore:line
    'use strict';

    var tweenAsync = require('util/tweenAsync');

    /**
     * Loading indicator abstraction
     *
     * @class LoadingContainer
     * @param {Element} element Container of loading indicator
     * @constructor
     */
    var LoadingContainer = function(element) {
        this.element = element;
        this.throbber = document.createElement('div');
        this.throbber.className = 'loadingIcon';
    };

    /**
     * Add in the pulsing loading indicator
     *
     * @method addThrobber
     * @private
     */
    LoadingContainer.prototype.addThrobber = function() {
        if (this.element.firstChild) {
            this.element.insertBefore(this.throbber, this.element.firstChild)
        } else {
            this.element.appendChild(this.throbber);
        }

        return tweenAsync.from(this.throbber, 0.3, {
            opacity: 0
        });
    };

    /**
     * Remove the loading indicator
     *
     * @method removeThrobber
     * @private
     */
    LoadingContainer.prototype.removeThrobber = function() {
        if (this.throbber.parentNode !== this.element) {
            return Promise.resolve();
        }
        return tweenAsync.to(this.throbber, 0.3, {
            opacity: 0,
            onComplete: function() {
                this.element.removeChild(this.throbber);
            },
            callbackScope: this
        });
    };

    return LoadingContainer;

});
