define(function(require, exports, module) { // jshint ignore:line
    'use strict';

    var Modernizr = require('modernizr');
    var PanelView = require('views/PanelView');

    // polyfill promises
    var ES6Promise = require('promise');
    ES6Promise.polyfill();

    /**
     * Initial application setup. Runs once upon every page load.
     *
     * @class App
     * @constructor
     */
    var App = function() {
        if (!this._cutsTheMustard()) {
            return;
        }
        this.init();
    };

    var proto = App.prototype;

    /**
     * Initializes the application and kicks off loading of prerequisites.
     *
     * @method init
     * @private
     */
    proto.init = function() {
        this.panelView = new PanelView();
    };

    /**
     * Checks if browser has necessary features to run application
     *
     * @method _cutsTheMustard
     * @private
     */
    proto._cutsTheMustard = function() {
        if (
            (typeof Object.getPrototypeOf !== 'function')
        ) {
            return false;
        }

        return true;
    };

    return App;

});
