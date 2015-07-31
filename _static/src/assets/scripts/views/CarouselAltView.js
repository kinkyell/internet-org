define(function(require, exports, module) { // jshint ignore:line
    'use strict';

    var $ = require('jquery');
    require('jquery-touchswipe');

    var AbstractView = require('./AbstractView');
    var Dragdealer = require('dragdealer');

    /**
     * Carousel View Wrapper
     *
     * @class CarouselView
     * @param {jQuery} $element A reference to the containing DOM element.
     * @constructor
     */
    var CarouselView = function($element) {
        AbstractView.call(this, $element);
    };

    var proto = AbstractView.createChild(CarouselView);

    /**
     * Performs any event binding to handlers.
     *
     * @method onEnable
     * @returns {MenuView}
     * @public
     */
    proto.onEnable = function() {
        // alt carousels
        this.inst = new Dragdealer(this.element.id, {
            steps: 5,
            x: 0.5,
            slide: true,
            speed: 0.1,
            loose: true,
            requestAnimationFrame: true,
            vertical: false,
            horizontal: true
        });
    };

    /**
     * Tears down any event binding to handlers.
     *
     * @method onDisable
     * @returns {MenuView}
     * @public
     */
    proto.onDisable = function() {
    };

    module.exports = CarouselView;

});
