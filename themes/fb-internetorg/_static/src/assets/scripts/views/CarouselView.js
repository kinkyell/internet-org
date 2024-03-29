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
        this._handleDragRelease = this._onDragRelease.bind(this);
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
        // [Docs](http://skidding.github.io/dragdealer/)
        this.inst = new Dragdealer(this.element.id, {
            steps: 5,
            x: 0,
            slide: true,
            speed: 0.1,
            loose: true,
            requestAnimationFrame: true,
            vertical: false,
            horizontal: true,
            callback: this._handleDragRelease
        });
    };

    proto._onDragRelease = function() {
        var currentStep = this.inst.getStep()[0] - 1;
        var $captions = this.$element.find('.carousel-captionBox').children();
        var $currentCaption = $captions.eq(currentStep);

        $captions.removeClass('isActive').addClass('isNotActive');
        $currentCaption.removeClass('isNotActive').addClass('isActive');
    };

    /**
     * Tears down any event binding to handlers.
     *
     * @method onDisable
     * @returns {MenuView}
     * @public
     */
    proto.onDisable = function() {
        this.inst = null;
    };


    proto.layout = function() {
        var $captionBox = $('.carousel-captionBox');
        var $slides = this.$element.find('.carousel-handle-slide');
        var i = 0;
        var l = $slides.length;

        for (; i < l; i++) {
            var $slide = $slides.eq(i);
            var $slideCaption = $slide.find('.carousel-handle-slide-caption');
            $captionBox.append($slideCaption);
            $slideCaption.addClass('isNotActive');
        }

        this.$element.find('.carousel-captionBox > *:first-child').removeClass('isNotActive').addClass('isActive');

    };

    module.exports = CarouselView;

});
